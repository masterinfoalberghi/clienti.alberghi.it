<?php

/**
 * MailMultilpaController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers;

use App\AcceptancePrivacy;
use App\Categoria;
use App\CmsPagina;
use App\CookieIA;
use App\GestioneMultiple;
use App\Hotel;
use App\Http\Requests\richiestaMailMultiplaMobileRequest;
use App\Http\Requests\RichiestaMailMultiplaRequest;
use App\Http\Requests\RichiestaWishlistRequest;
use App\Localita;
use App\Macrolocalita;
use App\MailDoppie;
use App\MailMultipla;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Request;

class MailMultiplaController extends Controller
{

    const MM_RETURN_PATH = "richiesta@info-alberghi.com";

    /* ------------------------------------------------------------------------------------
     * METODI PRIVATI
     * ------------------------------------------------------------------------------------ */

    /**
     * Filtra i trattamenti per scriverli correttamente sul database e sulla email
     *
     * @access private
     * @static
     * @param String $meal_plan
     * @param Array $list_meal_plan
     * @return String
     */

    private static function _filterMealPlan($meal_plan, $list_meal_plan)
    {

        $list_meal_plan = array_flip($list_meal_plan);
        $new_list_meal_plan_items = [];

        if (!strpos($meal_plan, ",")) {
            $meal_plan_items = [$meal_plan];
        } else {
            $meal_plan_items = explode(",", $meal_plan);
        }

        foreach ($meal_plan_items as $items) {
            if (in_array($items, $list_meal_plan)) {
                $new_list_meal_plan_items[] = $items;
            }

        }

        if (count($new_list_meal_plan_items) == 0) {
            $new_list_meal_plan_items[] = $list_meal_plan[Utility::_array_key_first($list_meal_plan)];
        }

        return implode(",", $new_list_meal_plan_items);

    }

    /** 
     * Prende i valori unici in caso di più riccorrenze nell'array (camere multiple)
     * 
     * @access private
     * @static
     * @param Array $trattamenti
     * @return Array
     */

    private static function _filtraTrattamentoSql($trattamenti)
    {

        $trattamenti_array = [];

        foreach ($trattamenti as $trattamento) {

            if (strpos($trattamento, ",")) {
                $trattamenti_items = explode(",", $trattamento);

                foreach ($trattamenti_items as $item) {
                    $trattamenti_array[] = $item;
                }
            } else
                $trattamenti_array[] = $trattamento; /* Ne ho solo 1 */
            
        }

        return array_unique($trattamenti_array);

    }

    /**
     * ATTENZIONE:  @Lucio&@Luigi 06/11/2020
     * se seleziono mp => voglio mp oppure mp_s
     * se seleziono mp_s voglio SOLO mp_s
     * QUINDI se HO SELEZIONATO ENTRAMBI I TRATTAMENTI (con e senza spiaggia)
     * VINCE QUELLO PIU' INCLUSIVO (quello che contiene anche la spiaggia)
     */
    
    private static function _gestisciTrattamentiSpiaggia(&$trattamento_filtrato, $trattamenti_con_spiaggia)
    {

        foreach ($trattamenti_con_spiaggia as $t) {
            $t_spiaggia = $t . '_spiaggia';
            if (in_array($t, $trattamento_filtrato) && in_array($t_spiaggia, $trattamento_filtrato)) {
                $key = array_search($t, $trattamento_filtrato);
                unset($trattamento_filtrato[$key]);
            }
        }

    }

    /**
     * Aggiorna i contatti per le email multiple
     *
     * @access private
     * @param Array $clienti_ids_arr
     * @param Boolean $debug_email
     */

    private function _aggiornaContattiMailMultiple($clienti_ids_arr, $debug_email)
    {

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Gestione multiple controllare</b><br />";
            echo Utility::echoDebug("id da scrivere", print_r($clienti_ids_arr, 1));
        }
        /* --------------------------- DEBUG --------------------------- */

        foreach ($clienti_ids_arr as $cia) {

            $inventory = GestioneMultiple::firstOrNew(['hotel_id' => $cia]);
            $inventory->contact = $inventory->contact + 1;
            $inventory->save();

        }

    }

    /**
     * Se sono più di 25 li limito e li prendo a caso
     *
     * @access private
     * @param  String $ids_send_mail
     * @return String
     */

    private function _riordinaELimita($ids_send_mail)
    {

        $ids_send_mail = explode(",", $ids_send_mail);

        if (count($ids_send_mail) > 25) {

            shuffle($ids_send_mail);
            $ids_send_mail = array_slice($ids_send_mail, 0, 25);

        }

        sort($ids_send_mail);
        return implode(",", $ids_send_mail);

    }

    /**
     * Elimina i doppioni sulla spedizione email
     * a All'interno della stessa email non spedisco 2 volte a giovanni@info-
     * alberghi.com che magari è nel campo A e nel CCN
     *
     * @access private
     * @param array $bcc la lista delle emai in copia nascosta
     * @return array
     */

    private function _ToEmail($bcc)
    {

        if (Config::get("mail.filter_list_email")) {

            if (is_array($bcc)):

                if (Config::get("mail.debug_email"))
                    echo "<br /><br /><b>SPEDISCO AD UNA LISTA FILTRATA DI EMAIL</b><br />";

                $bcc_new = array();
                $bcc =  explode(
                        "," , 
                        str_replace(
                            " ", 
                            "" , 
                            join(
                                ",", 
                                $bcc
                            )
                        )
                    );

                    config('app.debug_log') ? Log::emergency("\n" . '---> MAILMULTIPLA: hotel ID ' . print_r($bcc,1) . ' NO HA LE API e non scrivo sul DB <---' . "\n\n") : "";

                // Elimino le email doppie
                foreach ($bcc as $single) {
                    if (!in_array($single, $bcc_new))
                        array_push($bcc_new, $single);
                } 

            else :

                $bcc = str_replace(" ", "", $bcc);
                $bcc = explode(",", $bcc);
                $bcc_new = $bcc;

            endif;

            return $bcc_new;

        } else {

            if (Config::get("mail.debug_email"))
                echo "<br /><br /><b>SPEDISCO A TUTTI GLI ELEMENTI DELLA LISTA</b><br />";

            return $bcc;
        }

    }

    /**
     * Provo ad indovinare la localita dal contesto (referer)
     *
     * @access private
     * @return Macrolocalita
     */

    private function _guessLoc()
    {

        $ref = Request::server('HTTP_REFERER');
        $guessedLoc = 0;
        if ($ref != "") {

            $uri = str_replace(url('/'), '', $ref);

            $referer_page = CmsPagina::where('uri', $uri)->attiva()->first();

            if (!is_null($referer_page)) {
                if ($referer_page->template == 'localita') {
                    $guessedLoc = $referer_page->menu_localita_id;
                    if ($guessedLoc == 0) {
                        $guessedMacro = $referer_page->menu_macrolocalita_id;
                        if ($guessedMacro != 0) {
                            // se la macro è rimini la loc è Rimini Mare
                            if ($guessedMacro == 1) {
                                $guessedLoc = Macrolocalita::with('localita')->find($guessedMacro)->localita->find(39)->id;
                            }
                            // se la macro è Cesenatico la loc è Cesenatico centro
                            elseif ($guessedMacro == 3) {
                                $guessedLoc = Macrolocalita::with('localita')->find($guessedMacro)->localita->find(43)->id;
                            } else {
                                $guessedLoc = Macrolocalita::with('localita')->find($guessedMacro)->localita->first()->id;
                            }

                        }
                    }
                } elseif ($referer_page->template == 'listing') {
                    $guessedLoc = $referer_page->listing_localita_id;
                    if ($guessedLoc == 0) {
                        $guessedMacro = $referer_page->listing_macrolocalita_id;
                        if ($guessedMacro != 0) {
                            // se la macro è rimini la loc è Rimini Mare
                            if ($guessedMacro == 1) {
                                $guessedLoc = Macrolocalita::with('localita')->find($guessedMacro)->localita->find(39)->id;
                            }
                            // se la macro è Cesenatico la loc è Cesenatico centro
                            elseif ($guessedMacro == 3) {
                                $guessedLoc = Macrolocalita::with('localita')->find($guessedMacro)->localita->find(43)->id;
                            } else {
                                $guessedLoc = Macrolocalita::with('localita')->find($guessedMacro)->localita->first()->id;
                            }

                        }
                    }
                }
            }
        }

        return $guessedLoc;

    }

    /**
     * Controlla se l'hotel ha le API Attivate
     *
     * @access private
     * @static
     * @param Number $hotel_id
     * @param Array $ids_api
     *
     */

    private static function _checkAPI($hotel_id, $ids_api)
    {

        $trovato = false;
        foreach ($ids_api as $val) {
            if ($val->hotel_id == $hotel_id) {
                $trovato = true;
                break;
            }
        }

        if (!$trovato)
            config('app.debug_log') ? Log::emergency("\n" . '---> MAILMULTIPLA: hotel ID ' . $hotel_id . ' NO HA LE API e non scrivo sul DB <---' . "\n\n") : "";
        
        return $trovato;

    }

    /**
     * Trovo gli id degli hotel che hanno API attiva
     * per ogni hotel a cui devo spedire devo verificare se ha le api
     *
     * @access private
     * @static
     * @param Array $ids_send_mail_db
     * @return Array
     */

    private static function _eliminaIdNoAPI($ids_send_mail_db)
    {

        $to_return_ids = $ids_send_mail_db;

        if (Config::get("mail.send_to_api_db")) {

            $ids_api = DB::connection('api')
                ->table('tblAPIAgenziaHotel')
                ->select(DB::raw('DISTINCT hotel_id'))
                ->where('attivo', 1)
                ->orderBy('hotel_id')
                ->get();

            foreach ($ids_send_mail_db as $key => $hotel_id) {
                if (!Self::_checkAPI($hotel_id, $ids_api)) {
                    unset($to_return_ids[$key]);
                }

            }

        }

        return $to_return_ids;

    }

    /**
     * Scrive le email nel database delle API
     *
     * @access private
     * @static
     * @param Array $db_email
     * @param Array $camere_aggiuntive_api
     * @param Array $ids_send_mail_db
     * @return Boolean
     */

    private static function _scriviMailMultiplaAPI($db_email, $camere_aggiuntive_api, $ids_send_mail_db)
    {

        //? NON scrivo sulle API
        //return 0;
        //? /////////////////////

        // trovo solo gli id che hanno le API
        $ids_send_mail_db = Self::_eliminaIdNoAPI($ids_send_mail_db);

        if (Config::get("mail.send_to_api_db") && count($ids_send_mail_db)) {

            DB::connection('api')->beginTransaction();
            try {

                if ($db_email["nome"] == __("labels.no_name")) {
                    $db_email["nome"] = "";
                }

                DB::connection('api')->table('tblAPIMailMultipla')->insert($db_email);
                DB::connection('api')->table('tblAPICamereAggiuntive')->insert($camere_aggiuntive_api);

                $mailMultipla_id = $db_email['id'];
                $data['mailMultipla_id'] = $mailMultipla_id;

                foreach ($ids_send_mail_db as $hotel_id) {
                    $data['hotel_id'] = $hotel_id;
                    DB::connection('api')->table('tblAPIHotelMailMultiple')->insert($data);
                    unset($data['hotel_id']);
                }

                DB::connection('api')->commit();
                return 1;

            } catch (\Exception $e) {

                DB::connection('api')->rollback();
                $file = $e->getFile();
                $line = $e->getLine();
                $exception = $e->getMessage();
                config('app.debug_log') ? Log::emergency("\n" . '---> _scriviMailMultiplaAPI: Errore invio MAILMULTIPLA_API:--- ' . $e->getMessage() . ' <---' . "\n\n") : "";
                $ip = Request::server('REMOTE_ADDR');
                $server = Request::server('HTTP_HOST');
                $host = gethostbyaddr(Request::server('REMOTE_ADDR'));
                $url = $host . Request::server('REQUEST_URI');
                $subject = "_scriviMailMultiplaAPI: Errore invio MAILMULTIPLA_API " . $server . " (" . $file . " linea " . $line . ")";
                Utility::sendMeEmailError($subject, $exception, $server);
                return 0;

            }
        }

        return 1;

    }

    /**
     *
     * Elimina gli hotel dalla lista in base alla chiusura temporanea
     * Verifico se tra gli hotel ci sono degli hotel chiuso_temp; se ci sono verifico se devono essere eliminati in base alle date (solo se per arrivi > 30/09/2020
     *
     * @param Array $arrivo
     * @param Array &$ids_send_mail [ids degli hotel selezionati]
     * @return String
     */

    private static function _del_hotel_chiuso_temp($arrivo, $ids_send_mail)
    {

        $list_ids = collect(explode(',', $ids_send_mail));
        $chiusi_temp = $list_ids->filter(function ($value, $key) {
            return Hotel::find($value)->chiuso_temp;
        });

        // se c'è almeno un hotel chiuso verifico le date per eliminarlo da $ids_send_mail
        if ($chiusi_temp->count()) {
            foreach ($arrivo as $data_arrivo) {
                // se c'è almeno una data < 30/09/2020 allora tolgo gli hotel chiusi
                if (Carbon::createFromFormat('d/m/Y', $data_arrivo)->lessThan(Utility::getArrvioSeChiuso())) {

                    $aperti_ids = $list_ids->filter(function ($value, $key) {
                        return Hotel::find($value)->chiuso_temp == 0;
                    });

                    return implode(',', $aperti_ids->toArray());

                }
            }
        }

        return $ids_send_mail;

    }

    /* ------------------------------------------------------------------------------------
     * CONTROLLER
     * ------------------------------------------------------------------------------------ */

    /**
     * Spedisce una email multipla da Desktop
     * Ultima modifica: 17/04/2018 @giovanni
     * Controllo email duplicate su database
     *
     * @param RichiestaMailMultiplaRequest $request
     * @return Redirect
     */

    public function richiestaMailMultipla(RichiestaMailMultiplaRequest $request)
    {

        $locale = $this->getLocale();
        $my_input = $request->input(); // array()
        $date = Utility::getLocalDate(Carbon::now(), '%H:%M:%S');
        $spedisci_email_duplicate = Config::get("mail.spedisci_email_duplicate");
        $spedisci_sempre_email_dirette = Config::get("mail.spedisci_sempre_email_dirette");
        $add_copy_email = Config::get("mail.add_copy_email");
        $debug_email = Config::get("mail.debug_email");

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<b>Variabili impostate</b><br />";
            echo Utility::echoDebug("add_copy_email", $add_copy_email);
            echo Utility::echoDebug("spedisci_sempre_email_dirette", $spedisci_sempre_email_dirette);
            echo Utility::echoDebug("spedisci_email_duplicate", $spedisci_email_duplicate);
            echo "<b>Dati spediti</b><br />";
            echo Utility::echoDebug("spedisci_email_duplicate", $my_input);
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Trovo localita, categorie trattamento
         */

        $multiple_loc = $my_input['multiple_loc_single'];
        $categoria = $my_input['categoria'];

        ($categoria == 1) ? $info_categoria = $categoria . ' stella' : $info_categoria = $categoria . ' stelle';
        ($categoria == 6) ? $info_categoria = '3 stelle sup.' : "";

        $str_info_categoria_mail = $info_categoria;
        $localita_id = $multiple_loc;
        $localita = Localita::find($localita_id)->nome;

        $trattamento = $my_input['trattamento'];

        /**
         * Campi singoli
         */

        $referer = isset($my_input['referer']) ? $my_input['referer'] : '';
        $actual_link = isset($my_input['actual_link']) ? $my_input['actual_link'] : '';
        $spam = Utility::checkSenderMailBlacklisted($my_input['email']);
        $ip = Utility::get_client_ip();

        /**
         * 27/04/2020:
         *
         * se c'è almeno una data < 30/09/2020 allora FILTRO gli hotel chiusi aggiungendo lo scope ->notChiusoTemp()
         * altrimenti rilasso questo vincolo perché posso considerare anche gli hotel chiusi
         *
         */

        $vincolo_solo_aperti = false;

        foreach ($request->get('arrivo') as $data_arrivo) {
            // se c'è almeno una data < 30/09/2020 allora tolgo gli hotel chiusi
            if (Carbon::createFromFormat('d/m/Y', $data_arrivo)->lessThan(Utility::getArrvioSeChiuso())) {
                $vincolo_solo_aperti = true;
                break;
            }

        }

        // è un array che contiene tutti i trattamenti di tutte le camere
        // se ho mp nella camera 1 e mp nella camera 2 li ho selezionati entrambi
        // e quindi considero mp_s
        $trattamento_filtrato = Self::_filtraTrattamentoSql($trattamento);

        /*

        ^ array:5 [▼
        0 => "trattamento_pc"
        1 => "trattamento_mp"
        2 => "trattamento_bb"
        3 => "trattamento_ai"
        4 => "trattamento_mp_spiaggia"
        ]

         */

        /*echo '<pre>';
        dump($trattamento_filtrato);
        echo '</pre>';*/

        // i trattamenti che hanno anche la versione con la spiaggia sono
        // trattamento_mp, trattamento_bb, trattamento_sd
        $trattamenti_con_spiaggia = Utility::getTrattamentiSpiaggia();

        // ATTENZIONE:  @Lucio&@Luigi 06/11/2020
        //  se seleziono mp => voglio mp oppure mp_s
        //  se seleziono mp_s voglio SOLO mp_s
        //  QUINDI se HO SELEZIONATO ENTRAMBI I TRATTAMENTI (con e senza spiaggia)
        //  VINCE QUELLO PIU' INCLUSIVO (quello che contiene anche la spiaggia)

        Self::_gestisciTrattamentiSpiaggia($trattamento_filtrato, $trattamenti_con_spiaggia);

        // trattamento_filtrato contiene gli elementi corretti
        // dd($trattamento_filtrato);

        /*
        ^ array:4 [▼
        0 => "trattamento_pc"
        2 => "trattamento_bb"
        3 => "trattamento_ai"
        4 => "trattamento_mp_spiaggia"
        ]
         */

        // nel caso di bb devo mettere in or bb e bb_spiaggia

        $clienti = Hotel::where('localita_id', '=', $localita_id)
            ->where(function ($query) use ($categoria, $trattamento_filtrato, $trattamenti_con_spiaggia) {
                if ($categoria != "") {
                    $query->where('categoria_id', $categoria);
                }
                foreach ($trattamento_filtrato as $trat):
                    if ($trat != '0') {
                        if (in_array($trat, $trattamenti_con_spiaggia)) {
                            $query->where(function ($q) use ($trat) {
                                $q->where($trat, 1)
                                    ->orWhere($trat . '_spiaggia', 1);
                            });
                        } else {
                            $query->where("$trat", 1);
                        }
                    }
                endforeach;
            })
            ->attivo();

        //dd(Str::replaceArray('?', $clienti->getBindings(), $clienti->toSql()));

        if ($vincolo_solo_aperti) {
            $clienti = $clienti->notChiusoTemp();
        }

        $clienti = $clienti->orderByRaw("RAND()")->take(25)->get();
        $ids_send_mail = implode(',', $clienti->pluck('id')->toArray());

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo Utility::echoDebug("id", $ids_send_mail);
        }

        /* --------------------------- DEBUG --------------------------- */

        /**
         * Se non ho hotel do un alert
         */

        $num_hotel = $clienti->count();

        if ($num_hotel == 0) {

            Session::flash('flash_message', Lang::get('listing.no_hotel_found'));
            Session::flash('flash_message_important', true);
            return redirect(Utility::getUrlWithLang($locale, '/mail-multipla')); //->withInput();

        }

        /**
         * Controllo se sono spam
         */

        if (!$spam && isset($my_input['esca'])) {

            $esca = $my_input['esca'];
            $spam = Utility::checkSpider($esca);

        }

        /**
         * Campi soggiorno
         */

        $checkin = $my_input['arrivo'];
        $checkout = $my_input['partenza'];
        $meal_plan = $my_input['trattamento'];
        $adult = $my_input['adulti'];
        $children = $my_input['bambini'];
        $numero_camere = count($meal_plan); //$my_input['numero_camere'];

        if (isset($my_input['flex_date'])) {
            $flex_date_value = $my_input['flex_date'];
        }
        // Array
        else {
                $flex_date_value = array();
                for ($i = 0; $i < 10; $i++) {
                    $flex_date_value[$i] = 0;
                }

            }

            $age_0 = isset($my_input['eta_0']) ? $my_input['eta_0'] : array();
            $age_1 = isset($my_input['eta_1']) ? $my_input['eta_1'] : array();
            $age_2 = isset($my_input['eta_2']) ? $my_input['eta_2'] : array();
            $age_3 = isset($my_input['eta_3']) ? $my_input['eta_3'] : array();
            $age_4 = isset($my_input['eta_4']) ? $my_input['eta_4'] : array();
            $age_5 = isset($my_input['eta_5']) ? $my_input['eta_5'] : array();

            /**
             * Campi nuovo cookie Prefiil
             */

            $prefill = array();

            $prefill["ids_send_mail"] = $ids_send_mail;
            $prefill["customer"] = $my_input['nome'];
            if ($prefill["customer"] == "") {
                $prefill["customer"] = __("labels.no_name");
            }

            $prefill["email"] = $my_input['email'];
            $prefill["phone"] = null;
            $prefill["whatsapp"] = null;
            $prefill["information"] = $my_input['richiesta'];
            $prefill["tag"] = "EM";
            $prefill["sender"] = "info-alberghi.com";
            $prefill["language"] = $locale;
            $prefill['type'] = 'desktop';
            $prefill["rooms"] = array();

            if (isset($my_input['flex_date']) && $my_input['flex_date'] == 1) {$prefill["flex_date"] = 1;} else { $prefill["flex_date"] = 0;}

            /**
             * Ciclo sulle camere
             */

            for ($i = 0; $i < $numero_camere; $i++) {

                $prefill["rooms"][$i] = array();
                $prefill["rooms"][$i]["checkin"] = $checkin[$i];
                $prefill["rooms"][$i]["checkout"] = $checkout[$i];
                $prefill["rooms"][$i]["adult"] = $adult[$i];
                $prefill["rooms"][$i]["children"] = $children[$i];
                $prefill["rooms"][$i]["meal_plan"] = $meal_plan[$i];
                $prefill["rooms"][$i]["flex_date"] = $prefill["flex_date"];

                $eta_bambini = array();
                if (isset($age_0[$i])) {$eta_bambini[0] = $age_0[$i];}
                if (isset($age_1[$i])) {$eta_bambini[1] = $age_1[$i];}
                if (isset($age_2[$i])) {$eta_bambini[2] = $age_2[$i];}
                if (isset($age_3[$i])) {$eta_bambini[3] = $age_3[$i];}
                if (isset($age_4[$i])) {$eta_bambini[4] = $age_4[$i];}
                if (isset($age_5[$i])) {$eta_bambini[5] = $age_5[$i];}

                $prefill["rooms"][$i]["age_children"] = implode(",", $eta_bambini);

            }

            /**
             * Trovo i dati che mi servono per il controllo delle email doppie
             */

            $old_prefill = CookieIA::getCookiePrefill();
            $new_prefill = $prefill;
            $codice_cookie = (array_key_exists('codice_cookie', $old_prefill)) ? $old_prefill['codice_cookie'] : Carbon::now()->timestamp . "_" . uniqid(rand(), true);

            /**
             * Tolgo tutti i campi che non necessitano confronto
             */

            $old_prefill = Utility::unsetEmailPrefill($old_prefill, "OLD", true);
            $new_prefill = Utility::unsetEmailPrefill($new_prefill, "NEW", true);

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                echo "<br /><br /><b>Old prefill</b><br />";
                echo Utility::echoDebug("", $old_prefill);
                echo "<br /><br /><b>New prefill</b><br />";
                echo Utility::echoDebug("", $new_prefill);
                echo Utility::echoDebug("codice_cookie", $codice_cookie);
            }
            /* --------------------------- DEBUG --------------------------- */

            /**
             * Controllo che non sia doppia
             */

            $tipologia = "normale";
            $email_doppia = false;
            $clienti_ids_arr = explode(",", $ids_send_mail);
            $clienti_ids_arr_not_sent = [];
            $new_num_hotel = count($clienti_ids_arr);

            /**
             * Se ho settato che le email doppie vannos sempre spedite
             * passo avanti altrimenti vado al seconco controllo
             */

            if (!$spedisci_email_duplicate) {

                /**
                 * Controllo che queste email non sia stata spedita a questo hotel
                 * da questo codice utente negli ultimi 3 giorni
                 */

                $confronto = MailDoppie::controlloMailDoppie($codice_cookie, explode(",", $ids_send_mail), base64_encode(json_encode($new_prefill)));

                /**
                 * Metto negli oggetti giusti le due serie di arry che mi ritornano
                 */

                $clienti_ids_arr = $confronto["clienti_ids_arr"];
                $clienti_ids_arr_not_sent = $confronto["clienti_ids_arr_not_sent"];
                $new_num_hotel = count($clienti_ids_arr);

                /**
                 * Sel il numero di hotel è cambiato rispetto al precedente ho delle email doppie
                 */

                if ($new_num_hotel != $num_hotel) {

                    $tipologia = "doppia parziale";

                    /**
                     * Se sono a 0 allora è la stessa email identica
                     */

                    if ($new_num_hotel == 0) {
                        $tipologia = "doppia";
                        $email_doppia = true;
                    }

                }

            }

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                echo "<br /><br /><b>Risultato confronto</b><br />";
                echo Utility::echoDebug("tipologia", $tipologia);
                echo Utility::echoDebug("email_doppia", $email_doppia);
                echo Utility::echoDebug("id buoni", print_r($clienti_ids_arr, 1));
                echo Utility::echoDebug("id scartati", print_r($clienti_ids_arr_not_sent, 1));
            }
            /* --------------------------- DEBUG --------------------------- */

            /**
             * Scrivo sempre le email nel database marcandola come doppia in caso di replica
             */

            $db_email = array();
            $camere_aggiuntive_api = [];

            if (!$spam) {

                MailDoppie::logEmailDoppie($codice_cookie, explode(",", $ids_send_mail), base64_encode(json_encode($new_prefill)), $debug_email);

                $ids_send_mail_db = explode(",", $ids_send_mail);

                /* Scrivo nel DB solo se c'è almeno una spedizione */
                if ($new_num_hotel > 0) {

                    /* --------------------------- DEBUG --------------------------- */
                    if ($debug_email) {
                        echo "<br /><br /><b>Ho $new_num_hotel hotel a cui spedire </b><br />";
                    }
                    /* --------------------------- DEBUG --------------------------- */

                    $mail = new MailMultipla;

                    $dati_aggiuntivi = DB::transaction(function () use ($debug_email, $prefill, $clienti_ids_arr, $clienti_ids_arr_not_sent, $referer, $ip, $numero_camere, $email_doppia, $old_prefill, $new_prefill, $ids_send_mail, $tipologia, $new_num_hotel, $num_hotel, $codice_cookie, &$db_email, &$camere_aggiuntive_api, $ids_send_mail_db, &$mail, $locale) {

                        $db_email["IP"] = $ip;
                        $db_email["lang_id"] = $locale;
                        $db_email["referer"] = $referer;
                        $db_email["camere"] = $numero_camere;
                        $db_email["tipologia"] = $tipologia;

                        // SE LA MIAL E' DOPPIA la marco come sync perché altrimenti il cron cerca di rispedirla
                        if ($email_doppia) {
                            $db_email["api_sync"] = true;
                        }

                        $db_email["nome"] = $prefill["customer"];
                        $db_email["email"] = $prefill["email"];
                        $db_email["telefono"] = null;
                        $db_email["whatsapp"] = null;
                        $db_email["richiesta"] = $prefill["information"];

                        $db_email["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkin"]);
                        $db_email["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkout"]);
                        $db_email["adulti"] = $prefill["rooms"][0]["adult"];
                        $db_email["bambini"] = $prefill["rooms"][0]["children"];
                        $db_email["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][0]["age_children"], $db_email["bambini"]);
                        $db_email["trattamento"] = $prefill["rooms"][0]["meal_plan"];
                        $db_email["date_flessibili"] = $prefill["rooms"][0]["flex_date"];

                        $mail->fill($db_email);
                        $mail->save();
                        $mail->clienti()->sync($clienti_ids_arr);

                        /**
                         * preparazione per API
                         */

                        $db_email['id'] = $mail->id;

                        /**
                         * Ciclo sulle camere
                         */
                        for ($i = 1; $i < $numero_camere; $i++) {

                            $db_email_tutte = array();
                            $db_email_tutte["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkin"]);
                            $db_email_tutte["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkout"]);
                            $db_email_tutte["trattamento"] = $prefill["rooms"][$i]["meal_plan"];
                            $db_email_tutte["adulti"] = $prefill["rooms"][$i]["adult"];
                            $db_email_tutte["bambini"] = $prefill["rooms"][$i]["children"];
                            $db_email_tutte["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][$i]["age_children"], $db_email_tutte["bambini"]);
                            $db_email_tutte["date_flessibili"] = $prefill["rooms"][$i]["flex_date"];

                            $camera_gg = $mail->camereAggiuntive()->create($db_email_tutte);

                            /**
                             * preparazione per API
                             */

                            $db_email_tutte['mailMultipla_id'] = $mail->id;
                            $db_email_tutte['id'] = $camera_gg->id;
                            $camere_aggiuntive_api[] = $db_email_tutte;

                        }

                        /**
                         * Inserimento per statistiche
                         */

                        //StatsHotelMailMultipla::addStatsHotelMailMultiple($ids_send_mail_db);

                        return true;

                    });

                    if (!$email_doppia) {

                        $mail_multipla_id = Self::_scriviMailMultiplaAPI($db_email, $camere_aggiuntive_api, $clienti_ids_arr);
                        
                        if ($mail_multipla_id != 0) {
                            // aggiorno a 1 il campo api_sync di mailScheda per il record
                            $mail->api_sync = true;
                            $mail->save();
                        }

                    }

                } // if c'è almeno una spedizione

            } // if !spam

            /**
             * Scrivo la email
             */

            $dati_mail = array();
            $dati_mail_reply = array();

            /**
             * Scrivo il JSON da attaca alla email
             */
            $dati_json['politiche_canc'] = Lang::get('labels.politiche_canc_short');
            $dati_json['spiaggia'] = 0;
            $dati_json["customer"] = $prefill["customer"];
            $dati_json["email"] = $prefill["email"];
            $dati_json["phone"] = null;
            $dati_json["whatsapp"] = null;
            $dati_json["information"] = $prefill["information"];
            $dati_json["tag"] = "EM";
            $dati_json["sender"] = "info-alberghi.com";
            $dati_json["type"] = "desktop";

            $dati_json["message_wa"] = "";
            $dati_json["information2"] = "";

            if ($dati_json["whatsapp"] != null) {
                $dati_json["information2"] = true;
            } else {
                $dati_json["information2"] = false;
            }

            if ($locale == 'it') {
                $in_lingua = "";
                $dati_json["language"] = "it_IT";
            } else {
                $in_lingua = Utility::getLanguageIso($locale);
                $dati_json["language"] = $in_lingua;
            }

            $dati_json["rooms"] = array();

            /**
             * Ciclo sulle camere
             */

            for ($i = 0; $i < $numero_camere; $i++) {

                $dati_json["rooms"][$i] = array();
                $dati_json["rooms"][$i]["flex_date"] = $prefill["rooms"][$i]['flex_date'];
                $dati_json["rooms"][$i]["checkin"] = $prefill["rooms"][$i]['checkin'];
                $dati_json["rooms"][$i]["nights"] = Utility::night($prefill["rooms"][$i]['checkin'], $prefill["rooms"][$i]['checkout']);
                $dati_json["rooms"][$i]["checkout"] = $prefill["rooms"][$i]['checkout'];
                $dati_json["rooms"][$i]["adult"] = $prefill["rooms"][$i]['adult'];

                if (isset($prefill["rooms"][$i]['children']) && $prefill["rooms"][$i]['children'] > 0) {
                    $dati_json["rooms"][$i]["children"] = explode(",", Utility::purgeMenoUno($prefill["rooms"][$i]['age_children'], $prefill["rooms"][$i]['children']));
                } else {
                    $dati_json["rooms"][$i]["children"] = "";
                }

                $list_meal_plan = ["tattamento_ai", "trattamento_fb"];
                if (strpos($prefill["rooms"][$i]['meal_plan'], "_spiaggia")) {
                    $dati_json['spiaggia'] = 1;
                }
                $dati_json["rooms"][$i]["meal_plan"] = Utility::Trattamenti_json($prefill["rooms"][$i]['meal_plan']);

            }

            $dati_json["message_wa"] = "";
            // $dati_json["message_wa"] = Self::_getMessageWA($dati_json, $numero_camere);

            /**
             * Sono i dati aggiuntivi dell'email
             */

            $cat_string = "";
            if ($categoria == 1) {
                $cat_string = "★";
            }

            if ($categoria == 2) {
                $cat_string = "★★";
            }

            if ($categoria == 3) {
                $cat_string = "★★★";
            }

            if ($categoria == 4) {
                $cat_string = "★★★★";
            }

            if ($categoria == 5) {
                $cat_string = "★★★★★";
            }

            if ($categoria == 5) {
                $cat_string = "★★★ SUP";
            }

            $localita = Localita::where("id", $multiple_loc)->first();
            $dati_mail['referer'] = "Localit&agrave;: <b>" . $localita->alias . "</b>, Categoria: <b>" . $cat_string . "</b>";
            $dati_mail['actual_link'] = "";
            $dati_mail['ip'] = $ip;
            $dati_mail['device'] = "Computer";
            $dati_mail['hotels_contact'] = $new_num_hotel;
            $dati_mail['date_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%d %B %Y');
            $dati_mail['hour_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%R');
            $dati_mail['sign_email'] = Utility::putJsonMail($dati_json);
            $sign_email_al_dollaro = base64_encode(json_encode($dati_json));

            /**
             * Preparo l'oggetto
             */

            $oggetto = Utility::getOggettoMail($numero_camere, $prefill, false);

            /**
             * E' spam ?
             * @var $spam integer Può assumere 3 valori: 0 - non è spam, 1 - Utente blacklistato, 2 - Spider
             */

            $bcc = array();
            $clienti = Hotel::with(['localita', 'localita.macrolocalita', 'stelle', 'caparreAttive'])->whereIn('id', $clienti_ids_arr)->get();

            if ($spam == 0) {

                /**
                 * Gestione delle mail multiple
                 */

                self::_aggiornaContattiMailMultiple($clienti_ids_arr, $debug_email);

                /*
                 * Invio il JSON ad un servizio API esterno
                 */
                if ($add_copy_email) {
                    $bcc = "testing.infoalberghi@gmail.com";
                }

            } else if ($spam == 1) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - utente BLACKLISTATO al modulo invio mail multiple";

            // prendo un clieente solo perché la mail la mando a me facendo un loop sui clienti
            $clienti = $clienti->take(1);

        } else if ($spam == 2) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - SPIDER al modulo invio mail multiple";

            // prendo un clieente solo perché la mail la mando a me facendo un loop sui clienti
            $clienti = $clienti->take(1);

            if (isset($esca)) {
                $oggetto .= "(campo esca = " . $esca . ")";
            }

        }

        /**
         * Normalizzo i dati per compilare il template
         */

        $email_mittente = $prefill["email"];
        $nome_mittente = $prefill["customer"];
        $telefono_mittente = $prefill["phone"];

        AcceptancePrivacy::addPrivacyRow($email_mittente, $ip, "Computer");

        /**
         * Aggiorno i dati per la spedizione attraverso sendgrid
         */

        Utility::swapToSendGrid();

        /**
         *
         * 15/05/2020 @Luigi
         * LOOP HOTEL e n Mail Singole
         */
        if (count($clienti)) {

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                if ($spedisci_email_duplicate) {
                    echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                }

                echo "<br /><br /><b>Cosa spedisco</b><br />";
                echo Utility::echoDebug("oggetto", $oggetto);
            }
            /* --------------------------- DEBUG --------------------------- */

            /**
             * Se NON è una mail doppia (né doppia né doppia parziale) OPPURE ho abilitato la spedizione per le doppie
             * SPEDISCO
             */

            if (!$email_doppia || $spedisci_email_duplicate):

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>SPEDISCO</b>";
                    die();
                }
                /* --------------------------- DEBUG --------------------------- */

                $email_type = "Multipla";

                if ($spam == 0) {

                    foreach ($clienti as $hotel) {

                        try
                        {

                            $to = Self::_ToEmail($hotel->email);
                            $dati_mail['hotel_name'] = $hotel->nome;
                            $dati_mail['hotel_id'] = $hotel->id;
                            $dati_mail['hotel_telefono'] = $hotel->telefoni_mobile_call;
                            $dati_mail['hotel_indirizzo'] = $hotel->indirizzo . ' - ' . $hotel->cap . ' - ' . $hotel->localita->macrolocalita->nome;
                            $dati_mail['hotel_rating'] = $hotel->stelle->nome;
                            $dati_mail['hotel_loc'] = $hotel->localita->nome;
                            $dati_mail['caparre'] = null;

                            $dati_mr['hotel_name'] = $hotel->nome;
                            $dati_mr['hotel_id'] = $hotel->id;
                            $dati_mr['hotel_rating'] = $hotel->stelle->nome;
                            $dati_mr['hotel_loc'] = $hotel->localita->nome;

                            $dati_mail_reply[$hotel->id] = $dati_mr;

                            /**
                             * Ciclo sulle camere
                             */

                            for ($i = 0; $i < $numero_camere; $i++) {
                                $dati_json["rooms"][$i]['caparre'] = $hotel->attachCaparreDalAl($dati_json["rooms"][$i]["checkin"], $dati_json["rooms"][$i]["checkout"], $locale);

                                // caparra associata all'hotel che nn viene sovrascritta da passare alla mail-reply
                                $dati_json["rooms"][$i]['caparra'][$hotel->id] = $dati_json["rooms"][$i]['caparre'];
                            }

                            Mail::send(
                                "emails.mail_scheda", compact('email_type', 'dati_mail', 'dati_json'),
                                function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente) {
                                    $message->from(Self::MM_RETURN_PATH, $nome_mittente);
                                    $message->replyTo($email_mittente);
                                    $message->returnPath(Self::MM_RETURN_PATH)->sender(Self::MM_RETURN_PATH)->to($to);
                                    if ($bcc != "") {
                                        $message->bcc($bcc);
                                    }

                                    $message->subject($oggetto);
                                }
                            );

                        } catch (Exception $e) {

                            /**
                             * Scrivo nel log
                             */

                            config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAIL MULTIPLA DESKTOP HOTEL ID(' . $hotel->id . '): ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";
                        }

                    } /*foreach clienti */
                
                } else {

                    /** */
                    Mail::send(
                        "emails.mail_scheda", compact('email_type', 'dati_mail', 'dati_json'),
                        function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente) {
                            $message->from(Self::MM_RETURN_PATH, $nome_mittente);
                            $message->replyTo($email_mittente);
                            $message->returnPath(Self::MM_RETURN_PATH)->sender(Self::MM_RETURN_PATH)->to($to);
                            if ($bcc != "") {
                                $message->bcc($bcc);
                            }

                            $message->subject($oggetto);
                        }
                    );

                }
            endif; /*end if(!$email_doppia || $spedisci_email_duplicate)*/

        } /*end count($clienti)*/

        /**
         * Email ai risponditori
         */

        if (count($clienti)) {

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                if ($spedisci_email_duplicate) {
                    echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                }

                echo "<br /><br /><b>Cosa spedisco</b><br />";
                echo Utility::echoDebug("oggetto", $oggetto);
            }
            /* --------------------------- DEBUG --------------------------- */

            /**
             * Se NON è una mail doppia (né doppia né doppia parziale) OPPURE ho abilitato la spedizione per le doppie
             * SPEDISCO
             */

            if (!$email_doppia || $spedisci_email_duplicate):

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>SPEDISCO</b>";
                    die();
                }
                /* --------------------------- DEBUG --------------------------- */

                $email_type = "Multipla";

                    foreach ($clienti as $hotel) {

                        if ($hotel->email_risponditori != "" && $spam == 0) {

                            try
                            {

                                $email_risponditori = Self::_ToEmail($hotel->email_risponditori);
                                $dati_mail['hotel_name'] = $hotel->nome;
                                $dati_mail['hotel_id'] = $hotel->id;
                                $dati_mail['hotel_telefono'] = $hotel->telefoni_mobile_call;
                                $dati_mail['hotel_indirizzo'] = $hotel->indirizzo . ' - ' . $hotel->cap . ' - ' . $hotel->localita->macrolocalita->nome;
                                $dati_mail['hotel_rating'] = $hotel->stelle->nome;
                                $dati_mail['hotel_loc'] = $hotel->localita->nome;
                                $dati_mail['caparre'] = null;

                                $dati_json_new = $dati_json;
                                if ($dati_json["spiaggia"]) {
                                    $dati_json_new["information"] .= '[s][b]Richiesta supplemento spiaggia[/b] - Il cliente ha richiesto almeno un preventivo con la spiaggia inclusa.[/s]';
                                }
            
                                $dati_mail['sign_email'] = Utility::putJsonMail($dati_json_new, true);

                                $dati_mr['hotel_name'] = $hotel->nome;
                                $dati_mr['hotel_id'] = $hotel->id;
                                $dati_mr['hotel_rating'] = $hotel->stelle->nome;
                                $dati_mr['hotel_loc'] = $hotel->localita->nome;
                                
                                $dati_mail_reply[$hotel->id] = $dati_mr;

                                /**
                                 * Ciclo sulle camere
                                 */

                                for ($i = 0; $i < $numero_camere; $i++) {
                                    $dati_json["rooms"][$i]['caparre'] = $hotel->attachCaparreDalAl($dati_json["rooms"][$i]["checkin"], $dati_json["rooms"][$i]["checkout"], $locale);

                                    // caparra associata all'hotel che nn viene sovrascritta da passare alla mail-reply
                                    $dati_json["rooms"][$i]['caparra'][$hotel->id] = $dati_json["rooms"][$i]['caparre'];
                                }

                                Mail::send(
                                    "emails.mail_scheda", compact('email_type', 'dati_mail', 'dati_json'),
                                    function ($message) use ($email_mittente, $email_risponditori, $oggetto, $nome_mittente) {
                                        $message->from(Self::MM_RETURN_PATH, $nome_mittente);
                                        $message->replyTo($email_mittente);
                                        $message->returnPath(Self::MM_RETURN_PATH)->sender(Self::MM_RETURN_PATH)->to($email_risponditori);

                                        $message->subject($oggetto);
                                    }
                                );

                            } catch (Exception $e) {

                                /**
                                 * Scrivo nel log
                                 */

                                config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAIL MULTIPLA DESKTOP HOTEL ID(' . $hotel->id . '): ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";
                            }

                        }

                    } /*foreach clienti */
                
                endif;

        } /*end count($clienti)*/

        /**
         *
         * FINE
         *
         * 15/05/2020 @Luigi
         * END LOOP HOTEL e n Mail Singole
         */

        /**
         * Iscrivo l'email alla newsletter
         */

        $subscribe = "";
        if (!$spam && isset($my_input['newsletter'])) {
            $subscribe = Utility::mailUpSubscribe($email_mittente);
        }

        /**
         * $ids_to_fill_cookie viene messo in session solo per la request successiva (flash data)
         * MA il middleware CheckSendCookie lo trova in session
         */

        $ids_to_fill_cookie = $ids_send_mail;

        /**
         * Vado sempre alla thankyoupage anche in caso di email doppia
         */

        $parameters_to_pass = [
            'subscribe' => $subscribe,
            'subscribe_name' => $nome_mittente,
            'subscribe_email' => $email_mittente,
            'subscribe_phone' => $telefono_mittente,
            'ids_send_mail' => $ids_send_mail,
            'redirect_url' => $referer,
            'actual_link' => $referer,
            'listing' => "no_listing",
            'multipla' => 'multipla',
            'mobile' => 'desktop',
            'ids_to_fill_cookie' => $ids_to_fill_cookie,
            'localita' => $multiple_loc,
            'categoria' => $categoria,
        ];

        if (!$spam && !$email_doppia) {

            $nome_reply = trim($my_input['nome']);
            $mail_reply = $my_input['email'];

            $parameters_to_pass['nome_reply'] = $nome_reply;
            $parameters_to_pass['mail_reply'] = $mail_reply;
            $parameters_to_pass['dati_mail_reply'] = $dati_mail_reply;
            $parameters_to_pass['dati_json'] = $dati_json;

        }

        return redirect(
            Utility::getLocaleUrl($locale) . 'thankyou_multipla')
            ->with($parameters_to_pass);

    }

    /**
     * Spedisce una email multipla da Mobile
     * Ultima modifica: 17/04/2018 @giovanni
     * Controllo spedizione email multipla
     *
     * @param richiestaMailMultiplaMobileRequest $request
     * @return Redirect
     */

    public function richiestaMailMultiplaMobile(richiestaMailMultiplaMobileRequest $request)
    {

        $locale = $this->getLocale();
        $my_input = $request->input(); // array()
        $date = Utility::getLocalDate(Carbon::now(), '%H:%M:%S');
        $spedisci_email_duplicate = Config::get("mail.spedisci_email_duplicate");
        $spedisci_sempre_email_dirette = Config::get("mail.spedisci_sempre_email_dirette");
        $add_copy_email = Config::get("mail.add_copy_email");
        $debug_email = Config::get("mail.debug_email");
        $ids_send_mail = $my_input['ids_send_mail'];
        $trattamento = $my_input['trattamento'];

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<b>Variabili impostate</b><br />";
            echo Utility::echoDebug("add_copy_email", $add_copy_email);
            echo Utility::echoDebug("spedisci_sempre_email_dirette", $spedisci_sempre_email_dirette);
            echo Utility::echoDebug("spedisci_email_duplicate", $spedisci_email_duplicate);
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Campi singoli
         */

        /**
         * Se non esiste una pagina creo una pagina di servizio
         */

        if (isset($my_input['cms_pagina_id'])) {
            $cms_pagina_id = $my_input['cms_pagina_id'];
            $cms_pagina = CmsPagina::find($cms_pagina_id);
        } else {
            $cms_pagina_id = null;
            $cms_pagina = Utility::getFeaturedPage();
        }

        $actual_link = isset($my_input['actual_link']) ? $my_input['actual_link'] : '';
        $referer = isset($my_input['referer']) ? $my_input['referer'] : '';
        $spam = Utility::checkSenderMailBlacklisted($my_input['email']);
        $ip = Utility::get_client_ip();

        /**
         * 27/04/2020:
         *
         * se c'è almeno una data < 30/09/2020 allora FILTRO gli hotel chiusi aggiungendo lo scope ->notChiusoTemp()
         * altrimenti rilasso questo vincolo perché posso considerare anche gli hotel chiusi
         *
         */

        $vincolo_solo_aperti = false;

        foreach ($request->get('arrivo') as $data_arrivo) {
            // se c'è almeno una data < 30/09/2020 allora tolgo gli hotel chiusi
            if (Carbon::createFromFormat('d/m/Y', $data_arrivo)->lessThan(Utility::getArrvioSeChiuso())) {
                $vincolo_solo_aperti = true;
                break;
            }

        }

        // è un array che contiene tutti i trattamenti di tutte le camere
        // se ho mp nella camera 1 e mp nella camera 2 li ho selezionati entrambi
        // e quindi considero mp_s
        $trattamento_filtrato = Self::_filtraTrattamentoSql($trattamento);

        // i trattamenti che hanno anche la versione con la spiaggia sono
        // trattamento_mp, trattamento_bb, trattamento_sd
        $trattamenti_con_spiaggia = Utility::getTrattamentiSpiaggia();

        // ATTENZIONE:  @Lucio&@Luigi 06/11/2020
        //  se seleziono mp => voglio mp oppure mp_s
        //  se seleziono mp_s voglio SOLO mp_s
        //  QUINDI se HO SELEZIONATO ENTRAMBI I TRATTAMENTI (con e senza spiaggia)
        //  VINCE QUELLO PIU' INCLUSIVO (quello che contiene anche la spiaggia)

        Self::_gestisciTrattamentiSpiaggia($trattamento_filtrato, $trattamenti_con_spiaggia);

        /**
         * Trovo i clienti
         * Ne prendo al massimo 25 a caso
         * 
         * NOTA: Su questa query è stata sollevata una obiezione
         * Tutti i trattamenti sono infilati in AND quindi spesso non troviamo risultati
         * All'utente arriva un alert.
         * 
         * @Lucio&@Giovanni@Gigi il 24/11/2020 ne abbiamo parlato e abbiamo deciso di lasciarla
         * cosi anche se la soluzione e quella meno corretta
         * 
         */

        $clienti = Hotel::whereIn('id', explode(',', $ids_send_mail))
            ->where(function ($query) use ($trattamento_filtrato, $trattamenti_con_spiaggia) {
                foreach ($trattamento_filtrato as $trat):
                    if ($trat != '0') {
                        if (in_array($trat, $trattamenti_con_spiaggia)) {
                            $query->where(function ($q) use ($trat) {
                                $q->where($trat, 1)
                                  ->orWhere($trat . '_spiaggia', 1);
                            });
                        } else {
                            $query->where("$trat", 1);
                        }
                    }
                endforeach;
            })
            ->attivo();


        if ($vincolo_solo_aperti) {
            $clienti = $clienti->notChiusoTemp();
        }

        $clienti = $clienti->orderByRaw("RAND()")->take(25)->get();
        $ids_send_mail = implode(",", $clienti->pluck('id')->toArray());

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo Utility::echoDebug("id", $ids_send_mail);
        }

        /* --------------------------- DEBUG --------------------------- */

        /**
         * Se non ho hotel do un alert
         */

        $num_hotel = $clienti->count();

        if ($num_hotel == 0) {

            Session::flash('flash_message', Lang::get('listing.no_hotel_found'));
            Session::flash('flash_message_important', true);
            return redirect(Utility::getUrlWithLang($locale, '/mail-multipla')); //->withInput();

        }

        /**
         * Controllo se sono spam
         */

        if (!$spam && isset($my_input['esca'])) {
            $esca = $my_input['esca'];
            $spam = Utility::checkSpider($esca);
        }

        /**
         * Campi soggiorno
         */

        $checkin = $my_input['arrivo'];
        $checkout = $my_input['partenza'];
        $meal_plan = $my_input['trattamento'];
        $adult = $my_input['adulti'];
        $children = $my_input['bambini'];
        $numero_camere = count($meal_plan); //$my_input['numero_camere'];

        $age_0 = isset($my_input['eta_0']) ? $my_input['eta_0'] : array();
        $age_1 = isset($my_input['eta_1']) ? $my_input['eta_1'] : array();
        $age_2 = isset($my_input['eta_2']) ? $my_input['eta_2'] : array();
        $age_3 = isset($my_input['eta_3']) ? $my_input['eta_3'] : array();
        $age_4 = isset($my_input['eta_4']) ? $my_input['eta_4'] : array();
        $age_5 = isset($my_input['eta_5']) ? $my_input['eta_5'] : array();

        /**
         * Campi nuovo cookie Prefiil
         */

        $prefill = array();
        $prefill["ids_send_mail"] = $ids_send_mail;
        $prefill["customer"] = $my_input['nome'];
        if ($prefill["customer"] == "") {
            $prefill["customer"] = __("labels.no_name");
        }

        $prefill["email"] = $my_input['email'];
        $prefill["phone"] = null;
        $prefill["whatsapp"] = null;
        $prefill["information"] = $my_input['richiesta'];
        $prefill["tag"] = "EM";
        $prefill["sender"] = "info-alberghi.com";
        $prefill["language"] = $locale;
        $prefill['type'] = 'mobile';
        $prefill["rooms"] = array();

        if (isset($my_input['flex_date']) && $my_input['flex_date'] == 1) {$prefill["flex_date"] = 1;} else { $prefill["flex_date"] = 0;}

        /**
         * Ciclo sulle camere
         */

        for ($i = 0; $i < $numero_camere; $i++) {

            $prefill["rooms"][$i] = array();
            $prefill["rooms"][$i]["checkin"] = $checkin[$i];
            $prefill["rooms"][$i]["checkout"] = $checkout[$i];
            $prefill["rooms"][$i]["adult"] = $adult[$i];
            $prefill["rooms"][$i]["children"] = $children[$i];
            $prefill["rooms"][$i]["meal_plan"] = $meal_plan[$i];
            $prefill["rooms"][$i]["flex_date"] = $prefill["flex_date"];

            $eta_bambini = array();
            if (isset($age_0[$i])) {$eta_bambini[0] = $age_0[$i];}
            if (isset($age_1[$i])) {$eta_bambini[1] = $age_1[$i];}
            if (isset($age_2[$i])) {$eta_bambini[2] = $age_2[$i];}
            if (isset($age_3[$i])) {$eta_bambini[3] = $age_3[$i];}
            if (isset($age_4[$i])) {$eta_bambini[4] = $age_4[$i];}
            if (isset($age_5[$i])) {$eta_bambini[5] = $age_5[$i];}

            $prefill["rooms"][$i]["age_children"] = implode(",", $eta_bambini);

        }

        /**
         * Trovo i dati che mi servono per il controllo delle email doppie
         */

        $old_prefill = CookieIA::getCookiePrefill();
        $new_prefill = $prefill;
        $codice_cookie = $codice_cookie = (array_key_exists('codice_cookie', $old_prefill)) ? $old_prefill['codice_cookie'] : Carbon::now()->timestamp . "_" . uniqid(rand(), true);

        /**
         * Tolgo tutti i campi che non necessitano confronto
         */

        $old_prefill = Utility::unsetEmailPrefill($old_prefill, "OLD", true);
        $new_prefill = Utility::unsetEmailPrefill($new_prefill, "NEW", true);

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Old prefill</b><br />";
            echo Utility::echoDebug("", $old_prefill);
            echo "<br /><br /><b>New prefill</b><br />";
            echo Utility::echoDebug("", $new_prefill);
            echo Utility::echoDebug("codice_cookie", $codice_cookie);
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Controllo che non sia doppia
         */

        $tipologia = "mobile";
        $email_doppia = false;
        $clienti_ids_arr = explode(",", $ids_send_mail);
        $clienti_ids_arr_not_sent = [];
        $new_num_hotel = count($clienti_ids_arr);

        /**
         * Se ho settato che le email doppie vannos sempre spedite
         * passo avanti altrimenti vado al seconco controllo
         */

        if (!$spedisci_email_duplicate) {

            /**
             * Controllo che queste email non sia stata spedita a questo hotel
             * da questo codice utente negli ultimi 3 giorni
             */

            $confronto = MailDoppie::controlloMailDoppie($codice_cookie, explode(",", $ids_send_mail), base64_encode(json_encode($new_prefill)));

            $clienti_ids_arr = $confronto["clienti_ids_arr"];
            $clienti_ids_arr_not_sent = $confronto["clienti_ids_arr_not_sent"];
            $new_num_hotel = count($clienti_ids_arr);

            if ($new_num_hotel != $num_hotel) {

                $tipologia = "doppia parziale";

                if ($new_num_hotel == 0) {
                    $tipologia = "doppia";
                    $email_doppia = true;
                }

            }

        }

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Risultato confronto</b><br />";
            echo Utility::echoDebug("tipologia", $tipologia);
            echo Utility::echoDebug("email_doppia", $email_doppia);
            echo Utility::echoDebug("id buoni", print_r($clienti_ids_arr, 1));
            echo Utility::echoDebug("id scartati", print_r($clienti_ids_arr_not_sent, 1));
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Se ho degli id validi o il massaggio non uguale al precedente allora scrivo l'email nel database
         *
         * Ho 3 casi:
         *
         * 1. Tutte le email sono buone quindi non scrivo la row aggregata
         * 2. Telle le email non sono buone quindi scrivo la row aggregata
         * 3. Solo alcune email sono buone quindi nella row aggregata scrivo gli id non validi
         *
         */

        $db_email = array();
        $camere_aggiuntive_api = [];

        if (!$spam) {

            /**
             * Aggiorno l'ultimo codice
             */

            MailDoppie::logEmailDoppie($codice_cookie, explode(",", $ids_send_mail), base64_encode(json_encode($new_prefill)), $debug_email);

            $ids_send_mail_db = explode(",", $ids_send_mail);

            /* Scrivo nel DB solo se c'è almeno una spedizione */
            if ($new_num_hotel > 0) {
                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>Ho $new_num_hotel hotel a cui spedire </b><br />";
                }
                /* --------------------------- DEBUG --------------------------- */

                $mail = new MailMultipla;

                $dati_aggiuntivi = DB::transaction(function () use ($debug_email, $prefill, $clienti_ids_arr, $clienti_ids_arr_not_sent, $referer, $ip, $numero_camere, $email_doppia, $old_prefill, $new_prefill, $ids_send_mail, $tipologia, $new_num_hotel, $num_hotel, $codice_cookie, &$db_email, &$camere_aggiuntive_api, $ids_send_mail_db, &$mail, $locale) {

                    $db_email["IP"] = $ip;
                    $db_email["lang_id"] = $locale;
                    $db_email["referer"] = $referer;
                    $db_email["camere"] = $numero_camere;
                    $db_email["tipologia"] = $tipologia;

                    // SE LA MIAL E' DOPPIA la marco come sync perché altrimenti il cron cerca di rispedirla
                    if ($email_doppia) {
                        $db_email["api_sync"] = true;
                    }

                    $db_email["nome"] = $prefill["customer"];
                    $db_email["email"] = $prefill["email"];
                    $db_email["telefono"] = null;
                    $db_email["whatsapp"] = null;
                    $db_email["richiesta"] = $prefill["information"];

                    $db_email["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkin"]);
                    $db_email["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkout"]);
                    $db_email["adulti"] = $prefill["rooms"][0]["adult"];
                    $db_email["bambini"] = $prefill["rooms"][0]["children"];
                    $db_email["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][0]["age_children"], $db_email["bambini"]);
                    $db_email["trattamento"] = $prefill["rooms"][0]["meal_plan"];
                    $db_email["date_flessibili"] = $prefill["rooms"][0]["flex_date"];

                    $mail->fill($db_email);
                    $mail->save();
                    $mail->clienti()->sync($clienti_ids_arr);

                    //////////////////////////
                    // Preparazione per API //
                    //////////////////////////
                    $db_email['id'] = $mail->id;

                    for ($i = 1; $i < $numero_camere; $i++) {

                        $db_email_tutte = array();
                        $db_email_tutte["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkin"]);
                        $db_email_tutte["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkout"]);
                        $db_email_tutte["trattamento"] = $prefill["rooms"][$i]["meal_plan"];
                        $db_email_tutte["adulti"] = $prefill["rooms"][$i]["adult"];
                        $db_email_tutte["bambini"] = $prefill["rooms"][$i]["children"];
                        $db_email_tutte["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][$i]["age_children"], $db_email_tutte["bambini"]);
                        $db_email_tutte["date_flessibili"] = $prefill["rooms"][$i]["flex_date"];
                        $camera_gg = $mail->camereAggiuntive()->create($db_email_tutte);

                        /**
                         * preparazione per API
                         */

                        $db_email_tutte['mailMultipla_id'] = $mail->id;
                        $db_email_tutte['id'] = $camera_gg->id;
                        $camere_aggiuntive_api[] = $db_email_tutte;

                    }

                    /**
                     * Inserimento per statistiche
                     */

                    //StatsHotelMailMultipla::addStatsHotelMailMultiple($ids_send_mail_db, 'mobile');
                    return true;

                });

                if (!$email_doppia) {

                    $mail_multipla_id = Self::_scriviMailMultiplaAPI($db_email, $camere_aggiuntive_api, $clienti_ids_arr);
                    

                    if ($mail_multipla_id != 0) {
                        // aggiorno a 1 il campo api_sync di mailScheda per il record
                        $mail->api_sync = true;
                        $mail->save();
                    }

                }

            } // if c'è almeno una spedizione

        } // if not spam

        /**
         * Scrivo la email
         */

        $dati_mail = array();
        $dati_mail_reply = array();

        /**
         * Scrivo il JSON da attaca alla email
         */

        $dati_json['politiche_canc'] = Lang::get('labels.politiche_canc_short');
        $dati_json['spiaggia'] = 0;
        $dati_json["customer"] = $prefill["customer"];
        $dati_json["email"] = $prefill["email"];
        $dati_json["phone"] = null;
        $dati_json["whatsapp"] = null;
        $dati_json["information"] = $prefill["information"];
        $dati_json["tag"] = "EM";
        $dati_json["sender"] = "info-alberghi.com";
        $dati_json["type"] = "Mobile";

        $dati_json["message_wa"] = "";
        $dati_json["information2"] = "";

        if ($locale == 'it') {
            $in_lingua = "";
            $dati_json["language"] = "it_IT";
        } else {
            $in_lingua = Utility::getLanguageIso($locale);
            $dati_json["language"] = $in_lingua;
        }

        /**
         * Ciclo sulle camere
         */

        for ($i = 0; $i < $numero_camere; $i++) {

            $dati_json["rooms"][$i] = array();
            $dati_json["rooms"][$i]["flex_date"] = $prefill["rooms"][$i]['flex_date'];
            $dati_json["rooms"][$i]["checkin"] = $prefill["rooms"][$i]['checkin'];
            $dati_json["rooms"][$i]["checkout"] = $prefill["rooms"][$i]['checkout'];
            $dati_json["rooms"][$i]["nights"] = Utility::night($prefill["rooms"][$i]['checkin'], $prefill["rooms"][$i]['checkout']);
            $dati_json["rooms"][$i]["adult"] = $prefill["rooms"][$i]['adult'];

            if (isset($prefill["rooms"][$i]['children']) && $prefill["rooms"][$i]['children'] > 0) {
                $dati_json["rooms"][$i]["children"] = explode(",", Utility::purgeMenoUno($prefill["rooms"][$i]['age_children'], $prefill["rooms"][$i]['children']));
            } else {
                $dati_json["rooms"][$i]["children"] = "";
            }
            if (strpos($prefill["rooms"][$i]['meal_plan'], "_spiaggia")) {
                $dati_json['spiaggia'] = 1;
            }
            $dati_json["rooms"][$i]["meal_plan"] = Utility::Trattamenti_json($prefill["rooms"][$i]['meal_plan']);

        }

        /**
         * Sono i dati aggiuntivi dell'email
         */

        $dati_mail['referer'] = $referer;
        $dati_mail['actual_link'] = $actual_link;
        $dati_mail['ip'] = $ip;
        $dati_mail['device'] = "Mobile";
        $dati_mail['hotels_contact'] = $new_num_hotel;
        $dati_mail['date_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%d %B %Y');
        $dati_mail['hour_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%R');
        $dati_mail['sign_email'] = Utility::putJsonMail($dati_json);

        /**
         * Preparo l'oggetto
         */

        $oggetto = Utility::getOggettoMail($numero_camere, $prefill, true);

        /**
         * E' spam ?
         * @var $spam integer Può assumere 3 valori: 0 - non è spam, 1 - Utente blacklistato, 2 - Spider
         */

        $bcc = array();

        $clienti = Hotel::with(['localita', 'localita.macrolocalita', 'stelle', 'caparreAttive'])->whereIn('id', $clienti_ids_arr)->get();

        if ($spam == 0) {

            self::_aggiornaContattiMailMultiple($clienti_ids_arr, $debug_email);

            /*
             * Invio il JSON ad un servizio API esterno
             */

            if ($add_copy_email) {
                $bcc = "testing.infoalberghi@gmail.com";
            }

        } else if ($spam == 1) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - utente BLACKLISTATO al modulo invio mail multiple Mobile";
            $bcc = array();

            // prendo un clieente solo perché la mail la mando a me facendo un loop sui clienti
            $clienti = $clienti->take(1);

        } else if ($spam == 2) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - SPIDER al modulo invio mail multiple Mobile";
            $bcc = array();

            // prendo un clieente solo perché la mail la mando a me facendo un loop sui clienti
            $clienti = $clienti->take(1);

            if (isset($esca)) {
                $oggetto .= "(campo esca = " . $esca . ")";
            }

        }

        /**
         * Normalizzo i dati per compilare il template
         */

        $email_mittente = $prefill["email"];
        $nome_mittente = $prefill["customer"];
        $telefono_mittente = $prefill["phone"];

        AcceptancePrivacy::addPrivacyRow($email_mittente, $ip, "Mobile");

        /**
         * Aggiorno i dati per la spedizione attraverso sendgrid
         */

        Utility::swapToSendGrid();

        /**
         *
         * 15/05/2020 @Luigi
         * LOOP HOTEL e n Mail Singole
         */

        if (count($clienti)) {

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                if ($spedisci_email_duplicate) {
                    echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                }

                echo "<br /><br /><b>Cosa spedisco</b><br />";
                echo Utility::echoDebug("oggetto", $oggetto);
            }
            /* --------------------------- DEBUG --------------------------- */

            /**
             * Se NON è una mail doppia (né doppia né doppia parziale) OPPURE ho abilitato la spedizione per le doppie
             * SPEDISCO
             */

            if (!$email_doppia || $spedisci_email_duplicate) {

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>SPEDISCO</b>";
                    die();
                }
                /* --------------------------- DEBUG --------------------------- */

                $email_type = "Multipla";

                if ($spam == 0) {

                    foreach ($clienti as $hotel) {

                        try
                        {

                            $to = Self::_ToEmail($hotel->email);
                            $dati_mail['hotel_name'] = $hotel->nome;
                            $dati_mail['hotel_id'] = $hotel->id;
                            $dati_mail['hotel_telefono'] = $hotel->telefoni_mobile_call;
                            $dati_mail['hotel_indirizzo'] = $hotel->indirizzo . ' - ' . $hotel->cap . ' - ' . $hotel->localita->macrolocalita->nome;
                            $dati_mail['hotel_rating'] = $hotel->stelle->nome;
                            $dati_mail['hotel_loc'] = $hotel->localita->nome;
                            $dati_mail['caparre'] = null;
                            $dati_mr['hotel_name'] = $hotel->nome;
                            $dati_mr['hotel_id'] = $hotel->id;
                            $dati_mr['hotel_rating'] = $hotel->stelle->nome;
                            $dati_mr['hotel_loc'] = $hotel->localita->nome;
                            $dati_mail_reply[$hotel->id] = $dati_mr;

                            /**
                             * Ciclo sulle camere
                             */

                            for ($i = 0; $i < $numero_camere; $i++) {

                                $dati_json["rooms"][$i]['caparre'] = $hotel->attachCaparreDalAl($dati_json["rooms"][$i]["checkin"], $dati_json["rooms"][$i]["checkout"], $locale);
                                // caparra associata all'hotel che nn viene sovrascritta da passare alla mail-reply
                                $dati_json["rooms"][$i]['caparra'][$hotel->id] = $dati_json["rooms"][$i]['caparre'];

                            }

                            Mail::send(
                                "emails.mail_scheda", compact('email_type', 'dati_mail', 'dati_json'),
                                function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente) {
                                    $message->from(Self::MM_RETURN_PATH, $nome_mittente);
                                    $message->replyTo($email_mittente);
                                    $message->returnPath(Self::MM_RETURN_PATH)->sender(Self::MM_RETURN_PATH)->to($to);
                                    if ($bcc != "") {
                                        $message->bcc($bcc);
                                    }

                                    $message->subject($oggetto);
                                }
                            );

                        } catch (Exception $e) {

                            /**
                             * Scrivo nel log
                             */

                            config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAIL MULTIPLA MOBILE HOTEL ID(' . $hotel->id . '): ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";

                        }

                    } /*foreach clienti */

                }

            } else {
                
                 Mail::send(
                    "emails.mail_scheda", compact('email_type', 'dati_mail', 'dati_json'),
                    function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente) {
                        $message->from(Self::MM_RETURN_PATH, $nome_mittente);
                        $message->replyTo($email_mittente);
                        $message->returnPath(Self::MM_RETURN_PATH)->sender(Self::MM_RETURN_PATH)->to($to);
                        if ($bcc != "") {
                            $message->bcc($bcc);
                        }
                        $message->subject($oggetto);
                    }
                );

            } /*end if(!$email_doppia || $spedisci_email_duplicate)*/

        } /*end count($clienti)*/

        /**
         * Email ai risponditori
         */

        if (count($clienti)) {

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                if ($spedisci_email_duplicate) {
                    echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                }

                echo "<br /><br /><b>Cosa spedisco</b><br />";
                echo Utility::echoDebug("oggetto", $oggetto);
            }
            /* --------------------------- DEBUG --------------------------- */

            /**
             * Se NON è una mail doppia (né doppia né doppia parziale) OPPURE ho abilitato la spedizione per le doppie
             * SPEDISCO
             */

            if (!$email_doppia || $spedisci_email_duplicate):

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>SPEDISCO</b>";
                    die();
                }
                /* --------------------------- DEBUG --------------------------- */

                $email_type = "Multipla";

                    foreach ($clienti as $hotel) {

                        if ($hotel->email_risponditori != "" && $spam == 0) {

                            try
                            {

                                $email_risponditori = Self::_ToEmail($hotel->email_risponditori);
                                $dati_mail['hotel_name'] = $hotel->nome;
                                $dati_mail['hotel_id'] = $hotel->id;
                                $dati_mail['hotel_telefono'] = $hotel->telefoni_mobile_call;
                                $dati_mail['hotel_indirizzo'] = $hotel->indirizzo . ' - ' . $hotel->cap . ' - ' . $hotel->localita->macrolocalita->nome;
                                $dati_mail['hotel_rating'] = $hotel->stelle->nome;
                                $dati_mail['hotel_loc'] = $hotel->localita->nome;
                                $dati_mail['caparre'] = null;
                                $dati_mr['hotel_name'] = $hotel->nome;
                                $dati_mr['hotel_id'] = $hotel->id;
                                $dati_mr['hotel_rating'] = $hotel->stelle->nome;
                                $dati_mr['hotel_loc'] = $hotel->localita->nome;
                                $dati_mail_reply[$hotel->id] = $dati_mr;

                                $dati_json_new = $dati_json;
                                if ($dati_json["spiaggia"]) {
                                    $dati_json_new["information"] .= '[s][b]Richiesta supplemento spiaggia[/b] - Il cliente ha richiesto almeno un preventivo con la spiaggia inclusa.[/s]';
                                }
            
                                $dati_mail['sign_email'] = Utility::putJsonMail($dati_json_new, true);

                                /**
                                 * Ciclo sulle camere
                                 */

                                for ($i = 0; $i < $numero_camere; $i++) {

                                    $dati_json["rooms"][$i]['caparre'] = $hotel->attachCaparreDalAl($dati_json["rooms"][$i]["checkin"], $dati_json["rooms"][$i]["checkout"], $locale);
                                    // caparra associata all'hotel che nn viene sovrascritta da passare alla mail-reply
                                    $dati_json["rooms"][$i]['caparra'][$hotel->id] = $dati_json["rooms"][$i]['caparre'];

                                }

                                Mail::send(
                                    "emails.mail_scheda", compact('email_type', 'dati_mail', 'dati_json'),
                                    function ($message) use ($email_mittente, $email_risponditori, $oggetto, $nome_mittente) {
                                        $message->from(Self::MM_RETURN_PATH, $nome_mittente);
                                        $message->replyTo($email_mittente);
                                        $message->returnPath(Self::MM_RETURN_PATH)->sender(Self::MM_RETURN_PATH)->to($email_risponditori);
                                        $message->subject($oggetto);
                                    }
                                );

                            } catch (Exception $e) {

                                /**
                                 * Scrivo nel log
                                 */

                                config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAIL MULTIPLA MOBILE AI RISPONDITORI HOTEL ID(' . $hotel->id . '): ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";
                            }

                        } 

                    }/*foreach clienti */

            endif; /*end if(!$email_doppia || $spedisci_email_duplicate)*/

        } /*end count($clienti)*/


        /**
         *
         * END 15/05/2020 @Luigi
         * LOOP HOTEL e n Mail Singole
         */

        /**
         * Iscrivo l'email alla newsletter
         */

        $subscribe = "";
        if (!$spam && isset($my_input['newsletter'])) {
            $subscribe = Utility::mailUpSubscribe($email_mittente);
        }

        /**
         * Vado sempre alla thankyoupage anche in caso di email doppia
         */

        $parameters_to_pass = ['subscribe' => $subscribe,
            'subscribe_name' => $nome_mittente,
            'subscribe_email' => $email_mittente,
            'subscribe_phone' => $telefono_mittente,
            'ids_send_mail' => $ids_send_mail,
            'redirect_url' => $actual_link,
            'actual_link' => "",
            'cms_pagina_id' => $cms_pagina->id,
            'listing' => "no_listing",
            'multipla' => 'multipla',
            'mobile' => 'mobile',
        ];

        if (!$spam && !$email_doppia) {

            $nome_reply = trim($my_input['nome']);
            $mail_reply = $my_input['email'];

            $parameters_to_pass['nome_reply'] = $nome_reply;
            $parameters_to_pass['mail_reply'] = $mail_reply;
            $parameters_to_pass['dati_mail_reply'] = $dati_mail_reply;
            $parameters_to_pass['dati_json'] = $dati_json;

        }

        return redirect(
            Utility::getLocaleUrl($locale) . 'thankyou_multipla')
            ->with($parameters_to_pass);

    }

    /**
     * Spedisce una wishlist da Desktop
     * Ultima modifica: 17/04/2018 @giovanni
     * Controllo spedizione email multipla
     *
     * @param RichiestaWishlistRequest $request
     * @return Redirect
     */

    public function richiestaWishlist(RichiestaWishlistRequest $request)
    {

        $locale = $this->getLocale();
        $my_input = $request->input(); // array()
        $ids_send_mail = $request->get('ids_send_mail');
        $ids_send_mail = Self::_del_hotel_chiuso_temp($request->get('arrivo'), $ids_send_mail);

        /**
         * Se non ho hotel selezionati torno alla pagina in sessione
         */

        if ($ids_send_mail == "") {

            $page_to_return = $value = $request->session()->get('last_listing_page', '/');
            return redirect($page_to_return);

        }

        $date = Utility::getLocalDate(Carbon::now(), '%H:%M:%S');
        $add_copy_email = Config::get("mail.add_copy_email");
        $spedisci_email_duplicate = Config::get("mail.spedisci_email_duplicate");
        $spedisci_sempre_email_dirette = Config::get("mail.spedisci_sempre_email_dirette");
        $ids_send_mail = Self::_riordinaELimita($ids_send_mail);
        $debug_email = Config::get("mail.debug_email");

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<b>Variabili impostate</b><br />";
            echo Utility::echoDebug("add_copy_email", $add_copy_email);
            echo Utility::echoDebug("spedisci_sempre_email_dirette", $spedisci_sempre_email_dirette);
            echo Utility::echoDebug("spedisci_email_duplicate", $spedisci_email_duplicate);
            echo Utility::echoDebug("id", $ids_send_mail);
        }
        /* --------------------------- DEBUG --------------------------- */

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<b>Dati spediti</b><br />";
            echo Utility::echoDebug("spedisci_email_duplicate", $my_input);
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Campi singoli
         */

        $num_hotel = count(explode(',', $ids_send_mail));
        $referer = isset($my_input['referer']) ? $my_input['referer'] : '';
        $actual_link = isset($my_input['actual_link']) ? $my_input['actual_link'] : '';
        $spam = Utility::checkSenderMailBlacklisted($my_input['email']);
        $ip = Utility::get_client_ip();

        /**
         * Se non ho hotel do un alert
         */

        if ($num_hotel == 0) {

            Session::flash('flash_message', Lang::get('listing.no_hotel_found'));
            Session::flash('flash_message_important', true);
            return redirect(Utility::getUrlWithLang($locale, '/mail-multipla')); //->withInput();

        }

        /**
         * Controllo se sono spam
         */

        if (!$spam && isset($my_input['esca'])) {
            $esca = $my_input['esca'];
            $spam = Utility::checkSpider($esca);
        }

        /**
         * Campi soggiorno
         */

        $checkin = $my_input['arrivo'];
        $checkout = $my_input['partenza'];
        $meal_plan = $my_input['trattamento'];
        $adult = $my_input['adulti'];
        $children = $my_input['bambini'];
        $numero_camere = count($meal_plan); //$my_input['numero_camere'];

        $age_0 = isset($my_input['eta_0']) ? $my_input['eta_0'] : array();
        $age_1 = isset($my_input['eta_1']) ? $my_input['eta_1'] : array();
        $age_2 = isset($my_input['eta_2']) ? $my_input['eta_2'] : array();
        $age_3 = isset($my_input['eta_3']) ? $my_input['eta_3'] : array();
        $age_4 = isset($my_input['eta_4']) ? $my_input['eta_4'] : array();
        $age_5 = isset($my_input['eta_5']) ? $my_input['eta_5'] : array();

        /**
         * Campi nuovo cookie Prefiil
         */

        $prefill = array();
        $prefill["ids_send_mail"] = $ids_send_mail;
        $prefill["customer"] = $my_input['nome'];
        if ($prefill["customer"] == "") {
            $prefill["customer"] = __("labels.no_name");
        }

        $prefill["email"] = $my_input['email'];
        $prefill["information"] = $my_input['richiesta'];
        $prefill["phone"] = null;
        $prefill["whatsapp"] = null;
        $prefill["tag"] = "EM-WL";
        $prefill["sender"] = "info-alberghi.com";
        $prefill["language"] = $locale;
        $prefill['type'] = 'desktop';
        $prefill["rooms"] = array();

        if (isset($my_input['flex_date']) && $my_input['flex_date'] == 1) {$prefill["flex_date"] = 1;} else { $prefill["flex_date"] = 0;}

        /**
         * Ciclo sulle camere
         */

        for ($i = 0; $i < $numero_camere; $i++) {

            $prefill["rooms"][$i] = array();
            $prefill["rooms"][$i]["checkin"] = $checkin[$i];
            $prefill["rooms"][$i]["checkout"] = $checkout[$i];
            $prefill["rooms"][$i]["adult"] = $adult[$i];
            $prefill["rooms"][$i]["children"] = $children[$i];
            $prefill["rooms"][$i]["meal_plan"] = $meal_plan[$i];
            $prefill["rooms"][$i]["flex_date"] = $prefill["flex_date"];

            $eta_bambini = array();
            if (isset($age_0[$i])) {$eta_bambini[0] = $age_0[$i];}
            if (isset($age_1[$i])) {$eta_bambini[1] = $age_1[$i];}
            if (isset($age_2[$i])) {$eta_bambini[2] = $age_2[$i];}
            if (isset($age_3[$i])) {$eta_bambini[3] = $age_3[$i];}
            if (isset($age_4[$i])) {$eta_bambini[4] = $age_4[$i];}
            if (isset($age_5[$i])) {$eta_bambini[5] = $age_5[$i];}

            $prefill["rooms"][$i]["age_children"] = implode(",", $eta_bambini);

        }

        /**
         * Trovo i dati che mi servono per il controllo delle email doppie
         */

        $old_prefill = CookieIA::getCookiePrefill();
        $new_prefill = $prefill;
        $codice_cookie = (array_key_exists('codice_cookie', $old_prefill)) ? $old_prefill['codice_cookie'] : Carbon::now()->timestamp . "_" . uniqid(rand(), true);

        /**
         * Tolgo tutti i campi che non necessitano confronto
         */

        $old_prefill = Utility::unsetEmailPrefill($old_prefill, "OLD", true);
        $new_prefill = Utility::unsetEmailPrefill($new_prefill, "NEW", true);

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Old prefill</b><br />";
            echo Utility::echoDebug("", $old_prefill);
            echo "<br /><br /><b>New prefill</b><br />";
            echo Utility::echoDebug("", $new_prefill);
            echo Utility::echoDebug("codice_cookie", $codice_cookie);
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Controllo che non sia doppia
         */

        $tipologia = "wishlist";
        $email_doppia = false;
        $clienti_ids_arr = explode(",", $ids_send_mail);
        $clienti_ids_arr_not_sent = [];
        $new_num_hotel = count($clienti_ids_arr);

        /**
         * Se ho settato che le email doppie vannos sempre spedite
         * passo avanti altrimenti vado al seconco controllo
         */

        if (!$spedisci_email_duplicate) {

            /**
             * Controllo che queste email non sia stata spedita a questo hotel
             * da questo codice utente negli ultimi 3 giorni
             */

            $confronto = MailDoppie::controlloMailDoppie($codice_cookie, explode(",", $ids_send_mail), base64_encode(json_encode($new_prefill)));

            /**
             * Metto negli oggetti giusti le due serie di arry che mi ritornano
             */

            $clienti_ids_arr = $confronto["clienti_ids_arr"];
            $clienti_ids_arr_not_sent = $confronto["clienti_ids_arr_not_sent"];
            $new_num_hotel = count($clienti_ids_arr);

            /**
             * Sel il numero di hotel è cambiato rispetto al precedente ho delle email doppie
             */

            if ($new_num_hotel != $num_hotel) {

                $tipologia = "doppia parziale";

                /**
                 * Se sono a 0 allora è la stessa email identica
                 */

                if ($new_num_hotel == 0) {
                    $tipologia = "doppia";
                    $email_doppia = true;
                }

            }

        }

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Risultato confronto</b><br />";
            echo Utility::echoDebug("tipologia", $tipologia);
            echo Utility::echoDebug("email_doppia", $email_doppia);
            echo Utility::echoDebug("id buoni", print_r($clienti_ids_arr, 1));
            echo Utility::echoDebug("id scartati", print_r($clienti_ids_arr_not_sent, 1));
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Scrivo sempre le email nel database marcandola come doppia in caso di replica
         */

        $db_email = array();
        $camere_aggiuntive_api = [];

        if (!$spam) {

            MailDoppie::logEmailDoppie($codice_cookie, explode(",", $ids_send_mail), base64_encode(json_encode($new_prefill)), $debug_email);

            $ids_send_mail_db = explode(",", $ids_send_mail);

            /* Scrivo nel DB solo se c'è almeno una spedizione */
            if ($new_num_hotel > 0) {
                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>Ho $new_num_hotel hotel a cui spedire </b><br />";
                }
                /* --------------------------- DEBUG --------------------------- */

                $mail = new MailMultipla;

                $dati_aggiuntivi = DB::transaction(function () use ($debug_email, $prefill, $clienti_ids_arr, $clienti_ids_arr_not_sent, $referer, $ip, $numero_camere, $email_doppia, $old_prefill, $new_prefill, $ids_send_mail, $tipologia, $new_num_hotel, $num_hotel, $codice_cookie, &$db_email, &$camere_aggiuntive_api, $ids_send_mail_db, &$mail, $locale) {

                    $db_email["IP"] = $ip;
                    $db_email["lang_id"] = $locale;
                    $db_email["referer"] = $referer;
                    $db_email["camere"] = $numero_camere;
                    $db_email["tipologia"] = $tipologia;

                    // SE LA MIAL E' DOPPIA la marco come sync perché altrimenti il cron cerca di rispedirla
                    if ($email_doppia) {
                        if ($debug_email) {
                            echo "<br /><br /><b>Mail doppia o doppia parziale API</b><br />";
                        }
                        $db_email["api_sync"] = true;
                    }

                    $db_email["nome"] = $prefill["customer"];
                    $db_email["email"] = $prefill["email"];
                    $db_email["telefono"] = null;
                    $db_email["whatsapp"] = null;
                    $db_email["richiesta"] = $prefill["information"];

                    $db_email["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkin"]);
                    $db_email["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkout"]);
                    $db_email["adulti"] = $prefill["rooms"][0]["adult"];
                    $db_email["bambini"] = $prefill["rooms"][0]["children"];
                    $db_email["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][0]["age_children"], $db_email["bambini"]);
                    $db_email["trattamento"] = $prefill["rooms"][0]["meal_plan"];
                    $db_email["date_flessibili"] = $prefill["rooms"][0]["flex_date"];

                    $mail->fill($db_email);
                    $mail->save();
                    $mail->clienti()->sync($clienti_ids_arr);

                    //////////////////////////
                    // Preparazione per API //
                    //////////////////////////
                    $db_email['id'] = $mail->id;

                    /**
                     * Ciclo sulle camere
                     */

                    for ($i = 1; $i < $numero_camere; $i++) {

                        $db_email_tutte = array();
                        $db_email_tutte["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkin"]);
                        $db_email_tutte["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkout"]);
                        $db_email_tutte["trattamento"] = $prefill["rooms"][$i]["meal_plan"];
                        $db_email_tutte["adulti"] = $prefill["rooms"][$i]["adult"];
                        $db_email_tutte["bambini"] = $prefill["rooms"][$i]["children"];
                        $db_email_tutte["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][$i]["age_children"], $db_email_tutte["bambini"]);
                        $db_email_tutte["date_flessibili"] = $prefill["rooms"][$i]["flex_date"];

                        $camera_gg = $mail->camereAggiuntive()->create($db_email_tutte);

                        //////////////////////////
                        // preparazione per API //
                        //////////////////////////
                        $db_email_tutte['mailMultipla_id'] = $mail->id;
                        $db_email_tutte['id'] = $camera_gg->id;
                        $camere_aggiuntive_api[] = $db_email_tutte;

                    }

                    /**
                     * Inserimento per statistiche
                     */

                    ///StatsHotelMailMultipla::addStatsHotelMailMultiple($ids_send_mail_db, 'wishlist');

                    return true;

                });

                if (!$email_doppia) {

                    $mail_multipla_id = Self::_scriviMailMultiplaAPI($db_email, $camere_aggiuntive_api, $clienti_ids_arr);

                    if ($mail_multipla_id != 0) {
                        // aggiorno a 1 il campo api_sync di mailScheda per il record
                        $mail->api_sync = true;
                        $mail->save();
                    }

                }

            } // if c'è almeno una spedizione

        } // if !spam

        /**
         * Scrivo la email
         */

        $dati_mail = array();
        $dati_json = array();
        $dati_mail_reply = array();

        /**
         * Scrivo il JSON da attaca alla email
         */
        $dati_json['politiche_canc'] = Lang::get('labels.politiche_canc_short');
        $dati_json['spiaggia'] = 0;
        $dati_json["customer"] = $prefill["customer"];
        $dati_json["email"] = $prefill["email"];
        $dati_json["phone"] = null;
        $dati_json["whatsapp"] = null;
        $dati_json["information"] = $prefill["information"];
        $dati_json["tag"] = "EM-WL";
        $dati_json["sender"] = "info-alberghi.com";
        $dati_json["type"] = "desktop";

        $dati_json["message_wa"] = "";
        $dati_json["information2"] = "";

        if ($locale == 'it') {
            $in_lingua = "";
            $dati_json["language"] = "it_IT";
        } else {
            $in_lingua = Utility::getLanguageIso($locale);
            $dati_json["language"] = $in_lingua;
        }

        /**
         * Ciclo sulle camere
         */

        for ($i = 0; $i < $numero_camere; $i++) {

            $dati_json["rooms"][$i] = array();
            $dati_json["rooms"][$i]["flex_date"] = $prefill["rooms"][$i]['flex_date'];
            $dati_json["rooms"][$i]["checkin"] = $prefill["rooms"][$i]['checkin'];
            $dati_json["rooms"][$i]["checkout"] = $prefill["rooms"][$i]['checkout'];
            $dati_json["rooms"][$i]["nights"] = Utility::night($prefill["rooms"][$i]['checkin'], $prefill["rooms"][$i]['checkout']);
            $dati_json["rooms"][$i]["adult"] = $prefill["rooms"][$i]['adult'];

            if (isset($prefill["rooms"][$i]['children']) && $prefill["rooms"][$i]['children'] > 0) {
                $dati_json["rooms"][$i]["children"] = explode(",", Utility::purgeMenoUno($prefill["rooms"][$i]['age_children'], $prefill["rooms"][$i]['children']));
            } else {
                $dati_json["rooms"][$i]["children"] = "";
            }
            if (strpos($prefill["rooms"][$i]['meal_plan'], "_spiaggia")) {
                $dati_json['spiaggia'] = 1;
            }
            $dati_json["rooms"][$i]["meal_plan"] = Utility::Trattamenti_json($prefill["rooms"][$i]['meal_plan']);

        }

        $dati_json["message_wa"] = "";
        // $dati_json["message_wa"] = Self::_getMessageWA($dati_json, $numero_camere);

        /**
         * Preparo il codice HTML da passare all'email
         */

        $dati_mail['referer'] = $referer;
        $dati_mail['actual_link'] = $actual_link;
        $dati_mail['ip'] = $ip;
        $dati_mail['device'] = "Computer";
        $dati_mail['hotels_contact'] = $new_num_hotel;
        $dati_mail['date_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%d %B %Y');
        $dati_mail['hour_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%R');
        $dati_mail['sign_email'] = Utility::putJsonMail($dati_json);

        /**
         * Preparo l'oggetto
         */

        $oggetto = Utility::getOggettoMail($numero_camere, $prefill, false);

        /**
         * E' spam ?
         *
         * @var $spam integer Può assumere 3 valori: 0 - non è spam, 1 - Utente blacklistato, 2 - Spider
         */

        $bcc = array();

        $clienti = Hotel::with(['localita', 'localita.macrolocalita', 'stelle', 'caparreAttive'])->whereIn('id', $clienti_ids_arr)->get();

        if ($spam == 0) {

           

            self::_aggiornaContattiMailMultiple($clienti_ids_arr, $debug_email);

            /*
             * Invio il JSON ad un servizio API esterno
             */

            if ($add_copy_email) {
                $bcc = "testing.infoalberghi@gmail.com";
            }

        } else if ($spam == 1) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - utente BLACKLISTATO al modulo invio mail multiple";

            // prendo un clieente solo perché la mail la mando a me facendo un loop sui clienti
            $clienti = $clienti->take(1);

        } else if ($spam == 2) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - SPIDER al modulo invio mail multiple";

            // prendo un clieente solo perché la mail la mando a me facendo un loop sui clienti
            $clienti = $clienti->take(1);

            if (isset($esca)) {
                $oggetto .= "(campo esca = " . $esca . ")";
            }

        }

        /**
         * Normalizzo i dati per compilare il template
         */

        $email_mittente = $prefill["email"];
        $nome_mittente = $prefill["customer"];
        $telefono_mittente = $prefill["phone"];

        AcceptancePrivacy::addPrivacyRow($email_mittente, $ip, "Computer");

        /**
         * Aggiorno i dati per la spedizione attraverso sendgrid
         */

        Utility::swapToSendGrid();

        /**
         *
         * 14/05/2020 @Luigi
         * LOOP HOTEL e n Mail Singole
         */
        $sending_error = 0;
        if (count($clienti)) {

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                if ($spedisci_email_duplicate) {
                    echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                }

                echo "<br /><br /><b>Cosa spedisco</b><br />";
                echo Utility::echoDebug("oggetto", $oggetto);
            }
            /* --------------------------- DEBUG --------------------------- */

            /**
             * Se ho un numero di hotel a cui spedire dopo il confronto oppure $spedisci_email_duplicate == true
             * Attenzione se $spedisci_email_duplicate == true devo usare tutti gli ID
             */

            if (!$email_doppia || $spedisci_email_duplicate):

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>SPEDISCO</b>";
                    die();
                }
                /* --------------------------- DEBUG --------------------------- */

                $email_type = "Wishlist";

                if ($spam == 0) {

                    foreach ($clienti as $hotel) {

                        try
                        {
                            
                            $to = Self::_ToEmail($hotel->email);
                            $dati_mail['hotel_name'] = $hotel->nome;
                            $dati_mail['hotel_id'] = $hotel->id;
                            $dati_mail['hotel_telefono'] = $hotel->telefoni_mobile_call;
                            $dati_mail['hotel_indirizzo'] = $hotel->indirizzo . ' - ' . $hotel->cap . ' - ' . $hotel->localita->macrolocalita->nome;
                            $dati_mail['hotel_rating'] = $hotel->stelle->nome;
                            $dati_mail['hotel_loc'] = $hotel->localita->nome;
                            $dati_mail['caparre'] = null;

                            $dati_mr['hotel_name'] = $hotel->nome;
                            $dati_mr['hotel_id'] = $hotel->id;
                            $dati_mr['hotel_rating'] = $hotel->stelle->nome;
                            $dati_mr['hotel_loc'] = $hotel->localita->nome;

                            $dati_mail_reply[$hotel->id] = $dati_mr;

                            /**
                             * Ciclo sulle camere
                             */

                            for ($i = 0; $i < $numero_camere; $i++) {
                                $dati_json["rooms"][$i]['caparre'] = $hotel->attachCaparreDalAl($dati_json["rooms"][$i]["checkin"], $dati_json["rooms"][$i]["checkout"], $locale);

                                // caparra associata all'hotel che nn viene sovrascritta da passare alla mail-reply
                                $dati_json["rooms"][$i]['caparra'][$hotel->id] = $dati_json["rooms"][$i]['caparre'];

                            }
                            
                            Mail::send(
                                "emails.mail_scheda", compact('email_type', 'dati_mail', 'dati_json'),
                                function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente) {
                                    $message->from(Self::MM_RETURN_PATH, $nome_mittente);
                                    $message->replyTo($email_mittente);
                                    $message->returnPath(Self::MM_RETURN_PATH)->sender(Self::MM_RETURN_PATH)->to($to);
                                    if ($bcc != "") {
                                        $message->bcc($bcc);
                                    }

                                    $message->subject($oggetto);
                                }
                            );

                        } catch (\Exception $e) {

                            /**
                             * Scrivo nel log
                             */

                            config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAILWISHLIST HOTEL ID(' . $hotel->id . '): ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";
                        }

                    } /*foreach clienti */

                } else {

                    /** */
                    Mail::send(
                        "emails.mail_scheda", compact('email_type', 'dati_mail', 'dati_json'),
                        function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente) {
                            $message->from(Self::MM_RETURN_PATH, $nome_mittente);
                            $message->replyTo($email_mittente);
                            $message->returnPath(Self::MM_RETURN_PATH)->sender(Self::MM_RETURN_PATH)->to($to);
                            if ($bcc != "") {
                                $message->bcc($bcc);
                            }

                            $message->subject($oggetto);
                        }
                    );

                }

            endif; /*end if(!$email_doppia || $spedisci_email_duplicate)*/

        } /*if count(clienti)*/

        /**
         * Email ai risponditori
         */

        if (count($clienti)) {

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                if ($spedisci_email_duplicate) {
                    echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                }

                echo "<br /><br /><b>Cosa spedisco</b><br />";
                echo Utility::echoDebug("oggetto", $oggetto);
            }
            /* --------------------------- DEBUG --------------------------- */

            /**
             * Se ho un numero di hotel a cui spedire dopo il confronto oppure $spedisci_email_duplicate == true
             * Attenzione se $spedisci_email_duplicate == true devo usare tutti gli ID
             */

            if (!$email_doppia || $spedisci_email_duplicate):

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>SPEDISCO</b>";
                    die();
                }
                /* --------------------------- DEBUG --------------------------- */

                $email_type = "Wishlist";

                foreach ($clienti as $hotel) {

                    if ($hotel->email_risponditori != "" && $spam == 0) {

                        try
                        {

                            $email_risponditori = Self::_ToEmail($hotel->email_risponditori);
                            $dati_mail['hotel_name'] = $hotel->nome;
                            $dati_mail['hotel_id'] = $hotel->id;
                            $dati_mail['hotel_telefono'] = $hotel->telefoni_mobile_call;
                            $dati_mail['hotel_indirizzo'] = $hotel->indirizzo . ' - ' . $hotel->cap . ' - ' . $hotel->localita->macrolocalita->nome;
                            $dati_mail['hotel_rating'] = $hotel->stelle->nome;
                            $dati_mail['hotel_loc'] = $hotel->localita->nome;
                            $dati_mail['caparre'] = null;
                            
                            $dati_json_new = $dati_json;
                            if ($dati_json["spiaggia"]) {
                                $dati_json_new["information"] .= '[s][b]Richiesta supplemento spiaggia[/b] - Il cliente ha richiesto almeno un preventivo con la spiaggia inclusa.[/s]';
                            }
        
                            $dati_mail['sign_email'] = Utility::putJsonMail($dati_json_new, true);

                            $dati_mr['hotel_name'] = $hotel->nome;
                            $dati_mr['hotel_id'] = $hotel->id;
                            $dati_mr['hotel_rating'] = $hotel->stelle->nome;
                            $dati_mr['hotel_loc'] = $hotel->localita->nome;
                            $dati_mail_reply[$hotel->id] = $dati_mr;

                            /**
                             * Ciclo sulle camere
                             */

                            for ($i = 0; $i < $numero_camere; $i++) {
                                
                                $dati_json["rooms"][$i]['caparre'] = $hotel->attachCaparreDalAl($dati_json["rooms"][$i]["checkin"], $dati_json["rooms"][$i]["checkout"], $locale);

                                // caparra associata all'hotel che nn viene sovrascritta da passare alla mail-reply
                                $dati_json["rooms"][$i]['caparra'][$hotel->id] = $dati_json["rooms"][$i]['caparre'];

                            }

                            Mail::send(
                                "emails.mail_scheda", compact('email_type', 'dati_mail', 'dati_json'),
                                function ($message) use ($email_mittente, $email_risponditori, &$bcc, $oggetto, $nome_mittente) {
                                    $message->from(Self::MM_RETURN_PATH, $nome_mittente);
                                    $message->replyTo($email_mittente);
                                    $message->returnPath(Self::MM_RETURN_PATH)->sender(Self::MM_RETURN_PATH)->to($email_risponditori);
                                    $message->subject($oggetto);
                                }
                            );

                        } catch (\Exception $e) {

                            /**
                             * Scrivo nel log
                             */

                            config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAILWISHLIST AI RISPONDITORI HOTEL ID(' . $hotel->id . '): ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";
                        }

                    }

                } /*foreach clienti */

            endif; /*end if(!$email_doppia || $spedisci_email_duplicate)*/

        } /*if count(clienti)*/


        /**
         *
         * FINE
         *
         * 14/05/2020 @Luigi
         * LOOP HOTEL e n Mail Singole
         */

        /**
         * Iscrivo l'email alla newsletter
         */

        $subscribe = "";
        if (!$spam && isset($my_input['newsletter'])) {
            $subscribe = Utility::mailUpSubscribe($email_mittente);
        }

        /**
         * Vado sempre alla thankyoupage anche in caso di email doppia
         */

        $parameters_to_pass = ['subscribe' => $subscribe,
            'subscribe_name' => $nome_mittente,
            'subscribe_email' => $email_mittente,
            'subscribe_phone' => $telefono_mittente,
            'ids_send_mail' => $ids_send_mail,
            'redirect_url' => '/',
            'actual_link' => '/',
            'listing' => "no_listing",
            'multipla' => 'multipla',
            'mobile' => 'desktop'];

        if (!$spam && !$email_doppia) {

            $nome_reply = trim($my_input['nome']);
            $mail_reply = $my_input['email'];

            $parameters_to_pass['nome_reply'] = $nome_reply;
            $parameters_to_pass['mail_reply'] = $mail_reply;
            $parameters_to_pass['dati_mail_reply'] = $dati_mail_reply;
            $parameters_to_pass['dati_json'] = $dati_json;

        }

        return redirect(
            Utility::getLocaleUrl($locale) . 'thankyou_multipla')
            ->with($parameters_to_pass);

    }

    /* ------------------------------------------------------------------------------------
     * VIEWS
     * ------------------------------------------------------------------------------------ */

    /**
     * Wishlist view
     *
     * @access public
     * @param Request $request
     * @return View
     */

    public function wishlist(Request $request)
    {

        $locale = $this->getLocale();
        $menu_localita = Utility::getMenuLocalita($locale);
        $wishlist = 1;

        $hreflang_it = url('/') . "/form_wishlist.php";
        $hreflang_en = url('/') . "/ing/form_wishlist.php";
        $hreflang_fr = url('/') . "/fr/form_wishlist.php";
        $hreflang_de = url('/') . "/ted/form_wishlist.php";

        $prefill = CookieIA::getCookiePrefill();
        $referer = request::get("referer");

        $numero_camere = array_key_exists('rooms', $prefill) ? count($prefill['rooms']) : 0;
        $flex_date_value = array_key_exists('flex_date_value', $prefill) ? $prefill['flex_date_value'] : "";

        if ($numero_camere == 0) {

            $numero_camere = 1;
            $prefill["rooms"] = CookieIA::getCookieRoomPrefill();

        }

        if (Request::has('ids_send_mail')) {

            $ids_send_mail = Request::get('ids_send_mail');
            if ($ids_send_mail == "") {
                abort('404');
            }

        } else {
            abort('404');
        }

        $ids_send_mail = Self::_riordinaELimita($ids_send_mail);

        if ($ids_send_mail != "") {
            $count_ids_send_mail = explode(",", $ids_send_mail);
        } else {
            $count_ids_send_mail = 0;
        }

        $clienti = Hotel::find(explode(',', $ids_send_mail));
        $menu_tematico = Utility::getMenuTematico($locale, Utility::getMacroRR(), Utility::getMicroRR());

        $privacy = AcceptancePrivacy::getCheckForm($prefill["email"]);

        return View::make('templates.mail_multipla',
            compact(
                'count_ids_send_mail',
                'referer',
                'menu_tematico',
                'hreflang_it',
                'hreflang_en',
                'hreflang_fr',
                'hreflang_de',
                'clienti',
                'prefill',
                'locale',
                'ids_send_mail',
                'wishlist',
                'menu_localita',
                'numero_camere',
                'flex_date_value',
                'privacy'
            )
        );

    }

    /**
     * Mail multipla
     *
     * @access public
     * @return View
     */

    public function mailMultipla()
    {

        $hreflang_it = url('/') . "/mail-multipla";
        $hreflang_en = url('/') . "/ing/mail-multipla";
        $hreflang_fr = url('/') . "/fr/mail-multipla";
        $hreflang_de = url('/') . "/ted/mail-multipla";
        $actual_link = "";
        $referer = "";

        $messaggioScrittura = false;

        $locale = $this->getLocale();
        $stelle = Categoria::real()->orderBy('ordinamento')->pluck('nome', 'id');

        $wishlist = 0;
        $prefill = [];

        $ids_send_mail = Request::get("ids_send_mail");

        if ($ids_send_mail != "") {
            $count_ids_send_mail = explode(",", $ids_send_mail);
        } else {
            $count_ids_send_mail = 0;
        }

        $prefill = CookieIA::getCookiePrefill();
        $numero_camere = array_key_exists('rooms', $prefill) ? count($prefill['rooms']) : 0;
        $flex_date_value = array_key_exists('flex_date_value', $prefill) ? $prefill['flex_date_value'] : "";

        if ($numero_camere == 0) {
            $numero_camere = 1;
            $prefill["rooms"] = CookieIA::getCookieRoomPrefill();
        }

        $loc = (array_key_exists('multiple_loc', $prefill)) ? $prefill['multiple_loc'] : null;

        if (is_null($loc)) {

            if ($guessedLoc = $this->_guessLoc()) {
                $prefill['multiple_loc'] = $guessedLoc;
            }

        }

        $menu_tematico = Utility::getMenuTematico($locale, Utility::getMacroRR(), Utility::getMicroRR());

        $privacy = AcceptancePrivacy::getCheckForm($prefill["email"]);

        return View::make(

            'templates.mail_multipla',
            compact(

                'hreflang_it',
                'hreflang_en',
                'hreflang_fr',
                'hreflang_de',
                'locale',
                'ids_send_mail',
                'count_ids_send_mail',
                'wishlist',
                'stelle',
                'prefill',
                'numero_camere',
                'flex_date_value',
                'messaggioScrittura',
                'menu_tematico',
                'actual_link',
                'referer',
                'privacy'
            )
        );

    }

    /**
     * Mail multipla mobile
     *
     * @access public
     * @return View
     */

    public function mailMultiplaMobile()
    {

        $locale = $this->getLocale();
        $hreflang_it = url('/') . "/mail-multipla";
        $hreflang_en = url('/') . "/ing/mail-multipla";
        $hreflang_fr = url('/') . "/fr/mail-multipla";
        $hreflang_de = url('/') . "/ted/mail-multipla";
        $referer = Request::get('actual_link');
        $actual_link = "";

        $messaggioScrittura = false;

        if (!Request::has('ids_send_mail') && !Session::has('ids_send_mail')) {
            abort("404");
        }

        /**
         * @Lucio ha richiesto che da mobile non vengano ridotte le email a 25
         * ( Solo per le pagine località )
         */

        if (Request::has('ids_send_mail')) {

            $ids_send_mail = Request::get('ids_send_mail');
            Session::forget('ids_send_mail');

        } else {

            // ATTENZIONE: è richiestaMailMultiplaMobileRequest che mette in session
            $ids_send_mail = Session::get('ids_send_mail');

        }

        /* trovo  $cms_pagina per il menu dell'header */
        if (Request::has('cms_pagina_id')) {
            $cms_pagina_id = Request::get('cms_pagina_id');
            Session::forget('cms_pagina_id');
        } else {
            // ATTENZIONE: è richiestaMailMultiplaMobileRequest che mette in session
            $cms_pagina_id = Session::get('cms_pagina_id');
        }

        $selezione_localita = Request::get('selezione_localita');
        $cms_pagina = CmsPagina::find($cms_pagina_id);

        $prefill = CookieIA::getCookiePrefill();
        $numero_camere = array_key_exists('rooms', $prefill) ? count($prefill['rooms']) : 0;
        $flex_date_value = array_key_exists('flex_date_value', $prefill) ? $prefill['flex_date_value'] : "";

        if ($numero_camere == 0) {

            $numero_camere = 1;
            $prefill["rooms"] = CookieIA::getCookieRoomPrefill();

        }

        $multipla = "si";

        $privacy = AcceptancePrivacy::getCheckForm($prefill["email"]);

        return View::make(

            'templates.form_mail_mobile',
            compact(

                'selezione_localita',
                'multipla',
                'hreflang_it',
                'hreflang_en',
                'hreflang_fr',
                'hreflang_de',
                'prefill',
                'locale',
                'ids_send_mail',
                'cms_pagina',
                'cms_pagina_id',
                'numero_camere',
                'flex_date_value',
                'messaggioScrittura',
                'referer',
                'actual_link',
                'privacy'

            )
        );

    }

    /**
     * Thankyou page
     *
     * @access public
     * @return View
     */

    public function thanks()
    {

        $ids_send_mail = "";
        $locale = $this->getLocale();
        $listing = "no_listing";
        $multipla = "diretta";
        $cms_pagina_id = "";
        $mobile = "desktop";

        if (Session::has('multipla')) {
            $multipla = Session::get('multipla');
        }

        if (Session::has('mobile')) {
            $mobile = Session::get('mobile');
        }

        /*if(Session::has('mail_reply') && Session::has('nome_reply') && Session::has('dati_mail_reply') && Session::has('dati_json'))
        {
        $mail_reply = Session::get('mail_reply');
        $nome_reply = Session::get('nome_reply');
        $dati_mail_reply = Session::get('dati_mail_reply');
        $dati_json = Session::get('dati_json');

        $this->reply_to_client($nome_reply, $mail_reply, $dati_mail_reply, $dati_json, $multipla, $mobile);

        }*/

        $subscribe = "";
        $subscribe_name = "";
        $subscribe_email = "";
        $subscribe_phone = "";

        if (Session::has('ids_send_mail')) {
            $ids_send_mail = Session::get('ids_send_mail');
            if (is_array($ids_send_mail)) {
                $ids_send_mail = join(",", $ids_send_mail);
            }

        }

        if (Session::has('listing')) {
            $listing = Session::get('listing');
        }

        if (Session::has('redirect_url')) {
            $redirect_url = Session::get('redirect_url');
        }

        if (Session::has('subscribe')) {
            $subscribe = Session::get('subscribe');
        }

        if (Session::has('subscribe_name')) {
            $subscribe_name = Session::get('subscribe_name');
        }

        if (Session::has('subscribe_email')) {
            $subscribe_email = Session::get('subscribe_email');
        }

        if (Session::has('subscribe_phone')) {
            $subscribe_phone = Session::get('subscribe_phone');
        }

        if (!isset($redirect_url)) {
            $redirect_url = '/';
        }

        $redirect_url = str_replace("&amp;", "&", $redirect_url);

        /**
         * @Luigi 27/01/2020
         *
         * Nuovo $redirect_url dalla sessione
         *
         */

        if (Session::has('last_listing_page')) {
            $redirect_url = url(Session::get('last_listing_page'));
        } elseif (Session::has('last_hotel_page')) {
            $redirect_url = Session::get('last_hotel_page');
        }

        $view = view('thankyoupage.thankyou_mail')->with(compact('subscribe', 'subscribe_name', 'subscribe_email', 'subscribe_phone', 'locale', 'redirect_url', 'listing', 'multipla', 'ids_send_mail', 'cms_pagina_id', 'mobile'));

        $response = new Response($view);

        return $response;

    }

} // end Class MailMultiplaController

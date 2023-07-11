<?php

/**
 * MailSchedaController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers;

use App\AcceptancePrivacy;
use App\CookieIA;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests\RichiediPreventivoRequest;
use App\Http\Requests\RichiestaMailSchedaMobileRequest;
use App\MailDoppie;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Request;

class MailSchedaController extends Controller
{

    const MS_RETURN_PATH = "richiesta@info-alberghi.com";

    /* ------------------------------------------------------------------------------------------------------------
     * METODI PRIVATI
     * ------------------------------------------------------------------------------------------------------------ */

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
     * Crea il messaggio WA da aggiugere all'email
     *
     * @access private
     * @static
     * @param Array $data
     * @param Number $numero_camere
     * @param String $hotel_name
     * @param Integer $hotel_id
     * @param String $hotel_rating
     * @param String $hotel_rating
     * @param String $hotel_loc
     * @return String
     */

    private static function _getMessageWA($data, $numero_camere, $hotel_name, $hotel_id, $hotel_rating, $hotel_loc)
    {

        $message = __("hotel.email_whatsapp");

        /**
         * Ciclo sulle camere
         */

        for ($i = 0; $i < $numero_camere; $i++) {

            if ($data["rooms"][$i]["nights"] == 1) {
                $notti = "notte";
            } else {
                $notti = "notti";
            }

            if ($data["rooms"][$i]["adult"] == 1) {
                $adulti = "adulto";
            } else {
                $adulti = "adulti";
            }

            if (is_array($data["rooms"][$i]["children"]) && count($data["rooms"][$i]["children"]) == 1) {
                $bambini = __("labels.bambino");
            } else if (is_array($data["rooms"][$i]["children"]) && count($data["rooms"][$i]["children"]) > 1) {
                $bambini = __("labels.bambini");
            } else {
                $bambini = "";
            }

            $message .= "\n\n" . __("labels.room") . " " . ($i + 1) . " (" . $data["rooms"][$i]["nights"] . " " . $notti . ")\n";
            if ($data["rooms"][$i]["flex_date"]) {
                $message .= "_" . __("hotel.date_flessibili_email") . "_\n\n";
            } else {
                $message .= "\n";
            }

            $message .= "*Checkin:* " . $data["rooms"][$i]["checkin"] . "\n";
            $message .= "*Checkout:* " . $data["rooms"][$i]["checkout"] . "\n";
            $message .= "*" . __("labels.adulti") . ":* " . $data["rooms"][$i]["adult"] . "\n";

            if ($bambini != "") {
                $message .= "*" . __("labels.bambini") . ":* " . count($data["rooms"][$i]["children"]) . " ( età: " . implode(",", $data["rooms"][$i]["children"]) . " " . __("labels.anni") . ")\n";
            }

            if (is_array($data["rooms"][$i]['meal_plan']))
                $message .= "*" . __("hotel.trattamento") . ":* " . implode(",",$data["rooms"][$i]['meal_plan']) . "\n";
            else
                $message .= "*" . __("hotel.trattamento") . ":* " . $data["rooms"][$i]['meal_plan'] . "\n";

        }

        /**
         * Composngo il messaggio
         */

        $message .= "\n*" . __("hotel.nome") . "*: " . $data["customer"] . "\n";
        $message .= "*" . __("hotel.tel_phone") . "*: " . $data["phone"] . "\n";
        $message .= "*" . __("hotel.email") . "*: " . $data["email"] . "\n";
        $message .= "*" . trim(__("hotel.descrizione")) . "*: " . $data["information"] . "\n\n";

        $message .= $hotel_name . " " . $hotel_rating . "\n";
        $message .= $hotel_loc . "\n";
        $message .= "https://www.info-alberghi.com/hotel.php?id=" . $hotel_id . "&whatsappshare";
        $message = urlencode($message);

        return $message;

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

                if (Config::get("mail.debug_email")) {
                    echo "<br /><br /><b>SPEDISCO AD UNA LISTA FILTRATA DI EMAIL</b><br />";
                }

                $bcc = join(",", $bcc);
                $bcc = str_replace(" ", "", $bcc);
                $bcc = explode(",", $bcc);

                // Elimino le email doppie
                $bcc_new = array();
                foreach ($bcc as $single) {
                    if (!in_array($single, $bcc_new)) {
                        array_push($bcc_new, $single);
                    }
                }
                
            else :

                $bcc = str_replace(" ", "", $bcc);
                $bcc = explode(",", $bcc);
                $bcc_new = $bcc;

            endif;

            return $bcc_new;

        } else {

            if (Config::get("mail.debug_email")) {
                echo "<br /><br /><b>SPEDISCO A TUTTI GLI ELEMENTI DELLA LISTA</b><br />";
            }

            return $bcc;

        }

    }

    /**
     * Compone il messaggio email
     *
     * @access private
     * @static
     * @param Object $parts
     * @param String $data
     * @return Array
     */

    private static function _getBodiesMail($parts, $data)
    {

        $bodies = [];
        foreach ($parts as $part):
            $bodies[] = view($part, $data);
        endforeach;
        return $bodies;

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

    private static function _checkAPI($hotel_id)
    {
        try {

            $ids_api = DB::connection('api')
                ->table('tblAPIAgenziaHotel')
                ->select(DB::raw('DISTINCT hotel_id'))
                ->where('attivo', 1)
                ->orderBy('hotel_id')
                ->get();

            $trovato = false;
            foreach ($ids_api as $val) {
                if ($val->hotel_id == $hotel_id) {
                    $trovato = true;
                    break;
                }
            }

            if (!$trovato) {
                config('app.debug_log') ? Log::emergency("\n" . '---> MAILSCHEDA: hotel ID ' . $hotel_id . ' NO HA LE API e non scrivo sul DB <---' . "\n\n") : "";
            }

            return $trovato;

        } catch (\Exception $e) {
            $file = $e->getFile();
            $line = $e->getLine();
            $exception = $e->getMessage();
            config('app.debug_log') ? Log::emergency("\n" . '---> _checkAPI: Errore:--- ' . $e->getMessage() . ' <---' . "\n\n") : "";
            $ip = Request::server('REMOTE_ADDR');
            $server = Request::server('HTTP_HOST');
            $host = gethostbyaddr(Request::server('REMOTE_ADDR'));
            $url = $host . Request::server('REQUEST_URI');
            $subject = "_checkAPI: Errore  " . $server . " (" . $file . " linea " . $line . ")";
            Utility::sendMeEmailError($subject, $exception, $server);

            return false;

        }

    }

    /**
     * Scrive le email nel database delle API da Matteo
     *
     * @access private
     * @static
     * @param RichiediPreventivoRequest $request Tutto l'oggetto richiesta
     * @return void
     */

    private static function _scriviMailSchedaAPI($db_email, $camere_aggiuntive_api)
    {

        // ? NON SCRIVO SULLE API 
        //return 0;
        //? /////////////////////

        $hotel_id = $db_email["hotel_id"];

        if (Config::get("mail.send_to_api_db") && Self::_checkAPI($hotel_id)) {
            DB::connection('api')->beginTransaction();
            try {

                if ($db_email["nome"] == __("labels.no_name")) {
                    $db_email["nome"] = "";
                }

                DB::connection('api')->table('tblAPIMailScheda')->insert($db_email);
                DB::connection('api')->table('tblAPICamereAggiuntive')->insert($camere_aggiuntive_api);
                DB::connection('api')->commit();
                return 1;

            } catch (\Exception $e) {

                DB::connection('api')->rollback();
                $file = $e->getFile();
                $line = $e->getLine();
                $exception = $e->getMessage();
                config('app.debug_log') ? Log::emergency("\n" . '---> _scriviMailSchedaAPI: Errore invio MAILSCHEDA_API:--- ' . $e->getMessage() . ' <---' . "\n\n") : "";
                $ip = Request::server('REMOTE_ADDR');
                $server = Request::server('HTTP_HOST');
                $host = gethostbyaddr(Request::server('REMOTE_ADDR'));
                $url = $host . Request::server('REQUEST_URI');
                $subject = "_scriviMailSchedaAPI: Errore invio MAILSCHEDA_API " . $server . " (" . $file . " linea " . $line . ")";
                Utility::sendMeEmailError($subject, $exception, $server);
                return 0;
            }
        }

        return 1;

    }

    /**
     * Spedisce l'email diretta anche al cliente
     *
     * @access private
     * @static
     * @param String $nome_reply
     * @param String $mail_reply
     * @param Array $dati_mail
     * @param Array $dati_json
     * @param String $multipla
     * @param String $mobile
     */

    private static function _reply_to_client($nome_reply, $mail_reply, $dati_mail, $dati_json, $multipla, $mobile)
    {

        Utility::swapToMailUp();
        try {

            Mail::send(
                "emails.mail_reply_scheda", compact('nome_reply', 'dati_mail', 'dati_json'),
                function ($message) use ($mail_reply) {
                    $message->from(Self::MS_RETURN_PATH, 'no-reply@info-alberghi.com');
                    $message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($mail_reply);
                    //$message->bcc('lmaroncelli@gmail.com');
                    $message->subject('PREVENTIVO DA INFO-ALBERGHI.COM');
                }
            );

            config('app.debug_log') ? Log::emergency("\n" . '---> OK Invio MAILMILANESE ' . $multipla . ' ' . $mobile . ' a ' . $mail_reply . ' <---' . "\n\n") : "";

        } catch (\Exception $e) {

            config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAILMILANESE ' . $multipla . ' ' . $mobile . ' a ' . $mail_reply . ' : --- ' . $e->getMessage() . ' <---' . "\n\n") : "";

        }

    }

    /* ------------------------------------------------------------------------------------------------------------
     * CONTROLLER
     * ------------------------------------------------------------------------------------------------------------ */

    /**
     * Spedisce una email diretta da Desktop
     *
     * @param RichiediPreventivoRequest $request
     * @return Redirect
     */

    public function richiestaPreventivo(RichiediPreventivoRequest $request)
    {

        $locale = App::getLocale();
        $my_input = $request->input(); // array()
        $id = $my_input['ids_send_mail'];

        /**
         * Trovo l'hotel o ne creo una nuova istanza
         */

        try {
            $hotel = Hotel::with(['localita', 'localita.macrolocalita', 'stelle', 'caparreAttive'])->find($id);
        } catch (Exception $e) {
            throw new HotelNotExistsException;
        }

        /**
         * 27/04/2020:
         *
         * se l'hotel è chiuso_temp
         * se c'è almeno una data < 30/09/2020 allora NON INVIO LA MAIL
         *
         */

        if ($hotel->chiuso_temp) {
            foreach ($request->get('arrivo') as $data_arrivo) {
                // se c'è almeno una data < 30/09/2020 allora tolgo gli hotel chiusi
                if (Carbon::createFromFormat('d/m/Y', $data_arrivo)->lessThan(Utility::getArrvioSeChiuso())) {
                    Session::flash('flash_message', 'La struttura è temporaneamente chiusa nelle date selezionate!');
                    Session::flash('flash_message_important', true);
                    return redirect(url('hotel.php?id=' . $id), 301);
                }
            }
        }

        $date = Utility::getLocalDate(Carbon::now(), '%H:%M:%S');
        $spedisci_email_duplicate = Config::get("mail.spedisci_email_duplicate");
        $spedisci_sempre_email_dirette = Config::get("mail.spedisci_sempre_email_dirette");
        $add_copy_email = Config::get("mail.add_copy_email");
        $debug_email = Config::get("mail.debug_email");

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<b>Variabili impostate</b><br />";
            echo Utility::echoDebug("id", $id);
            echo Utility::echoDebug("add_copy_email", $add_copy_email);
            echo Utility::echoDebug("spedisci_sempre_email_dirette", $spedisci_sempre_email_dirette);
            echo Utility::echoDebug("spedisci_email_duplicate", $spedisci_email_duplicate);
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

        $referer = isset($my_input['referer']) ? $my_input['referer'] : '';
        $actual_link = isset($my_input['actual_link']) ? $my_input['actual_link'] : '';
        $ip = Utility::get_client_ip();
        $struttura_contattata = url('hotel.php?id=' . $id);
        $spam = Utility::checkSenderMailBlacklisted($my_input['email']);
        $redirect_url = '/' . Utility::getLocaleUrl($locale);

        /**
         * Controllo se sono spam
         */

        if (!$spam && isset($my_input['esca'])) {
            $esca = $my_input['esca'];
            $spam = Utility::checkSpider($esca);
        }

        $offer = "";
        if (isset($my_input['offerta'])) {
            $offer = $my_input['offerta'];
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
        $prefill["ids_send_mail"] = $id;
        $prefill["customer"] = trim($my_input['nome']);
        if ($prefill["customer"] == "") {
            $prefill["customer"] = __("labels.no_name");
        }

        $prefill["email"] = $my_input['email'];
        $prefill["phone"] = $my_input['telefono'];

        if (isset($my_input['prefix_input'])) {
            $prefix = $my_input['prefix_input'];
        } else {
            $prefix = "39";
        }

        $prefill["whatsapp"] = isset($my_input['telefono']) && $my_input['telefono'] != "" ? Utility::getPhoneWa($prefix, $my_input['telefono']) : null;

        $prefill["information"] = $my_input['richiesta'];
        $prefill["tag"] = "ED";
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

        $old_prefill = Utility::unsetEmailPrefill($old_prefill, "OLD");
        $new_prefill = Utility::unsetEmailPrefill($new_prefill, "NEW");

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
        $debugTemp = "";

        /* --------------------------- DEBUG --------------------------- */
        if ($spedisci_sempre_email_dirette) {
            $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DIRETTE</b><br />";
        }

        /* --------------------------- DEBUG --------------------------- */

        /**
         * Se ho settato che le email dirette vanno spedite o che devo sempre
         * spedire i duplicati passo avanti altrimenti vado al seconco controllo
         */

        $confronto = [];
        $confronto = ["clienti_ids_arr" => [], "clienti_ids_arr_not_sent" => []];

        if (!$spedisci_sempre_email_dirette && !$spedisci_email_duplicate) {

            /**
             * Controllo che questa email non sia stata spedita a questo hotel
             * da questo codice utente negli ultimi 3 giorni
             */

            $confronto = MailDoppie::controlloMailDoppie($codice_cookie, [$id], base64_encode(json_encode($new_prefill)));

            /**
             * Se non mi ritorna neanche un id allora vuole dire che li ho scartati tutti
             */

            if (count($confronto["clienti_ids_arr"]) == 0) {

                $tipologia = "doppia";
                $email_doppia = true;

            }

        }

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Risultato confronto</b><br />";
            if ($debugTemp != "") {
                echo $debugTemp;
            }

            echo Utility::echoDebug("id buoni", print_r($confronto["clienti_ids_arr"], 1));
            echo Utility::echoDebug("id scartati", print_r($confronto["clienti_ids_arr_not_sent"], 1));
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Scrivo sempre le email nel database marcandola come doppia in caso di replica
         */

        /**
         * Trovo la lista di trattamenti supportati da questo hotel
         */

        $list_meal_plan = $hotel->getListingTrattamento();

        if (!$spam) {

            $db_email = array();
            $db_email["hotel_id"] = $id;
            $db_email["lang_id"] = $locale;
            $db_email["IP"] = $ip;
            $db_email["referer"] = $referer;
            $db_email["camere"] = $numero_camere;
            $db_email["tipologia"] = $tipologia;

            /**
             * SE LA MIAL E' DOPPIA la marco come sync perché altrimenti il cron cerca di rispedirla
             */

            if ($email_doppia) {
                $db_email["api_sync"] = true;
            }

            $db_email["nome"] = $prefill["customer"];
            $db_email["email"] = $prefill["email"];
            $db_email["telefono"] = $prefill["phone"];
            $db_email["whatsapp"] = $prefill["whatsapp"];
            $db_email["richiesta"] = $prefill["information"];

            $db_email["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkin"]);
            $db_email["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkout"]);
            $db_email["adulti"] = $prefill["rooms"][0]["adult"];
            $db_email["bambini"] = $prefill["rooms"][0]["children"];
            $db_email["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][0]["age_children"], $db_email["bambini"]);

            /**
             * 25/08/2020
             * Spedendiamo dei preventivi multitrattamento
             * Non tutti i trattamenti somo presenti nelle strutture quindi se ho più tratamenti di quelli che la struttura
             * fornisce li elimino dalla email senza toglierli dalla variabile prefill
             */

            $db_email["trattamento"] = Self::_filterMealPlan($prefill["rooms"][0]["meal_plan"], $list_meal_plan);
            $db_email["date_flessibili"] = $prefill["rooms"][0]["flex_date"];

            $mail_scheda = $hotel->mailScheda()->create($db_email);

            /**
             * preparazione per API
             */

            $db_email['id'] = $mail_scheda->id;

            /**
             * Ciclo sulle camere
             */

            $camere_aggiuntive_api = [];
            for ($i = 1; $i < $numero_camere; $i++) {

                $db_email_tutte = array();
                $db_email_tutte["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkin"]);
                $db_email_tutte["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkout"]);
                $db_email_tutte["trattamento"] = $prefill["rooms"][$i]["meal_plan"];
                $db_email_tutte["adulti"] = $prefill["rooms"][$i]["adult"];
                $db_email_tutte["bambini"] = $prefill["rooms"][$i]["children"];
                $db_email_tutte["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][$i]["age_children"], $db_email_tutte["bambini"]);
                $db_email_tutte["date_flessibili"] = $prefill["rooms"][$i]["flex_date"];

                $camera_gg = $mail_scheda->camereAggiuntive()->create($db_email_tutte);

                /**
                 * preparazione per API
                 */

                $db_email_tutte['mailScheda_id'] = $mail_scheda->id;
                $db_email_tutte['id'] = $camera_gg->id;
                $camere_aggiuntive_api[] = $db_email_tutte;

            }

            if (!$email_doppia) {

                $mail_scheda_id = Self::_scriviMailSchedaAPI($db_email, $camere_aggiuntive_api);
                if ($mail_scheda_id != 0) {
                    // aggiorno a 1 il campo api_sync di mailScheda per il record
                    $mail_scheda->api_sync = true;
                    $mail_scheda->save();
                }
            }

            /**
             * Aggiorno l'ultimo codice
             */

            MailDoppie::logEmailDoppie($codice_cookie, [$id], base64_encode(json_encode($new_prefill)), $debug_email);

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                echo "<br /><br /><b>Dati da scrivere nel database </b><br />";
                echo Utility::echoDebug("", $db_email, 1);
            }
            /* --------------------------- DEBUG --------------------------- */

        }

        /**
         * Scrivo la email
         */

        $dati_mail = array();
        $dati_json = array();

        /**
         * Scrivo il JSON che poi passo all'email
         */

        $dati_json['politiche_canc'] = Lang::get('labels.politiche_canc_short');
        $dati_json['spiaggia'] = 0;
        $dati_json["customer"] = $prefill["customer"];
        $dati_json["email"] = $prefill["email"];
        $dati_json["phone"] = $prefill["phone"];
        $dati_json["whatsapp"] = $prefill["whatsapp"];
        $dati_json["information"] = $prefill["information"];

        if ($dati_json["whatsapp"] != null) {
            $dati_json["information2"] = true;
        } else {
            $dati_json["information2"] = false;
        }

        $dati_json["tag"] = "ED";
        $dati_json["sender"] = "info-alberghi.com";
        $dati_json["type"] = "desktop";

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
            $dati_json["rooms"][$i]["checkout"] = $prefill["rooms"][$i]['checkout'];
            $dati_json["rooms"][$i]["nights"] = Utility::night($prefill["rooms"][$i]['checkin'], $prefill["rooms"][$i]['checkout']);
            $dati_json["rooms"][$i]["adult"] = $prefill["rooms"][$i]['adult'];

            if (isset($prefill["rooms"][$i]['children']) && $prefill["rooms"][$i]['children'] > 0) {
                $dati_json["rooms"][$i]["children"] = explode(",", Utility::purgeMenoUno($prefill["rooms"][$i]['age_children'], $prefill["rooms"][$i]['children']));
            } else {
                $dati_json["rooms"][$i]["children"] = "";
            }

           

            $prefill["rooms"][$i]['meal_plan'] = Self::_filterMealPlan($prefill["rooms"][$i]['meal_plan'], $list_meal_plan);
            if (strpos($prefill["rooms"][$i]['meal_plan'], "_spiaggia")) {
                $dati_json['spiaggia'] = 1;
            }
            $dati_json["rooms"][$i]["meal_plan"] = Utility::Trattamenti_json($prefill["rooms"][$i]['meal_plan']);
            $dati_json["rooms"][$i]['caparre'] = $hotel->attachCaparreDalAl($dati_json["rooms"][$i]["checkin"], $dati_json["rooms"][$i]["checkout"], $locale);

        }

        $dati_json["message_wa"] = Self::_getMessageWA($dati_json, $numero_camere, $hotel->nome, $hotel->id, $hotel->stelle->nome, $hotel->localita->nome);
        
        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Dati json </b><br />";
            echo Utility::echoDebug("", $dati_json, 1);
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Sono i dati aggiuntivi dell'email
         */

        $dati_mail['hotel_name'] = $hotel->nome;
        $dati_mail['hotel_id'] = $hotel->id;
        $dati_mail['hotel_telefono'] = $hotel->telefoni_mobile_call;
        $dati_mail['hotel_indirizzo'] = $hotel->indirizzo . ' - ' . $hotel->cap . ' - ' . $hotel->localita->macrolocalita->nome;
        $dati_mail['hotel_rating'] = $hotel->stelle->nome;
        $dati_mail['hotel_loc'] = $hotel->localita->nome; //($hotel->localita->alias ? $hotel->localita->alias : $hotel->localita->nome);
        $dati_mail['referer'] = $referer;
        $dati_mail['actual_link'] = $actual_link;
        $dati_mail['ip'] = $ip;
        $dati_mail['device'] = "Computer";
        $dati_mail['hotels_contact'] = "";
        $dati_mail['date_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%d %B %Y');
        $dati_mail['hour_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%R');
        $dati_mail["flex_date"] = $prefill["flex_date"];

        if (config('mail.footer_json_mail')) {
            $dati_mail['sign_email'] = Utility::putJsonMail($dati_json);
        } else {
            $dati_mail['sign_email'] = "";
        }

        $dati_mail['caparre'] = null;

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Dati Email </b><br />";
            echo Utility::echoDebug("", $dati_mail, 1);
        }

        /**
         * Preparo l'oggetto
         */

        $oggetto = Utility::getOggettoMail($numero_camere, $prefill, false);

        /**
         * E' spam ?
         * @var Integer $spam - Può assumere 3 valori: 0 - non è spam, 1 - Utente blacklistato, 2 - Spider
         */

        $bcc = array();
        $email_risponditori = "";

        if ($spam == 0) {

            $to = $hotel->email;

            if ($add_copy_email) {
                $bcc = "testing.infoalberghi@gmail.com";
            }

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email && $add_copy_email) {
                echo "<br /><br /><b>SPEDISCO UN COPIA A INFOALBERGHI</b><br />";
            }

            /* --------------------------- DEBUG --------------------------- */

        } else if ($spam == 1) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - utente BLACKLISTATO al modulo invio mail";

        } else if ($spam == 2) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - SPIDER al modulo invio mail";

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
         * Preparo i destinatari
         */

        $to = Self::_ToEmail($to);
        if ($hotel->email_risponditori != "" && $spam == 0) {
            $email_risponditori = Self::_ToEmail($hotel->email_risponditori);
        }

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Risultato email</b><br />";
            echo Utility::echoDebug("to", print_r($to, 1));
            echo Utility::echoDebug("email_risponditori", print_r($email_risponditori, 1));
            echo Utility::echoDebug("bcc", print_r($bcc, 1));
            echo Utility::echoDebug("tipologia", $tipologia);
            echo Utility::echoDebug("email_doppia", $email_doppia);
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Aggiorno i dati per la spedizione attraverso sendgrid
         */

        Utility::swapToSendGrid();

        /**
         * Spedisco l'email
         */

        try {

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                if ($spedisci_email_duplicate) {
                    echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                }

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

                Mail::send(
                    "emails.mail_scheda", compact('dati_mail', 'dati_json'),
                    function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente) {

                        $message->from(Self::MS_RETURN_PATH, $nome_mittente);
                        $message->replyTo($email_mittente);
                        $message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($to);
                        if ($bcc != "") {
                            $message->bcc($bcc);
                        }

                        $message->subject($oggetto);
                    }
                );

            endif;

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                echo "<br /><br /><b>NON SPEDISCO</b>";
                die();
            }
            /* --------------------------- DEBUG --------------------------- */

        } catch (\Exception $e) {

            /**
             * Scrivo nel log
             */

            config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAILSCHEDA: ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";
            config('app.debug_log') ? Log::emergency("\n" . json_encode($dati_mail) . ' <---' . "\n\n") : "";
            config('app.debug_log') ? Log::emergency("\n" . json_encode($dati_json) . ' <---' . "\n\n") : "";

            /**
             * Redirect alla pagina di errore
             */

            return redirect(
                Utility::getLocaleUrl($locale) . 'error_send')
                ->with(
                    array(
                        'redirect_url' => $referer,
                        'ids_send_mail' => $id,
                        'listing' => 'no_listing',
                        'multipla' => 'diretta',
                        'mobile' => 'desktop',
                    )
                );

        }

        /**
         * Spedisco l'email ai risponditori
         */

        if ($email_risponditori) {

            try {

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    if ($spedisci_email_duplicate) {
                        echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                    }

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
                        echo "<br /><br /><b>SPEDISCO EMAIL RISPONDITORI</b>";
                        die();
                    }
                    /* --------------------------- DEBUG --------------------------- */
                    
                    $dati_json_new = $dati_json;
                    if($dati_json["information2"]) {
                        $dati_json_new["information"] .= '<br />[w][b]WhatsApp®[/b] - L\'utente richiede il preventivo via whatsapp per avere la possibilità di condividerlo con famiglia / amici e per avere una comunicazione più diretta con la struttura.[/w]';
                    }

                    if ($dati_json["spiaggia"]) {
                        $dati_json_new["information"] .= '[s][b]Richiesta supplemento spiaggia[/b] - Il cliente ha richiesto almeno un preventivo con la spiaggia inclusa.[/s]';
                    }

                    $dati_mail['sign_email'] = Utility::putJsonMail($dati_json_new, true);

                    Mail::send(
                        "emails.mail_scheda", compact('dati_mail', 'dati_json'),
                        function ($message) use ($email_mittente, $email_risponditori, $oggetto, $nome_mittente) {

                            $message->from(Self::MS_RETURN_PATH, $nome_mittente);
                            $message->replyTo($email_mittente);
                            $message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($email_risponditori);
                            $message->subject($oggetto);
                        }
                    );

                endif;

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>NON SPEDISCO EMAIL AI RISONDITORI</b>";
                    die();
                }
                /* --------------------------- DEBUG --------------------------- */

            } catch (\Exception $e) {

                /**
                 * Scrivo nel log
                 */

                config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAILSCHEDA RISPONDITORI: ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";
                config('app.debug_log') ? Log::emergency("\n" . json_encode($dati_mail) . ' <---' . "\n\n") : "";
                config('app.debug_log') ? Log::emergency("\n" . json_encode($dati_json) . ' <---' . "\n\n") : "";

                /**
                 * Redirect alla pagina di errore
                 */

                return redirect(
                    Utility::getLocaleUrl($locale) . 'error_send')
                    ->with(
                        array(
                            'redirect_url' => $referer,
                            'ids_send_mail' => $id,
                            'listing' => 'no_listing',
                            'multipla' => 'diretta',
                            'mobile' => 'desktop',
                        )
                    );

            }

        }

        /**
         * Metto l'utente in lista di attesa per la mail di Upselling
         */

        if ($hotel->mail_upselling && $referer != '' && $locale == 'it' && !$spam && Config::get("mail.upselling")) {

            $data_for_upselling = ['referer' => $referer, 'email' => $email_mittente, 'nominativo' => $nome_mittente, 'dal' => $dati_json["rooms"][0]["checkin"], 'al' => $dati_json["rooms"][0]["checkout"]];
            $hotel->mailUpsellingQueue()->create($data_for_upselling);

        }

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
            'redirect_url' => $actual_link, // alla pagina dell'hotel
            'ids_send_mail' => $id,
            'listing' => 'no_listing',
            'multipla' => 'diretta',
            'mobile' => 'desktop'];

        if (!$spam && !$email_doppia) {
            $nome_reply = trim($my_input['nome']);
            $mail_reply = $my_input['email'];

            $parameters_to_pass['nome_reply'] = $nome_reply;
            $parameters_to_pass['mail_reply'] = $mail_reply;
            $parameters_to_pass['dati_mail'] = $dati_mail;
            $parameters_to_pass['dati_json'] = $dati_json;

        }

        return redirect(
            Utility::getLocaleUrl($locale) . 'thankyou')
            ->with($parameters_to_pass);

    }

    /**
     * Spedisce una email diretta da Mobile
     * Ultima modifica: 17/04/2018 @giovanni
     * Controllo email duplicate su database
     *
     * @access public
     * @param RichiestaMailSchedaMobileRequest $request Tutto l'oggetto richiesta
     * @return void
     */

    public function richiestaMailSchedaMobile(RichiestaMailSchedaMobileRequest $request)
    {

        $locale = App::getLocale();
        $my_input = $request->input();
        $id = $my_input['ids_send_mail'];

        /**
         * Trovo l'hotel o ne creo una nuova istanza
         */

        try {
            $hotel = Hotel::with(['localita', 'localita.macrolocalita', 'stelle'])->find($id);
        } catch (Exception $e) {
            throw new HotelNotExistsException;
        }

        /**
         * 27/04/2020:
         *
         * se l'hotel è chiuso_temp
         * se c'è almeno una data < 30/09/2020 allora NON INVIO LA MAIL
         *
         */

        if ($hotel->chiuso_temp) {

            foreach ($request->get('arrivo') as $data_arrivo) {
                // se c'è almeno una data < 30/09/2020 allora tolgo gli hotel chiusi
                if (Carbon::createFromFormat('d/m/Y', $data_arrivo)->lessThan(Utility::getArrvioSeChiuso())) {
                    Session::flash('flash_message', 'La struttura è temporaneamente chiusa nelle date selezionate!');
                    Session::flash('flash_message_important', true);
                    return redirect(url('hotel.php?id=' . $id), 301);
                }
            }

        }

        $date = Utility::getLocalDate(Carbon::now(), '%H:%M:%S');
        $spedisci_email_duplicate = Config::get("mail.spedisci_email_duplicate");
        $spedisci_sempre_email_dirette = Config::get("mail.spedisci_sempre_email_dirette");
        $add_copy_email = Config::get("mail.add_copy_email");
        $debug_email = Config::get("mail.debug_email");

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<b>Variabili impostate</b><br />";
            echo Utility::echoDebug("id", $id);
            echo Utility::echoDebug("add_copy_email", $add_copy_email);
            echo Utility::echoDebug("spedisci_sempre_email_dirette", $spedisci_sempre_email_dirette);
            echo Utility::echoDebug("spedisci_email_duplicate", $spedisci_email_duplicate);
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

        $referer = isset($my_input['referer']) ? $my_input['referer'] : '';
        $actual_link = isset($my_input['actual_link']) ? $my_input['actual_link'] : '';
        $ip = Utility::get_client_ip();
        $struttura_contattata = url('hotel.php?id=' . $id);
        $spam = Utility::checkSenderMailBlacklisted($my_input['email']);
        $redirect_url = '/' . Utility::getLocaleUrl($locale);

        /**
         * Controllo se sono spam
         */

        if (!$spam && isset($my_input['esca'])) {
            $esca = $my_input['esca'];
            $spam = Utility::checkSpider($esca);
        }

        $offer = "";
        if (isset($my_input['offerta'])) {
            $offer = $my_input['offerta'];
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
        $prefill["ids_send_mail"] = $id;
        $prefill["customer"] = $my_input['nome'];
        if ($prefill["customer"] == "") {
            $prefill["customer"] = __("labels.no_name");
        }

        $prefill["email"] = $my_input['email'];
        $prefill["phone"] = $my_input['telefono'];

        if (isset($my_input['prefix_input'])) {
            $prefix = $my_input['prefix_input'];
        } else {
            $prefix = "39";
        }

        $prefill["whatsapp"] = isset($my_input['telefono']) && $my_input['telefono'] != "" ? Utility::getPhoneWa($prefix, $my_input['telefono']) : null;

        $prefill["information"] = $my_input['richiesta'];
        $prefill["tag"] = "ED";
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
        $codice_cookie = (array_key_exists('codice_cookie', $old_prefill)) ? $old_prefill['codice_cookie'] : Carbon::now()->timestamp . "_" . uniqid(rand(), true);

        /**
         * Tolgo tutti i campi che non necessitano confronto
         */

        $old_prefill = Utility::unsetEmailPrefill($old_prefill, "OLD");
        $new_prefill = Utility::unsetEmailPrefill($new_prefill, "NEW");

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
        $debugTemp = "";

        /* --------------------------- DEBUG --------------------------- */
        if ($spedisci_sempre_email_dirette) {
            $debugTemp = "<br /><br /><b>SPEDISCO SEMPRE LE EMAIL DIRETTE</b><br />";
        }

        /* --------------------------- DEBUG --------------------------- */

        /**
         * Se ho settato che le email dirette vanno spedite o che devo sempre
         * spedire i duplicati passo avanti altrimenti vado al seconco controllo
         */

        $confronto = [];
        $confronto = ["clienti_ids_arr" => [], "clienti_ids_arr_not_sent" => []];

        if (!$spedisci_sempre_email_dirette && !$spedisci_email_duplicate) {

            /**
             * Controllo che questa email non sia stata spedita a questo hotel
             * da questo codice utente negli ultimi 3 giorni
             */

            $confronto = MailDoppie::controlloMailDoppie($codice_cookie, [$id], base64_encode(json_encode($new_prefill)));

            /**
             * Se non mi ritorna neanche un id allora vuole dire che li ho scartati tutti
             */

            if (count($confronto["clienti_ids_arr"]) == 0) {

                $tipologia = "doppia";
                $email_doppia = true;

            }
        }

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Risultato confronto</b><br />";
            if ($debugTemp != "") {
                echo $debugTemp;
            }

            echo Utility::echoDebug("id buoni", print_r($confronto["clienti_ids_arr"], 1));
            echo Utility::echoDebug("id scartati", print_r($confronto["clienti_ids_arr_not_sent"], 1));
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Scrivo sempre le email nel database marcandola come doppia in caso di replica
         */

        /**
         * Trovo la lista di trattamenti supportati da questo hotel
         */

        $list_meal_plan = $hotel->getListingTrattamento();

        if (!$spam) {

            $db_email = array();
            $db_email["hotel_id"] = $id;
            $db_email["lang_id"] = $locale;
            $db_email["IP"] = $ip;
            $db_email["referer"] = $referer;
            $db_email["camere"] = $numero_camere;
            $db_email["tipologia"] = $tipologia;

            // SE LA MIAL E' DOPPIA la marco come sync perché altrimenti il cron cerca di rispedirla
            if ($email_doppia) {
                $db_email["api_sync"] = true;
            }

            $db_email["nome"] = $prefill["customer"];
            $db_email["email"] = $prefill["email"];
            $db_email["telefono"] = $prefill["phone"];
            $db_email["richiesta"] = $prefill["information"];
            $db_email["whatsapp"] = $prefill["whatsapp"];

            $db_email["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkin"]);
            $db_email["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][0]["checkout"]);
            $db_email["adulti"] = $prefill["rooms"][0]["adult"];
            $db_email["bambini"] = $prefill["rooms"][0]["children"];
            $db_email["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][0]["age_children"], $db_email["bambini"]);

            /**
             * 25/08/2020
             * Spedendiamo dei preventivi multitrattamento
             * Non tutti i trattamenti somo presenti nelle strutture quindi se ho più tratamenti di quelli che la struttura
             * fornisce li elimino dalla email senza toglierli dalla variabile prefill
             */

            $db_email["trattamento"] = Self::_filterMealPlan($prefill["rooms"][0]["meal_plan"], $list_meal_plan);
            $db_email["date_flessibili"] = $prefill["rooms"][0]["flex_date"];

            $mail_scheda = $hotel->mailScheda()->create($db_email);

            /**
             * preparazione per API
             */

            $db_email['id'] = $mail_scheda->id;

            /**
             * Ciclo sulle camere
             */

            $camere_aggiuntive_api = [];
            for ($i = 1; $i < $numero_camere; $i++) {

                $db_email_tutte = array();
                $db_email_tutte["arrivo"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkin"]);
                $db_email_tutte["partenza"] = Carbon::createFromFormat('d/m/Y', $prefill["rooms"][$i]["checkout"]);
                $db_email_tutte["trattamento"] = $prefill["rooms"][$i]["meal_plan"];
                $db_email_tutte["adulti"] = $prefill["rooms"][$i]["adult"];
                $db_email_tutte["bambini"] = $prefill["rooms"][$i]["children"];
                $db_email_tutte["eta_bambini"] = Utility::purgeMenoUno($prefill["rooms"][$i]["age_children"], $db_email_tutte["bambini"]);
                $db_email_tutte["date_flessibili"] = $prefill["rooms"][$i]["flex_date"];

                $camera_gg = $mail_scheda->camereAggiuntive()->create($db_email_tutte);

                //////////////////////////
                // preparazione per API //
                //////////////////////////
                $db_email_tutte['mailScheda_id'] = $mail_scheda->id;
                $db_email_tutte['id'] = $camera_gg->id;
                $camere_aggiuntive_api[] = $db_email_tutte;

            }

            if (!$email_doppia) {

                $mail_scheda_id = Self::_scriviMailSchedaAPI($db_email, $camere_aggiuntive_api);
                if ($mail_scheda_id != 0) {
                    // aggiorno a 1 il campo api_sync di mailScheda per il record
                    $mail_scheda->api_sync = true;
                    $mail_scheda->save();
                }

            }

            /**
             * Aggiorno l'ultimo codice
             */

            MailDoppie::logEmailDoppie($codice_cookie, [$id], base64_encode(json_encode($new_prefill)), $debug_email);

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                echo "<br /><br /><b>Dati da scrivere nel database </b><br />";
                echo Utility::echoDebug("", $db_email, 1);
            }
            /* --------------------------- DEBUG --------------------------- */

        }

        /**
         * Scrivo la email
         */

        $dati_mail = array();
        $dati_json = array();

        /**
         * Scrivo il JSON da attaca alla email
         */

        $dati_json['politiche_canc'] = Lang::get('labels.politiche_canc_short');
        $dati_json['spiaggia'] = 0;
        $dati_json["customer"] = $prefill["customer"];
        $dati_json["email"] = $prefill["email"];
        $dati_json["phone"] = $prefill["phone"];
        $dati_json["whatsapp"] = $prefill["whatsapp"];
        $dati_json["information"] = $prefill["information"];

        if ($dati_json["whatsapp"] != null) {
            $dati_json["information2"] = true;
        } else {
            $dati_json["information2"] = false;
        }

        $dati_json["tag"] = "ED";
        $dati_json["sender"] = "info-alberghi.com";
        $dati_json["type"] = "mobile";

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
            $dati_json["rooms"][$i]["checkout"] = $prefill["rooms"][$i]['checkout'];
            $dati_json["rooms"][$i]["nights"] = Utility::night($prefill["rooms"][$i]['checkin'], $prefill["rooms"][$i]['checkout']);
            $dati_json["rooms"][$i]["adult"] = $prefill["rooms"][$i]['adult'];

            if (isset($prefill["rooms"][$i]['children']) && $prefill["rooms"][$i]['children'] > 0) {
                $dati_json["rooms"][$i]["children"] = explode(",", Utility::purgeMenoUno($prefill["rooms"][$i]['age_children'], $prefill["rooms"][$i]['children']));
            } else {
                $dati_json["rooms"][$i]["children"] = "";
            }

            $prefill["rooms"][$i]['meal_plan'] = Self::_filterMealPlan($prefill["rooms"][$i]['meal_plan'], $list_meal_plan);
            if (strpos($prefill["rooms"][$i]['meal_plan'], "_spiaggia")) {
                $dati_json['spiaggia'] = 1;
            }
            $dati_json["rooms"][$i]["meal_plan"] = Utility::Trattamenti_json($prefill["rooms"][$i]['meal_plan']);
            $dati_json["rooms"][$i]['caparre'] = $hotel->attachCaparreDalAl($dati_json["rooms"][$i]["checkin"], $dati_json["rooms"][$i]["checkout"], $locale);

        }

        $dati_json["message_wa"] = Self::_getMessageWA($dati_json, $numero_camere, $hotel->nome, $hotel->id, $hotel->stelle->nome, $hotel->localita->nome);

        /**
         * Sono i dati aggiuntivi dell'email
         */

        $dati_mail['hotel_name'] = $hotel->nome;
        $dati_mail['hotel_id'] = $hotel->id;
        $dati_mail['hotel_telefono'] = $hotel->telefoni_mobile_call;
        $dati_mail['hotel_indirizzo'] = $hotel->indirizzo . ' - ' . $hotel->cap . ' - ' . $hotel->localita->macrolocalita->nome;
        $dati_mail['hotel_rating'] = $hotel->stelle->nome;
        $dati_mail['hotel_loc'] = $hotel->localita->nome; // ($hotel->localita->alias ? $hotel->localita->alias : $hotel->localita->nome);
        $dati_mail['referer'] = $referer;
        $dati_mail['actual_link'] = $actual_link;
        $dati_mail['ip'] = $ip;
        $dati_mail['device'] = "Mobile";
        $dati_mail['hotels_contact'] = "";
        $dati_mail['date_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%d %B %Y');
        $dati_mail['hour_created_at'] = Carbon::now(new \DateTimeZone('Europe/Rome'))->formatLocalized('%R');
        $dati_mail["flex_date"] = $prefill["flex_date"];

        if (config('mail.footer_json_mail')) {
            $dati_mail['sign_email'] = Utility::putJsonMail($dati_json);
        } else {
            $dati_mail['sign_email'] = "";
        }

        $dati_mail['caparre'] = null;

        /**
         * Preparo l'oggetto
         */

        $oggetto = Utility::getOggettoMail($numero_camere, $prefill, true);

        /**
         * E' spam ?
         * @var $spam integer Può assumere 3 valori: 0 - non è spam, 1 - Utente blacklistato, 2 - Spider
         */

        $bcc = array();
        $email_risponditori = "";

        if ($spam == 0) {

            $to = $hotel->email;

            if ($add_copy_email) {
                $bcc = "testing.infoalberghi@gmail.com";
            }

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email && $add_copy_email) {
                echo "<br /><br /><b>SPEDISCO UN COPIA A INFOALBERGHI</b><br />";
            }

            /* --------------------------- DEBUG --------------------------- */

        } else if ($spam == 1) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - utente BLACKLISTATO al modulo invio mail";

        } else if ($spam == 2) {

            $to = "luigi@info-alberghi.com";
            $oggetto = "INFO-ALBERGHI.COM - SPIDER al modulo invio mail";

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
         * Preparo i destinatari
         */

        $to = Self::_ToEmail($to);
        if ($hotel->email_risponditori != "" && $spam == 0) {
            $email_risponditori = Self::_ToEmail($hotel->email_risponditori);
        }

        /* --------------------------- DEBUG --------------------------- */
        if ($debug_email) {
            echo "<br /><br /><b>Risultato email</b><br />";
            echo Utility::echoDebug("to", print_r($to, 1));
            echo Utility::echoDebug("bcc", print_r($bcc, 1));
            echo Utility::echoDebug("tipologia", $tipologia);
            echo Utility::echoDebug("email_doppia", $email_doppia);
        }
        /* --------------------------- DEBUG --------------------------- */

        /**
         * Aggiorno i dati per la spedizione attraverso sendgrid
         */

        Utility::swapToSendGrid();

        /**
         * Spedisco l'email
         */

        try {

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                if ($spedisci_email_duplicate) {
                    echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                }

                echo Utility::echoDebug("oggetto", $oggetto);
            }
            /* --------------------------- DEBUG --------------------------- */

            if (!$email_doppia || $spedisci_email_duplicate):

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>SPEDISCO</b>";
                    die();
                }
                /* --------------------------- DEBUG --------------------------- */

                $message_bodies = Self::_getBodiesMail(["emails.mail_scheda_text", "emails.mail_scheda"], compact('dati_mail', 'dati_json'));

                Mail::send(
                    "emails.mail_scheda", compact('dati_mail', 'dati_json'),
                    function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente, $message_bodies) {

                        $message->from(Self::MS_RETURN_PATH, $nome_mittente);
                        $message->replyTo($email_mittente);
                        $message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($to);
                        if ($bcc != "") {
                            $message->bcc($bcc);
                        }

                        $message->subject($oggetto);
                        //    $message->setBody($message_bodies[1]);
                        //    $message->addPart($message_bodies[0]);

                    }
                );

            endif;

            /* --------------------------- DEBUG --------------------------- */
            if ($debug_email) {
                echo "<br /><br /><b>NON SPEDISCO</b>";
                die();
            }
            /* --------------------------- DEBUG --------------------------- */

        } catch (\Exception $e) {

            /**
             * Scrivo nel log
             */

            config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAILSCHEDA mobile: ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";

            /**
             * Redirect alla pagina di errore
             */

            return redirect(
                Utility::getLocaleUrl($locale) . 'error_send')
                ->with(
                    array(
                        //'redirect_url'=>$referer,
                        'redirect_url' => $actual_link,
                        'ids_send_mail' => $id,
                        'listing' => 'no_listing',
                        'multipla' => 'diretta',
                        'mobile' => 'mobile',
                    )
                );

        }

        /**
         * Spedisco l'email ai risponditori
         */

        if ($email_risponditori) {

            try {

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    if ($spedisci_email_duplicate) {
                        echo $debugTemp = "<br /><b>SPEDISCO SEMPRE LE EMAIL DUPLICATE</b><br />";
                    }

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
                        echo "<br /><br /><b>SPEDISCO EMAIL RISPONDITORI</b>";
                        die();
                    }
                    /* --------------------------- DEBUG --------------------------- */

                    $dati_json_new = $dati_json;
                    if($dati_json["information2"]) {
                        $dati_json_new["information"] .= '<br />[w][b]WhatsApp®[/b] - L\'utente richiede il preventivo via whatsapp per avere la possibilità di condividerlo con famiglia / amici e per avere una comunicazione più diretta con la struttura.[/w]';
                    }

                    if ($dati_json["spiaggia"]) {
                        $dati_json_new["information"] .= '[s][b]Richiesta supplemento spiaggia[/b] - Il cliente ha richiesto almeno un preventivo con la spiaggia inclusa.[/s]';
                    }

                    $dati_mail['sign_email'] = Utility::putJsonMail($dati_json_new, true);

                    Mail::send(
                        "emails.mail_scheda", compact('dati_mail', 'dati_json'),
                        function ($message) use ($email_mittente, $email_risponditori, $oggetto, $nome_mittente) {

                            $message->from(Self::MS_RETURN_PATH, $nome_mittente);
                            $message->replyTo($email_mittente);
                            $message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($email_risponditori);
                            $message->subject($oggetto);
                        }
                    );

                endif;

                /* --------------------------- DEBUG --------------------------- */
                if ($debug_email) {
                    echo "<br /><br /><b>NON SPEDISCO EMAIL AI RISONDITORI</b>";
                    die();
                }
                /* --------------------------- DEBUG --------------------------- */

            } catch (\Exception $e) {

                /**
                 * Scrivo nel log
                 */

                config('app.debug_log') ? Log::emergency("\n" . '---> Errore invio MAILSCHEDA RISPONDITORI: ' . $ip . ' --- ' . $e->getMessage() . ' <---' . "\n\n") : "";
                config('app.debug_log') ? Log::emergency("\n" . json_encode($dati_mail) . ' <---' . "\n\n") : "";
                config('app.debug_log') ? Log::emergency("\n" . json_encode($dati_json) . ' <---' . "\n\n") : "";

                /**
                 * Redirect alla pagina di errore
                 */

                return redirect(
                    Utility::getLocaleUrl($locale) . 'error_send')
                    ->with(
                        array(
                            'redirect_url' => $referer,
                            'ids_send_mail' => $id,
                            'listing' => 'no_listing',
                            'multipla' => 'diretta',
                            'mobile' => 'desktop',
                        )
                    );

            }

        }

        /**
         * Metto l'utente in lista di attesa per la mail di Upselling
         */

        if ($hotel->mail_upselling && $referer != '' && $locale == 'it' && !$spam && Config::get("mail.upselling")) {

            $data_for_upselling = ['referer' => $referer, 'email' => $email_mittente, 'nominativo' => $nome_mittente, 'dal' => $dati_json["rooms"][0]["checkin"], 'al' => $dati_json["rooms"][0]["checkout"]];
            $hotel->mailUpsellingQueue()->create($data_for_upselling);

        }

        /* if (!$spam && !$email_doppia)
        {
        $nome_reply        = trim($my_input['nome']);
        $mail_reply     = $my_input['email'];
        $hotel_reply    = $hotel;

        $this->_reply_to_client($nome_reply, $mail_reply, $dati_mail, $dati_json);
        }*/

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
            'redirect_url' => $actual_link,
            'ids_send_mail' => $id,
            'listing' => 'no_listing',
            'multipla' => 'diretta',
            'mobile' => 'mobile',
            'spam' => $spam,
        ];

        if (!$spam && !$email_doppia) {
            $nome_reply = trim($my_input['nome']);
            $mail_reply = $my_input['email'];

            $parameters_to_pass['nome_reply'] = $nome_reply;
            $parameters_to_pass['mail_reply'] = $mail_reply;
            $parameters_to_pass['dati_mail'] = $dati_mail;
            $parameters_to_pass['dati_json'] = $dati_json;
        }

        return redirect(
            Utility::getLocaleUrl($locale) . 'thankyou')
            ->with($parameters_to_pass);

    }

    /* ------------------------------------------------------------------------------------------------------------
     * VIEW
     * ------------------------------------------------------------------------------------------------------------ */

    /**
     * Scheda mobile
     *
     * @access public
     * @param String $param
     * @return View
     */

    public function mailSchedaMobile($param = "")
    {

        $locale = $this->getLocale();
        $ids_send_mail = Request::get('id');
        $offerta = Request::get('offerta');
        $testo_offerta = Request::get('testo_offerta');
        $tipo_offerta = Request::get('tipo_offerta');
        $referer = Request::get('referer');
        $actual_link = Utility::getUrlWithLang($locale, "/hotel.php?id=" . $ids_send_mail, true);
        $messaggioScrittura = false;

        /**
         * Prendo il cliente
         */

        $cliente = Hotel::with(['localita.macrolocalita'])
            ->attivo()
            ->find($ids_send_mail);

        /**
         * se è un cliente Gibberish
         */

        if (is_null($cliente)) {

            $cliente = Hotel::with(['localita.macrolocalita'])
                ->where('attivo', -1)
                ->first();
            $ids_send_mail = $cliente->id;

        }

        /**
         * Prendo il cookie
         */

        $prefill = CookieIA::getCookiePrefill();
        $numero_camere = array_key_exists('rooms', $prefill) ? count($prefill['rooms']) : 0;

        /**
         * Titolo, descrizione, localita per il Mobile
         */

        $title = Lang::get('hotel.prev_title');
        $description = Lang::get('hotel.prev_desc');
        $selezione_localita = $cliente->localita->nome;

        /**
         * Controllo che le camere non siano a 0
         */

        if ($numero_camere == 0) {

            /**
             * Creo il cookie mancante
             */

            $numero_camere = 1;
            $prefill["rooms"] = CookieIA::getCookieRoomPrefill();

        }

        /**
         * Chiamo la pagina
         */

        if ($param == '2_bottoni') {
            $view_name = 'templates.form_mail_mobile_2_bottoni';
        } elseif ($param == 'modal_first') {
            $view_name = 'templates.form_mail_mobile_modal_first';
        } else {
            $view_name = 'templates.form_mail_mobile';
        }

        if (array_key_exists('codice_cookie', $prefill)) {
            $recente = Utility::scrittoDiRecente($prefill['email'], $cliente->id);
        }

        $privacy = AcceptancePrivacy::getCheckForm($prefill["email"]);

        return View::make(
            $view_name,
            compact(
                'selezione_localita',
                'cliente',
                'prefill',
                'locale',
                'ids_send_mail',
                'title',
                'description',
                'actual_link',
                'referer',
                'offerta',
                'tipo_offerta',
                'testo_offerta',
                'numero_camere',
                'recente',
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

        if (Session::has('mail_reply') && Session::has('nome_reply') && Session::has('dati_mail') && Session::has('dati_json')) {
            $mail_reply = Session::get('mail_reply');
            $nome_reply = Session::get('nome_reply');
            $dati_mail = Session::get('dati_mail');
            $dati_json = Session::get('dati_json');

            if (Config::get("mail.spedisci_al_mittente")) {
                $this->_reply_to_client($nome_reply, $mail_reply, $dati_mail, $dati_json, $multipla, $mobile);
            }

        }

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

    /**
     * Error page
     *
     * @access public
     * @return View
     */

    public function error()
    {

        $ids_send_mail = "";
        $locale = $this->getLocale();
        $listing = "";
        $multipla = "";
        $cms_pagina_id = "";
        $mobile = "n";

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

        if (!$ids_send_mail || $ids_send_mail == "") {
            abort("404");
        }

        if (Session::has('multipla')) {
            $multipla = Session::get('multipla');
        }

        if (Session::has('listing')) {
            $listing = Session::get('listing');
        }

        if (Session::has('cms_pagina_id')) {
            $cms_pagina_id = Session::get('cms_pagina_id');
        }

        if (Session::has('mobile')) {
            $mobile = Session::get('mobile');
        }

        if (Session::has('redirect_url')) {
            $redirect_url = Session::get('redirect_url');
        }

        if (!isset($redirect_url)) {
            $redirect_url = '/';
        }

        $view = view('thankyoupage.error_send')->with(compact('subscribe', 'subscribe_name', 'subscribe_email', 'subscribe_phone', 'locale', 'redirect_url', 'listing', 'multipla', 'ids_send_mail', 'cms_pagina_id', 'mobile'));
        $response = new Response($view);
        return $response;

    }

    /**
     * Conteggio delle email al Milanese
     *
     * @access public
     * @param Integer $hotel_id
     * @return Redirect
     *
     */

    public function replyCountHotel($hotel_id)
    {

        if (is_null(Hotel::find($hotel_id))) {
            return redirect(url('/'), 301);
        }

        config('app.debug_log') ? Log::emergency("\n" . '---> hotel_id = ' . $hotel_id . ' <---' . "\n\n") : "";
        $exists = DB::table('tblMailReplyCount')->where('hotel_id', $hotel_id)->first();

        if (is_null($exists)) {

            DB::table('tblMailReplyCount')->insert(['hotel_id' => $hotel_id]);
            config('app.debug_log') ? Log::emergency("\n" . '---> exists is null <---' . "\n\n") : "";

        } else {

            $new_count = $exists->count + 1;
            config('app.debug_log') ? Log::emergency("\n" . '---> new_count = ' . $new_count . ' <---' . "\n\n") : "";

            DB::table('tblMailReplyCount')
                ->where('hotel_id', $hotel_id)
                ->update(['count' => $new_count]);

        }

        return redirect(url('hotel.php?id=' . $hotel_id));

    }

}

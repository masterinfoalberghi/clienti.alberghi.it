<?php

namespace App\Http\Controllers\Admin;

use DB;
use Log;
use File;
use Langs;
use App\Poi;
use App\User;
use Response;
use App\Hotel;
use Validator;
use App\Utility;
use App\Localita;
use App\Categoria;
use App\Tipologia;
use Carbon\Carbon;
use App\HotelRevision;
use App\ImmagineGallery;
use App\GestioneMultiple;
use App\AccettazioneCaption;
use Illuminate\Http\Request;
use SessionResponseMessages;
use App\Helpers\NuovaLocalita;
use App\VetrinaTrattamentoTop;
use App\Helpers\NumeriTelefono;
use Illuminate\Support\Facades\App;
use App\library\ImageVersionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Admin\HotelRequest;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\Admin\AcceptPolicyGalleryRequest;

class HotelController extends AdminBaseController
{


    /**
     * Torno i commerciali
     * 
     * @access private
     * @static
     * @return Array
     */

    private static function _getCommerciali()
    {
        return ['0' => 'Seleziona'] + User::withRole('commerciale')->where('name', '!=', '')->pluck('name', 'id')->toArray() + User::where('email', 'box@info-alberghi.com')->pluck('name', 'id')->toArray();
    }

    /**
     * Torna le localita
     * 
     * @access protected
     * @return Array
     */

    protected function getLocalitaForView()
    {

        $_localita = [];
        $localita = Localita::orderBy("nome", "asc")->get();
        foreach ($localita as $l) {

            if(gettype(json_decode($l->nome)) == "object") {
                $obj = json_decode($l->nome, true);
               $l->nome = $obj['it'];
            }
            $_localita[$l->id] = "{$l->nome} ({$l->prov}) - {$l->cap}";
        }

        return $_localita;
    }

    /**
     * Cancella la cache dell scheda hotel
     * 
     * @access public
     * @static
     * @param Integer $id
     * @param Request $request
     * @return SessionResponseMessages
     */

    public static function clearCacheHotel($id, Request $request)
    {

        Utility::clearCacheHotel($id);
        SessionResponseMessages::add("success", "Cache svuotata con successo");
        return SessionResponseMessages::redirect("/admin", $request);
    }

    /**
     * Lista tutti gli hotel 
     *
     * @access public
     * @param Request $request
     * @return Response
     */

    public function index(Request $request)
    {

        $ord = Utility::filterOrderFieldHotel($request->get('ord'));
        $dir = Utility::filterOrderDirection($request->get('dir'));

        $old = $request->all();

        foreach (["id", "localita_id", "nome", "attivo", "chiuso_temp", "annuale"] as $key) {
            if (!isset($old[$key])) {
                $old[$key] = null;
            }
        }

        $hotels = Hotel
            ::with(["localita", "editors","revisions"])
            ->where(function ($query) use ($request) {

                $key = "id";
                if ($id = $request->get($key)) {
                    $query->where('id', $id);
                }

                $key = "localita_id";
                if ($id = $request->get($key)) {
                    $query->where('localita_id', $id);
                }

                $key = "nome";
                if ($nome = $request->get($key)) {
                    $query->where('nome', 'LIKE', "%$nome%");
                }

                $key = "attivo";
                if ($request->filled($key)) {
                    $query->where('attivo', $request->get($key));
                }

                $key = "chiuso_temp";
                if ($request->filled($key)) {
                    $query->where('chiuso_temp', $request->get($key));
                }

                $key = "annuale";
                if ($request->filled($key)) {
                    $query->where('annuale', $request->get($key));
                }
            })

            ->orderBy($ord, $dir)
            ->paginate(100);

        $attivi = Hotel::attivo()->count();

        $lingue = [];
        foreach (Langs::getAll() as $lang) {
            $lingue[$lang] = $lang;
        }
        
        $new_hotels = [];

        $data = [
            "langs" => $lingue,
            "records" => $hotels,
            "attivi" => $attivi,
            "localita" => array(0 => "") + $this->getLocalitaForView(),
        ];

        return view('admin.hotels_index', compact("data", "old"));
    }

    /**
     * Mostra il form per editare un hotel
     *
     * @access public
     * @param  Integer $id
     * @return Response
     */
    public function edit($id)
    {   
        $lingue = [];
        foreach (Langs::getAll() as $lang) {
            $lingue[$lang] = $lang;
        }

        $hotel = Hotel::with([
            'revisions',
            'editors',
            'poi.poi_lingua' => function ($query) {
                $query
                    ->where('lang_id', '=', 'it');
            },
        ])->find($id);

        /* Ritorno numeri */

        $hotel["telefono"] = NumeriTelefono::getArrayOfNumbers($hotel['telefono']);
        $hotel["cell"] = NumeriTelefono::getArrayOfNumbers($hotel["cell"]);
        $hotel["whatsapp"] = NumeriTelefono::getArrayOfNumbers($hotel['whatsapp']);

        $zero_array = [];
        $zero_array[] = 0;
        $zero_array[] = 0;

        list($reception1_da_ora, $reception1_da_minutes) = (strpos($hotel->reception1_da, ':') !== false) ? explode(':', $hotel->reception1_da) : $zero_array;
        list($reception1_a_ora, $reception1_a_minutes) = (strpos($hotel->reception1_a, ':') !== false) ? explode(':', $hotel->reception1_a) : $zero_array;
        list($reception2_da_ora, $reception2_da_minutes) = (strpos($hotel->reception2_da, ':') !== false) ? explode(':', $hotel->reception2_da) : $zero_array;
        list($reception2_a_ora, $reception2_a_minutes) = (strpos($hotel->reception2_a, ':') !== false) ? explode(':', $hotel->reception2_a) : $zero_array;

        /**
         * 04/09/17: voglio verificare se su qualche trattamento ASSOCIATO c'è una EVIDENZA (vtt) CORRISPONDENTE: in modo che PRIMA DI DISASSOCIARE IL TRATTAMENTO MI RENDA CONTO CHE LA VETRINA TOP DOPO NON SI VEDRA' PIU'
         * trovo le pagine associate ai vtt ( SOLO QUELLE IN IT ) 
         */

        $pages = [];
        $trattamenti_evidenze = [];
        $commerciali = Self::_getCommerciali();

        $vetrine = VetrinaTrattamentoTop::where('hotel_id', $id)
            ->attiva()
            ->with(['translate.pagina'])
            ->get();

        if (count($vetrine)) {
            foreach ($vetrine as $vetrina) {
                $pages[] = $vetrina->translate->first()->pagina;
            }
        }

        /**
         * per ogni pagina verifico a quale trattamento è associata
         */

        if (count($pages)) {
            foreach ($pages as $page) {

                /** se è una pagina di tipo listing servizio */
                if ($page->listing_trattamento != '')
                    $trattamenti_evidenze[] = $page->listing_trattamento;
            }
        }

        /* Trasformo i numeri di telefono in un array */


        $data = [
            "langs" => $lingue,
            "record" => $hotel,
            "newid" => 0,
            "localita" => $this->getLocalitaForView(),
            "categorie" => Categoria::pluck("nome", "id"),
            "tipologie" => Tipologia::pluck("nome", "id"),
            "reception1_da_ora" => $reception1_da_ora,
            "reception2_da_ora" => $reception2_da_ora,
            "reception1_a_ora" => $reception1_a_ora,
            "reception2_a_ora" => $reception2_a_ora,
            "reception1_da_minutes" => $reception1_da_minutes,
            "reception2_da_minutes" => $reception2_da_minutes,
            "reception1_a_minutes" => $reception1_a_minutes,
            "reception2_a_minutes" => $reception2_a_minutes,
            "trattamenti_evidenze" => $trattamenti_evidenze,
            "commerciali" => $commerciali,
            "user" => Auth::id()
        ];

        /** AUTOMATICAMENTE MI IDENTIFICO CON L'HOTEL CHE STO MODIFICANDO !!!!*/
        $ui_editing_hotel = "$hotel->id $hotel->nome";

        Auth::user()->setCommercialeUiEditingHotelId($id);
        Auth::user()->setUiEditingHotelId($id);
        Auth::user()->setUiEditingHotel($ui_editing_hotel);
        Auth::user()->setUiEditingHotelPriceList($hotel->hide_price_list);

        $msgs = [];

        /** verifico se ci sono errori dalla funzione store() */
        $errors = Session::get("SessionResponseMessages");

        if ($errors) {
            foreach ($errors as $error) {
                $msgs[] = ["type" => $error['type'], "text" => $error['text']];
            }
        } else {
            $msgs[] = ["type" => "success", "text" => "Hai selezionato l'hotel: $ui_editing_hotel"];
        }

        Session::put("SessionResponseMessages", $msgs);
        return view('admin.hotels_edit', compact("data"));
    }

    public function revisions($id, $revision) 
    {

        $hotel = Hotel::with([
            'commerciale',
            'stelle',
            'revisions.editors',
            'editors',
            'poi.poi_lingua' => function ($query) {
                $query
                    ->where('lang_id', '=', 'it');
            },
        ])->find($id);

        $data = [
            "record" => $hotel,
            "commerciali" => User::where("role", "commerciale")->pluck( "name", "id")->toArray(),
            "tipologie" => Tipologia::pluck( "nome", "id")->toArray(),
            "categorie" => Categoria::pluck( "nome", "id")->toArray(),
        ];

        return view('admin.hotel_revisions', compact("data"));

    }

    public function changeRevision () {       

    }

    public function createRomagna()
    {
        
        $hotel = new Hotel;
        $commerciali = Self::_getCommerciali();

        $lastIDInsert = Hotel::where("id", "<", 5000)->orderBy("id", "DESC")->first();
        $newid = $lastIDInsert->id + 1;

        $hotel["telefono"] = NumeriTelefono::getArrayOfNumbers($hotel['telefono']);
        $hotel["cell"] = NumeriTelefono::getArrayOfNumbers($hotel["cell"]);
        $hotel["whatsapp"] = NumeriTelefono::getArrayOfNumbers($hotel['whatsapp']);

        $data = [
            "record" => $hotel,
            "newid" => $newid,
            "localita" => $this->getLocalitaForView(),
            "categorie" => Categoria::pluck("nome", "id"),
            "tipologie" => Tipologia::pluck("nome", "id"),
            "commerciali" => $commerciali,
        ];

        return view('admin.hotels_edit', compact("data"));
    }

    public function createItalia()
    {
        
        $hotel = new Hotel;
        $commerciali = Self::_getCommerciali();

        $hotel["telefono"] = NumeriTelefono::getArrayOfNumbers($hotel['telefono']);
        $hotel["cell"] = NumeriTelefono::getArrayOfNumbers($hotel["cell"]);
        $hotel["whatsapp"] = NumeriTelefono::getArrayOfNumbers($hotel['whatsapp']);

        $lastIDInsert = Hotel::orderBy("id", "DESC")->where("id", ">", "4999999")->first();
        if (!$lastIDInsert)
            $newid = 5000000;
        else
            $newid = $lastIDInsert->id + 1;

        $data = [
            "record" => $hotel,
            "newid" => $newid,
            "localita" => $this->getLocalitaForView(),
            "categorie" => Categoria::pluck("nome", "id"),
            "tipologie" => Tipologia::pluck("nome", "id"),
            "commerciali" => $commerciali,
        ];

        return view('admin.hotels_edit', compact("data"));
    }

    /**
     * Salva tutti dati dell'hotel
     * 
     * @access public
     * @param  HotelRequest  $request
     * @return SessionResponseMessages
     */

    public function store(HotelRequest $request)
    {   
       
        $id = $request->input("id");
        $newid = $request->input("newid");
        $geolocation_valid = true;
        $msgs = [];
        Utility::clearCacheHotel($id);

        if (!$id && $newid > 0) {
            $hotel = new Hotel;
            $hotel->id = $newid;
        } else
            $hotel = Hotel::find($id);

        $hotel->editor = Auth::user()->id;

        /**
         * VALIDAZIONE SUI TRATTAMENTI; ALMENO 1 PRESENTE
         */

        if (
            !$request->has('trattamento_ai') &&
            !$request->has('trattamento_pc') &&
            !$request->has('trattamento_mp') &&
            !$request->has('trattamento_bb') &&
            !$request->has('trattamento_sd') &&
            !$request->has('trattamento_mp_spiaggia') &&
            !$request->has('trattamento_bb_spiaggia') &&
            !$request->has('trattamento_sd_spiaggia')

        ) {
            if (!$id) {
                $msgs[] = [
                    'type' => 'error',
                    'text' => "Specificare almeno un trattamento !!!"
                ];

                Session::put("SessionResponseMessages", $msgs);

                return back()->withInput();
            } else {
                SessionResponseMessages::add("error", "Specificare almeno un trattamento !!!");
                return SessionResponseMessages::redirect("/admin/hotels/{$hotel->id}/edit", $request);
            }
        }

        /**
         * Questi sono campi che vanno preprocessati
         */

        $hotel->reception1_da = $request->input("reception1_da_ora") . ":" . $request->input("reception1_da_minutes");
        $hotel->reception1_a = $request->input("reception1_a_ora") . ":" . $request->input("reception1_a_minutes");
        $hotel->reception2_da = $request->input("reception2_da_ora") . ":" . $request->input("reception2_da_minutes");
        $hotel->reception2_a = $request->input("reception2_a_ora") . ":" . $request->input("reception2_a_minutes");
        $hotel->localita_id = $request->input("localita_id");
        $hotel->nome = $request->input("nome");
        $hotel->mappa_latitudine = (float) $request->input("mappa_latitudine");
        $hotel->mappa_longitudine = (float) $request->input("mappa_longitudine");

        $request->input("slug_it") == NULL ? $hotel->slug_it = NuovaLocalita::getSlugStruttura($hotel->id, "it", $hotel->nome, $hotel->localita_id) : $hotel->slug_it = $request->input("slug_it");
        $request->input("slug_en") == NULL ? $hotel->slug_en = NuovaLocalita::getSlugStruttura($hotel->id, "en", $hotel->nome, $hotel->localita_id) : $hotel->slug_en = $request->input("slug_en");
        $request->input("slug_fr") == NULL ? $hotel->slug_fr = NuovaLocalita::getSlugStruttura($hotel->id, "fr", $hotel->nome, $hotel->localita_id) : $hotel->slug_fr = $request->input("slug_fr");
        $request->input("slug_de") == NULL ? $hotel->slug_de = NuovaLocalita::getSlugStruttura($hotel->id, "de", $hotel->nome, $hotel->localita_id) : $hotel->slug_de = $request->input("slug_de");

        /** Serve solo per le traduzioni */
        $lang_fields = [
            'aperto_altro_',
            'checkin_',
            'checkout_',
            'caparra_altro_',
            'note_contanti_',
            'note_assegno_',
            'note_carta_credito_',
            'note_bonifico_',
            'note_paypal_',
            'note_bancomat_',
            'note_altro_pagamento_',
            'altra_lingua_',
            'notewa_'
        ];

        foreach ($lang_fields as $f) {

            if ($request->get($f . 'it') != '') {
                $value = $request->get($f . 'it');
                foreach (Langs::getAll() as $lang) {
                    if ($lang != 'it') {
                        $field = $f . $lang;
                        if ($request->get($field) == '') {
                            $request->merge([$field => Utility::translate($value, $lang)]);
                        }
                    }
                }
            }
        }

        $fields = [
            'attivo',
            'chiuso_temp',
            'hide_price_list',
            'nome',
            'commerciale_id',
            'categoria_id',
            'tipologia_id',
            'wifi_gratis',
            'localita_id',
            'cap',
            'indirizzo',
            'distanza_centro',
            'distanza_staz',
            'distanza_spiaggia',
            'distanza_fiera',
            'distanza_casello',
            'telefono',
            'cell',
            'telefoni_mobile_call',
            'contanti',
            'note_contanti_it',
            'note_contanti_en',
            'note_contanti_fr',
            'note_contanti_de',
            'assegno',
            'note_assegno_it',
            'note_assegno_en',
            'note_assegno_fr',
            'note_assegno_de',
            'carta_credito',
            'note_carta_credito_it',
            'note_carta_credito_en',
            'note_carta_credito_fr',
            'note_carta_credito_de',
            'bonifico',
            'note_bonifico_it',
            'note_bonifico_en',
            'note_bonifico_fr',
            'note_bonifico_de',
            'paypal',
            'note_paypal_it',
            'note_paypal_en',
            'note_paypal_fr',
            'note_paypal_de',
            'bancomat',
            'note_bancomat_it',
            'note_bancomat_en',
            'note_bancomat_fr',
            'note_bancomat_de',
            'altro_pagamento',
            'note_altro_pagamento_it',
            'note_altro_pagamento_en',
            'note_altro_pagamento_fr',
            'note_altro_pagamento_de',
            'inglese',
            'francese',
            'tedesco',
            'spagnolo',
            'russo',
            'altra_lingua_it',
            'altra_lingua_en',
            'altra_lingua_fr',
            'altra_lingua_de',
            'green_booking',
            'eco_sostenibile',
            'chiedi_camere',
            'fax',
            'whatsapp',
            'notewa_it',
            'notewa_en',
            'notewa_fr',
            'notewa_de',
            'skype',
            'email',
            'email_multipla',
            'email_risponditori',
            'email_secondaria',
            'link',
            'testo_link',
            'annuale',
            'aperto_eventi_e_fiere',
            'aperto_pasqua',
            'aperto_capodanno',
            'aperto_25_aprile',
            'aperto_1_maggio',
            'aperto_altro_check',
            'aperto_altro_it',
            'aperto_altro_en',
            'aperto_altro_fr',
            'aperto_altro_de',
            'checkin_it',
            'checkout_it',
            'checkin_en',
            'checkout_en',
            'checkin_fr',
            'checkout_fr',
            'checkin_de',
            'checkout_de',
            'colazione_da',
            'colazione_a',
            'pranzo_da',
            'pranzo_a',
            'cena_da',
            'cena_a',
            'reception_24h',
            'caparra_si',
            'caparra_no',
            'caparra_altro_check',
            'caparra_altro_it',
            'caparra_altro_en',
            'caparra_altro_fr',
            'caparra_altro_de',
            'prezzo_min',
            'prezzo_max',
            'trattamento_ai',
            'trattamento_pc',
            'trattamento_mp',
            'trattamento_bb',
            'trattamento_sd',
            'trattamento_mp_spiaggia',
            'trattamento_bb_spiaggia',
            'trattamento_sd_spiaggia',
            'note_ai_it',
            'note_pc_it',
            'note_mp_it',
            'note_bb_it',
            'note_sd_it',
            'note_mp_spiaggia_it',
            'note_bb_spiaggia_it',
            'note_sd_spiaggia_it',
            'n_camere',
            'n_posti_letto',
            'n_appartamenti',
            'n_suite',
            'rating_ia',
            'n_rating_ia',
            'source_rating_ia',
            'enabled_rating_ia',
            'certificazione_aci',
            'organizzazione_matrimoni',
            'organizzazione_comunioni',
            'organizzazione_cresime',
            'note_organizzazione_matrimoni',
            'design_hotel',
            'family_hotel', 
            'cir'
        ];

        foreach ($fields as $field) {

            if ($field == "source_rating_ia") {
                $t = 0;
                $value = $request->input($field);

                foreach ($value as $f) {
                    if (trim($f) == "") {
                        unset($value[$t]);
                    }
                    $t++;
                }

                if (empty($value)) {
                    $hotel->$field = json_encode([]);
                } else {
                    $hotel->$field = json_encode($value);
                }
            } else {
                $hotel->$field = $request->input($field, '');
            }
        }

        /**
         * azzero le note dei matrimoni se NON organizzati
         */

        if (!$request->input('organizzazione_matrimoni') && !$request->input('organizzazione_comunioni') && !$request->input('organizzazione_cresime'))
            $hotel->note_organizzazione_matrimoni = '';

        if ($request->input('nascondi_url'))
            $hotel->nascondi_url = true;

        else
            $hotel->nascondi_url = false;

        if ($request->filled('aperto_dal'))
            $hotel->aperto_dal = Utility::getCarbonDate($request->get('aperto_dal'));

        if ($request->filled('aperto_al'))
            $hotel->aperto_al = Utility::getCarbonDate($request->get('aperto_al'));

        if (!$hotel->mappa_latitudine || !$hotel->mappa_longitudine)
            $geolocation_valid = false;

        if (!$geolocation_valid) {

            $msgs[] = [
                'type' => 'error',
                'text' => "I valori di Latitudine e Longitudine sono invalidi, in questa situazione non è possibile calcolare le distanze dai POI e trovare i luoghi vicini."
            ];

            Session::put("SessionResponseMessages", $msgs);

            return back()->withInput();
        }

        if (!$hotel->distanza_centro) {
            $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $hotel->localita->centro_lat, $hotel->localita->centro_long);
            $hotel->distanza_centro = round($distanza_metri / 1000, 2);
        }

        if (!$hotel->distanza_staz) {
            $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $hotel->localita->staz_lat, $hotel->localita->staz_long);
            $hotel->distanza_staz = round($distanza_metri / 1000, 2);
        }

        if (!$hotel->distanza_fiera) {
            if ($hotel->id > 20000) {
                $fiera = Poi::where("id", 56)->first();
            } else {
                $fiera = Poi::where("id", 9)->first();
            }   
            $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $fiera->lat, $fiera->long);
            $hotel->distanza_fiera = round($distanza_metri / 1000, 2);

        }

        // if (!is_null($hotel->localita->distanza_casello)) {
        //     if (!$hotel->distanza_casello) {
        //         $caselli = json_decode($hotel->localita->distanza_casello);
        //         $distanza = 10000000;

        //         foreach ($caselli as $casello) :
        //             $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $casello->lat, $casello->long);
        //             if ($distanza_metri < $distanza) {
        //                 $hotel->distanza_casello =  round($distanza_metri / 1000, 2);
        //                 $hotel->distanza_casello_label = $casello->name;
        //                 $distanza = $distanza_metri;
        //             }
        //         endforeach; 

        //     }
        // } else {

        /**
         * FILIPPO 20/03/2023: 
         * Ho commentato $hotel->distanza_casello =  0 
         * perché le ragazze hanno segnalato che la distanza nelle strutture di bologna editate recentemente 
         * avevano tutte la distanza dal casello 0.00
         * 
         */
            
        // $hotel->distanza_casello =  0;
        $hotel->distanza_casello_label = NULL;
        // }


        /**
         * Formattazione dei numeri di telefono 
         * */ 
        
        
        $telefono = array_map(function($tel) {
            $tel = preg_replace('/\s+/', '', $tel);
            return NumeriTelefono::formatNumber($tel, 'fisso');
        }, $request->input('telefono'));
        
        $cell = array_map(function($tel) {
            $tel = preg_replace('/\s+/', '', $tel);
            return NumeriTelefono::formatNumber($tel, 'mobile');
        }, $request->input('cell'));
        
        $whatsapp = array_map(function($tel) {
            $tel = preg_replace('/\s+/', '', $tel);
            $result = NumeriTelefono::formatNumber($tel, 'mobile'); 
            if ($result == 0) 
             return NumeriTelefono::formatNumber($tel, 'fisso'); 
            else 
             return $result;           
        }, $request->input('whatsapp'));

        // dd($whatsapp);

        if (count($telefono) > 1)
            $hotel->telefono = implode(" / ", $telefono);
        else
            $hotel->telefono = $telefono[0];

        if (count($cell) > 1)
            $hotel->cell = implode(" / ", $cell);
        else
            $hotel->cell = $cell[0];

        if (count($whatsapp) > 1)
            $hotel->whatsapp = implode(" / ", $whatsapp);
        else
            $hotel->whatsapp = $whatsapp[0];

        /**
         * se l'albergatore ha già modificato i trattamenti trattamenti_moderati è passato da NULL a 0
         * e quindi adesso lo setto a 1
         */

        if (!is_null($hotel->trattamenti_moderati)) {
            $hotel->trattamenti_moderati = 1;
            $hotel->data_moderazione = Carbon::now();
        }

        $hotel->save();

        $hotelRevision = new HotelRevision();
        $hotelRevision->hotel_id = $hotel->id;
        $hotelRevision->editor_id = Auth::user()->id;
        $hotelRevision->data = $hotel->toJson();
        $hotelRevision->save();

        /**
         * Aggiunge l'hotel alla gestione delle email multiple
         * @var [type]
         */

        $inventory = GestioneMultiple::where('hotel_id', $hotel->id)->first();
        if (!$inventory)
            GestioneMultiple::insert(["hotel_id" => $hotel->id, "contact" => 0]);

        /**
         * SE L'HOTEL HA delle vetrine (slotVetrine) aggiorno i prezzi nella tabella tblSlotVetrine
         */

        if ($hotel->slotVetrine()->count()) {
            foreach ($hotel->slotVetrine as $slot) {
                $slot->hotel_nome = $hotel->nome;
                $slot->hotel_categoria_id = $hotel->categoria_id;
                $slot->hotel_prezzo_min = $hotel->prezzo_min;
                $slot->hotel_prezzo_max = $hotel->prezzo_max;
                $slot->save();
            }
        }

        //? poi_ricalcola_distanza solo se sono un NUVO HOTEL
        if ($request->input("poi_ricalcola_distanza") == 1) {
            //! ATTENZIONE: adesso la tabella di pivot contiene anche 
            //! g_distanza, g_durata, g_descrizione_rotta,g_modo che sono aggiunte con un command artisan
            //! quindi non le posso ELIMINARE PRIMA

            /**
             * ATTENZIONE non sono più i POI generici, MA quelli assocaiti alla località dell'hotel
             */

            $points_of_interest = $hotel->localita->poi;

            /*
             * Se uso il salvataggio della relazione nativo di Laravel
             * http://laravel.com/docs/5.1/eloquent-relationships#inserting-related-models
             * mi farebbe una query per ogni relazione, preferisco fare una query unica
             */

            $relations = [];
            foreach ($points_of_interest as $point_of_interest) {

                $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $point_of_interest->lat, $point_of_interest->long);

                $relations[] = [
                    "hotel_id" => $hotel->id,
                    "poi_id" => $point_of_interest->id,
                    "distanza" => round($distanza_metri / 1000, 2),
                    "g_distanza" => null,
                    "g_durata" => null,
                    "g_distanza_numeric" => null,
                    "g_durata_numeric" => null,
                    "g_descrizione_rotta" => null,
                    "g_modo" => null,
                    "g_url" => null,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),
                ];
            }

            if ($relations) {

                //? Cancello i POI vecchi
                DB::table("tblHotelPoi")->where('hotel_id', $hotel->id)->delete(); 
                DB::table("tblHotelPoi")->insert($relations);

                try {

                    //? PER QUESTO HOTEL
                    //? devo trovare i tempi di percorrenza dai poi 
                    Artisan::call('poi:distanze', [
                        '--hotel_id' => $hotel->id
                    ]);

                    SessionResponseMessages::add("info", "Calcolate le distanze dai POI");
                } catch (\Exception  $e) {

                    SessionResponseMessages::add("error", "ERRORE calcolo DISTANZE dai POI (poi:distanze) " . $e->getMessage());

                    Log::emergency("\n" . $hotel->id . '---> ERRORE calcolo DISTANZE dai POI (poi:distanze) --- ' . $e->getMessage() . ' <---' . "\n\n");
                }
            }

            //? PER QUESTO HOTEL
            //? devo trovare i google poi
            try {

                //? PER QUESTO HOTEL
                //? devo trovare i tempi di percorrenza dai poi 
                Artisan::call('google:place', [
                    '--hotel_id' => $hotel->id
                ]);

                SessionResponseMessages::add("info", "Google ha trovato i POI nearby");

            } catch (\Exception  $e) {

                SessionResponseMessages::add("error", "ERRORE calcolo dei GOOGLE POI (google:place) " . $e->getMessage());
                Log::emergency("\n" . $hotel->id . '---> ERRORE calcolo dei GOOGLE POI (google:place) --- ' . $e->getMessage() . ' <---' . "\n\n");
            }
        }

        /**
         * GESTIONE UPLOAD IMMAGINE LISTING
         */

        if ($id && !is_null($request->file('listing_img'))) {

            $rules = array(
                'listing_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3000',
            );

            $messages = [
                'image' => 'Il file selezionato deve essere un\'immagine!',
                'max' => 'La dimensione massima del file deve essere :max KB',
            ];

            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                $errors = $validation->errors()->all();

                foreach ($errors as $error) {
                    SessionResponseMessages::add("warning", $error);
                }

                return SessionResponseMessages::redirect("/admin/hotels/{$hotel->id}/edit", $request);
            } else {

                try {

                    $imagev = new ImageVersionHandler;

                    if ($file = $request->file('listing_img')) {

                        $uploaded_filename = File::name($file->getClientOriginalName());
                        $imagev->setImageBasename(str_replace(' ', '_', "{$uploaded_filename}_{$hotel->nome}_" . uniqid()));
                        $backup_path = config("app.storage_images_path") . "/original_images/listing";

                        $imagev->enableOriginalBackup($backup_path);
                        $imagev->loadOriginalFromUpload($file);
                        SessionResponseMessages::add("info", "Copia backup immagine originale salvata in $backup_path/" . $imagev->getImageFilename());

                        foreach (ImmagineGallery::getImagesVersions() as $v) {
                            $imagev->process($v["mode"], $v["basedir"], $v["width"], $v["height"]);

                            SessionResponseMessages::add("info", "Creata variante immagine {$v["width"]}x{$v["height"]} ({$v["mode"]})");
                        }

                        $hotel->update(['listing_img' => $imagev->getImageFilename()]);
                    }
                } catch (\Exception $e) {
                    config('app.debug_log') ? Log::emergency("\n" . '---> Errore UPLOAD IMMAGINE LISTING: ' . $e->getMessage() . ' <---' . "\n\n") : "";
                    SessionResponseMessages::add("warning", $e->getMessage());
                    return SessionResponseMessages::redirect("/admin/hotels/{$hotel->id}/edit", $request);
                }
            }
        }

        SessionResponseMessages::add("success", "Modifiche salvate con successo.");
        return SessionResponseMessages::redirect("/admin/hotels/{$hotel->id}/edit", $request);
    }

     /**
     * Salva tutti dati per lo scarico di responsabilità
     * 
     * @access public
     * @param  Request  $request
     * @return Void
     */

    public function release(Request $request) {

        $user_id = $request->release_user_id;
        $text_id = $request->release_text_id;

        if (!$user_id || !$text_id )
            abort("404");

        DB::table("tblAcceptanceRelease")->insert(["user_id" => $user_id, "release_id" => $text_id, "created_at" => \Carbon\Carbon::now(), "updated_at" => \Carbon\Carbon::now()]);
        
        
    }

    // public static function releasetext() {

    //     $release = DB::table("tblAcceptanceRelease")
    //                     ->leftJoin("tblReleaseText", "tblAcceptanceRelease.release_id", "=", "tblReleaseText.id")
    //                     ->orderBy("tblAcceptanceRelease.created_at", "DESC")
    //                     ->get();
    //     dd($release);

    //     // $data = [
            
    //     //     "release" => ,
    //     //     "localita" => array(0 => "") + $this->getLocalitaForView(),
    //     // ];

    //     return view('admin.hotels_index', compact("data", "old"));

    // }


    /**
     * Cancella l'immagine listing_img (file su filesystem e valore su db)
     * 
     * @access public 
     * @param  Request  $request
     * @return Response
     */

    public function removeImage(Request $request)
    {
        $id = $request->input("id");

        $hotel = Hotel::find($id);

        if ($hotel->deleteListingImg()) {

            $hotel->save();
            SessionResponseMessages::add("success", "Immagine cancellata con successo.");
        } else
            SessionResponseMessages::add("error", "Non è stato possibile cancellare l'immagine, verificare i diritti della directory " . public_path(Hotel::LISTING_IMG_PATH));

        return SessionResponseMessages::redirect("/admin/hotels/{$hotel->id}/edit", $request);
    }

    /**
     * Elimina un hotel
     *
     * @access public
     * @param  Request  $request
     * @return SessionResponseMessages
     */

    public function destroy(Request $request)
    {
        $id = $request->input("id");

        Hotel::destroy($id);
        Utility::clearCacheHotel($id);

        SessionResponseMessages::add("success", "Il record ID=$id è stato eliminato.");
        return SessionResponseMessages::redirect("/admin/hotels", $request);
    }

    /**
     * Non ne ho idea
     *
     * @access public
     * @param  AcceptPolicyGalleryRequest  $request
     * @return Response
     */

    public function acceptPolicyGallery(AcceptPolicyGalleryRequest $request)
    {
        $hotel_id = $this->getHotelId();
        $accettazioneCaption = new AccettazioneCaption();
        $accettazioneCaption->save();

        Hotel::find($hotel_id)
            ->update(['accettazioneCaption_id' => $accettazioneCaption->id]);

        return redirect("/admin/immagini-gallery-titoli");
    }

    public function newsletter(Request $request, $id)
    {   
        try {

            $from    = "andreabernabei@info-alberghi.com";
            $nome    = "Info Alberghi srl - Maria Andrea Bernabei";
            $oggetto = "Ora fai parte del nostro nuovo progetto";
            $to = $request->email_presentazione;

            $hotel = Hotel::find($id);
            $url = "https://www.info-alberghi.com/" . $hotel->slug_it;

            Utility::swapToSendGrid();
            Mail::send("emails.newsletter_localita",
            compact(
                "oggetto",
                "url"
            ), function ($message) use ($from, $nome, $oggetto, $to ) {
                $message->from($from, $nome);
                $message->replyTo($from);
                $message->to($to);
                //$message->bcc("giovanni@info-alberghi.com");
                $message->subject($oggetto);
            });

            $hotel->newsletter_send = 1;
            $hotel->save();

            SessionResponseMessages::add("success", "Lettera di presentazione inviata a " . $to);
            return SessionResponseMessages::redirect("/admin/hotels/" . $hotel->id . "/edit", $request);

        } catch (\Exception $error) {
            
            SessionResponseMessages::add("/admin/hotels", $error->getMessage());
            return SessionResponseMessages::redirect("/admin/hotels", $request);

        }
    }


    public function generate_password(Request $request, $id)
    {
        try {

            /** recupero i dati */
            $email = $request->email;
            $email_account = $request->email_account;
            $email_preventivo = $request->email_preventivo;

            /** creo la password */
            $pswLength = rand(8, 20);
            $pw = '';

            for ($i = 0; $i < $pswLength; $i++) {
                $pw .= chr(rand(32, 126));
            }

            /** trovo l'hotel */
            $hotel = Hotel::find($id);
            $account = User::where("hotel_id", $hotel->id)->first();

            /** Cambio l'account */
            if (!$account) {

                $account = new User();
                $account->email = $email_account;
                $account->username = $email_account;
                $account->role = "hotel";
                $account->hotel_id = $id;
                $account->password = Hash::make($pw);

            } else {

                $account->email = $email_account;
                $account->password = Hash::make($pw);

            }

            $account->save();

            /** Confermo l'email per i preventivi */
            $hotel->email = $email_preventivo;
            $hotel->psw_sent = 1;
            $hotel->save();

            /** Invio email */
            $user = $email_account;
            $password = $pw;
            $from = "supporto@info-alberghi.com";
            $nome = "Lo staff di Info Alberghi";
            $oggetto = "Nuovo account creato";

            Utility::swapToSendGrid();
            Mail::send("emails.generazione_password",

            compact(
                "user",
                "password",
                "oggetto"
            ), function ($message) use ($from, $nome, $oggetto, $email ) {
                $message->from($from, $nome);
                $message->replyTo($from);
                $message->to($email);
                $message->subject($oggetto);
            });

            SessionResponseMessages::add("success", "L'account è stato generato correttamente");
            return SessionResponseMessages::redirect("/admin/hotels/" . $hotel->id . "/edit", $request);

        } catch (\Exception $error) {
            
            SessionResponseMessages::add("/admin/hotels", $error->getMessage());
            return SessionResponseMessages::redirect("/admin/hotels", $request);

      }
       /*try {

            $hotel = Hotel::find($id);
            $pswLength = rand(8, 20);
            $pw = '';

            for ($i = 0; $i < $pswLength; $i++) {
                $pw .= chr(rand(32, 126));
            }
            
            $account = new User();
            $account->email = $hotel->email;
            $account->username = $hotel->email;
            $account->role = "hotel";
            $account->hotel_id = $id;
            $account->password = Hash::make($pw);
            $account->save();

            $hotel->psw_sent = 1;
            $hotel->save();

            if (App::environment() == "local") {
                $email = 'testing.infoalberghi@gmail.com';
            }  else {
                $email = $account->email;
            }

            $user = $account->email;
            $password = $pw;
            $from = "supporto@info-alberghi.com";
            $nome = "Lo staff di Info Alberghi";
            $oggetto = "Nuovo account creato";

            Utility::swapToSendGrid();
            Mail::send("emails.generazione_password",
            compact(
                "user",
                "password",
                "oggetto"
            ), function ($message) use ($from, $nome, $oggetto, $email ) {

                $message->from($from, $nome);
                $message->replyTo($from);
                $message->to($email);
                $message->subject($oggetto);
                
            });
            
            SessionResponseMessages::add("success", "L'account è stato generato correttamente");
            return SessionResponseMessages::redirect("/admin/hotels", $request);


       } catch (\Exception $error) {
            
             SessionResponseMessages::add("/admin/hotels", $error->getMessage());
            return SessionResponseMessages::redirect("/admin/hotels", $request);

       }*/

        // SessionResponseMessages::add("success", "Password generata con successo.");
        // return SessionResponseMessages::redirect("/admin/hotels/{$hotel->id}/edit", $request);

    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\CategoriaServizi;
use App\GruppoServizi;
use App\Hotel;
use App\HotelServizi;
use App\Http\Controllers\getLocale;
use App\Http\Requests\Admin\ServiziRequest;
use App\Servizio;
use App\ServizioLingua;
use App\ServizioPrivato;
use App\ServizioPrivatoLingua;
use App\Utility;
use App\VetrinaServiziTop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SessionResponseMessages;

class ServiziController extends AdminBaseController
{

    /* ------------------------------------------------------------------------------------
	 * METODI PRIVATI
	 * ------------------------------------------------------------------------------------ */

    /**
     * A regime sarà così: $hotel->servizi()->detach();
     * Adesso devo togliere SOLO i servizi NUOVI 
     * 
     * @access private
     * @param Hotel $hotel
     */

    private function _cancellaServizi($hotel)
    {

        $ids_to_detach = Servizio::nuovo()->pluck('id')->toArray();
        $hotel->servizi()->detach($ids_to_detach);
    }

    /**
     * Prende tutti i servizi associati
     * 
     * @access private
     * @param Hotel $hotel
     * @return Array
     */

    private function _getIdServiziAssociati($hotel)
    {
        return $hotel->servizi()->nuovo()->get()->pluck('id')->toArray();
    }

    /**
     * Prende tutti i servizi associati in lingua
     * 
     * @access private
     * @param String $lang_id
     * @return Servizio
     */

    private function _listaServiziLingua($lang_id = null)
    {
        if (is_null($lang_id))
            return Servizio::with('servizi_lingua')->nuovo()->get();

        else
            return Servizio::with(['servizi_lingua' => function ($query) use ($lang_id) {
                $query->where('lang_id', '=', $lang_id);
            }])->nuovo()->get();
    }

    /**
     * Prende tutti i servizi associati
     * 
     * @access private
     * @return ServizioLingua
     */

    private function _listaServizi()
    {

        return ServizioLingua::where('lang_id', 'it')->whereHas('servizio', function ($q) {
            $q->nuovo();
        })->with(['servizio.gruppo', 'servizio.categoria'])->orderBy('nome')->get();
    }

    /**
     * Prende tutti i servizi da associare
     * 
     * @access private
     * @param String $locale
     * @return CategoriaServizi
     */

    private function _listaServiziDaAssociare($locale = 'it')
    {
        return CategoriaServizi::has('servizi')->with([
            'servizi',
            'servizi.servizi_lingua' => function ($query) use ($locale) {
                $query->where('lang_id', '=', $locale);
            },
            'servizi.gruppo',
            'serviziPrivati'
        ])
            ->orderBY('position')->get();
    }

    /**
     * Aggiunge dati comuni all'array di riferimento
     * 
     * @access private
     * @param Array $data
     * @return Void
     */

    private function _pass_common_data_to_form(&$data)
    {

        $categorie = CategoriaServizi::pluck('nome', 'id');
        $gruppi = GruppoServizi::pluck('nome', 'id');
        $gruppi = array('0' => 'In nessuna ricerca') + $gruppi->toArray();

        $data["categorie"] = $categorie;
        $data["gruppi"] = $gruppi;

        // voglio visualizzare anche lenco dei servizi inseriti (SOLO in lingua 'it' per visualizzarne il nome) MA solo per poter modificare gruppo e categoria
        $servizi = $this->_listaServizi();
        $data["servizi"] = $servizi;

        // quelli nseriti devono essere visualizzati per categoria, allo stesso modo in cui li visualizzo quando li devo associare
        $servizi_per_categorie = $this->_listaServiziDaAssociare();
        $data['servizi_per_categorie'] = $servizi_per_categorie;
    }

    /**
     * Aggiunge dati comuni all'array di riferimento
     * 
     * @access private
     * @param Array $data
     * @param String $locale
     * @return Void
     */

    private function _getDataForServizi(&$data, $locale)
    {

        $data['locale'] = $locale;

        /** Trovo l'hotel */
        $hotel_id = $this->getHotelId();
        $hotel = Hotel::find($hotel_id);
        $data['hotel_id'] = $hotel_id;

        /** Trovo gli id servizi */
        $servizi_ids = $this->_getIdServiziAssociati($hotel);
        $data['servizi_ids'] = $servizi_ids;

        /** Trovo le categorie */
        $categorie = $this->_listaServiziDaAssociare($locale);
        $data['categorie'] = $categorie;

        /**
         * devo creare un array del tipo array[$id_servizio]= note
         * sfruttando la relazione hotel-servizi e la tabella pivot
         */
        $array_note = [];

        /**
         * Per ogni categoria
         * ho dei servizi privati del cliente
         */

        $array_servizi_privati = [];

        foreach ($categorie as $categoria) {

            $array_servizi_privati[$categoria->id] = [];

            foreach ($categoria->servizi as $servizio) {

                $cliente_con_servizio = $servizio->clienti()->where('hotel_id', $hotel_id)->first();

                if (is_null($cliente_con_servizio))
                    $array_note[$servizio->id] = " ";

                else
                    if ($locale == 'it') {
                    $array_note[$servizio->id] = $cliente_con_servizio->pivot->note;
                } else {
                    $note_name = "note_$locale";
                    $array_note[$servizio->id] = $cliente_con_servizio->pivot->$note_name;
                }
            }

            $servizi = $categoria->serviziPrivati()->where('hotel_id', $hotel_id)->get();

            if (!is_null($servizi))
                foreach ($servizi as $servizio_privato) {

                    $sp = [];
                    $sp['id'] = $servizio_privato->id;

                    if (is_null($servizio_privato->translate($locale)->first())) {
                        $sp['nome'] = '(TRADUZIONE MANCANTE)';
                    } else {
                        $sp['nome'] = $servizio_privato->translate($locale)->first()->nome;
                    }

                    $sp['placeholder'] = [];
                    $sp['placeholder']['it'] = $servizio_privato->translate("it")->first();
                    $sp['placeholder']['en'] = $servizio_privato->translate("en")->first();
                    $sp['placeholder']['fr'] = $servizio_privato->translate("fr")->first();
                    $sp['placeholder']['de'] = $servizio_privato->translate("de")->first();

                    $array_servizi_privati[$categoria->id][] = $sp;
                }
        }

        $data['array_note'] = $array_note;
        $data['array_servizi_privati'] = $array_servizi_privati;

        /**
         * 04/09/17
         * Voglio verificare se su qualche servizio ASSOCIATO c'è una EVIDENZA (vst) CORRISPONDENTE: in modo che PRIMA DI DISASSOCIARE IL SERVIZIO MI RENDA CONTO CHE LA VETRINA TOP DOPO NON SI VEDRA' PIU' 
         * Trovo le pagine associate ai vst ( SOLO QUELLE IN IT ) 
         */

        $pages = [];
        $servizi_evidenze_ids = [];

        $vetrine = VetrinaServiziTop::where('hotel_id', $hotel_id)
            ->attiva()
            ->with(['translate.pagina'])
            ->get();

        if (count($vetrine))
            foreach ($vetrine as $vetrina) {
                $pages[] = $vetrina->translate->first()->pagina;
            }


        /** Per ogni pagina verifico a quali servizi è associata  */
        if (count($pages))
            foreach ($pages as $page) {

                /** Se è una pagina di tipo listing servizio */
                if ($page->listing_gruppo_servizi_id) {

                    $servizi_gruppo = GruppoServizi::find($page->listing_gruppo_servizi_id)->servizi;

                    if (count($servizi_gruppo))
                        foreach ($servizi_gruppo as $servizio) {
                            $servizi_evidenze_ids[] = $servizio->id;
                        }
                }
            }

        /** di tutti i servizi associati alle evidenze prendo solo quelli checkati $servizi_ids */
        $servizi_evidenze_ids = array_intersect($servizi_evidenze_ids, $servizi_ids);
        $data['servizi_evidenze_ids'] = $servizi_evidenze_ids;
    }

    /* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI
	 * ------------------------------------------------------------------------------------ */

    /**
     * View principale Servizi
     *
     * @access public
     * @return Response
     */

    public function index()
    {

        $servizi = $this->_listaServiziLingua();
        $data = [];
        foreach ($servizi as $servizio) {
            foreach ($servizio->servizi_lingua as $servizio_lingua) {
                $data[$servizio_lingua->lang_id][$servizio->id] = $servizio_lingua->nome . '|' . $servizio->categoria->nome;
            }
        }

        return view('admin.servizi_edit', compact("data"));
    }

    /**
     * View Crea nuovi servizi
     *
     * @access public
     * @return Response
     */

    public function create()
    {

        $data = [];
        $this->_pass_common_data_to_form($data);
        return view('admin.servizio_form', $data);
    }

    /**
     * View modifica servizi
     *
     * @access public
     * @param String $id
     * @return Response
     */

    public function edit($id)
    {

        $data = [];
        $servizio = Servizio::find($id);
        $data['showButtons'] = 1;
        $data["servizio"] = $servizio;

        // select box per selezione categoria e gruppo
        $this->_pass_common_data_to_form($data);
        return view('admin.servizio_form', $data);
    }

    public function show($id)
    {
    }

    /* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI CUSTOM
	 * ------------------------------------------------------------------------------------ */

    /**
     * View Servizi hotel
     * 
     * @access public
     * @return Response
     */

    public function viewServiziHotel()
    {
        $locale = $this->getLocale();
        $data = [];
        $this->_getDataForServizi($data, $locale);
        return view('admin.servizi_hotel', $data);
    }

    /**
     * View Servizi hotel 
     * 
     * @access public
     * @return Response
     */

    public function viewServiziHotelReadOnly()
    {
        $locale = 'it';
        $data = [];
        $this->_getDataForServizi($data, $locale);
        return view('admin.servizi_hotel_readonly', $data);
    }

    /**
     * Salvataggio servizi
     *
     * @access public
     * @param ServiziRequest $request
     * @return SessionResponseMessages (redirect)
     */

    public function store(ServiziRequest $request)
    {

        $servizio = Servizio::create(['gruppo_id' => $request->get('gruppo_id'), 'categoria_id' => $request->get('categoria_id')]);

        /*
         * Utilizzo le API di google translate per tradurre il nomE nelle lingue
         */

        $nome = $request->get('nome');
        $servizio_lingua[] = ["master_id" => $servizio->id, "nome" => $nome, "lang_id" => 'it'];

        foreach (Utility::linguePossibili() as $lingua) {
            if ($lingua != 'it') {
                $servizio_lingua[] = ["master_id" => $servizio->id, "nome" => Utility::translate($nome, $lingua), "lang_id" => $lingua];
            }
        }

        ServizioLingua::insert($servizio_lingua);
        SessionResponseMessages::add("success", "Inserimento effettuato con successo.<br />Le modifiche saranno visibili entro le 2 ore successive");
        return SessionResponseMessages::redirect("admin/servizi/create", $request);
    }

    /**
     * Aggiornamento dei servizi
     *
     * @param  int  $id
     * @param  Request $request
     * @return SessionResponseMessages (redirect)
     */

    public function update($id, Request $request)
    {

        Servizio::where('id', $id)->update(['gruppo_id' => $request->get('gruppo_id'), 'categoria_id' => $request->get('categoria_id')]);
        SessionResponseMessages::add("success", "Modifiche salvate con successo.");
        //SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
        return SessionResponseMessages::redirect("admin/servizi/create", $request);
    }

    /**
     * Aggiornamento dei servizi in lingua
     *
     * @param  Request $request
     * @return SessionResponseMessages (redirect)
     */

    public function updateLingua(Request $request)
    {

        $traduci = $request->input('traduci');

        /* 
         * Cancello tutta "la parte in lingua" perché posso aver modificato più lingue nello stesso tempo !!!
         */

        if (isset($traduci)) {

            /**
             * TRADUCO I SERVIZI IN TUTTE LE LINGUE
             */

            $servizi = Servizio::nuovo()->get();
            foreach ($servizi as $servizio) {

                $key = "servizioit{$servizio->id}";

                if ($request->filled($key)) {

                    foreach ($servizio->servizi_lingua as $servizi_lingua) {
                        $servizi_lingua->delete();
                    }

                    foreach (Utility::linguePossibili() as $lingua) {

                        $servizio_lingua = new ServizioLingua;

                        if ($lingua == 'it')
                            $servizio_lingua->nome = $request->get($key);

                        else
                            try {
                                $servizio_lingua->nome = Utility::translate($request->get($key), $lingua);
                            } catch (Exception $e) {
                                $servizio_lingua->nome = strtoupper($lingua) . '__' . $request->get($key);
                            }

                        $servizio_lingua->lang_id = $lingua;
                        $servizio_lingua->master_id = $servizio->id;
                        $servizio_lingua->save();
                    }
                }
            }
        } else {

            /**
             * NON TRADUCO I SERVIZI
             */

            $servizi = $this->_listaServiziLingua();

            foreach ($servizi as $servizio) {
                $nuovo_servizio_lingua = [];
                foreach ($servizio->servizi_lingua as $servizio_lingua) {

                    $original_servizio_lingua = $servizio_lingua->toArray();
                    /*
                        $original_servizio_lingua =  array:6 [▼
                            "id" => 23537
                            "master_id" => 127
                            "lang_id" => "it"
                            "nome" => "cucina romagnola asdada"
                            "created_at" => "-0001-11-30 00:00:00"
                            "updated_at" => "-0001-11-30 00:00:00"
                        ]
                     */

                    $key = "servizio{$servizio_lingua->lang_id}{$servizio->id}";

                    if ($request->filled($key)) {
                        $original_servizio_lingua['nome'] = $request->get($key);
                    }

                    unset($original_servizio_lingua['id']);
                    $nuovo_servizio_lingua[] = $original_servizio_lingua;

                    $servizio_lingua->delete();
                }
                ServizioLingua::insert($nuovo_servizio_lingua);
            }
        }

        /*
        SessionResponseMessages::add("success", "Modifiche salvate con successo.");
        SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");*/

        return SessionResponseMessages::redirect("admin/servizi", $request);
    }

    /**
     * Distrugge il servizio e il servizio in lingua
     * 
     * @access public  
     * @param Int $id
     * @param Request $request
     * @return SessionResponseMessages (redirect)
     */

    public function destroy($id, Request $request)
    {

        ServizioLingua::where('master_id', $id)->delete();
        Servizio::destroy($id);
        SessionResponseMessages::add("success", "Il servizio è stato eliminato.");
        SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
        return SessionResponseMessages::redirect("admin/servizi/create", $request);
    }
    /**
     * Associa i servizi da un hotel
     * ATTENZIONE: a regime, qauando avrò i servizi tutti uguali e non vecchi da mantenere e nuovi
     * TUTTO SI RIASSUME IN :
     * $hotel->servizi()->sync($request->get('servizi'));
     * 
     * @access public
     * @param  Request $request
     * @return SessionResponseMessages ( redirect )
     */

    public function associaServiziHotel(Request $request)
    {


        $hotel_id = $this->getHotelId();
        Utility::clearCacheHotel($hotel_id);
        $hotel = Hotel::find($hotel_id);

        $servizi = $request->get('servizi');

        $serviziPrivati = $request->get('serviziPrivati');
        $note = $request->get('note');

        $locale = $this->getLocale();
        $locale_db = "_" . $this->getLocale();

        if ($locale_db == "_it")
            $locale_db = "";

        $old_servizi = DB::table("tblHotelServizi")->where("hotel_id", $hotel_id)->get();

        /** Servizi pubblici */

        if (is_null($servizi)) {

            $hotel->servizi()->detach();
        } else {

            /** Elimino i servizi rimossi */
            foreach ($old_servizi as $old_servizio) {

                if (!in_array($old_servizio->servizio_id, $servizi)) {
                    DB::table("tblHotelServizi")->where("id", $old_servizio->id)->delete();
                }
            }

            //dd($note);

            /** reinserisco i servizi marcati */
            foreach ($servizi as $servizio) {


                if (!isset($note[$servizio])) {
                    $note[$servizio] = '';
                };
                /*HotelServizi::updateOrCreate(
                        ["hotel_id" => $hotel_id, "servizio_id" => $servizio],
                        ["note" . $locale_db => trim($note[$servizio])]
                    );*/

                $serv_assoc = HotelServizi::where("hotel_id", $hotel_id)
                    ->where("servizio_id", $servizio)
                    ->first();

                if (is_null($serv_assoc)) {

                    $serv_assoc = HotelServizi::create(
                        [
                            "hotel_id" => $hotel_id,
                            "servizio_id" => $servizio,
                            "note" . $locale_db => trim($note[$servizio])
                        ]
                    );
                } else {
                    $col = "note" . $locale_db;
                    $serv_assoc->$col = trim($note[$servizio]);
                    $serv_assoc->save();
                }


                if ($locale == 'it') {

                    foreach (Utility::linguePossibili() as $lingua) {

                        if ($lingua != $locale) {

                            $value_tradotto = Utility::translate(trim($note[$servizio]), $lingua);

                            /*HotelServizi::updateOrCreate(
                                    ["hotel_id" => $hotel_id, "servizio_id" => $servizio],
                                    ["note_" . $lingua => $value_tradotto]
                                );*/

                            $col = "note_" . $lingua;
                            $serv_assoc->$col = $value_tradotto;
                        }
                    }

                    $serv_assoc->save();
                }
            }
        }

        /** Servizi privati */
        if (!is_null($serviziPrivati)) {
            $note_private = $request->get('note_private');


            /** reinserisco i servizi marcati */
            foreach ($serviziPrivati as $servizio) {

                if (!isset($note_private[$servizio])) {
                    $note_private[$servizio] = '';
                };

                if (trim($note_private[$servizio]) == "") {
                    ServizioPrivatoLingua::where("master_id", $servizio)->where("lang_id", $locale)->delete();
                } else {

                    ServizioPrivatoLingua::updateOrCreate(
                        ["lang_id" => $locale, "master_id" => $servizio],
                        ["nome" => trim($note_private[$servizio])]
                    );

                    /** FACCIO TRADURRE DA GOOGLE API TRANSLATE LE NOTE !! */
                    if ($locale == 'it') {

                        foreach (Utility::linguePossibili() as $lingua) {

                            if ($lingua != $locale) {

                                $spl = ServizioPrivatoLingua::where("lang_id", $lingua)->where("master_id", $servizio)->first();

                                if (!$spl) {

                                    $value_tradotto = Utility::translate(trim($note_private[$servizio]), $lingua);
                                    ServizioPrivatoLingua::create(['master_id' => $servizio, 'lang_id' => $lingua, 'nome' => $value_tradotto]);
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($locale == "it") {
            $redirect = "admin/servizi/associa-servizi";
        } else if ($locale == "en") {
            $redirect = "admin/servizi/associa-servizi/ing";
        } else if ($locale == "fr") {
            $redirect = "admin/servizi/associa-servizi/fr";
        } else if ($locale == "de") {
            $redirect = "admin/servizi/associa-servizi/ted";
        }

        SessionResponseMessages::add("success", "Modifiche salvate con successo.");
        SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
        return SessionResponseMessages::redirect($redirect, $request);
    }

    /**
     * Dissocia i servizi da un hotel
     *
     * @access public
     * @param  Request $request
     * @return SessionResponseMessages ( redirect )
     */

    public function dissociaServiziHotel(Request $request)
    {

        // Servizi
        $hotel_id = $this->getHotelId();
        Utility::clearCacheHotel($hotel_id);

        $hotel = Hotel::find($hotel_id);
        $hotel->servizi()->detach();
        foreach ($hotel->serviziPrivati as $sp) :
            $servizio_privato = ServizioPrivato::find($sp->id);
            $servizio_privato->servizi_privati_lingua()->delete();
            $servizio_privato->delete();
            $servizio_privato->translate("en")->delete();
            $servizio_privato->translate("de")->delete();
            $servizio_privato->translate("fr")->delete();
        endforeach;

        SessionResponseMessages::add("success", "Modifiche salvate con successo.");
        SessionResponseMessages::add("success", "La cache è stata aggiornata con successo");
        return SessionResponseMessages::redirect("admin/servizi/associa-servizi", $request);
    }

    /**
     * Associa un nota servizio via AJAX
     *
     * @access public
     * @param  Request $request
     * @return String
     */

    public function AssociaNotaServizioAjax(Request $request)
    {

        $locale = $this->getLocale();

        // tipo <servizio_id|hotel_id>
        $id = $request->get('id');
        Utility::clearCacheHotel($id);

        list($servizio_id, $hotel_id) = explode('|', $id);

        $hotel = Hotel::find($hotel_id)->servizi()->where('servizio_id', $servizio_id)->first();

        // valore inserito nella cella
        $value = $request->get('value');

        if (trim($value) != '') {

            // il servizio NON è associato all'hotel
            // LO ASSOCIO IO
            if (is_null($hotel)) {
                $hotel = Hotel::find($hotel_id);
                $hotel->servizi()->attach($servizio_id);
                $hotel = Hotel::find($hotel_id)->servizi()->where('servizio_id', $servizio_id)->first();
            }

            if ($locale == 'it') {

                $fill_array = ['note' => $value];
                ////////////////////////////////////////////////////////
                // FACCIO TRADURRE DA GOOGLE API TRANSLATE LE NOTE !! //
                ////////////////////////////////////////////////////////
                foreach (Utility::linguePossibili() as $lingua) {
                    if ($lingua != $locale) {
                        $fill_array['note_' . $lingua] = Utility::translate($value, $lingua);
                    }
                }
            } else {
                $fill_array = ['note_' . $locale => $value];
            }

            $hotel->pivot->fill($fill_array)->save();

            if ($value == '' || $value == ' ') {
                $to_return = ' ';
            } else {
                $to_return = $value;
            }
        } else {

            if ($locale == 'it') {
                $fill_array = ['note' => $value];
            } else {
                $fill_array = ['note_' . $locale => $value];
            }

            try {
                $hotel->pivot->fill($fill_array)->save();
            } catch (Exception $e) {
                // almeno non invia la mail
            }

            $to_return = ' ';
        }

        echo $to_return;
    }

    /**
     * Associa un nota servizio Listing via AJAX
     *
     * @access public
     * @param  Request $request
     * @return String
     */

    public function AssociaNotaServizioListingAjax(Request $request)
    {

        $locale = $this->getLocale();

        // tipo <servizio_id|hotel_id>
        $id = $request->get('id');

        list($servizio_id, $hotel_id) = explode('|', $id);

        $hotel = Hotel::find($hotel_id)->servizi()->where('servizio_id', $servizio_id)->first();

        // valore inserito nella cella
        $value = $request->get('value');

        if (is_numeric($value)) {

            // il servizio NON è associato all'hotel
            // LO ASSOCIO IO
            if (is_null($hotel)) {
                $hotel = Hotel::find($hotel_id);
                $hotel->servizi()->attach($servizio_id);
                $hotel = Hotel::find($hotel_id)->servizi()->where('servizio_id', $servizio_id)->first();
            }

            $fill_array = ['note' => $value];
            foreach (Utility::linguePossibili() as $lingua) {
                if ($lingua != $locale) {
                    $fill_array['note_' . $lingua] = Utility::translate($value, $lingua);
                }
            }

            $hotel->pivot->fill($fill_array)->save();

            $to_return = $value;

            echo $to_return;
        } else {
            echo "ERR!";
        }
    }

    /**
     * Crea un servizio privato via AJAX
     *
     * @access public
     * @param  Request $request
     * @return Int
     */

    public function CreaServizioPrivatoAjax(Request $request)
    {

        $locale = $this->getLocale();
        $id = $request->get('id');

        list($categoria_id, $hotel_id) = explode('|', $id);
        $sp = ServizioPrivato::create(['categoria_id' => $categoria_id, 'hotel_id' => (int) $hotel_id, 'nome' => time()]);
        $spl = ServizioPrivatoLingua::create(['master_id' => $sp->id, 'lang_id' => $locale, 'nome' => ""]);

        return $sp->id;
    }

    /**
     * Aggiorna un servizio privato via AJAX
     *
     * @access public
     * @param  Request $request
     * @return Int
     */

    public function AggiornaServizioPrivatoAjax(Request $request)
    {

        $locale = $this->getLocale();

        // id della tabella tblServiziPrivati
        $id_servizio_privato = $request->get('id');

        // valore inserito nella cella (valore in $locale di quel servizio provato)
        $value = $request->get('value');

        if (trim($value) != '') {
            $sp = ServizioPrivato::find($id_servizio_privato);
            $spl = $sp->servizi_privati_lingua()->save(ServizioPrivatoLingua::create(['lang_id' => $locale, 'nome' => $value]));
        }

        if ($value == '' || $value == ' ') {
            $to_return = ' ';
        } else {
            $to_return = $value;
        }

        echo $to_return;
    }

    /**
     * Cancella un servizio privato via AJAX
     *
     * @access public
     * @param  Request $request
     * @return ServizioPrivato
     */

    public function DelServizioPrivatoAjax(Request $request)
    {
        $locale = $this->getLocale();
        $id_servizio = $request->get('id_servizio');
        $servizio_privato = ServizioPrivato::find($id_servizio);
        $servizio_privato->translate($locale)->delete();
    }

    /**
     * Cancella tutti i servizi privati via AJAX
     *
     * @access public
     * @param  Request $request
     * @return ServizioPrivato
     */

    public function DelServizioPrivatoAllAjax(Request $request)
    {

        $locale = $this->getLocale();
        $id_servizio = $request->get('id_servizio');

        $servizio_privato_lang = ServizioPrivatoLingua::where("master_id", $id_servizio)->delete();
        ServizioPrivato::find($id_servizio)->delete();
    }

    /**
     * Ordina i servizi privati via AJAX
     *
     * @access public
     * @param  Request $request
     * @return ServizioPrivato
     *
     */

    public function OrderServizioCategoriaAjax(Request $request)
    {

        $servizi_to_order = $request->get('recordsArray');

        foreach ($servizi_to_order as $order => $value) {
            list($id_servizio, $id_categoria) = explode('|', $value);

            Servizio::where('id', $id_servizio)
                ->where('categoria_id', $id_categoria)
                ->update(['admin_position' => $order]);
        }

        echo "ok";
    }
}

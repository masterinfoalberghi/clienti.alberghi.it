<?php

/**
 * PagesController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers\Admin;

use App\Categoria;
use App\CmsPagina;
use App\Http\Controllers\Controller;
use App\Localita;
use App\Macrolocalita;
use App\ParolaChiave;
use App\SpotPagine;
use App\Utility;
use Carbon\Carbon;
use Config;
use DB;
use Illuminate\Http\Request;
use Langs;
use File;
use SessionResponseMessages;

class PagesController extends AdminBaseController
{

    const SPOT_PATH = "spothome";
    const EVIDENZA_PATH = "pagine";

    private static $alternate_array = [

        "it" => "",
        "en" => "",
        "fr" => "",
        "de" => "",

    ];

    /* ------------------------------------------------------------------------------------
     * METODI PUBBLICI ( VIEWS )
     * ------------------------------------------------------------------------------------ */

    /**
     * Mostra la lista delle pagine
     *
     * @access public
     * @param Request $request
     * @return View
     */

    public function index(Request $request)
    {

        $ord = Utility::filterOrderFieldPages($request->get('ord'));
        $dir = Utility::filterOrderDirection($request->get('dir'));

        $old = $request->all();

        foreach (['id', 'attiva', 'lang_id', 'template', 'uri', 'attiva_nel_menu', 'updated_at'] as $key) {
            if (!isset($old[$key])) {
                $old[$key] = null;
            }
        }

        $appends = [];
        $cms_pagine = CmsPagina::where(function ($query) use ($request) {

            $query->where('template', '=', 'statica');

            if ($id = $request->get('id')) {
                $query->where('id', $id);
            }

            if ($attiva = $request->filled('attiva')) {
                $query->where('attiva', $attiva);
            }

            if ($lang_id = $request->get('lang_id')) {
                $query->where('lang_id', $lang_id);
            }

            if ($uri = $request->get('uri')) {
                $query
                ->where('uri', 'LIKE', "%$uri%")
                ->orWhere('seo_title', 'LIKE', "%$uri%")
                ->orWhere('seo_description', 'LIKE', "%$uri%");
            }

            if ($updated_at = $request->get('updated_at')) {
                $date = Carbon::createFromFormat('d/m/Y', $updated_at)->toDateString();
                $query->where('updated_at', '>=', "$date 00:00:00")->where('updated_at', '<=', "$date 23:59:59");
            }

        })
        ->orderBy($ord, $dir)
        ->paginate(100);

        /**
         * Filtri pagine
         */

        if ($id = $request->get('id')) {
            $appends["id"] = $id;
        }

        if ($attiva = $request->filled('attiva')) {
            $appends["attiva"] = $attiva;
        }

        if ($lang_id = $request->get('lang_id')) {
            $appends["lang_id"] = $lang_id;
        }

        if ($template = $request->get('template')) {
            $appends["template"] = $template;
        }

        if ($uri = $request->get('uri')) {
            $appends["uri"] = $uri;
        }

        if ($updated_at = $request->get('updated_at')) {
            $appends["updated_at"] = $updated_at;
        }

        $appends["type_page"] = "pages";

        $data = ["records" => $cms_pagine];
        return view('admin.pages_index', compact("data", "old", "appends"));

    }

    /**
     * Mostra la lista delle pagine stradario
     *
     * @access public
     * @param Request $request
     * @return View
     */

    /*
    public function index_stradario(Request $request)
    {

        $ord = Utility::filterOrderFieldPages($request->get('ord'));
        $dir = Utility::filterOrderDirection($request->get('dir'));

        $old = $request->all();

        foreach (['id', 'attiva', 'lang_id', 'template', 'uri', 'updated_at'] as $key) {
            if (!isset($old[$key])) {
                $old[$key] = null;
            }
        }

        $appends = [];
        $cms_pagine = CmsPagina::where(function ($query) use ($request) {

            $query->where('indirizzo_stradario', '!=', '');

            if ($id = $request->get('id')) {
                $query->where('id', $id);
            }

            if ($request->filled('attiva')) {
                $query->where('attiva', $request->get('attiva'));
            }

            if ($id = $request->get('lang_id')) {
                $query->where('lang_id', $id);
            }

            if ($template = $request->get('template')) {
                $query->where('template', 'LIKE', "%$template%");
            }

            if ($uri = $request->get('uri')) {
                $query->where('uri', 'LIKE', "%$uri%");
            }

            if ($updated_at = $request->get('updated_at')) {
                $date = Carbon::createFromFormat('d/m/Y', $updated_at)->toDateString();
                $query->where('updated_at', '>=', "$date 00:00:00")->where('updated_at', '<=', "$date 23:59:59");
            }
        })
            ->orderBy($ord, $dir)
            ->paginate(100);

        /**
         * Filtri pagine
         * /

        if ($id = $request->get('id')) {
            $appends["id"] = $id;
        }

        if ($attiva = $request->filled('attiva')) {
            $appends["attiva"] = $attiva;
        }

        if ($lang_id = $request->get('lang_id')) {
            $appends["lang_id"] = $lang_id;
        }

        if ($template = $request->get('template')) {
            $appends["template"] = $template;
        }

        if ($uri = $request->get('uri')) {
            $appends["uri"] = $uri;
        }

        if ($updated_at = $request->get('updated_at')) {
            $appends["updated_at"] = $updated_at;
        }

        $appends["type_page"] = "listing";
        
        $data = ["records" => $cms_pagine];
        return view('admin.listing_index', compact("data", "old", "appends"));

    }
    */

    /**
     * Modifica di una pagina 
     *
     * @access public
     * @param mixed $id
     * @return View
     */

    public function edit($id)
    {

        $record = CmsPagina::findOrFail($id);
        $spot_pagina = SpotPagine::where('id_pagina', $id)->first();
        $tipi_evidenze_cms = ["" => "Nessuna categoria"] + DB::table('tipo_evidenza_crm')->orderBy('nome')->pluck('nome', 'nome')->toArray();

        /**
         * Se non ho uno spot allora creo lo spot vuoto
         */

        if (empty($spot_pagina)) {

            $spot_pagina = new SpotPagine;
            $spot_pagina->id_pagina = $id;
            $spot_pagina->save();

        }

        $lingue = [];
        foreach (Langs::getAll() as $lang) {
            $lingue[$lang] = $lang;
        }

        /**
         * Trovo i link alternate
         */

        $alterante_id = $record->alternate_uri;

        if ($alterante_id != "") {

            $alterante_uri = CmsPagina::where("alternate_uri", $alterante_id)->get();
            foreach ($alterante_uri as $a_uri) {
                Self::$alternate_array[$a_uri["lang_id"]] = $a_uri["uri"];
            }

        }

        /**
         * Devo capire se la pagina è di tipo vecchio o nuovo
         * Se è di tipo vecchio h2, descrizione_2 sono scritti in chiaro
         * e li devo trasformare al volo in formato JSON
         * Altrimenti vengono scritti già come JSON
         */

        if (!Utility::is_JSON($record->descrizione_2) && $record->descrizione_2 != "") {

            $arrayContenuti = array();
            $arrayContenutiSecondari = new \StdClass;
            $arrayContenutiSecondari->tipocontenuto = "text";
            $arrayContenutiSecondari->layout = "normal";
            $arrayContenutiSecondari->immagine = "";
            $arrayContenutiSecondari->h2 = $record->h2;
            $arrayContenutiSecondari->h3 = "";
            $arrayContenutiSecondari->descrizione_2 = $record->descrizione_2;
     
            $arrayContenuti[] = $arrayContenutiSecondari;
            $record->descrizione_2 = json_encode($arrayContenuti);

        }

        $data = [

            "record" => $record,
            "macrolocalita" => array('' => '') + Macrolocalita::pluck("nome", "id")->toArray(),
            "localita_chained" => Utility::getLocalitaChainedForView(),
            "langs" => $lingue,
            "parole_chiave" => ParolaChiave::orderBy("chiave")->pluck("chiave", "id")->toArray(),
            "spot_attivo" => $spot_pagina->spot_attivo,
            "spot_attivo_footer" => $spot_pagina->spot_attivo_footer,
            "spot_h1" => $spot_pagina->spot_h1,
            "spot_ordine" => $spot_pagina->spot_ordine,
            "spot_ordine_footer" => $spot_pagina->spot_ordine_footer,
            "spot_h2" => $spot_pagina->spot_h2,
            "spot_colore" => $spot_pagina->spot_colore,
            "spot_video" => $spot_pagina->spot_video,
            "spot_descrizione" => $spot_pagina->spot_descrizione,
            "spot_immagine" => $spot_pagina->spot_immagine,
            "spot_visibile_ricorsivo" => $spot_pagina->spot_visibile_ricorsivo,
            "spot_visibile_dal" => Carbon::parse($spot_pagina->spot_visibile_dal),
            "spot_visibile_al" => Carbon::parse($spot_pagina->spot_visibile_al),
            "alternate_uri" => Self::$alternate_array,
        ];

        return view('admin.pages_edit', compact("data"));

    }

    /**
     * Editor massivo delle pagine.
     *
     * @access public
     * @param Request $request
     * @return View
     */

    public function massiveEdit(Request $request)
    {

        $ids = $request->get("modify_all_ids");
        $querystring_ricerca = $request->get("querystring_ricerca");
        return view('admin.pages_edit_massive', compact("ids", "querystring_ricerca"));

    }

    /**
     * Pagina creazione nuovi contenuti
     *
     * @access public
     * @return View
     */

    public function create()
    {
        $record = new CmsPagina;

        $lingue = [];
        foreach (Langs::getAll() as $lang) {
            $lingue[$lang] = $lang;
        }

        $data = [

            "record" => $record,
            "macrolocalita" => array('' => '') + Macrolocalita::pluck("nome", "id")->toArray(),
            "localita_chained" => Utility::getLocalitaChainedForView(),
            "langs" => $lingue,
            "parole_chiave" => ParolaChiave::pluck("chiave", "id")->toArray(),
            "spot_attivo" => 0,
            "spot_attivo_footer" => 0,
            "spot_h1" => "",
            "spot_ordine" => "",
            "spot_ordine_footer" => "",
            "spot_h2" => "",
            "spot_colore" => "",
            "spot_video" => "",
            "spot_descrizione" => "",
            "spot_immagine" => "",
            "spot_visibile_ricorsivo" => 0,
            "spot_visibile_dal" => "",
            "spot_visibile_al" => "",
            "alternate_uri" => Self::$alternate_array,
            
        ];

        return view('admin.pages_edit', compact("data"));

    }

    /* ------------------------------------------------------------------------------------
     * METODI PUBBLICI ( CONTROLLERS )
     * ------------------------------------------------------------------------------------ */

    /**
     * Salvo i dati della nuova pagina
     *
     * @access public
     * @param  Request  $request
     * @return SessionResponseMessages
     */

    public function store(Request $request)
    {


        $id = $request->input("id");
        $it = $request->input("alternate_it");
        $en = $request->input("alternate_en");
        $fr = $request->input("alternate_fr");
        $de = $request->input("alternate_de");

        if (!$id) {
            $cms_pagina = new CmsPagina;
            $spot_pagina = new SpotPagine;
        } else {
            $cms_pagina = CmsPagina::findOrFail($id);
            $spot_pagina = SpotPagine::where("id_pagina", $id)->firstOrFail();
        }

        if (!$request->get('spot_attivo')) {
            $this->validate($request, [
                'uri' => 'required',
                'ancora' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'uri' => 'required',
                'ancora' => 'required',
                'spot_h1' => 'required',
            ]);
        }

        /**
         * Questi sono campi che si chiamano esattamente come arrivano in post
         */

        $fields = [
            'immagine', 
            'menu_evidenza', 
            'attiva', 
            'lang_id', 
            'template', 
            'menu_macrolocalita_id', 
            'menu_localita_id', 
            'uri', 
            'ancora', 
            'seo_title', 
            'seo_description', 
            'h1', 
            'descrizione_1', 
            'listing_attivo', 
            'listing_macrolocalita_id', 
            'listing_localita_id', 
            'listing_parolaChiave_id', 
            'listing_puntoForzaChiave_id', 
            'listing_offerta', 
            'listing_gruppo_servizi_id', 
            'listing_bonus_vacanze_2020', 
            'listing_whatsapp', 
            'listing_green_booking', 
            'listing_eco_sostenibile', 
            'listing_coupon', 
            'listing_bambini_gratis',
            'listing_annuali', 
            'vetrina_id', 
            'banner_vetrina_id', 
            'evidenza_vetrina_id', 
            'vetrine_top_enabled', 
            'menu_riviera_romagnola', 
            'menu_auto_annuale', 
            'tipo_evidenza_crm'
        ];

        foreach ($fields as $field) {
            $cms_pagina->$field = $request->input($field, '');
        }

        /**
         * Cerco le pagine alternative
         */

        $cms_pagina->alternate_uri = $id;

        if ($it != "") {
            CmsPagina::where("uri", $it)->update(["alternate_uri" => $id]);
        }

        if ($en != "") {
            CmsPagina::where("uri", $en)->update(["alternate_uri" => $id]);
        }

        if ($fr != "") {
            CmsPagina::where("uri", $fr)->update(["alternate_uri" => $id]);
        }

        if ($de != "") {
            CmsPagina::where("uri", $de)->update(["alternate_uri" => $id]);
        }

        $cms_pagina->menu_dal = Utility::getCarbonDate($request->get('menu_dal'));
        $cms_pagina->menu_al = Utility::getCarbonDate($request->get('menu_al'));
        $cms_pagina->faq = $request->get("faq");

        $tipocontenuti = $request->get("tipocontenuti");
        $layout = $request->get("layout");
        $immagine = $request->get("immagine_secondaria");
        $h2 = $request->get("h2");
        $h3 = $request->get("h3");
        $descrizione_2 = $request->get("descrizione_2");

        $immagine_gallery = $request->get("immagine_gallery");
        $testo_gallery = $request->get("testo_gallery");

        $sorgente_mappa = $request->get("map_source");
        $lat_lon = $request->get("map_lat_lon");

        $t = 0;
        $g = 0;
        $m = 0;
        $arrayContenutiSecondari = array();

        if ($tipocontenuti):
            foreach ($tipocontenuti as $tc):

                switch ($tc) {

                    case "text":

                        $arrayContenutiSecondari[] = array(

                            "tipocontenuto" => $tc,
                            "layout" => $layout[$t],
                            "immagine" => $immagine[$t],
                            "h2" => $h2[$t],
                            "h3" => $h3[$t],
                            "descrizione_2" => $descrizione_2[$t],
                        );
                        $t++;
                        break;

                    case "gallery":

                        $arrayContenutiSecondari[] = array(

                            "tipocontenuto" => $tc,
                            "galleria" => ["immagini" => $immagine_gallery[$g], "testo" => $testo_gallery[$g]],

                        );
                        $g++;
                        break;

                    case "map":

                        $arrayContenutiSecondari[] = array(

                            "tipocontenuto" => $tc,
                            "map_source" => $sorgente_mappa[$m],
                            "map_lat_lon" => $lat_lon[$m],

                        );
                        $m++;
                        break;

                }

            endforeach;
            $cms_pagina->descrizione_2 = json_encode($arrayContenutiSecondari);

        else:

            $cms_pagina->descrizione_2 = "";

        endif;

        $cms_pagina->save();

        /**
         * Salvataggio dello spot
         */

        $fields = ['spot_attivo', 'spot_attivo_footer', 'spot_h1', 'spot_ordine', 'spot_ordine_footer', 'spot_h2', 'spot_colore', 'spot_video', 'spot_descrizione', 'spot_immagine', 'spot_visibile_ricorsivo'];

        foreach ($fields as $field) {
            $spot_pagina->$field = $request->input($field, '');
        }

        $spot_pagina->spot_visibile_dal = Utility::getCarbonDate($request->get('spot_visibile_dal'));
        $spot_pagina->spot_visibile_al = Utility::getCarbonDate($request->get('spot_visibile_al'));
        $spot_pagina->id_pagina = $cms_pagina->id;

        $spot_pagina->save();

        SessionResponseMessages::add("success", "Modifiche salvate con successo.");
        SessionResponseMessages::add("success", "Le modifiche saranno disponibili nelle prossime 2 ore.");

        return SessionResponseMessages::redirect("/admin/pages/{$cms_pagina->id}/edit", $request);

    }

    /**
     * Salvo i dati del'editor massivo
     *
     * @access public
     * @param Request $request
     * @return SessionResponseMessages
     */

    public function massiveEditStore(Request $request)
    {

        // Predo titolo SEO e descrizione SEO
        $titolo_seo = $request->get("titolo_seo_massivo");
        $descrizione_seo = $request->get("descrizione_seo_massivo");

        $request->get("querystring_ricerca") ? $querystring = "?" . $request->get("querystring_ricerca") : $querystring = "";

        $ajax = $request->get("ajax");
        $ids_array = $request->get("ids_to_change");

        $ids = explode(",", $ids_array);

        /**
         * Prendo e pagine e ciclo cambiando titolo e descrizione SEO
         */

        $cms_pagina = CmsPagina::findOrFail($ids);

        foreach ($cms_pagina as $pagina):

            if ($titolo_seo != "") {
                $pagina->seo_title = $titolo_seo;
            }

            if ($descrizione_seo != "") {
                $pagina->seo_description = $descrizione_seo;
            }

            if ($descrizione_seo != "" || $titolo_seo != "") {
                $pagina->save();
            }

        endforeach;

        if (!$ajax) {

            SessionResponseMessages::add("success", "Modifiche salvate con successo.");
            SessionResponseMessages::add("success", "Le modifiche saranno disponibili nelle prossime 2 ore.");
            return SessionResponseMessages::redirect("/admin/pages" . $querystring, $request);

        }

    }

    /**
     * Clona un pagina
     *
     * @access public
     * @param int $id
     * @param Request $request
     * @return SessionResponseMessages
     */

    public function clona($id, Request $request)
    {

        $cms_pagina = CmsPagina::findOrFail($id);
        $spot_pagina = SpotPagine::where("id_pagina", $id)->first();

        /**
         * Clono la pagina
         */

        $replicant = $cms_pagina->replicate();
        $replicant->uri = uniqid();
        $replicant->save();

        /**
         * Clono lo spot
         */

        $replicant2 = $spot_pagina->replicate();
        $replicant2->id_pagina = $replicant->id;
        $replicant2->save();

        SessionResponseMessages::add("success", "Clonazione avvenuta, ora sei nella pagina clone, ricorda di settare la URI giusta!.");
        SessionResponseMessages::add("success", "La cache è stata aggiornata con successo.");

        return SessionResponseMessages::redirect("/admin/pages/{$replicant->id}/edit", $request);

    }

    /**
     * Cancella una pagina
     *
     * @access public
     * @param  Request  $request
     * @return SessionResponseMessages
     */

    public function destroy(Request $request)
    {

        $id = $request->input("id");
        $cms_pagina = CmsPagina::findOrFail($id);
        $spot_pagina = SpotPagine::where("id_pagina", $id)->first();

        CmsPagina::destroy($id);

        if ($spot_pagina) {
            SpotPagine::destroy($spot_pagina->id);
        }

        SessionResponseMessages::add("success", "Il record ID=$id è stato eliminato.");
        SessionResponseMessages::add("success", "Le modifiche saranno disponibili nelle prossime 2 ore.");

        return SessionResponseMessages::redirect("/admin/pages", $request);

    }

/**
     * Carico le immagini dello spot sul server
     *
     * @param Request $request
     * @return Response
     */

    public function uploadImage(Request $request)
    {

        $immagine = Utility::getResizedImages($request);

        if (is_array($immagine)) {

            $error_msg = $immagine['msg'];
            return response()->json($error_msg, 400);

        } else {

            return response()->json([$immagine, ( config("app.cdn_online") . "/images/romagna/" . self::SPOT_PATH . "/600x290") . '/' . $immagine], 200);
        }

    }

    public function uploadImageEvidenza(Request $request)
    {

        $immagine = Utility::getResizedImages($request, Self::EVIDENZA_PATH);

        if (is_array($immagine)) {

            $error_msg = $immagine['msg'];
            return response()->json($error_msg, 400);

        } else {

            return response()->json([$immagine, ( config("app.cdn_online") . "/images/romagna/" . self::EVIDENZA_PATH . "/600x290") . '/' . $immagine], 200);
        }

    }

    public function uploadImagePagine(Request $request)
    {   

        $immagine = Utility::getResizedImages($request, Self::EVIDENZA_PATH);

        if (is_array($immagine)) {

            $error_msg = $immagine['msg'];
            return response()->json($error_msg, 400);

        } else {

            return response()->json([$immagine, (config("app.cdn_online") .  "/images/romagna/" . self::EVIDENZA_PATH . "/300x200") . '/' . $immagine], 200);
        }

    }

}

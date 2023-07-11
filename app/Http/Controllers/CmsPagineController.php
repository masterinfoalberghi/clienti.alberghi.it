<?php

/**
 * CmsPagineController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers;

use App;
use App\CmsPagina;
use App\Localita;
use App\Macrolocalita;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CmsPagineController extends Controller
{

    private function _briciole($cms_pagina)
    {
        $briciole = array();
        $key = "briciole_" . $cms_pagina->id;
        $base_url = "/";

        if ($cms_pagina->lang_id != "it") 
            $base_url = "/" . Utility::getUrlLocaleFromAppLocale($cms_pagina->lang_id);

        if (!$briciole = Utility::activeCache($key, "Cache Briciole Listing")) {

            // Radice sempre Home
            $briciole['Home'] = url($base_url);

            if($cms_pagina->listing_macrolocalita_id != Utility::getIdMacroPesaro()) { 
                if ($cms_pagina->lang_id == 'it') 
                    if ($cms_pagina->uri != 'italia/hotel_riviera_romagnola.html') 
                        $briciole['Hotel Riviera Romagnola'] = Utility::getUrlWithLang($cms_pagina->lang_id, '/italia/hotel_riviera_romagnola.html');
                    
                else
                    if ($cms_pagina->uri != Utility::getUrlLocaleFromAppLocale($cms_pagina->lang_id) . '/italia/hotel_riviera_romagnola.html')
                        $briciole['Hotel Riviera Romagnola'] = Utility::getUrlWithLang($cms_pagina->lang_id, '/italia/hotel_riviera_romagnola.html');
            }
            
            if (count($briciole) == 1 && isset($briciole['Home']) && $cms_pagina->listing_macrolocalita_id != Utility::getIdMacroPesaro())
                $briciole = [];
            
            Utility::putCache($key, $briciole);

        } // if activeCache

        return $briciole;
    }

    /* ------------------------------------------------------------------------------------
     * METODI PUBBLICI (VIEWS)
     * ------------------------------------------------------------------------------------ */

    /**
     *
     * Pagina vecchi browser non più supportati
     *
     */
    
    public function oldBrowser($locale = "it")
    {
        return View::make('templates.page_oldbrowser', compact('locale'));
    }


    /**
     * Controller principale del sito
     * IN base al template restituisce le pagine con tutti gli oggetti
     *
     * @access public
     * @param STRING $uri
     * @param Request $request
     * @return VIEW
     */

    public function index($uri)
    {   

        $cms_pagina = CmsPagina::where('uri', $uri)->attiva()->first();

        if (!$cms_pagina)
            abort("404");

        $for_canonical = Utility::getPublicUri($uri);
        $locale = $cms_pagina->lang_id;
        App::setlocale($locale);

        $menu_localita = Utility::getMenuLocalita($locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);
        $key = "href_lang_" . $locale . "_" . $cms_pagina->id;

        if (!$other_pages = Utility::activeCache($key, "Href Lang (MID)")) {

            if ($cms_pagina->alternate_uri != "") 
                $other_pages = CmsPagina::where("alternate_uri", $cms_pagina->alternate_uri)->get();

            Cache::put($key, $other_pages, Carbon::now()->addDays(15));

        }

        $language_uri = [];
        if (isset($other_pages) && count($other_pages)) {
            foreach ($other_pages as $op) {
                $language_uri[$op->lang_id] = $op->uri;
            }
        }

        if ($cms_pagina->descrizione_2 != "" && !Utility::is_JSON($cms_pagina->descrizione_2)) {

            /**
             * Non è un json
             */

            $arrayContenutiSecondari = new \StdClass;
            $arrayContenutiSecondari->tipocontenuto = "text";
            $arrayContenutiSecondari->layout = "1col";
            $arrayContenutiSecondari->immagine = "";
            $arrayContenutiSecondari->h2 = $cms_pagina->h2;
            $arrayContenutiSecondari->h3 = "";
            $arrayContenutiSecondari->descrizione_2 = $cms_pagina->descrizione_2;

            $arrayContenuti[] = $arrayContenutiSecondari;
            $cms_pagina->descrizione_2 = json_encode($arrayContenuti);

        }

        $briciole = $this->_briciole($cms_pagina);

        $macro_localita_seo = "Riviera Romagnola";
        $localita_seo = "Riviera Romagnola";
        $selezione_localita = $localita_seo;

        $titolo = $cms_pagina->h1;
        $testo = $cms_pagina->descrizione_1;
        $seo_title = $cms_pagina->seo_title;
        $seo_description = $cms_pagina->seo_description;

        if (!$cms_pagina->menu_riviera_romagnola) 
            $menu_tematico = Utility::getMenuTematico($locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);
        else
            $menu_tematico = Utility::getMenuTematico($locale, Utility::getMacroRR(), Utility::getMicroRR());
        

        return response()
            ->view(
                "cms_pagina_statica.statica",
                compact(
                    'locale',
                    'language_uri',
                    'cms_pagina',
                    'for_canonical',
                    'titolo',
                    'testo',
                    'selezione_localita',
                    'menu_tematico',
                    'seo_title',
                    'seo_description',
                    'menu_localita'
                )
            );

    }

     /**
         * Chiamata ajax per l'iscirzione alla newsletter tramite le API di MailUp
         * @param  Request $request
         * @return [type]
         */

        public function iscrizione_newsletter(Request $request)
        {

            $Email = $request->get('Email');
            $response = Utility::mailUpSubscribe($Email);
            header('Content-Type: application/json');
            die('{"Message":' . json_encode($response) . "}");

        }

}

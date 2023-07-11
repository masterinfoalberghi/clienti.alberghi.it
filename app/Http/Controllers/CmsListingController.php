<?php

/**
 * CmsPagineController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers;

use App;
use Session;
use App\Hotel;
use App\Offerta;
use App\Utility;
use App\Vetrina;
use App\Localita;
use App\Servizio;
use App\Categoria;
use App\CmsPagina;
use Carbon\Carbon;
use App\SlotVetrina;
use App\ParolaChiave;
use App\GruppoServizi;
use App\Macrolocalita;
use App\KeywordRicerca;
use App\MappaRicercaPoi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\VetrinaOffertaTopLingua;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CercaRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\VetrinaController;
use Illuminate\Pagination\LengthAwarePaginator;
use BeyondCode\ServerTiming\Facades\ServerTiming;
use App\Http\Controllers\VetrinaServiziTopLinguaController;

class CmsListingController extends Controller
{

    const HOTEL_PER_PAGE = 50;

    /* ------------------------------------------------------------------------------------
     * METODI PRIVATI
     * ------------------------------------------------------------------------------------ */

        /**
         * Prendo le briciole per il percorso
         *
         * @access private
         * @static
         * @param CmsPagina $cms_pagin
         * @return Array
         */

        private function _briciole_filtri($cms_pagina)
        {
            $briciole = array();

            $key = "briciole_" . $cms_pagina->id;

            $base_url = "/";

            if ($cms_pagina->lang_id != "it") {
                $base_url = "/" . Utility::getUrlLocaleFromAppLocale($cms_pagina->lang_id);
            }

            if (!$briciole = Utility::activeCache($key, "Cache Briciole Listing")) {
                $briciole['Home'] = url($base_url);

                // Per trovare la pagina corrispondente di RR NON posso basarmi sull'URL
                // perché potrebbero cambiare le URL in lingua

                // LISTING
                if ($cms_pagina->template == 'listing') {

                    // Questa parte di query si ripete sempre quindi la ISOLO QUI e la richiamo
                    $pagina_rr_core = CmsPagina::where('template', 'listing')
                        ->where('lang_id', $cms_pagina->lang_id)
                        ->where(function ($query) {
                            $query->where('listing_macrolocalita_id', Utility::getMacroRR())
                                ->orWhere('listing_macrolocalita_id', 0);
                        })
                        ->where(function ($query) {
                            $query->where('listing_localita_id', Utility::getMicroRR())
                                ->orWhere('listing_localita_id', 0);
                        });

                    // CATEGORIE
                    if ($cms_pagina->listing_categorie != '') {

                        // PAGINA RR
                        $pagina_rr =
                        $pagina_rr_core
                            ->where('listing_categorie', $cms_pagina->listing_categorie)
                            ->first();

                        if (!is_null($pagina_rr) && $pagina_rr->id == $cms_pagina->id) 
                            $briciole[$pagina_rr->ancora . ' Riviera Romagnola'] = url($pagina_rr->uri);
                        

                        // PAGINA MACRO
                        // Se la pagina è di 1 micro, allora faccio vedere la macro

                        if ($cms_pagina->listing_localita_id != 0) {

                            $pagina_macro = CmsPagina::with('listingMacroLocalita')->where('template', 'listing')
                                ->where('lang_id', $cms_pagina->lang_id)
                                ->where('listing_macrolocalita_id', $cms_pagina->listing_macrolocalita_id)
                                ->where('listing_localita_id', 0)
                                ->where('listing_categorie', $cms_pagina->listing_categorie)
                                ->first();

                            if (!is_null($pagina_macro) && $pagina_macro->id == $cms_pagina->id)
                                $briciole[$pagina_macro->ancora . ' ' . $pagina_macro->listingMacroLocalita->nome] = url($pagina_macro->uri);
                            

                        }

                    } // end categorie

                    // SERVIZI
                    if ($cms_pagina->listing_gruppo_servizi_id != '') {

                        // PAGINA RR
                        $pagina_rr =
                        $pagina_rr_core
                            ->where('listing_gruppo_servizi_id', $cms_pagina->listing_gruppo_servizi_id)
                            ->first();

                        if (!is_null($pagina_rr) && $pagina_rr->id == $cms_pagina->id) 
                            $briciole[$pagina_rr->ancora . ' Riviera Romagnola'] = url($pagina_rr->uri);

                        // PAGINA MACRO
                        // I SERVIZI sono SOLO per le MACRO, quindi sono già in una pagina MACRO

                    } // end servizi

                    // PRENOTA PRIMA
                    if ($cms_pagina->listing_offerta_prenota_prima != '') {
                        // PAGINA RR
                        $pagina_rr =
                        $pagina_rr_core
                            ->where('listing_offerta_prenota_prima', $cms_pagina->listing_offerta_prenota_prima)
                            ->first();

                        if (!is_null($pagina_rr) || $pagina_rr->id == $cms_pagina->id) 
                            $briciole[$pagina_rr->ancora . ' Riviera Romagnola'] = url($pagina_rr->uri);


                        // PAGINA MACRO
                        // PRENOTA PRIMA sono SOLO per le MACRO, quindi sono già in una pagina MACRO
                    }

                    // OFFERTE
                    if ($cms_pagina->listing_parolaChiave_id != 0) {
                        // PAGINA RR
                        $pagina_rr =
                        $pagina_rr_core
                            ->where('listing_parolaChiave_id', $cms_pagina->listing_parolaChiave_id)
                            ->first();

                        if (!is_null($pagina_rr) && $pagina_rr->id == $cms_pagina->id) 
                            $briciole[$pagina_rr->ancora . ' Riviera Romagnola'] = url($pagina_rr->uri);

                        // PAGINA MACRO
                        // OFFERTE sono SOLO per le MACRO, quindi sono già in una pagina MACRO
                    }

                    // BAMBINI GRATIS
                    if ($cms_pagina->listing_bambini_gratis != 0) {
                        // PAGINA RR
                        $pagina_rr =
                        $pagina_rr_core
                            ->where('listing_bambini_gratis', $cms_pagina->listing_bambini_gratis)
                            ->first();

                        if (!is_null($pagina_rr) && $pagina_rr->id == $cms_pagina->id) 
                            $briciole[$pagina_rr->ancora . ' Riviera Romagnola'] = url($pagina_rr->uri);

                        // PAGINA MACRO
                        // OFFERTE sono SOLO per le MACRO, quindi sono già in una pagina MACRO
                    }

                    // TRATTAMENTI
                    if ($cms_pagina->listing_trattamento != '') {
                        // PAGINA RR
                        $pagina_rr =
                        $pagina_rr_core
                            ->where('listing_trattamento', $cms_pagina->listing_trattamento)
                            ->first();

                        //dd(Str::replaceArray('?', $pagina_rr->getBindings(), $pagina_rr->toSql()));

                        if (!is_null($pagina_rr) && $pagina_rr->id == $cms_pagina->id) 
                            $briciole[$pagina_rr->ancora . ' Riviera Romagnola'] = url($pagina_rr->uri);

                        // PAGINA MACRO
                        // OFFERTE sono SOLO per le MACRO, quindi sono già in una pagina MACRO
                    }

                } //end listing

                if ($cms_pagina->template == 'localita') {
                    $briciole['Hotel Riviera Romagnola'] = Utility::getUrlWithLang($cms_pagina->lang_id, '/italia/hotel_riviera_romagnola.html');

                    if ($cms_pagina->listing_localita_id != 0) {

                        // SE SONO MICROLOCALITA faccio vedere la MACROLOCALITA
                        $pagina_macro = CmsPagina::with('listingMacroLocalita')->where('template', 'localita')
                            ->where('lang_id', $cms_pagina->lang_id)
                            ->where('listing_macrolocalita_id', $cms_pagina->listing_macrolocalita_id)
                            ->where('listing_localita_id', 0)
                            ->first();

                        if (!is_null($pagina_macro) && $pagina_macro->id == $cms_pagina->id) 
                            $briciole[$pagina_macro->ancora] = url($pagina_macro->uri);

                    }

                } //end localita

                if (count($briciole) == 1 && isset($briciole['Home'])) {
                    $briciole = [];
                }

                Utility::putCache($key, $briciole);

            } // if activeCache

            return $briciole;
        }

        private function _briciole($cms_pagina)
        {
            $briciole = array();

            $key = "briciole_" . $cms_pagina->id;

            $base_url = "/";

            if ($cms_pagina->lang_id != "it") {
                $base_url = "/" . Utility::getUrlLocaleFromAppLocale($cms_pagina->lang_id);
            }

            /*$briciole = Utility::activeCache($key, "Cache Briciole Listing");
            dump($briciole);*/
            
            if (!$briciole = Utility::activeCache($key, "Cache Briciole Listing")) {
                // Radice sempre Home
                $briciole['Home'] = url($base_url);

                //dd($cms_pagina->uri,Utility::getUrlLocaleFromAppLocale($cms_pagina->lang_id).'/italia/hotel_riviera_romagnola.html');

                if($cms_pagina->listing_macrolocalita_id != Utility::getIdMacroPesaro()) {
                    if ($cms_pagina->lang_id == 'it') {
                        if ($cms_pagina->uri != 'italia/hotel_riviera_romagnola.html') {
                            // 1 figlio: sempre Hotel RR
                            $briciole['Hotel Riviera Romagnola'] = Utility::getUrlWithLang($cms_pagina->lang_id, '/italia/hotel_riviera_romagnola.html');
                        }
                    } else {
                        if ($cms_pagina->uri != Utility::getUrlLocaleFromAppLocale($cms_pagina->lang_id) . '/italia/hotel_riviera_romagnola.html') {
                            // 1 figlio: sempre Hotel RR
                            $briciole['Hotel Riviera Romagnola'] = Utility::getUrlWithLang($cms_pagina->lang_id, '/italia/hotel_riviera_romagnola.html');
                        }
                    }
                }

                // LISTING
                if ($cms_pagina->template == 'listing') {

                    // 2 figlio: sempre localita macro
                    // TRANNE CHE PER LE PAGINE TRASVERSALI
                    if ($cms_pagina->listing_macrolocalita_id != Utility::getMacroRR() && $cms_pagina->listing_localita_id != Utility::getMicroRR()) {

                        $pagina_macro = CmsPagina::where('template', 'localita')
                            ->where('lang_id', $cms_pagina->lang_id)
                            ->where('menu_localita_id', 0)
                            ->where('menu_macrolocalita_id', $cms_pagina->listing_macrolocalita_id)
                            ->first();

                        if (!is_null($pagina_macro)) {
                            $briciole[$pagina_macro->ancora] = url($pagina_macro->uri);
                        }

                        // CATEGORIE su Micro
                        if ($cms_pagina->listing_categorie != '' && $cms_pagina->listing_localita_id > 0) {
                            // 3 figlio: localita micro
                            $pagina_micro = CmsPagina::where('template', 'localita')
                                ->where('lang_id', $cms_pagina->lang_id)
                                ->where('menu_localita_id', $cms_pagina->listing_localita_id)
                                ->first();

                            if (!is_null($pagina_micro)) {
                                $briciole[$pagina_micro->ancora] = url($pagina_micro->uri);
                            }

                        }

                    } // se NON sono trasversale

                } // end listing

                // se sono una localita micro
                if ($cms_pagina->template == 'localita' && $cms_pagina->menu_localita_id > 0) {
                    // 2 figlio: sempre localita macro
                    $pagina_macro = CmsPagina::where('template', 'localita')
                        ->where('lang_id', $cms_pagina->lang_id)
                        ->where('menu_localita_id', 0)
                        ->where('menu_macrolocalita_id', $cms_pagina->listing_macrolocalita_id)
                        ->first();

                    if (!is_null($pagina_macro)) {
                        $briciole[$pagina_macro->ancora] = url($pagina_macro->uri);
                    }

                } // end localita

                if (count($briciole) == 1 && isset($briciole['Home']) && $cms_pagina->listing_macrolocalita_id != Utility::getIdMacroPesaro()) {
                    $briciole = [];
                }

                Utility::putCache($key, $briciole);

            } // if activeCache

            return $briciole;
        }

        /**
         * Prendo e setto i parametri principali della pagina
         *
         * @access private
         * @static
         * @param String $uri
         * @param Request $request
         * @return Array
         */

        private static function _checkPageExist($uri, $request) {

            $cms_pagina = CmsPagina::with(['menuMacroLocalita'])->where('uri', $uri)->attiva()->first();

            /**
             * Controllo che non sia un ordinamento o un filtro
             */

            if (!$cms_pagina && $request->get("cms_pagina_id"))
                $cms_pagina = CmsPagina::with(['menuMacroLocalita'])->find($request->get("cms_pagina_id"));

            if (!$cms_pagina)
                abort("404");
            
            /**
             * Controllo che ci siano i parametri scritti giusti in caso di ordinamento e filtro
             */

            $order = $request->get('order');
            $filter = $request->get('filter');
            $page = $request->get("page");

            if (isset($order) && !Utility::verifyOrderParams($order))
                abort("404");

            if (isset($filter) && !Utility::verifyFilterParams($filter))
                abort("404");

            $locale = $cms_pagina->lang_id;
            App::setlocale($locale);

            $for_canonical = Utility::getPublicUri($uri);
            $menu_localita = Utility::getMenuLocalita($locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);

            /**
             * Link per le bandiere
             */

            $key = "href_lang_" . $locale . "_" . $cms_pagina->id;

            if (!$other_pages = Utility::activeCache($key, "Href Lang (MID)")) {

                if ($cms_pagina->alternate_uri != "") {
                    $other_pages = CmsPagina::where("alternate_uri", $cms_pagina->alternate_uri)->get();
                }

                Cache::put($key, $other_pages, Carbon::now()->addDays(15));

            }

            $language_uri = [];
            if (isset($other_pages) && count($other_pages)) {
                foreach ($other_pages as $op) {
                    $language_uri[$op->lang_id] = $op->uri;
                }
            }

            return [
                "locale" => $locale,
                "cms_pagina" => $cms_pagina, 
                "for_canonical" => $for_canonical ,
                "other_pages" => $other_pages,
                "language_uri" => $language_uri,
                "order" => $order,
                "filter" => $filter,
                "page" => $page
            ];

        }

        /**
         * Trova i contenuti testuali del listing
         *
         * @access private
         * @static
         * @param CmsPagina $cms_pagina
         * @return Json
         */

        private static function _getContent($cms_pagina) 
        {

            /**
             * Devo capire se la pagina è di tipo vecchio o nuovo
             * Se è di tipo vecchio h2, descrizione_2 sono scritti in chiaro
             * e li devo trasformare al volo in formato JSON
             */

            $arrayContenuti = array();

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
                return json_encode($arrayContenuti);

            }

            return $cms_pagina->descrizione_2;

        }
    
        /**
         * Trova i contenuti SEO del listing
         *
         * @access private
         * @static
         * @param CmsPagina $cms_pagina
         * @param String $order
         * @param String $filter
         * @return Json
         */
        
        private static function _getSeoContent($cms_pagina, $locale, $n_hotel, $offerte_count, $prezzo_min, $prezzo_max, $localita_seo, $macro_localita_seo) 
        {

            $arrayChiavi = array("{HOTEL_COUNT}", "{OFFERTE_COUNT}", "{PREZZO_MIN}", "{PREZZO_MAX}", "{LOCALITA}", "{MACRO_LOCALITA}", "{CURRENT_YEAR}", "{CURRENT-YEAR}");
            $arrayValori = array($n_hotel, $offerte_count, $prezzo_min, $prezzo_max, $localita_seo, $macro_localita_seo, date("Y")+Utility::fakeNewYear(), date("Y")+Utility::fakeNewYear());

            $titolo = Utility::replacePlaceholder($arrayChiavi, $cms_pagina->h1, $arrayValori);
            $testo = Utility::replacePlaceholder($arrayChiavi, $cms_pagina->descrizione_1, $arrayValori);
            $seo_title = Utility::replacePlaceholder($arrayChiavi, $cms_pagina->seo_title, $arrayValori);
            $seo_description = Utility::replacePlaceholder($arrayChiavi, $cms_pagina->seo_description, $arrayValori);

            if (!$cms_pagina->menu_riviera_romagnola) {
                $menu_tematico = Utility::getMenuTematico($locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);
            } else {
                $menu_tematico = Utility::getMenuTematico($locale, Utility::getMacroRR(), Utility::getMicroRR());
            }

            return [
                "menu_tematico" => $menu_tematico,
                "titolo" => $titolo,
                "testo" => $testo,
                "seo_title" => $seo_title,
                "seo_description" => $seo_description
            ];
        }

        /**
         * Prende le vetrine associate ad una pagina
         *
         * @access private
         * @static
         * @param CmsPagina $cms_pagina
         * @param String $order
         * @param String $filter
         * @return Json
         */

        private static function _getVetrine($cms_pagina, $order, $filter) 
        {
            
            $vetrina = null;

            if ($cms_pagina->vetrina_id) {

                /**
                 * Tolte dal listing perchè devono girare più velocemente di 2 minuti
                 */
                if ($order == "0")
                    $order = "";
                    
                $key = "vetrina_" . $cms_pagina->vetrina_id . "_" . $order . "_" . $filter;
                if (!$vetrina = Utility::activeCache($key, "Vetrine localita ordinate " . $cms_pagina->vetrina_id . " " . $order)) {

                    /**
                     * ci sono vetrine doppie (perché erano in 2 pagine distinte nella vecchia vetrina)
                     * che devo separare in questo modo
                     */
                   
                    $vetrina = Vetrina::with([

                        'slots' => function ($query) use ($cms_pagina, $order, $filter) {
                            
                            $query->withClienteLazyEagerLoaded($cms_pagina, $order, $filter);
                            $query->where("attiva", "=", "1");

                            $direction = "asc";

                            if ($order == "categoria_desc") {

                                $query->orderByRaw("FIELD(hotel_categoria_id,'5','4','6','3','2','1')");

                            } else if ($order == "categoria_asc") {

                                $query->orderByRaw("FIELD(hotel_categoria_id,'1','2','3','6','4','5')");

                            } else {

                                if ($order == "") {
                                    $order = "posizione";
                                }

                                if ($order == "prezzo_min") {
                                    $order = "hotel_prezzo_min";
                                }

                                if ($order == "prezzo_max") {
                                    $order = "hotel_prezzo_max";
                                    $direction = "desc";
                                }

                                if ($order == "nome") {
                                    $order = "hotel_nome";
                                }
                                
                                $query->orderBy($order, $direction);

                            }

                        },

                    ])->find($cms_pagina->vetrina_id);
                 
                        
                    /*
                    * ATTENZIONE: le pagine RR hanno una pseudo vetrina con id=-1 che prende n slots random tra tutte le vetrine
                    * di tutte le località QUINDI SOSTITUISCO GLI SLOT di questa vetrina con n slot RANDOM
                    */

                    if ($cms_pagina->vetrina_id < 0) {

                        $random_slots = SlotVetrina::withClienteLazyEagerLoaded($cms_pagina, $order, $filter)->where('attiva', 1)->limit(50);
                        $direction = "asc";

                        if ($order == "categoria_desc") {
                            $random_slots->orderByRaw("FIELD(hotel_categoria_id,'5','4','6','3','2','1')");
                        } else if ($order == "categoria_asc") {
                            $random_slots->orderByRaw("FIELD(hotel_categoria_id,'1','2','3','6','4','5')");
                        } else {

                            if ($order == "" || $order == "0") {
                                $myorder = "posizione";
                            }

                            if ($order == "prezzo_min") {
                                $myorder = "hotel_prezzo_min";
                            }

                            if ($order == "prezzo_max") {
                                $myorder = "hotel_prezzo_max";
                                $direction = "desc";
                            }

                            if ($order == "nome") {
                                $myorder = "hotel_nome";
                            }

                            $random_slots->orderBy($myorder, $direction);

                        }

                        $random_slots = $random_slots->get();
                        $vetrina = new Vetrina;
                        $vetrina->setRelation('slots', $random_slots);

                    }

                    Utility::putCache($key, $vetrina, Config::get("cache.listing"));

                }

            } 

            return $vetrina;

        }

        /**
         * Cerca il nome dell'hotel
         *
         * @access private
         * @static
         * @param Array $token_da_cercare_arr
         * @param String $locale
         * @return Object
         */

        private static function _cerca_nome_hotel($token_da_cercare_arr, $locale)
        {
            $nome_hotel = implode(' ', $token_da_cercare_arr);

            $hotels = Hotel::with([
                'localita.macrolocalita',
                'stelle'])
                ->attivo()
                ->nome($nome_hotel)
                ->get();

            if ($hotels->keys()->count()) {
                $hotels = $hotels->shuffle();
            }

            return $hotels;
        }

        /* ------------------------------------------------------------------------------------
         * VETRINE / EVIDENZE
         * ------------------------------------------------------------------------------------ */

        /**
         * Aggiunge alla collection dei clienti i vot associati a questa pagina.
         *
         * @access private
         * @param mixed $clienti
         * @param mixed $vot
         * @return void
         */

        private function _addVot($clienti, $vot)
        {
            if (is_null($vot)) {
                return $clienti;
            }

            $clienti_vot_ids = [];

            foreach ($vot as $v) {

                $hotel = $v->offerta->cliente;
                if (!is_null($hotel)) {
                    $clienti->prepend($v);
                    $clienti_vot_ids[] = $hotel->id;
                }

            }

            $filtered = $clienti->reject(function ($cliente) use ($clienti_vot_ids) {
                return in_array($cliente->id, $clienti_vot_ids);
            });

            return $filtered;

        }

        /**
         * Aggiunge alla collection dei clienti i vot associati a questa pagina
         *
         * @access private
         * @param Hotel $clienti
         * @param mixed $vot
         * @param CmsPagina $cms_pagina
         * @return void
         */

        private function _addVotConOfferte($clienti, $vot, $cms_pagina)
        {
            if (is_null($vot)) {
                return $clienti;
            }

            $clienti_vot_ids = [];

            foreach ($vot as $v) {

                $hotel = $v->offerta->cliente;

                if (!is_null($hotel)) {
                    if ($cms_pagina->listing_parolaChiave_id) {
                        $v->setRelation('altre_offerte', $hotel->offerteLast);
                    } elseif (!empty($cms_pagina->listing_offerta_prenota_prima)) {
                        $v->setRelation('altre_offerte', $hotel->offertePrenotaPrima);
                    } elseif ($cms_pagina->listing_offerta == 'offerta') {
                        $v->setRelation('altre_offerte', $hotel->offerte);
                    } elseif ($cms_pagina->listing_offerta == 'lastminute') {
                        $v->setRelation('altre_offerte', $hotel->last);
                    } elseif ($cms_pagina->listing_bambini_gratis) {
                        $v->setRelation('altre_offerte', $hotel->bambiniGratisAttivi);
                    } else {
                        $v->setRelation('altre_offerte', collect([]));
                    }

                    $clienti_vot_ids[] = $hotel->id;
                    $clienti->prepend($v);
                }

            }

            /**
             * DAI CLIENTI TOLGO QUELLI CHE HANNO IL VOT (perché adesso le altre offerte sono nel VOT)
             */

            $filtered = $clienti->reject(function ($cliente) use ($clienti_vot_ids) {
                return in_array($cliente->id, $clienti_vot_ids);
            });
            return $filtered;

        }

        /*
        * Aggiungo le vetrine TOp in testa ai clienti e TOLGO GLI STESSI DAL LISTING CIOE' LI SPOSTO IN TESTA
        *
        * ATTENZIONE DEVO FARE IN MODO CHE SE UN CLIENTE NON C'E' NEL LISTING NON POSSA NEANCHE ESSERE MESSO COME TOP
        * il 2 parametro può essere UNA COLLECTION di VetrinaTrattamentoTopLingua oppure VetrinaServiziTopLingua
        *
        * @access private
        * @param Hotel $clienti
        * @param mixed $vtt
        * @return void
        */

        private function _addVetrinaTop($clienti, $vtt)
        {

            if (is_null($vtt)) {
                return $clienti;
            }

            $clienti_vtt_ids = [];
            $clienti_ids = $clienti->pluck('id')->toArray();

            /**
             * trovo i clienti che hanno la posizione TOP
             */

            foreach ($vtt as $v) {

                if (!is_null($v->vetrina->cliente)) {
                    $clienti_vtt_ids[] = $v->vetrina->cliente->id;
                }

            }

            /**
             * elimino i clienti che hanno la posizione TOP dall'elenco dei clienti
             */

            $clienti = $clienti->reject(function ($cliente) use ($clienti_vtt_ids) {
                return in_array($cliente->id, $clienti_vtt_ids);
            });

            /**
             * cerco le vetrine TOP che NON SONO DEI CLIENTI DEL LISTING: NON LE DEVO CONSIDERARE
             */

            $not_to_prepend_ids = [];
            foreach ($clienti_vtt_ids as $id) {
                if (!in_array($id, $clienti_ids)) {
                    $not_to_prepend_ids[] = $id;
                }
            }

            /**
             * aggiungo in testa ai clienti
             * DIRETTAMENTE IL CLIENTE ASSOCIATO ALLA VETRINA TOP !!!!!
             * ma per differenziarlo nella collection setta la proprietà
             * protected $position_top = 0;
             * al valore 1 con il metodo $cliente->setToTop();
             */

            foreach ($vtt as $v) {
                $cliente = $v->vetrina->cliente;
                if (!is_null($cliente)) {

                    if (!in_array($cliente->id, $not_to_prepend_ids)) {
                        if (is_a($v, 'App\VetrinaTrattamentoTopLingua')) {
                            $cliente->setToTop('vtt|' . $v->id);
                        } else {
                            $cliente->setToTop('vst|' . $v->id);
                        }

                        $clienti->prepend($cliente);
                    }

                }

            }

            return $clienti;

        }

        /**
         * Aggiungo le vetrine TOp in testa ai clienti e TOLGO GLI STESSI DAL LISTING CIOE' LI SPOSTO IN TESTA
         *
         * ATTENZIONE DEVO FARE IN MODO CHE SE UN CLIENTE NON C'E' NEL LISTING NON POSSA NEANCHE ESSERE MESSO COME TOP
         * il 2 parametro può essere UNA COLLECTION di VetrinaTrattamentoTopLingua oppure VetrinaServiziTopLingua
         *
         * @access private
         * @param Hotel $clienti
         * @param mixed $vtt
         * @return void
         */

        private function _addEvidenzeBBTop($clienti, $evidenze_bb, $cms_pagina, $locale = 'it')
        {
            $key = "evidenza_BB_" . $cms_pagina->id;

            if (!$clienti_return = Utility::activeCache($key, "Evidenza in cima " . $cms_pagina->id)) {

                if (is_null($evidenze_bb)) {
                    return $clienti;
                }

                $clienti_evidenze_ids = [];

                /**
                 * trovo i clienti che hanno la posizione TOP
                 */

                foreach ($evidenze_bb as $c) {
                    $clienti_evidenze_ids[] = $c->id;
                }

                /**
                 * Elimino i clienti che hanno la posizione TOP
                 * dall'elenco dei clienti
                 * non è detto che quelli che hanno le evidenze TOP siano tutti nelle lista dei clienti, quindi non ha senso memorizzare quelli che tolgo perché
                 * magari ne devo rimettere di più
                 */

                // $clienti = $clienti->reject(function ($cliente) use ($clienti_evidenze_ids) {
                //     return in_array($cliente->id, $clienti_evidenze_ids);
                // });

                /**
                 * aggiungo in testa ai clienti
                 */

                // foreach ($clienti_evidenze_ids as $id) {

                //     $cliente = Hotel::withListingLazyEagerLoading($cms_pagina)->find($id);
                //     $cliente->setEvidenzaBB();
                //     $clienti->prepend($cliente);

                // }


                foreach ($clienti as $key => $cliente) {
                    if (in_array($cliente->id, $clienti_evidenze_ids)) {
                    $cliente->setEvidenzaBB();
                    $clienti->pull($key);
                    $clienti->prepend($cliente);
                    }
                }

                $clienti_return = $clienti;



                Utility::putCache($key, $clienti_return, Config::get("cache.listing"));

            }

            return $clienti_return;

        }

        /**
         * Prendo i VOT online
         * CACHE 5 MINUTI
         *
         * @access private
         * @param mixed $cms_pagina
         * @param string $locale (default: 'it')
         * @param string $ids_vot (default: '')
         * @return void
         */

        private function _getVotOnLine($cms_pagina, $locale = 'it', $ids_vot = '')
        {

            /**
             * Mon metto il voncolo attiva() nel with perché con il with
             * MI PRENDEREBBE TUTTE LE OFFERTE TOP LINGUA "EAGER LOADANDO" SOLO LE OFFERTE attive]
             *
             * prendo solo quelli ONLINE //
             */

            $key = "VOT_all_" . $cms_pagina->id . "_" . $locale;

            if (!$vot = Utility::activeCache($key, "Cache VOT Online")) {

                $vot_all = $cms_pagina
                    ->vetrine_offerte_top_lingua()
                    ->limitIds($ids_vot)
                    ->with([
                        'offerta' => function ($query) use ($locale, $cms_pagina) {
                            $query
                                ->withClienteLazyEagerLoaded($locale, $cms_pagina);
                        },

                    ])
                    ->whereHas('offerta', function($q) {
                        $q->whereAttivo(1)->whereRaw("FIND_IN_SET('".date('n')."-".date('Y')."',mese) > 0");
                    })
                    ->orderBy('id')
                    ->get();
                
                /**
                 * prendo solo quelli ONLINE
                 */

                // $vot = $vot_all->filter(function ($item) {
                //     return $item->offerta()->attiva()->count();
                // });
                $vot = $vot_all;

                Utility::putCache($key, $vot, Config::get("cache.vetrine"));

            }

            return $vot;

        }

        /**
         * Prendo i VTT online
         * CACHE 5 MINUTI
         *
         * @access private
         * @param mixed $cms_pagina
         * @param string $locale (default: 'it')
         * @param string $ids_vot (default: '')
         * @return void
         */

        private function _getVttOnLine($cms_pagina, $locale = 'it', $ids_vot = '')
        {

            $key = "VTT_all_" . $cms_pagina->id . "_" . $locale;

            if (!$vtt = Utility::activeCache($key, "Cache VTT Online")) {

                $vtt_all = $cms_pagina
                    ->vetrine_trattamenti_top_lingua()
                    ->limitIds($ids_vot)
                    ->with([
                        'vetrina' => function ($query) use ($cms_pagina) {
                            $query
                                ->withClienteLazyEagerLoaded($cms_pagina);
                        },

                    ])
                    ->whereHas('vetrina', function ($q) {
                        $q->whereAttivo(1)->whereRaw("FIND_IN_SET('".date('n')."-".date('Y')."',mese) > 0");
                    })
                    ->orderBy('id')
                    ->get();

                /**
                 * prendo solo quelli ONLINE
                 */

                // $vtt = $vtt_all->filter(function ($item) {
                //     return $item->vetrina()->attiva()->count();
                // });

                $vtt = $vtt_all;

                Utility::putCache($key, $vtt, Config::get("cache.vetrine"));

            }

            return $vtt;

        }

        /**
         * Prendo i VST online
         * CACHE 5 MINUTI
         *
         * @access private
         * @param mixed $cms_pagina
         * @param string $locale (default: 'it')
         * @param string $ids_vot (default: '')
         * @return void
         */

        private function _getVstOnLine(CmsPagina $cms_pagina, $locale = 'it', $ids_vot = '')
        {

            $listing_gruppo_servizi_id = $cms_pagina->listing_gruppo_servizi_id;

            $key = "VST_all_" . $cms_pagina->id . "_" . $locale;

            if (!$vst = Utility::activeCache($key, "VST Online")) {

                $vst_all = $cms_pagina
                    ->vetrine_servizi_top_lingua()
                    ->limitIds($ids_vot)
                    ->with([
                        'vetrina' => function ($query) use ($locale, $cms_pagina) {
                            $query
                            ->withClienteLazyEagerLoaded($locale, $cms_pagina)
                            ->attiva();
                        },

                    ])
                    ->orderBy('id')
                    ->get();

                /**
                 * prendo solo quelli ONLINE
                 */

                $vst = $vst_all->filter(function ($item) {
                    return !is_null($item->vetrina);
                });

                Utility::putCache($key, $vst, Config::get("cache.vetrine"));

            }

            return $vst;
        }

        /**
         * Prendo i VAAT online
         * CACHE 5 MINUTI
         *
         * @access private
         * @param mixed $cms_pagina
         * @param string $locale (default: 'it')
         * @param string $ids_vot (default: '')
         * @return void
         */

        private function _getVaatOnLine($cms_pagina, $locale = 'it', $ids_vot = '')
        {

            $key = "VAAT_all_" . $cms_pagina->id . "_" . $locale;

            if (!$vaat = Utility::activeCache($key, "Cache VAAT Online")) {

                $vaat_all = $cms_pagina
                    ->vetrine_offerte_bg_lingua()
                    ->limitIds($ids_vot)
                    ->with([
                        'offerta' => function ($query) use ($locale) {
                            $query
                                ->withClienteLazyEagerLoaded($locale);
                        },

                    ])
                    ->whereHas('offerta', function ($q) {
                        $q->whereAttivo(1)->whereRaw("FIND_IN_SET('" . date('n') . "-" . date('Y') . "',mese) > 0");
                    })
                    ->orderBy('id')
                    ->get();

                /**
                 * prendo solo quelli ONLINE
                 */

                // $vaat = $vaat_all->filter(function ($item) {
                //     return $item->offerta()->attiva()->count();
                // });

                $vaat = $vaat_all;

                //dd($vaat);
            
                Utility::putCache($key, $vaat, Config::get("cache.vetrine"));

            }

            return $vaat;

        }

        /**
         * Prende le evidenza Bad & Breakfast
         * CACHE 5 MINUTI
         *
         * @access private
         * @param CmsPAgina $cms_pagina
         * @param String $locale
         * @return Object
         */

        private function _getEvidenzeBB($cms_pagina, $locale)
        {

            $localita_ids = [];

            /**
             * Per RR non filtro sulle località !!
             */

            if ($cms_pagina->listing_macrolocalita_id > 0 && $cms_pagina->listing_macrolocalita_id != 11) {

                $macro = Macrolocalita::find($cms_pagina->listing_macrolocalita_id);
                if (!is_null($macro) && !is_null($macro->localita)) {
                    foreach ($macro->localita as $loc) {
                        $localita_ids[] = $loc->id;
                    }
                }

            }

            $key = "Evidenze_BB_" . $cms_pagina->id . "_" . $locale;

            if (!$evidenze_bb = Utility::activeCache($key, "Evidenze B&B")) {

                $q = DB::table('tblHotel')->where('attivo', 1);

                if (count($localita_ids)) {
                    $q->whereIn('localita_id', $localita_ids);
                }

                $evidenze_bb = $q->where(function ($query) {

                    $query
                        ->where('trattamento_ai', 0)
                        ->where('trattamento_pc', 0)
                        ->where('trattamento_mp', 0)
                        ->where('trattamento_bb', 1)
                        ->where('trattamento_sd', 1)
                        ->orWhere(function ($query1) {

                            $query1
                                ->where('trattamento_ai', 0)
                                ->where('trattamento_pc', 0)
                                ->where('trattamento_mp', 0)
                                ->where('trattamento_bb', 1)
                                ->where('trattamento_sd', 0);

                        });

                })
                    ->select('id')
                    ->get()->all();

                Utility::putCache($key, $evidenze_bb, Config::get("cache.vetrine"));

            }

            if (count($evidenze_bb)) {
                shuffle($evidenze_bb);
            }

            return $evidenze_bb;

        }

        /**
         * Toglie dai clienti quelli che hanno uno slot nella vetrina
         *
         * @access private
         * @param Hotel $clienti
         * @param Vetrine $vetrina
         * @return void
         */

        private function _clientiSenzaVetrina($clienti, $vetrina)
        {

            if (is_null($vetrina)) {
                return $clienti;
            }

            $clienti_slot_ids = [];

            foreach ($vetrina->slots as $slot) {
                $clienti_slot_ids[] = $slot->hotel_id;
            }

            $filtered = $clienti->reject(function ($cliente) use ($clienti_slot_ids) {
                return in_array($cliente->id, $clienti_slot_ids);
            });

            return $filtered;

        }

        /**
         * Aggiunge ai clienti quelli che hanno uno slot nella vetrina
         *
         * @access private
         * @param Object $clienti
         * @param Object $gHotels
         * @return void
         */

        private function _clientiEVetrine($clienti, $gHotels)
        {

            if (is_null($gHotels)) {
                return $clienti;
            }

            $arrayHotels = array();

            foreach ($gHotels as $h) {
                $arrayHotels[] = $h;
            }

            foreach ($clienti as $h) {
                $arrayHotels[] = $h;
            }

            return $arrayHotels;

        }

        /**
         * Prende le coordinate e lo zoom per la mappa
         *
         * @access private
         * @static
         * @param Localita $localita
         * @return void
         */

        private static function _getGeoCoords($localita)
        {
            $coordinate = array();
            $lat_fallback = "44.063409";
            $long_fallback = "12.585280";
            $zoom_fallback = 9;

            $lat = $localita->latitudine;
            $long = $localita->longitudine;
            $zoom = $localita->zoom;
            /**
             * ci sono località che hanno come coordinate 0.0000
             */

            $coordinate['lat'] = ($lat > 0.1) ? $lat : $lat_fallback;
            $coordinate['long'] = ($long > 0.1) ? $long : $long_fallback;
            $coordinate['zoom'] = ($zoom > 0) ? $zoom : $zoom_fallback;

            return $coordinate;

        }

        	/**
	 * Elimina i clienti dal listing se sono nelle vetrine
	 * 
	 * @access protected
	 * @param Hotel $clienti
	 * @param Vetrina $vetrina
	 * @param int $listing_categorie (default: 0)
	 * @param string $locale (default: 'it')
	 * @return void
	 */
	 
	private function _mergeListingClientiSlotVetrine($clienti, $vetrina, $cms_pagina = 0, $locale = 'it')
	{
		
		/**
	     *  DEFINISCO OGNI QUANTI ELEMENTI DEL LISTING INSERIRO' LE VETRINE (5)
	     * 0 per dire che NON VOGLIO INSERIE VETRINE tra gli elementi del listing
	     */
	     
		define("STEP_INSERT_VETRINE", 0);
		
		/*
	     * DEFINISCO QUANTE VERTINE INSERISCO IN OGNI STEP (1)
	     * 0 per dire che NON VOGLIO INSERIE VETRINE tra gli elementi del listing
	     */
	     
		define("N_VETRINE_STEP", 0);

		/**
		 *  DEFINISCO QUANTE VERTINE INSERISCO IN HEAD AL LISTING 
		 */
		 
		define("N_VETRINE_HEAD", 2);

		/** 
		 * DEFINISCO SE VOGLIO METTERE IN FONDO LE VETRINE CHE NON SONO STATE VISUALIZZATE NEL LISTING TRA HEAD E STEP 
 		 */
 		 
		define("INSERT_VETRINE_BOTTOM", false);
		
		//$key = "update_pointer_slot_vetrina_" . $cms_pagina->id . "_" . $locale;
		
		//if (!$new_final = Utility::activeCache($key, "Valori slot pointer Vetrina ( ongi 2 minuti ) page id " . $cms_pagina->id . " " . $locale)) {
		
				/**
				 * Creo l'oggetto Vetrina
				 */
				 
				$v = new VetrinaController();
				$slots = $v->updatePointerSlotVetrina($vetrina, $cms_pagina->listing_categorie, $locale);
				$slots_iterator = $slots->getIterator();
								
				/**
				 * array con gli id degli hotel che ho messo in vetrina
				 */
				 
				$hotel_vetrina_ids = [];
				
				/** 
				 * array temporaneo in cui metto i clienti come array (dalla collection) 
				 */
				 
				$temp = $clienti;
				
				if (STEP_INSERT_VETRINE == 0)
					$chunks = collect([$clienti]);
					
				else
					$chunks = $temp->chunk(STEP_INSERT_VETRINE, true);
					
				/**
				 * $chunks in ENTRAMBI I CASI è una collezione di collezioni
				 */
				 
				/**
				 * array in cui rimetto tutti i chunks 
				 */
				 
				$final = collect(array());
				
				$n_chunk = 1;
				$pointer_slots_iterator = 0;
				
				/**
				 * Ciclo su tutti i chunks (pezzi)
				 */
				 
				foreach ($chunks as $chunk) {
					
					if ($pointer_slots_iterator != - 1 && $slots_iterator->valid()) {
						
						/**
						 * PRIMO CHUNK 
						 */
						 
						if ($n_chunk == 1) {
							
							/**
							 * AGGIUNGO N_VETRINE_HEAD in testa al chunk degli hotel 
							 */
							 
							$vetrine_to_add = N_VETRINE_HEAD;
							$count = 0;
							$vetrine_queue = array();
							
							if ($vetrine_to_add > 0) {
								
								do {
									
									$dati_vetrina = $slots_iterator[$pointer_slots_iterator];
																		
									$pointer_slots_iterator++;
									if ($pointer_slots_iterator == $slots->count())
										$pointer_slots_iterator = - 1;
		
									$count++;

									if (!is_null($dati_vetrina)) {
										$vetrina_to_list = $dati_vetrina;
										$vetrine_queue[] = $vetrina_to_list;
									}
								}
								
								while ($count < $vetrine_to_add && $pointer_slots_iterator > 0);
								
							}
							
							if (count($vetrine_queue)) {
								$vetrine_queue = array_reverse($vetrine_queue, true);
								foreach ($vetrine_queue as $vetrina_to_list) {
																		
									
									$chunk->prepend($vetrina_to_list);
									if (!is_null($vetrina_to_list->cliente))
										$hotel_vetrina_ids[] = $vetrina_to_list->cliente->id;
										
								}
							}
							
						}else {
							
							/**
							 * AGGIUNGO N_VETRINE_STEP in testa al chunk degli hotel 
							 */
							 
							$vetrine_to_add = N_VETRINE_STEP;
							$count = 0;
							$vetrine_queue = array();
							
							if ($vetrine_to_add > 0) {
								
								do {
									
									$dati_vetrina = $slots_iterator[$pointer_slots_iterator];
									
									$pointer_slots_iterator++;
									
									if ($pointer_slots_iterator == $slots->count())
										$pointer_slots_iterator = - 1;
		
									$count++;
									
									if (count($dati_vetrina)) {
										$vetrina_to_list = $dati_vetrina;
										$vetrine_queue[] = $vetrina_to_list;
									}
									
								}
								while ($count < $vetrine_to_add && $pointer_slots_iterator > 0);
								
							}
							
							if (count($vetrine_queue)) {
							
								$vetrine_queue = array_reverse($vetrine_queue, true);
								
								foreach ($vetrine_queue as $vetrina_to_list) {
									$chunk->prepend($vetrina_to_list);
									$hotel_vetrina_ids[] = $vetrina_to_list->cliente->id;
								}
								
							}
							
						}
					}
					
					
									
					/**
					 * end if ancora vetrine
					 */
					


					$final = $final->merge($chunk);
					$n_chunk++;

				}
				
				if ($pointer_slots_iterator != - 1 && INSERT_VETRINE_BOTTOM) {
		
					/**
					 * Aggiungo le eventuali vetrine rimaste che non sono state messe tra gli hotel perché gli hotel (i chunk) sono "finiti" 
					 */
					 
					for ($i = $pointer_slots_iterator; $i < $slots->count(); $i++) {
						
						$final = $final->push($slots_iterator[$i]);
						$hotel_vetrina_ids[] = $slots_iterator[$i]->cliente->id;
						
					}
					
				}
		
				/* 
				 * DEVO LOOPPARE SULLA collection $final ed eliminare gli hotel con gli id in $hotel_vetrina_ids
				 */
				
				$new_final = $final->reject(function ($object) use ($hotel_vetrina_ids)
				{
						/**
						 * se è un Hotel e NON è uno slot
						 * se l'hotel è in vetrina
						 * RITORNO true perché lo voglio rigettare
						 */
						 
						try {
							return is_null($object->vetrina_id) && in_array($object->id, $hotel_vetrina_ids);
							
						} catch (\Exception $e) {}
		
				});
				
			//Utility::putCache($key, $new_final, Config::get("cache.listing"));
				
		//}
			
	   /*
		* $new_final è una collezione di oggetti Hotel e SlotVetrina "mischiati"
        */
        
		return $new_final;
		
	}
        /* ------------------------------------------------------------------------------------
         * RICERCA
         * ------------------------------------------------------------------------------------ */
        
        /**
         * Ricerca nelle localita.
         *
         * @access private
         * @param mixed $token_da_cercare_arr
         * @param mixed $locale
         * @return void
         */

        private function _cerca_pagine_localita($token_da_cercare_arr, $locale)
        {

            $fields = ['uri'];

            $query = CmsPagina::attiva()
                ->lingua($locale)
                ->where('template', 'localita')
                ->where(function ($query1) use ($fields, $token_da_cercare_arr) {
                    foreach ($fields as $count => $field) {
                        $query1->orWhere(function ($query2) use ($field, $token_da_cercare_arr) {
                            foreach ($token_da_cercare_arr as $token) {
                                $query2->where($field, 'LIKE', '%' . $token . '%');
                            }
                        });
                    }
                });

            $pages = $query->get();

            if ($pages->count()) {
                $pages = $pages->shuffle();
            }

            return $pages;

        }

        /**
         * Cerca nelle pagine
         *
         * @access private
         * @param mixed $token_da_cercare_arr
         * @param mixed $locale
         * @return void
         */

        private function _cerca_pagine($token_da_cercare_arr, $locale)
        {

            //$fields = ['seo_title', 'seo_description', 'uri', 'h1', 'h2', 'descrizione_1', 'descrizione_2'];
            $fields = ['uri', 'h1', 'seo_title', 'seo_description'];

            /**
             * cerco in title, description, url
             */

            $query = CmsPagina::attiva()
                ->lingua($locale)
                ->where('template', '!=', 'localita')
                ->where(function ($query1) use ($fields, $token_da_cercare_arr) {
                    foreach ($fields as $count => $field) {
                        $query1->orWhere(function ($query2) use ($field, $token_da_cercare_arr) {
                            foreach ($token_da_cercare_arr as $token) {
                                $query2->where($field, 'LIKE', '%' . $token . '%');
                                //$query2->where($field, $token);
                            }
                        });
                    }
                });

            $pages = $query->get();

            if ($pages->count())
            //$pages = $pages->shuffle();

            {
                return $pages;
            }

        }

        /**
         * Cerca tra gli hotel
         *
         * @access private
         * @param mixed $token_da_cercare_arr
         * @param mixed $locale
         * @return void
         */

        private function _cerca_hotel($token_da_cercare_arr, $locale, $ids_hotel_to_exclude = [])
        {

            /* Cerco nel title dell'hotel che viene generato dinamicamente dalla funzione Hotel::getTitle */
            $query = DB::table('tblHotel')
                ->select('tblHotel.id', DB::raw(' CONCAT(tblHotel.nome, " ", tblLocalita.nome, " ", tblMacrolocalita.nome, " ", tblHotel.indirizzo) AS title'))
                ->join('tblLocalita', 'tblHotel.localita_id', '=', 'tblLocalita.id')
                ->join('tblMacrolocalita', 'tblLocalita.macrolocalita_id', '=', 'tblMacrolocalita.id')
                ->where('tblHotel.attivo', 1);

            foreach ($token_da_cercare_arr as $token)
            //$query->where('tblDescrizioneHotelLang.testo', 'LIKE', '%'.$token.'%');
            {
                $query->where(DB::raw('UPPER(CONCAT(tblHotel.nome, " ", tblLocalita.nome, " ", tblMacrolocalita.nome,  " ", tblHotel.indirizzo))'), 'LIKE', '%' . $token . '%');
            }

            $hotels_title_query = $query->select('tblHotel.id')->get();

            $query = DB::table('tblHotel')
                ->join('tblDescrizioneHotel', 'tblHotel.id', '=', 'tblDescrizioneHotel.hotel_id')
                ->join('tblDescrizioneHotelLang', 'tblDescrizioneHotel.id', '=', 'tblDescrizioneHotelLang.master_id')
                ->where('tblHotel.attivo', 1)
                ->where('tblDescrizioneHotelLang.lang_id', $locale);

            foreach ($token_da_cercare_arr as $token)
            //$query->where('tblDescrizioneHotelLang.testo', 'LIKE', '%'.$token.'%');
            {
                $query->where(DB::raw('UPPER(`tblDescrizioneHotelLang`.`testo`)'), strtoupper($token));
            }

            $hotels_desc_query = $query->select('tblHotel.id')->get();

            /**
             * considero tutti i 9 pti di forza come un testo e faccio la ricerca nel testo in AND
             */

            $query = DB::table('tblHotel')
                ->join('tblPuntiForza', 'tblHotel.id', '=', 'tblPuntiForza.hotel_id')
                ->join('tblPuntiForzaLang', 'tblPuntiForza.id', '=', 'tblPuntiForzaLang.master_id')
                ->where('tblHotel.attivo', 1)
                ->where('tblPuntiForzaLang.lang_id', $locale);

            foreach ($token_da_cercare_arr as $token) {
                $query->where('tblPuntiForzaLang.nome', 'LIKE', '%' . $token . '%');
            }

            $hotels_9pti_query = $query->select('tblHotel.id')->get();

            $hotels_ids = [];

            foreach ($hotels_desc_query as $h) {
                $hotels_ids[] = $h->id;
            }

            foreach ($hotels_9pti_query as $h) {
                $hotels_ids[] = $h->id;
            }

            foreach ($hotels_title_query as $h) {
                $hotels_ids[] = $h->id;
            }

            $hotels_ids = array_unique($hotels_ids);

            /**
             * Tolgo gli hotel già selezionati con la ricerca per nome
             */
            if (count($ids_hotel_to_exclude)) {
                foreach ($hotels_ids as $key => $id) {
                    if (in_array($id, $ids_hotel_to_exclude)) {
                        unset($hotels_ids[$key]);
                    }
                }
            }

            $hotels = Hotel::with([
                'localita.macrolocalita',
                'stelle'])
                ->whereIn('id', $hotels_ids)
                ->get();

            if ($hotels->count()) {
                $hotels = $hotels->shuffle();
            }

            return $hotels;

        }

        /**
         * Cerca tra le offerte
         *
         * @access private
         * @param mixed $token_da_cercare_arr
         * @param mixed $locale
         * @return void
         */

        private function _cerca_offerte($token_da_cercare_arr, $locale)
        {

            $query = DB::table('tblOfferte')
                ->join('tblOfferteLang', 'tblOfferte.id', '=', 'tblOfferteLang.master_id')
                ->where('tblOfferte.attivo', 1)
                ->where('tblOfferte.valido_al', '>=', date('Y-m-d'))
                ->where('tblOfferteLang.lang_id', $locale);

            foreach ($token_da_cercare_arr as $token) {
                $query->where('tblOfferteLang.testo', 'LIKE', '%' . $token . '%');
            }

            $offerte = $query->select('tblOfferte.*', 'tblOfferteLang.titolo', 'tblOfferteLang.testo')->get()->all();

            if (count($offerte)) {
                shuffle($offerte);
            }

            return $offerte;

        }

        /* ------------------------------------------------------------------------------------
         * LISTING
         * ------------------------------------------------------------------------------------ */

        /**
         * Fa la query per il listing in base hai parametri .
         *
         * @access private
         * @param CmsPagina $cms_pagina
         * @param string $locale (default: 'it')
         * @param int $order (default: 0)
         * @param int $filter (default: 0)
         * @param mixed $terms (default: [])
         * @return void
         */

        private static function _getClientiListing(CmsPagina $cms_pagina, $locale = 'it', $order = 0, $filter = 0, $terms = [])
        {

            $clienti = Hotel::withListingLazyEagerLoading($cms_pagina, $terms, $order)
                ->attivo();

            if ($cms_pagina->listing_puntoForzaChiave_id > 0) {
                $clienti = $clienti->listingPuntoForzaChiave($cms_pagina->listing_puntoForzaChiave_id);
            }

            if ($cms_pagina->listing_categorie != "") {
                $clienti = $clienti->listingCategorie($cms_pagina->listing_categorie);
            }

            if ($cms_pagina->listing_tipologie != "") {
                $clienti = $clienti->listingTipologie($cms_pagina->listing_tipologie);
            }


            //? I preferiti NON DEVONO FILTRARE PER MACRO
            //?  POSSONO essere anche di Pesaro INSIEME A RR
            if (!$cms_pagina->listing_preferiti > 0) {
                if ($cms_pagina->listing_macrolocalita_id > 0) {
                    $clienti = $clienti->listingMacrolocalita($cms_pagina->listing_macrolocalita_id);
                }

                if ($cms_pagina->listing_localita_id > 0) {
                    $clienti = $clienti->listingLocalita($cms_pagina->listing_localita_id);
                }
            }


            if ($cms_pagina->listing_parolaChiave_id > 0) {
                if ($cms_pagina->template == 'listing') {
                    $clienti = $clienti->listingParolaChiaveOfferteAttiveVotAttivi($cms_pagina->id, $cms_pagina->listing_parolaChiave_id,$locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);
                } else {
                    $clienti = $clienti->listingParolaChiaveOfferteAttive($cms_pagina->listing_parolaChiave_id, $locale);
                }

            }

            if ($cms_pagina->listing_preferiti > 0) {
                $clienti = $clienti->listingPreferiti($cms_pagina->listing_preferiti);
            }

            if ($cms_pagina->indirizzo_stradario != "") {
                $clienti = $clienti->listingIndirizzoStradario($cms_pagina->indirizzo_stradario);
            }

            if ($cms_pagina->listing_green_booking > 0) {
                $clienti = $clienti->listingGreenBooking($cms_pagina->listing_green_booking);
            }

            if ($cms_pagina->listing_annuali > 0) {
                $clienti = $clienti->listingAnnuali($cms_pagina->listing_annuali);
            }

            if ($cms_pagina->listing_eco_sostenibile != "") {
                $clienti = $clienti->listingEcoSostenibile($cms_pagina->listing_eco_sostenibile);
            }

            if ($cms_pagina->listing_trattamento != "") {
                $clienti = $clienti->listingTrattamentoNew($cms_pagina->listing_trattamento);
            }

            if ($cms_pagina->listing_offerta_prenota_prima != "") {
                $clienti = $clienti->listingOffertaPrenotaPrimaVotPrenotaPrima($cms_pagina->id, $cms_pagina->listing_offerta_prenota_prima, $locale);
            }

            if ($cms_pagina->listing_gruppo_servizi_id > 0) {
                $clienti = $clienti->listingGruppoServizi($cms_pagina->listing_gruppo_servizi_id,$cms_pagina->listing_macrolocalita_id,$cms_pagina->listing_localita_id);
            }

            if ($cms_pagina->listing_offerta != "") {
                $clienti = $clienti->ListingOffertaVot($cms_pagina->id, $cms_pagina->listing_offerta, $locale);
            }

            if ($cms_pagina->listing_whatsapp > 0) {
                $clienti = $clienti->listingWhatsapp($cms_pagina->listing_whatsapp);
            }

            if ($cms_pagina->listing_bonus_vacanze_2020 == 1) {
                $clienti = $clienti->listingBonusVacanze($cms_pagina->listing_bonus_vacanze_2020);
            }

           
            if ($cms_pagina->listing_bambini_gratis > 0) {
                $clienti = $clienti->listingBambiniGratisVaat($cms_pagina->id, $cms_pagina->listing_bambini_gratis,$locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);
            }

            if ($filter) {
                $clienti = $clienti->listingFilter($filter);
            }

            $clienti = $clienti->listingOrderBy($order, $cms_pagina->uri);

            return $clienti;

        }

        /**
         * Trova le strade partendo dalla pagina
         *
         * @access private
         * @static
         * @param String $locale
         * @param Localita $localita (default: null)
         * @param int $macro_localita (default: 11)
         * @return CmsPagine
         */

        private static function _getStradeFromCmsPagine($locale, $localita = 0, $macro_localita = 11)
        {

            /**
             * Se sono RR allora imposto la localita
             */

            $key = "strade_" . $macro_localita . "_" . $localita . "_" . $locale;

            if (!$strade = Utility::activeCache($key, "Strade per la macro/loc:" . $macro_localita . "/" . $localita)) {

                if ($macro_localita == 11) {
                    $localita = 49;
                }

                $strade = CmsPagina::where('indirizzo_stradario', '!=', '')
                    ->where('lang_id', $locale)
                    ->where('macrolocalita_id_stradario', $macro_localita);

                if ($localita != 0) {
                    $strade = $strade->where('localita_id_stradario', $localita);
                }

                $strade = $strade->get();

                Utility::putCache($key, $strade);

            }

            return $strade;

        }

        /**
         * Listing per le strade
         *
         * @access protected
         * @param array $strade (default: array())
         * @return void
         */

        private static function _getListingFromStrade($strade = array())
        {

            $clienti = [];

            foreach ($strade as $s):

                $key = "listing_strade_" . $s->macrolocalita_id_stradario . "_" . $s->localita_id_stradario . "_" . $s->indirizzo_stradario;

                if (!$hotel = Utility::activeCache($key, "Recupera il listing strade")) {

                    $hotel = Hotel::with([
                        'stelle',
                        'localita.macrolocalita',
                    ])
                        ->attivo()
                        ->listingMacrolocalita($s->macrolocalita_id_stradario)
                        ->listingLocalita($s->localita_id_stradario)
                        ->listingIndirizzoStradario($s->indirizzo_stradario)
                        ->get();

                    Utility::putCache($key, $hotel);

                }

                array_push($clienti, $hotel);

            endforeach;

            return $clienti;

        }

        /**
         * Prende il liting associato alla pagina
         *
         * @access protected
         * @param CmsPagina $cms_pagina
         * @param string $locale (default: 'it')
         * @param int $order (default: 0)
         * @param int $filter (default: 0)
         * @return Hotel $clienti
         */

        private static function _getListing(CmsPagina $cms_pagina, $locale = 'it', $order_by = 0, $page = 1, $filter = 0)
        {

            $terms = [];
            $clienti = [];
            $paginate = false;

            /**
             * ATTENZIONE PASSO $order=0 come ordinamento standard
             * MA nel caso di listing di tipo categoria/tipologia NON è RANDOM
             * MA ordinamento per campo 'numero_click'
             */

            if ((!$order_by || $order_by == 0) && ($cms_pagina->listing_categorie != '' || $cms_pagina->listing_tipologie != ''))
            //$order_by = 'numero_click'; @Lucio: mi metti il random su ogni listing? non più con i pesi.
            {
                $order_by = 'RAND()';
            }

            /**
             * ATTENZIONE VOGLIO MOSTRARE SOLO LE OFFERTE CHE HANNO QUELLA PAROLA CHIAVE
             * QUINDI PRECARICO SOLO QUELLE OFFERTE NEGLI HOTEL NELL' EAGER LOADING!!!
             */

            if ($cms_pagina->listing_parolaChiave_id) {

                /*
                * Dalla parola chiave ottengo le parole chiave espanse
                */

                $parola_chiave = ParolaChiave::with("alias")->find($cms_pagina->listing_parolaChiave_id);
                if (isset($parola_chiave->alias)) {
                    foreach ($parola_chiave->alias as $term) {
                        $terms[] = $term->chiave;
                    }
                }

            }

            /**
             * Sono in RR
             */

            $key = "getListing_" . $cms_pagina->id . "_" . $locale . "_" . $order_by;

            if (strpos($cms_pagina->uri, 'italia/hotel_riviera_romagnola') !== false || strpos($cms_pagina->uri, 'riviera-romagnola') !== false) {

                $paginate = true;
                $page == "" ? $page = 1 : "";
                $key = $key . "paginate-" . $page;

            }

            //echo $key;

            /**
             * ATTENZIONE!!!
             * Se sono in una pagina preferiti rifaccio sempre la query altrimenti ricshio di vedere i preferiti di un altro.
             */

            if ($cms_pagina->listing_preferiti > 0 || !$clienti = Utility::activeCache($key, "Listing " . $paginate . " " . $cms_pagina->id . " " . $locale . " " . $order_by)) {

                /**
                 * Prendo gli slot del listing
                 */

                $clienti = Self::_getClientiListing($cms_pagina, $locale, $order_by, $filter, $terms);

            //dd($clienti->toSql());

            $clienti = $clienti->get();
                //$clienti = $clienti->get(['id', 'nome', 'indirizzo', 'localita_id','categoria_id','tipologia_id','distanza_centro', 'distanza_staz', 'distanza_spiaggia', 'tmp_punti_di_forza_it', 'tmp_punti_di_forza_en', 'tmp_punti_di_forza_fr', 'tmp_punti_di_forza_de', 'prezzo_min', 'prezzo_max', 'rating_ia']);

                //dd($clienti);

                /**
                 * @Sacco 01/08/2019
                 * Se id = 1583 la relazione stelle() (Hotel.php) è fatta con la tabella CategoriaFake che visualizza come nome "4stelle SUP"; SE faccio l'eager loading però non è stato ancora instanziato l'hotel con l'id e quindi viene sempre caricata la relazione con la tabella Categoria.
                 * Quindi faccio un loop sugli hotel con egarloading e per quello che deve cambiare assegno le stelle chimando la relazione sull'oggetto instanziato
                 */
                $clienti->each(function ($c) {
                    if (in_array($c->id, Utility::fakeHotel())) {
                        $c->setRelation('stelle', Hotel::find($c->id)->stelle);
                    }
                });

                /**
                 * ATTENZIONE: Ordinamento prezzo delle offerte
                 * ============================================
                 *
                 * Ogni hotel ha le offerte associate che sono ordinate per prezzo_a_persona; le offerte che NON devono essere considerate hanno la parte in lingua vuota (perché ho un eager loading e NON un join quindi cmq le predno tutte!!)
                 *
                 * Loop su questa collection e per ogni hotel prendo la prima DISPOBIBILE (sono ordinate!!!) e ne scrivo il valore del prezzo_a_persona in un campo dell'oggetto hotel
                 *
                 * Riordino la collection basandomi su questo campo!!!
                 */

                if ($cms_pagina->listing_parolaChiave_id && ($order_by == "prezzo_min" || $order_by == "prezzo_max")) {

                    /**
                     * ATTENZIONE offerteLast è un eager loading e contiene TUTTE LE OFFERTE non solo quelle filtrate
                     * SE prendo il primo di queste le considero tutte
                     * PRIMA PER OGNI CLIENTE ELIMINO LE OOFFERTE CHE HANNO LA PARTE IN LINGUA VUOTA
                     */

                    foreach ($clienti as $cliente) {
                        foreach ($cliente->offerteLast as $key => $offerta) {
                            if (!$offerta->offerte_lingua->count()) {
                                unset($cliente->offerteLast[$key]);
                            }
                        }
                    }

                    foreach ($clienti as $cliente) {
                        if (!is_null($cliente->offerteLast->first())) {
                            $cliente->off = $cliente->offerteLast->first()->prezzo_a_persona;
                        }
                    }

                    /**
                     * ordinamento
                     */

                    if ($order_by == "prezzo_min") {
                        $clienti = $clienti->sortBy('off');
                    } elseif ($order_by == "prezzo_max") {
                        $clienti = $clienti->sortByDesc('off');
                    }

                } // if listing_parolaChiave_id && order by prezzo

                /**
                 * Ordinamento per prezzo delle offerte con listing_offerta == 'offerta' (VEDI ORDINAMENTO OFFERTE SOPRA)
                 */

                if (!empty($cms_pagina->listing_offerta) && $cms_pagina->listing_offerta == 'offerta') {

                    foreach ($clienti as $cliente) {

                        foreach ($cliente->offerte as $key => $offerta) {
                            if (!$offerta->offerte_lingua->count()) {
                                unset($cliente->offerte[$key]);
                            }
                        }

                        foreach ($cliente->offerteTopOS as $key => $offerta) {
                            if (!$offerta->offerte_lingua->count()) {
                                unset($cliente->offerteTopOS[$key]);
                            }
                        }

                    }

                    foreach ($clienti as $cliente) {

                        if (!is_null($cliente->offerteTopOS->first())) {
                            $cliente->off = $cliente->offerteTopOS->first()->prezzo_a_persona;
                        } elseif (!is_null($cliente->offerte->first())) {
                            $cliente->off = $cliente->offerte->first()->prezzo_a_persona;
                        }

                    }

                    /**
                     * ordinamento
                     */

                    if ($order_by == "prezzo_min") {
                        $clienti = $clienti->sortBy('off');
                    } elseif ($order_by == "prezzo_max") {
                        $clienti = $clienti->sortByDesc('off');
                    }

                }

                /**
                 * Ordinamento per prezzo delle offerte con listing_offerta == 'lastminute' (VEDI ORDINAMENTO OFFERTE SOPRA)
                 */

                if (!empty($cms_pagina->listing_offerta) && $cms_pagina->listing_offerta == 'lastminute') {

                    foreach ($clienti as $cliente) {

                        foreach ($cliente->last as $key => $offerta) {
                            if (!$offerta->offerte_lingua->count()) {
                                unset($cliente->last[$key]);
                            }
                        }

                        foreach ($cliente->offerteTopLast as $key => $offerta) {
                            if (!$offerta->offerte_lingua->count()) {
                                unset($cliente->offerteTopLast[$key]);
                            }
                        }

                    }

                    foreach ($clienti as $cliente) {

                        if (!is_null($cliente->offerteTopLast->first())) {
                            $cliente->off = $cliente->offerteTopLast->first()->prezzo_a_persona;
                        } elseif (!is_null($cliente->last->first())) {
                            $cliente->off = $cliente->last->first()->prezzo_a_persona;
                        }

                    }

                    /**
                     * ordinamento
                     */

                    if ($order_by == "prezzo_min") {
                        $clienti = $clienti->sortBy('off');
                    } elseif ($order_by == "prezzo_max") {
                        $clienti = $clienti->sortByDesc('off');
                    }

                }

                /**
                 * Ordinamento per sconto dei prenota prima (VEDI ORDINAMENTO OFFERTE SOPRA)
                 */

                if (!empty($cms_pagina->listing_offerta_prenota_prima)) {

                    foreach ($clienti as $cliente) {

                        foreach ($cliente->offertePrenotaPrima as $key => $offerta) {
                            if (!$offerta->offerte_lingua->count()) {
                                unset($cliente->offertePrenotaPrima[$key]);
                            }
                        }

                        foreach ($cliente->offerteTopPP as $key => $offerta) {
                            if (!$offerta->offerte_lingua->count()) {
                                unset($cliente->offerteTopPP[$key]);
                            }
                        }

                    }

                    foreach ($clienti as $cliente) {

                        if (!is_null($cliente->offerteTopPP->first())) {
                            $cliente->off = $cliente->offerteTopPP->first()->perc_sconto;
                        } elseif (!is_null($cliente->offertePrenotaPrima->first())) {
                            $cliente->off = $cliente->offertePrenotaPrima->first()->perc_sconto;
                        }

                    }

                    /**
                     * ordinamento
                     */

                    if ($order_by == "prezzo_min") {
                        $clienti = $clienti->sortBy('off');
                    } elseif ($order_by == "prezzo_max") {
                        $clienti = $clienti->sortByDesc('off');
                    }

                }

                $c = 0;

                /**
                 * Aggiungo delle elaborazioni
                 */

                if ($cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoPiscinaFuori() && $cms_pagina->listing_categorie == '') {

                    foreach ($clienti as $hotel) {

                        /*
                        * VERIFICO I SERVIZI LISTING
                        * ATTENZIONE QUESTO FUNZIONA SOLO SE HO EAGERLOADATO LE MODEL
                        * ALTRIMENTI LUI FA LE QUERY E NON E' MAI NULLO PERCHE' $cliente->servizi prende tutti i servizi e basta (è nell' eager loading che ho il filtro per gruppo e categoria)
                        * ed anche $servizio->categoria prende la categoria SENZA filtro listing=1
                        * QUINDI DEVO VERIFICARE SE il CLIENTE HA CARICATO LA RELAZIONE CON I SERVIZI; utilizzo il metodo della model relationLoaded (Determine if the given relation is loaded.)
                        */

                        if ($hotel->relationLoaded('servizi') && !is_null($hotel->servizi)):
                            foreach ($hotel->servizi as $servizio):
                                if ($servizio->relationLoaded('categoria') && !is_null($servizio->categoria)):

                                    /**
                                     * è un servizio che appartiene ad una categoria di tipo Listing
                                     * per la pisicna la label NON E' DEL TIPO "<nome servizio> a x metri" (ES: "Piscina fuori hotel a 200 mt")
                                     * MA del tipo "piscina a x metri dall'hotel"
                                     * QUINDI SI UTILIZZANO DELLE LABEL
                                     */

                                    if ($servizio->translate('it')->first()->nome == 'piscina fuori hotel'):
                                        $clienti[$c]->notePiscina = Lang::get('listing.piscina') . " " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri') . " " . Lang::get('labels.dall_hotel');
                                    else:
                                        $clienti[$c]->notePiscina = $servizio->servizi_lingua->first()->nome . " " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri');
                                    endif;

                                endif;
                            endforeach;
                        endif;

                        $c++;

                    }

                } elseif ($cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoBenessere() && $cms_pagina->listing_categorie == '') {

                    foreach ($clienti as $hotel) {

                        /**
                         * VERIFICO I SERVIZI LISTING
                         * ATTENZIONE QUESTO FUNZIONA SOLO SE HO EAGERLOADATO LE MODEL
                         * ALTRIMENTI LUI FA LE QUERY E NON E' MAI NULLO PERCHE' $cliente->servizi prende tutti i servizi e basta (è nell' eager loading che ho il filtro per gruppo e categoria)
                         * ed anche $servizio->categoria prende la categoria SENZA filtro listing=1
                         * QUINDI DEVO VERIFICARE SE il CLIENTE HA CARICATO LA RELAZIONE CON I SERVIZI; utilizzo il metodo della model relationLoaded (Determine if the given relation is loaded.)
                         */

                        if ($hotel->relationLoaded('servizi') && !is_null($hotel->servizi)):
                            foreach ($hotel->servizi as $servizio):
                                if ($servizio->relationLoaded('categoria') && !is_null($servizio->categoria)):

                                    /*
                                    * un servizio che appartiene ad una categoria di tipo Listing
                                    */

                                    $clienti[$c]->noteBenessere = $servizio->servizi_lingua->first()->nome . " " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri');

                                endif;
                            endforeach;
                        endif;

                        $c++;

                    }

                }

                /**
                 * 23/07/18
                 * se sono entrato qui perché devo listare dei preferiti (devo fare tutte le query MA non devo mettere in cache perché se no ci metto il listing dei preferiti)
                 */

                if (!$cms_pagina->listing_preferiti) {
                    Utility::putCache($key, $clienti, Config::get("cache.listing"));
                }

            }

            return $clienti;

        } 

        /**
         * Restituisco il numero di hotel nel listing.
         *
         * @access protected
         * @param CmsPagina $cms_pagina
         * @param string $locale (default: 'it')
         * @param int $order (default: 0)
         * @param int $filter (default: 0)
         * @return void
         */

        private static function _getListingCount(CmsPagina $cms_pagina, $locale = 'it', $order = 0, $filter = 0)
        {

            $terms = [];
            if ($cms_pagina->listing_parolaChiave_id) {

                /**
                 * ATTENZIONE VOGLIO MOSTRARE SOLO LE OFFERTE CHE HANNO QUELLA PAROLA CHIAVE
                 * QUINDI PRECARICO SOLO QUELLE OFFERTE NEGLI HOTEL NELL' EAGER LOADING!!!
                 * Dalla parola chiave ottengo le parole chiave espanse
                 */

                $parola_chiave = ParolaChiave::with("alias")->find($cms_pagina->listing_parolaChiave_id);
                if (isset($parola_chiave->alias)) {
                    foreach ($parola_chiave->alias as $term) {
                        $terms[] = $term->chiave;
                    }
                }

            }

            $key = "getListing_count_" . $cms_pagina->id . "_" . $locale . "_order_" . $order . "_apertura_" . $filter;

            if (!$clienti = Utility::activeCache($key, "Conteggio Hotel Pagina id " . $cms_pagina->id . " (" . $cms_pagina->uri . ")")) {

                //$clientiCount = Hotel::attivo();
                $clientiCount = Hotel::withListingLazyEagerLoading($cms_pagina, $terms)
                    ->attivo();

                if ($cms_pagina->listing_puntoForzaChiave_id > 0) {
                    $clientiCount = $clientiCount->listingPuntoForzaChiave($cms_pagina->listing_puntoForzaChiave_id);
                }

                if ($cms_pagina->listing_categorie != "") {
                    $clientiCount = $clientiCount->listingCategorie($cms_pagina->listing_categorie);
                }

                if ($cms_pagina->listing_tipologie != "") {
                    $clientiCount = $clientiCount->listingTipologie($cms_pagina->listing_tipologie);
                }

                if ($cms_pagina->listing_macrolocalita_id > 0) {
                    $clientiCount = $clientiCount->listingMacrolocalita($cms_pagina->listing_macrolocalita_id);
                }

                if ($cms_pagina->listing_localita_id > 0) {
                    $clientiCount = $clientiCount->listingLocalita($cms_pagina->listing_localita_id);
                }

                if ($cms_pagina->listing_parolaChiave_id > 0) {
                    $clientiCount = $clientiCount->listingParolaChiaveOfferteAttive($cms_pagina->listing_parolaChiave_id, $locale);
                }

                if ($cms_pagina->listing_preferiti > 0) {
                    $clientiCount = $clientiCount->listingPreferiti($cms_pagina->listing_preferiti);
                }

                if ($cms_pagina->indirizzo_stradario != "") {
                    $clientiCount = $clientiCount->listingIndirizzoStradario($cms_pagina->indirizzo_stradario);
                }

                if ($cms_pagina->listing_bonus_vacanze_2020 == 1) {
                    $clientiCount = $clientiCount->listingBonusVacanze($cms_pagina->listing_bonus_vacanze_2020);
                }

                if ($cms_pagina->listing_green_booking > 0) {
                    $clientiCount = $clientiCount->listingGreenBooking($cms_pagina->listing_green_booking);
                }

                if ($cms_pagina->listing_annuali > 0) {
                    $clientiCount = $clientiCount->listingAnnuali($cms_pagina->listing_annuali);
                }

                if ($cms_pagina->listing_eco_sostenibile != "") {
                    $clientiCount = $clientiCount->listingEcoSostenibile($cms_pagina->listing_eco_sostenibile);
                }

                if ($cms_pagina->listing_trattamento != "") {
                    $clientiCount = $clientiCount->listingTrattamentoNew($cms_pagina->listing_trattamento);
                }

                if ($cms_pagina->listing_offerta_prenota_prima != "") {
                    $clientiCount = $clientiCount->listingOffertaPrenotaPrima($cms_pagina->listing_offerta_prenota_prima, $locale);
                }

                if ($cms_pagina->listing_gruppo_servizi_id > 0) {
                    $clientiCount = $clientiCount->listingGruppoServizi($cms_pagina->listing_gruppo_servizi_id, $cms_pagina->listing_macrolocalita_id,$cms_pagina->listing_localita_id);
                }

                if ($cms_pagina->listing_offerta != "") {
                    $clientiCount = $clientiCount->listingOfferta($cms_pagina->listing_offerta, $locale);
                }

                if ($cms_pagina->listing_whatsapp > 0) {
                    $clientiCount = $clientiCount->listingWhatsapp($cms_pagina->listing_whatsapp);
                }

                if ($cms_pagina->listing_bambini_gratis > 0) {
                    $clientiCount = $clientiCount->listingBambiniGratis($cms_pagina->listing_bambini_gratis);
                }

                $clienti = $clientiCount->get();
                Utility::putCache($key, $clienti);

            }

            return $clienti;

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
         * Pagina di ricerca.
         *
         * @access public
         * @param CercaRequest $request
         * @return void
         */

        public function cerca (CercaRequest $request)
        {
            $locale = $this->getLocale();
            $pages = collect([]);
            $hotels = collect([]);
            $da_eliminare = ['di', 'a', 'da', 'in', 'con', 'su', 'per', 'tra', 'fra', 'dal', 'al', 'del', 'della', 'alla', 'x', 'hotel', 'albergo', 'alberghi', 'appartamenti', 'appartamento', 'residence'];
            $da_cercare = $request->get('cerca');

            KeywordRicerca::create(['keyword' => $da_cercare]);
            $token_da_cercare_arr = explode(" ", $da_cercare);

            /**
             * Togliere preposizioni semplici e articolate
             */

            foreach ($da_eliminare as $value_da_eliminare) 
                foreach ($token_da_cercare_arr as $key => $value) 
                    if (strtoupper($value) == strtoupper($value_da_eliminare)) 
                        unset($token_da_cercare_arr[$key]);

            /**
             * tolgo valori vuoti !!! (perché rimangono ???)
             */

            if (count($token_da_cercare_arr))
                foreach ($token_da_cercare_arr as $key => $value)
                    if ($value == '')
                        unset($token_da_cercare_arr[$key]);

            /**
             * Ricerche
             */

            $nome_hotels = $this->_cerca_nome_hotel($token_da_cercare_arr, $locale);

            /**
             * @Sacco 01/08/2019
             * Se id = 1583 la relazione stelle() (Hotel.php) è fatta con la tabella CategoriaFake che visualizza come nome "4stelle SUP"; SE faccio l'eager loading però non è stato ancora instanziato l'hotel con l'id e quindi viene sempre caricata la relazione con la tabella Categoria.
             * Quindi faccio un loop sugli hotel con egarloading e per quello che deve cambiare assegno le stelle chimando la relazione sull'oggetto instanziato
             */

            $nome_hotels->each(function ($c) {
                if (in_array($c->id, Utility::fakeHotel()))
                    $c->setRelation('stelle', Hotel::find($c->id)->stelle);
            });

            $ids_hotel_to_exclude = [];
            if (!is_null($nome_hotels) && $nome_hotels->count()) 
                foreach ($nome_hotels as $hotel) 
                    $ids_hotel_to_exclude[] = $hotel->id;

            $pages_localita = $this->_cerca_pagine_localita($token_da_cercare_arr, $locale);
            $pages          = $this->_cerca_pagine($token_da_cercare_arr, $locale);
            $hotels         = $this->_cerca_hotel($token_da_cercare_arr, $locale, $ids_hotel_to_exclude);
            $offerte        = $this->_cerca_offerte($token_da_cercare_arr, $locale);

            $n_ris = 0;

            foreach (['nome_hotels', 'pages_localita', 'pages', 'hotels', 'offerte'] as $value)
                if (!is_null($$value)) 
                    if (is_array($$value))
                        $n_ris += count($$value);
                    else
                        $n_ris += $$value->count();

            return View::make(

                'search.risultati_ricerca',
                compact(
                    'locale',
                    'da_cercare',
                    'pages_localita',
                    'pages',
                    'hotels',
                    'nome_hotels',
                    'offerte',
                    'n_ris'
                )

            );
        }

        /**
         * Pagina localita
         *
         * @access public
         * @param String $uri
         * @param Request $request
         * @return View
         */

        public function localita($uri, $request)
        {

            /**
             * Elementi comuni tra i tipi di listing
             */

                /**
                 * Parametri e controlli
                 */

                ServerTiming::start('localita');


                $listingArrayParams = Self::_checkPageExist($uri, $request);
                $ip = Utility::get_client_ip();
                $page_number = 0;
                $previous_page_url = null;
                $next_page_url = null;
                $selezione_localita = "";
                $macro_localita_seo = "";
                $localita_seo = "";
                $evidenza_vetrina = true; // Differenzia graficamente le evidenze nella pagina
                $ids_send_mail = []; // lista di TUTTI gli id presenti nel listing
                $hotel_ids = [];
                $prezzo_min = 0;
                $prezzo_max = 0;
                $offerte_count = 0;
                $actual_link = Utility::getCurrentUri();
                $coordinate = Utility::getGenericGeoCoords();
                $google_maps = ["coords" => $coordinate, "hotels" => []];
                $referer = \URL::previous();

                $locale             = $listingArrayParams["locale"];
                $cms_pagina         = $listingArrayParams["cms_pagina"];
                $for_canonical      = $listingArrayParams["for_canonical"];
                $other_pages        = $listingArrayParams["other_pages"];
                $language_uri       = $listingArrayParams["language_uri"];
                $order              = $listingArrayParams["order"];
                $filter             = $listingArrayParams["filter"];
                $page               = $listingArrayParams["page"];

                $briciole = $this->_briciole($cms_pagina);

                /**
                 * Contenuti
                 */

                $cms_pagina->descrizione_2 = Self::_getContent($cms_pagina);

                /**
                 * Vetrine
                 */
                
                $vetrina = Self::_getVetrine($cms_pagina, $order, $filter);

            /**
             * Localita
             */

                /**
                 * Parametri e controlli
                 */

                $localita_ids = [];
                session('last_listing_page', $uri);
                $macro_localita_seo = "Riviera Romagnola";
                $localita_seo = "Riviera Romagnola";
                $menuMacroLocalita = $cms_pagina->menuMacroLocalita;
                $google_maps['macrolocalita_id'] = $cms_pagina->menu_macrolocalita_id;
                $google_maps['localita_id'] = $cms_pagina->menu_localita_id;
                $google_maps['ancora'] = $cms_pagina->ancora;
                $coordinate = Utility::getGenericGeoCoords();

                if ($cms_pagina->menu_macrolocalita_id > 0 && $cms_pagina->menu_localita_id == 0) {
                    $macroloc = Macrolocalita::find($cms_pagina->menu_macrolocalita_id);
                    $macro_localita_seo = $macroloc->nome;
                    $localita_seo = $macroloc->nome;
                    $coordinate = $this->_getGeoCoords($macroloc);
                }

                if ($cms_pagina->menu_localita_id > 0) {
                    $macroloc = Macrolocalita::find($cms_pagina->menu_macrolocalita_id);
                    $loc = Localita::find($cms_pagina->menu_localita_id);
                    $macro_localita_seo = $macroloc->nome;
                    $localita_seo = $loc->nome;
                    $coordinate = $this->_getGeoCoords($macroloc);
                }

                $vedi_tutti_url = 'elenco-hotel/' . $macroloc->linked_file;
                if ($cms_pagina->menu_macrolocalita_id && $cms_pagina->menu_macrolocalita_id == Utility::getMacroRR()) {
                    $vedi_tutti_url = Utility::getLocaleUrl($locale) . 'elenco-hotel/riviera-romagnola.php';
                }

                if ($cms_pagina->menu_macrolocalita_id && $cms_pagina->menu_macrolocalita_id == Utility::getIdMacroPesaro()) {
                    $vedi_tutti_url = Utility::getLocaleUrl($locale) . 'marche/pesaro/elenco-hotel ';
                }


                $google_maps["coords"] = $coordinate;
                $selezione_localita = $localita_seo;

                /**
                 * se ci sono vetrine
                 * gli id a cui spedire sono quelli degli slot delle vetrine
                 */
                
                if ($vetrina) {
                    foreach ($vetrina->slots as $slot) {
                        $hotel_ids[$slot->hotel_id] = $slot->hotel_id;
                        $ids_send_mail[] = $slot->hotel_id;
                    }
                    $google_maps["hotels"] = $vetrina->slots;
                }

                /**
                 * se c'è un listing per categoria
                 * trovo anche i clienti e le metto in un listing SOTTO le vetrine NON FACCIO IL MERGE !!
                 *
                 * Se questa pagina è configurata per avere anche un listing,
                 * estraggo gli hotel definiti dal listing,
                 * per poi togliere quelli definiti dal listing e anche dalla vetrina
                 * $clienti_for_localita sono gli hotel mostrati nella pagina
                 */

                if ($cms_pagina->listing_attivo) {

                    $clienti = Self::_getListing($cms_pagina, $locale, $order, $page);

                    /**
                     * Se ho un ordinamento allora faccio diventare tutti clienti normali
                     * altrimenti estrapolo le vetrine
                     */

                    if ($order == "") {
                        $clienti = $this->_clientiSenzaVetrina($clienti, $vetrina);
                    } else {
                        $vetrina = null;
                    }

                    /**
                     * se ci sono dei clienti in più (ho già controllato che non abbiano la vetrina)
                     * devo aggiungerli agli id a cui spedire
                     *
                     * @Luigi- 060219: nel caso delle località (elenco delle vetrine) sul mobile non c'è il limite a 25, c'è solo sul desktop: oggi ha detto LUCIO DI LASCIARE COSI'!!!
                     */

                    foreach ($clienti->pluck('id') as $id) {
                        if (!in_array($id, $ids_send_mail)) {
                            $ids_send_mail[] = $id;
                        }
                    }

                    /**
                     * Devo aggiungere i clienti del listing alla mappa
                     * PRIMA facevamo un query in WHER IN con gli ID delle vetrine e dei $client
                     * ADESSO torniamo un array marge delle 2 liste quindi avro un ARRAY invece di una Class STD
                     */

                    if (isset($google_maps)) {
                        $google_maps["hotels"] = $this->_clientiEVetrine($clienti, $google_maps["hotels"]);
                    }

                } else {
                    $clienti = collect([]);
                }

                /**
                 * CACHE
                 * Metto in cache tutti valori che mi servono per la pagina
                 */

                $key = "valori_listing_" . $cms_pagina->id . "_" . $locale;

                if (!$valori_listing = Utility::activeCache($key, "Valori listing ( page id: " . $cms_pagina->id . ")")) {

                    $valori_listing = array();
                    $valori_listing["localita_ids"] = "";
                    $valori_listing["prezzo_min"] = 0;
                    $valori_listing["prezzo_max"] = 0;
                    $valori_listing["offerte_count"] = 0;
                    $valori_listing["clienti_count"] = 0;

                    /**
                     * Definisco il contesto geografico su cui estrarrò:
                     * - numero di offerte
                     * - prezzo_min
                     * - prezzo_max
                     *
                     * Se il contesto geografico è riconducibile all'impostazione di località del menu,
                     * preferisco questo al listing, altrimenti uso il listing, altrimenti NON svolgo i placeholder sopra descritti
                     */

                    if ($cms_pagina->menu_localita_id) {
                        $localita_ids[] = $cms_pagina->menu_localita_id;
                    } elseif ($cms_pagina->menu_macrolocalita_id) {
                        $localita_ids = Localita::where("macrolocalita_id", $cms_pagina->menu_macrolocalita_id)->pluck("id", "id");
                    } elseif ($cms_pagina->listing_localita_id) {
                        $localita_ids[] = $cms_pagina->listing_localita_id;
                    } elseif ($cms_pagina->listing_macrolocalita_id) {
                        $localita_ids = Localita::where("macrolocalita_id", $cms_pagina->listing_macrolocalita_id)->pluck("id", "id");
                    }

                    $valori_listing["localita_ids"] = $localita_ids;

                    /*
                    * Ok ora sulla base delle località estratte tiro fuori
                    * - numero di offerte
                    * - prezzo_min
                    * - prezzo_max
                    *
                    *  Attenzione: codice NON-LARAVELISH
                    *  ci vado di query secche perchè altrimenti è troppo costoso
                    */

                    if ($localita_ids && !is_array($localita_ids)) 
                        $localita_ids = $localita_ids->toArray();
                    
                    if ($localita_ids) {

                        $offerte_count = DB::table("tblHotel")
                            ->join('tblOfferte', 'tblOfferte.hotel_id', '=', 'tblHotel.id');

                        if (!isset($localita_ids[Utility::getMicroRR()])) {
                            $offerte_count = $offerte_count->whereIn("localita_id", $localita_ids);
                        }

                        $offerte_count = $offerte_count->where('tblOfferte.attivo', 1)
                            ->where('tblOfferte.valido_al', '>=', date('Y-m-d'))
                            ->select(DB::raw('COUNT(tblOfferte.id) as n'))
                            ->value("n");

                        /**
                         * 01/06/2018 - @Lucio - Escludiamo i residence dal calcolo dei prezzi min e max //
                         */

                        $prices = DB::table("tblHotel")->whereAttivo(1)->where('tipologia_id', '!=', 2);

                        if (!isset($localita_ids[Utility::getMicroRR()])) 
                            $prices = $prices->whereIn("localita_id", $localita_ids);
                        
                        $prices = $prices->where('prezzo_min', '>', 0)
                            ->select(DB::raw('MIN(prezzo_min) prezzo_min, MAX(prezzo_max) prezzo_max'))
                            ->first();

                        $valori_listing["prices"] = $prices;
                        $valori_listing["offerte_count"] = $offerte_count;

                    }

                    //$valori_listing["clienti_count"] = Self::_getListingCount($cms_pagina, $locale)->count();
                    if (count($localita_ids) == 1 && collect($localita_ids)->first() == Utility::getMicroRR()) {
                        $valori_listing["clienti_count"] = Hotel::attivo()->where('localita_id', '!=', Utility::getIdMicroPesaro())->count();
                    } else {
                        $valori_listing["clienti_count"] = Hotel::attivo()->whereIn('localita_id', $localita_ids)->count();
                    }

                    //$valori_listing["clienti_count"] = $clienti->keys()->count();
                    Utility::putCache($key, $valori_listing, Config::get("cache.listing"));
                }

                $prezzo_min = $valori_listing["prices"]->prezzo_min;
                $prezzo_max = $valori_listing["prices"]->prezzo_max;
                $offerte_count = $valori_listing["offerte_count"];
                $n_hotel = $valori_listing["clienti_count"];

                if ($cms_pagina->menu_localita_id == 0 && !$cms_pagina->listing_attivo) 
                    $evidenza_vetrina = false;
                

                /**
                 * Tengo l'aggiornamento dei parametri fuori dalla cache
                 */

                $options_update = [];
                if ($valori_listing["clienti_count"] > 0) $options_update["listing_count"] = $valori_listing["clienti_count"];
                if ($valori_listing["offerte_count"] > 0) $options_update["n_offerte"] = $valori_listing["offerte_count"];
                if ($valori_listing["prices"]->prezzo_min > 0) $options_update["prezzo_minimo"] = $valori_listing["prices"]->prezzo_min;
                if ($valori_listing["prices"]->prezzo_max > 0) $options_update["prezzo_massimo"] = $valori_listing["prices"]->prezzo_max;

                if (!empty($options_update))
                    CmsPagina::where("id", $cms_pagina->id)
                        ->update(
                            $options_update
                        );

                $ids_send_mail = implode(",", $ids_send_mail);

                /**
                 * SEO Content
                 */
                $seoContent = Self::_getSeoContent($cms_pagina, $locale, $n_hotel, $offerte_count, $prezzo_min, $prezzo_max, $localita_seo, $macro_localita_seo) ;
                $menu_tematico = $seoContent["menu_tematico"];
                $titolo = $seoContent["titolo"];
                $testo = $seoContent["testo"];
                $seo_title = $seoContent["seo_title"];
                $seo_description = $seoContent["seo_description"];

                // Hack vetrne igea marina
                if ($localita_seo == "Igea Marina") {
                    $evidenza_vetrina = false;
                }

                ServerTiming::stop('localita');

                return response()
                    ->view(
                        'cms_pagina_localita.localita',
                        compact(
                            'briciole',
                            'titolo',
                            'testo',
                            'seo_title',
                            'seo_description',
                            'menu_tematico',
                            'locale',
                            'cms_pagina',
                            'for_canonical',
                            'macro_localita_seo',
                            'localita_seo',
                            'selezione_localita',
                            'evidenza_vetrina',
                            'google_maps',
                            'ids_send_mail',
                            'vetrina',
                            'clienti',
                            'n_hotel',
                            'offerte_count',
                            'prezzo_min',
                            'prezzo_max',
                            'vedi_tutti_url',
                            'actual_link',
                            'referer',
                            'language_uri',
                            'page_number',
                            'order',
                            'filter'
                        )
                    );

        }

        /**
         * Pagina listing
         *
         * @access public
         * @param String $uri
         * @param Request $request
         * @return View
         */

        public function listing($uri, $request)
        {
            /**
             * Elementi comuni tra i tipi di listing
             */
                /**
                 * Parametri e controlli
                 */
                ServerTiming::start('listing');
                

                $listingArrayParams = Self::_checkPageExist($uri, $request);
                $ip = Utility::get_client_ip();
                $page_number = 0;
                $previous_page_url = null;
                $next_page_url = null;
                $selezione_localita = "";
                $macro_localita_seo = "";
                $localita_seo = "";
                $evidenza_vetrina = true; // Differenzia graficamente le evidenze nella pagina
                $ids_send_mail = []; // lista di TUTTI gli id presenti nel listing
                $hotel_ids = [];
                $prezzo_min = 0;
                $prezzo_max = 0;
                $offerte_count = 0;
                $actual_link = Utility::getCurrentUri();
                $coordinate = Utility::getGenericGeoCoords();
                $google_maps = ["coords" => $coordinate, "hotels" => []];
                $referer = \URL::previous();

                $locale             = $listingArrayParams["locale"];
                $cms_pagina         = $listingArrayParams["cms_pagina"];
                $for_canonical      = $listingArrayParams["for_canonical"];
                $other_pages        = $listingArrayParams["other_pages"];
                $language_uri       = $listingArrayParams["language_uri"];
                $order              = $listingArrayParams["order"];
                $filter             = $listingArrayParams["filter"];
                $page               = $listingArrayParams["page"];

                $briciole = $this->_briciole($cms_pagina);

                /**
                 * Contenuti
                 */

                $cms_pagina->descrizione_2 = Self::_getContent($cms_pagina);

                /**
                 * Vetrine
                 */


                $vetrina = Self::_getVetrine($cms_pagina, $order, $filter);


         
            /**
             * Listing
             */

                /**
                 * Parametri e controlli
                 */

                session('last_listing_page', $uri);
                $array_ids_vot = []; // id dei clienti che hanno il vot
                $array_ids_vaat = []; // id dei clienti che hanno il vaat
                $evidenze_bb = null; //id delle evidenze BB
                $vot = null; // Vetrine OFFERTE TOP
                $vaat = null; // Vetrine BAMBINI GRATIS
                $vtt = null; // Vetrine TRATTAMENTI TOP
                $vst = null; // Vetrine SERVIZI TOP
                $offerta_min = 10000;
                $offerta_last_min = 10000;
                $offerta_speciali_min = 10000;
                $si_offerte = true;
                $n_off_cli = 0;
                $n_last_cli = 0;
                $clienti_count = 0;
                $pagination = false;
                $macro_localita_seo = "Riviera Romagnola";
                $localita_seo = "Riviera Romagnola";
                $coordinate = Utility::getGenericGeoCoords();

                if ($cms_pagina->listing_macrolocalita_id > 0 && $cms_pagina->listing_localita_id == 0) {
                    $macroloc = Macrolocalita::find($cms_pagina->listing_macrolocalita_id);
                    $macro_localita_seo = $macroloc->nome;
                    $localita_seo = $macroloc->nome;
                    $coordinate = $this->_getGeoCoords($macroloc);
                }

                if ($cms_pagina->listing_macrolocalita_id > 0 && $cms_pagina->listing_localita_id > 0) {
                    $macroloc = Macrolocalita::find($cms_pagina->listing_macrolocalita_id);
                    $loc = Localita::find($cms_pagina->listing_localita_id);
                    $macro_localita_seo = $macroloc->nome;
                    $localita_seo = $loc->nome;
                    $coordinate = $this->_getGeoCoords($macroloc);
                }

                $selezione_localita = $localita_seo;

        //Se sono una statica senza listing allora non faccio tutta la parte listing
                ServerTiming::start('clienti listing');

                if ($cms_pagina->listing_attivo) {

                    /**
                     * @luigi 07/01/2019 ATTENZIONE: se per errore nelle pagine riviera-romagnola vengono messe delle OfferteTOP
                     * la view da errore perché è prevista la paginazione ma aggiungento le OfferteTOP viene un oggetto collection
                     * QUINDI CERVO LE TOP solo se NON SONO in una pagina riviera-romagnola
                     *
                     * Devo cercare per URL e non per macro e micro perché ad esempio Fiera ha come micro e macro Riviera Romagnola,
                     * ma ha le evidenze e non ha la paginazione
                     */

                    if (strpos($uri, 'riviera-romagnola') === false) {
                        /**
                         * VERIFICO EVIDENZE BB
                         * ritona gli ID degli hotel che devono avere le evidenze in ordine random !!
                         */

                        if ($order == "" && $filter == "" && $cms_pagina->listing_attivo && $cms_pagina->listing_trattamento == 'bb') {
                            $evidenze_bb = $this->_getEvidenzeBB($cms_pagina, $locale);
                        }

                        /**
                         * SE NON C'E' LA VETRINA, POTREBBERO ESSERCI VOT (se la pagina è vot_enabled)
                         * SE E' UNA PAGINA DI TIPO DIVERSO DA "BAMBINI GRATIS" ed è ABILITATA POTREBBE AVERE DELLE VOT (Vetrine Offerte Top)
                         */

                        if ( /*$order == "" && */$vetrina == null && !$cms_pagina->listing_bambini_gratis && $cms_pagina->vetrine_top_enabled) {
                            $vot = $this->_getVotOnLine($cms_pagina, $locale);
                        }

                        /**
                         * SE E' UNA PAGINA DI TIPO "BAMBINI GRATIS" ed è ABILITATA POTREBBE AVERE DELLE VAAT (Vetrine bAmnini grAtis Top)
                         */

                        if ( /*$order == "" &&*/$vetrina == null && $cms_pagina->listing_bambini_gratis && $cms_pagina->vetrine_top_enabled) {
                            $vaat = $this->_getVaatOnLine($cms_pagina, $locale);
                        }

                        /**
                         * SE NON HA VETRINE NE' E' PREDISPOSTA PER VAAT o VOT, potrebbe avere
                         * - dei trattamenti TOP se è un listing di tipo trattamento
                         * - dei servizi TOP se è un listing di tipo servizio
                         */

                        if ($order == "" && $vetrina == null && !$cms_pagina->vetrine_top_enabled && $cms_pagina->listing_trattamento != '') {
                            $vtt = $this->_getVttOnLine($cms_pagina, $locale);
                        }

                        if ($order == "" && $vetrina == null && !$cms_pagina->vetrine_top_enabled && $cms_pagina->listing_gruppo_servizi_id != 0) {
                            $vst = $this->_getVstOnLine($cms_pagina, $locale);
                        }

                        /**
                         * Se c'è il listing attivo prendo gli hotels
                         */

                        //dd($vot, $vaat, $vtt, $vst);
                    }

                    $clienti = Self::_getListing($cms_pagina, $locale, $order, $page, $filter);

                    $clientiPerConteggio = $clienti;


                    /*dd($clienti->pluck('chiuso_temp','nome'));*/

                    // se ho dei vot
                    // QUELLI CHE CONTENGONO ANCHE I VOT (metodo listingParolaChiaveOfferteAttiveVotAttivi e/o scopeListingOffertaPrenotaPrimaVotPrenotaPrima)
                    // QUINDI AGGIUNGO I VOT A TUTTI GLI HOTEL
                    // SUCCESSIVAMENTE SE NON SONO IN ORDINAMENTO AGGIUNGO 2 VOT in testa e li tolgo dai clienti
                    // MA gli altri hotel che non girano nei primi 2 hanno cmq i VOT
                    if (isset($vot) && !is_null($vot) && $vot->count()) {

                        $clienti = $clienti->keyBy('id');

                        foreach ($vot as $v) {

                            $hotel = $v->offerta->cliente;

                            if (!is_null($hotel)) {

                                // cerco il cliente che corrisponde a questo hotel e gli aggiungo la relazione con il VOT
                                if (!is_null($cliente = $clienti->get($hotel->id))) {
                                    $cliente->setEvidenza($v);
                                }

                            }

                        }

                        /*
                        se devo ordinare per prezzo, ogni cliente può contenere
                        offerta/last/OfferteTop con il campo prezzo_a_persona
                        assegno questo campo a un attributo e poi lo metto come chiave e ordino

                        ATTENZIONE: $cliente->offerteLast e $cliente->offerteTop
                        VANNO SCRITTE COSI SENZA () perché voglio PRENDERE L'OGGETTO
                        IN EAGER LOADING COSI E' GIA' FILTRATO E ORDINATO !!!!

                        */

                        if ($order == 'prezzo_min' || $order == 'prezzo_max') {

                            foreach ($clienti as $cliente) {

                                $prezzo_to_order = 100000;
                                $perc_sconto_to_order = 100;

                                /* Offerte per Parole Chiave e relative Offerte TOP */
                                if ($cms_pagina->listing_parolaChiave_id) {

                                    if ($cliente->offerteLast->count()) {
                                        foreach ($cliente->offerteLast as $offerteLast) {
                                            if ($offerteLast->offerte_lingua->count()) {
                                                $prezzo_to_order = $offerteLast->prezzo_a_persona;
                                                // LA PRIMA offerta con la parte in lingua che trovo è quella minore perché sono ordinate
                                                // APPENA LA TRROVO ESCO DAL foreach (prima andavo direttamente sulla prima ma potrei avere la prima che nopn ha la parte in lingua)
                                                break;
                                            }
                                        }
                                    }

                                    if ($cliente->offerteTop->count()) {
                                        foreach ($cliente->offerteTop as $offerteTop) {
                                            if ($offerteTop->offerte_lingua->count()) {
                                                $prezzo = $offerteTop->prezzo_a_persona;
                                                if ($prezzo < $prezzo_to_order) {
                                                    $prezzo_to_order = $prezzo;
                                                }
                                                // LA PRIMA offerta con la parte in lingua che trovo è quella minore perché sono ordinate
                                                // APPENA LA TRROVO ESCO DAL foreach (prima andavo direttamente sulla prima ma potrei avere la prima che nopn ha la parte in lingua)
                                                break;
                                            }

                                        }
                                    }

                                }
                                /* Offerte per Parole Chiave e relative Offerte TOP */

                                /* Offerte Prenota Prima e relative OfferteTOP */
                                if (!empty($cms_pagina->listing_offerta_prenota_prima)) {

                                    if ($cliente->offertePrenotaPrima->count()) {
                                        foreach ($cliente->offertePrenotaPrima as $offertePrenotaPrima) {
                                            if ($offertePrenotaPrima->offerte_lingua->count()) {
                                                $perc_sconto_to_order = $offertePrenotaPrima->perc_sconto;
                                                // LA PRIMA offerta con la parte in lingua che trovo è quella minore perché sono ordinate
                                                // APPENA LA TRROVO ESCO DAL foreach (prima andavo direttamente sulla prima ma potrei avere la prima che nopn ha la parte in lingua)
                                                break;
                                            }
                                        }
                                    }

                                    if ($cliente->offerteTopPP->count()) {
                                        foreach ($cliente->offerteTopPP as $offerteTopPP) {
                                            if ($offerteTopPP->offerte_lingua->count()) {
                                                $prezzo = $offerteTopPP->perc_sconto;
                                                if ($prezzo < $perc_sconto_to_order) {
                                                    $perc_sconto_to_order = $prezzo;
                                                }
                                                // LA PRIMA offerta con la parte in lingua che trovo è quella minore perché sono ordinate
                                                // APPENA LA TRROVO ESCO DAL foreach (prima andavo direttamente sulla prima ma potrei avere la prima che nopn ha la parte in lingua)
                                                break;
                                            }

                                        }
                                    }

                                }
                                /* Offerte Prenota Prima e relative OfferteTOP */

                                /*Offerte generiche e relative Offerte TOP*/
                                if ($cms_pagina->listing_offerta == 'offerta') {
                                    if ($cliente->offerte->count()) {
                                        foreach ($cliente->offerte as $offerte) {
                                            if ($offerte->offerte_lingua->count()) {
                                                $prezzo_to_order = $offerte->prezzo_a_persona;
                                                // LA PRIMA offerta con la parte in lingua che trovo è quella minore perché sono ordinate
                                                // APPENA LA TRROVO ESCO DAL foreach (prima andavo direttamente sulla prima ma potrei avere la prima che nopn ha la parte in lingua)
                                                break;
                                            }
                                        }
                                    }

                                    if ($cliente->offerteTopOS->count()) {
                                        foreach ($cliente->offerteTopOS as $offerteTopOS) {
                                            if ($offerteTopOS->offerte_lingua->count()) {
                                                $prezzo = $offerteTopOS->prezzo_a_persona;
                                                if ($prezzo < $prezzo_to_order) {
                                                    $prezzo_to_order = $prezzo;
                                                }
                                                // LA PRIMA offerta con la parte in lingua che trovo è quella minore perché sono ordinate
                                                // APPENA LA TRROVO ESCO DAL foreach (prima andavo direttamente sulla prima ma potrei avere la prima che nopn ha la parte in lingua)
                                                break;
                                            }

                                        }
                                    }
                                }

                                if ($cms_pagina->listing_offerta == 'lastminute') {
                                    if ($cliente->last->count()) {
                                        foreach ($cliente->last as $last) {
                                            if ($last->offerte_lingua->count()) {
                                                $prezzo_to_order = $last->prezzo_a_persona;
                                                // LA PRIMA offerta con la parte in lingua che trovo è quella minore perché sono ordinate
                                                // APPENA LA TRROVO ESCO DAL foreach (prima andavo direttamente sulla prima ma potrei avere la prima che nopn ha la parte in lingua)
                                                break;
                                            }
                                        }
                                    }

                                    if ($cliente->offerteTopLast->count()) {
                                        foreach ($cliente->offerteTopLast as $offerteTopLast) {
                                            if ($offerteTopLast->offerte_lingua->count()) {
                                                $prezzo = $offerteTopLast->prezzo_a_persona;
                                                if ($prezzo < $prezzo_to_order) {
                                                    $prezzo_to_order = $prezzo;
                                                }
                                                // LA PRIMA offerta con la parte in lingua che trovo è quella minore perché sono ordinate
                                                // APPENA LA TRROVO ESCO DAL foreach (prima andavo direttamente sulla prima ma potrei avere la prima che nopn ha la parte in lingua)
                                                break;
                                            }

                                        }
                                    }
                                }

                                /*Offerte generiche e relative Offerte TOP*/

                                $cliente->prezzo_to_order = $prezzo_to_order;
                                $cliente->perc_sconto_to_order = $perc_sconto_to_order;

                            }

                            if ($order == 'prezzo_min') {
                                $cms_pagina->listing_offerta_prenota_prima != "" ? $clienti = $clienti->sortBy('perc_sconto_to_order') : $clienti = $clienti->sortBy('prezzo_to_order');
                            } else {
                                $cms_pagina->listing_offerta_prenota_prima != "" ? $clienti = $clienti->sortByDesc('perc_sconto_to_order') : $clienti = $clienti->sortByDesc('prezzo_to_order');
                            }

                        }

                    } // isset($vot) && !is_null($vot) && $vot->count()

                    // se ho dei vaat
                    // QUELLI CHE CONTENGONO ANCHE I VAAT (metodo scopeListingBambiniGratisVaat)
                    // QUINDI AGGIUNGO I VAAT A TUTTI GLI HOTEL
                    // SUCCESSIVAMENTE SE NON SONO IN ORDINAMENTO AGGIUNGO 2 VAAT in testa e li tolgo dai clienti
                    // MA gli altri hotel che non girano nei primi 2 hanno cmq i VAAT

                    if (isset($vaat) && !is_null($vaat) && $vaat->count()) {
                        $clienti = $clienti->keyBy('id');

                        foreach ($vaat as $v) {

                            $hotel = $v->offerta->cliente;

                            if (!is_null($hotel)) {

                                // cerco il cliente che corrisponde a questo hotel e gli aggiungo la relazione con il VOT
                                if (!is_null($cliente = $clienti->get($hotel->id))) {
                                    $cliente->setEvidenza($v);
                                }

                            }

                        }

                    } // isset($vaat) && !is_null($vaat) && $vaat->count()

                    // ADESSO CHE HO TUTTI I CLIENTI COME COLLECTION ED EVENTUALMENTE ORDINATI ANCHE PER PREZZO
                    // SE SONO SU RR CREO UNA PAGINAZIONE

                    if (strpos($cms_pagina->uri, 'italia/hotel_riviera_romagnola') !== false || strpos($cms_pagina->uri, 'riviera-romagnola') !== false) {

                        // this basically gets the request's page variable... or defaults to 1
                        $page = Paginator::resolveCurrentPage('page') ?: 1;

                        $items_per_page = 50;

                        // Assume 15 items per page... so start index to slice our array
                        $startIndex = ($page - 1) * $items_per_page;

                        // Length aware paginator needs a total count of items... to paginate properly
                        $total = $clienti->keys()->count();

                        // Eliminate the non relevant items by slicing the array to page content...
                        $results = $clienti->slice($startIndex, $items_per_page);

                        $clienti = new LengthAwarePaginator($results, $total, $items_per_page, $page, [
                            'path' => Paginator::resolveCurrentPath(),
                            'pageName' => 'page',
                        ]);

                    }

                    /**
                     * Se i dati sono paginati trovo tutti gli oggetti
                     */

                    if ($clienti instanceof Paginator || $clienti instanceof LengthAwarePaginator) {

                        /**
                         * verifico se ho passato un numero di pagina inesistente
                         */

                        if ($request->get('page') && $request->get('page') > $clienti->lastPage()) {
                            return redirect($cms_pagina->uri);
                        }

                        $appends = [];
                        $pagination = true;
                        $page_number = $clienti->currentPage();

                        if ($order != '') {
                            $appends['order'] = $order;
                        }

                        if ($filter != '') {
                            $appends['apertura'] = $filter;
                        }

                        if (count($appends)) {
                            $clienti->appends($appends);
                        }

                        $previous_page_url = $clienti->previousPageUrl();
                        $next_page_url = $clienti->nextPageUrl();

                    }

                    /**
                     * ogni hotel ha una relazione che viene dall'eagerLoading, che per ogni servizio, è del tipo servizio->categoria //
                     * per ogni servizio, se la categoria è NULL, vuole dire che non è un servizio di tipo Listing perché nell'eagerLoading ho il
                     * constraint per categoria.listing = 1
                     *
                     * lista di TUTTI gli id presenti nel listing
                     * LO LIMITO A 25 !!!! @Lucio 20/02/2018
                     */

                    if ($clienti->keys()->count()) {

                        $n_clienti_listing = $clienti->keys()->count();

                        $ids_send_mail = $clienti->pluck('id')->toArray();
                        shuffle($ids_send_mail);

                        // cambio le chiavi con i valori (con gli ID hotel) perché array_rand prende le chiavi (quindi adesso prende random gli ID hotel)
                        $flipped = array_flip($ids_send_mail);

                        $count = count($flipped) < 25 ? count($flipped) : 25;

                        $ids_send_mail = array_rand($flipped, $count);
                    }

                    $google_maps = ["coords" => $coordinate, "hotels" => $clienti];

                } else {
                    $clienti = collect();
                }

                ServerTiming::stop('clienti listing');


                /**
                 * Metto in cache tutti i valore che mi servono per la pagina
                 */

                $key = "valori_listing_" . $cms_pagina->id . "_" . $locale;

                if (!$valori_listing = Utility::activeCache($key, "Valori listing ( page id: " . $cms_pagina->id . ")")) {

                    $valori_listing = array();
                    $valori_listing["prezzo_min"] = 0;
                    $valori_listing["prezzo_max"] = 0;
                    $valori_listing["offerte_count"] = 0;
                    $valori_listing["offerta_min"] = 10000;
                    $valori_listing["offerta_last_min"] = 10000;
                    $valori_listing["offerta_speciali_min"] = 10000;
                    $valori_listing["clienti_count"] = 0;

                    if (!isset($listing_bambini_gratis))
                        $listing_bambini_gratis = 0;
                
                    if ($cms_pagina->listing_attivo) {

                        $valori_listing["listing_gruppo_piscina"] = Utility::getGruppoPiscina();
                        $valori_listing["listing_gruppo_benessere"] = Utility::getGruppoBenessere();
                        $valori_listing["listing_gruppo_piscina_fuori"] = Utility::getGruppoPiscinaFuori();

                        /**
                         * Se sono una piscina o una spa niente offerte
                         */

                        if ($cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoPiscina() || $cms_pagina->listing_gruppo_servizi_id == Utility::getGruppoBenessere()) {
                            $si_offerte = false;
                        }

                        /**
                         * Attenzione il conteggio lo faccio sulla lista totoale
                         */

                        //? $clientiPerConteggio è UNA COIPIA DI CLIENTI 
                         //$clientiPerConteggio = Self::_getListingCount($cms_pagina, $locale);


                        foreach ($clientiPerConteggio as $hotel) {

                            ///////////////////////////////////////////////////////////////////////////////////
                            // 01/06/2018 - @Lucio - Escludiamo i residence dal calcolo dei prezzi min e max //
                            ///////////////////////////////////////////////////////////////////////////////////

                            // se NON SONO UN RESIDENCE OPPURE NON sono nella pagina RESIDENCE
                            if ($hotel->tipologia_id != 2 || $cms_pagina->listing_tipologie == 2) {
                                // Trovo prezzo massimo e minimo
                                if ($hotel->prezzo_max > $valori_listing["prezzo_max"]) {
                                    $valori_listing["prezzo_max"] = $hotel->prezzo_max;
                                }

                                if ($hotel->prezzo_min > 0 && (!$valori_listing["prezzo_min"] || $hotel->prezzo_min < $valori_listing["prezzo_min"])) {
                                    $valori_listing["prezzo_min"] = $hotel->prezzo_min;
                                }

                            }

                            /**
                             * Prendo tutte le offerto e le paragono per trovare quella minima
                             * Le offerte sono sempre al giorno per persona per gli hotel
                             * Per i residence possono essere anche a pacchetto
                             */

                            if ($cms_pagina->listing_offerta == 'lastminute') {

                                $si_offerte = false;
                                $offerte = $hotel->last;
                                $valori_listing["offerte_count"] += $hotel->last->keys()->count();

                                foreach ($offerte as $o) {
                                    if ($o->offerte_lingua->keys()->count() > 0 && $o->prezzo_a_persona < $offerta_last_min) {
                                        $valori_listing["offerta_last_min"] = $o->prezzo_a_persona;
                                    }
                                }

                            } else if ($cms_pagina->listing_offerta == 'offerta') {

                                $si_offerte = false;
                                $offerte = $hotel->offerte;
                                $valori_listing["offerte_count"] += $hotel->offerte->keys()->count();

                                foreach ($offerte as $o) {
                                    if ($o->offerte_lingua->keys()->count() > 0 && $o->prezzo_a_persona < $offerta_speciali_min) {
                                        $valori_listing["offerta_speciali_min"] = $o->prezzo_a_persona;
                                    }
                                }

                            } else if ($cms_pagina->listing_parolaChiave_id) {

                                $offerte = $hotel->offerteLast;

                                foreach ($offerte as $o) {
                                    if ($o->offerte_lingua->keys()->count() > 0 && $o->prezzo_a_persona < $valori_listing["offerta_min"]) {
                                        $valori_listing["offerta_min"] = $o->prezzo_a_persona;
                                    }
                                }

                            } elseif ($cms_pagina->listing_offerta_prenota_prima) {

                                $si_offerte = false;
                                $offerte = $hotel->offertePrenotaPrima;
                                $valori_listing["offerte_count"] += $hotel->offertePrenotaPrima->keys()->count();

                                foreach ($offerte as $o) {
                                    if ($o->offerte_lingua->keys()->count() > 0 && $o->prezzo_a_persona < $valori_listing["offerta_min"]) {
                                        $valori_listing["offerta_min"] = $o->prezzo_a_persona;
                                    }
                                }

                            } elseif ($cms_pagina->listing_bambini_gratis > 0) {

                                $si_offerte = false;
                                $offerte = $hotel->bambiniGratisAttivi;
                                $valori_listing["offerte_count"] += $hotel->bambiniGratisAttivi->keys()->count();

                                foreach ($offerte as $o)
                                //if ( $o->offerte_lingua->count() >0 && $o->prezzo_a_persona < $valori_listing["offerta_min"])
                                {
                                    $valori_listing["offerta_min"] = $o->prezzo_a_persona;
                                }

                            }

                            if ($si_offerte && isset($offerte)) {

                                foreach ($offerte as $o) {
                                    if ($o->offerte_lingua->keys()->count() > 0) {
                                        $valori_listing["offerte_count"] += $o->offerte_lingua->keys()->count();
                                    }
                                }

                            } else if ($si_offerte && !isset($offerte)) {

                                $hotel->numero_offerte_attive->isNotEmpty() ? $valori_listing["offerte_count"] += $hotel->numero_offerte_attive->first()->tot : "";
                                $hotel->numero_last_attivi->isNotEmpty() ? $valori_listing["offerte_count"] += $hotel->numero_last_attivi->first()->tot : "";
                                $hotel->numero_pp_attivi->isNotEmpty() ? $valori_listing["offerte_count"] += $hotel->numero_pp_attivi->first()->tot : "";

                            }

                        }

                        $valori_listing["offerta_min"] == 10000 ? $valori_listing["offerta_min"] = 0 : "";
                        $valori_listing["offerta_last_min"] == 10000 ? $valori_listing["offerta_last_min"] = 0 : "";
                        $valori_listing["offerta_speciali_min"] == 10000 ? $valori_listing["offerta_speciali_min"] = 0 : "";

                        if (isset($n_clienti_listing)) {
                            $valori_listing["clienti_count"] = $n_clienti_listing;
                        } else {
                            $valori_listing["clienti_count"] = $clientiPerConteggio->keys()->count();
                        }

                    } else {

                        $valori_listing["listing_gruppo_piscina"] = 0;
                        $valori_listing["listing_gruppo_benessere"] = 0;
                        $valori_listing["listing_gruppo_piscina_fuori"] = 0;

                    }

                    Utility::putCache($key, $valori_listing, Config::get("cache.listing"));

                }

                $prezzo_min = $valori_listing["prezzo_min"];
                $prezzo_max = $valori_listing["prezzo_max"];
                $offerta_min = $valori_listing["offerta_min"];
                $offerte_count = $valori_listing["offerte_count"];

                if (isset($clientiPerConteggio)) 
                    {
                    $n_hotel = $clientiPerConteggio->keys()->count();
                    } 
                else 
                    {
                    $n_hotel = 0;
                    }

                $listing_gruppo_piscina = $valori_listing["listing_gruppo_piscina"];
                $listing_gruppo_benessere = $valori_listing["listing_gruppo_benessere"];
                $listing_gruppo_piscina_fuori = $valori_listing["listing_gruppo_piscina_fuori"];

                /* ---------------------------------------------------------------------------------------------
                 * LISTING CON VETRINE
                 * ---------------------------------------------------------------------------------------------
                 *
                 * se è un listing per categoria devo farte il merge dei clienti e delle vetrine
                 * E aggiungo le vetrine alle google maps, ANZI passo alla google maps la collection di clienti e/o slot e si arrangia lei
                 */ 

                /*
                 * Nota: duplico la lista dei clienti
                 * Se ho un pagina da paginare con delle vetrine l'oggetto chienti si modifica e non riesco più a fare la paginazione
                 * Per questo me ne creo una copia per poter paginare e una sul quale fare il loop
                 *
                 * $clienti_with_vetrine la uso per il loop
                 * $clienti solo per la paginazione
                 */
                
                $cms_pagina->evidenza_bb = 0;

                if ($cms_pagina->listing_attivo) {

                    if ($cms_pagina->listing_categorie && !is_null($vetrina)) {

                        if ($order == "" || $order == "0")
                            $clienti = $this->_mergeListingClientiSlotVetrine($clienti, $vetrina, $cms_pagina, $locale);

                        $google_maps["hotels"] = $clienti;

                    } elseif (isset($vst) && !is_null($vst) && $vst->keys()->count()) {

                        $v = new VetrinaServiziTopLinguaController();

                        /**
                         * attenzione modifica da togliere per far girare momentaneamente 3 evidenze sulla pagina "hotel-parcheggio/rimini.php
                         * e le eventuali pagine in lungua se e quando saranno create
                         * $vst = $v->updatePointer($vst);
                         */

                        $vst = $v->updatePointer($vst, $cms_pagina->uri);

                        $clienti = $this->_addVetrinaTop($clienti, $vst);

                    }

                    /*
                    * se ci sono delle vetrine offerte top le devo mettere IN TESTA AL LISTING DEI CLIENTI
                    */

                    elseif (isset($vot) && !is_null($vot) && $vot->keys()->count()) {

                        $v = new VetrinaOfferteTopLinguaController();

                        $old_vot = $vot;

                        $vot = $v->updatePointer($vot);

                        /////////////////////////////////////////////////////////
                        // trovo i vot che sono NASCOSTI                                              //
                        // cioè i vecchi vot che NON sono tra i nuovi visibili //
                        /////////////////////////////////////////////////////////
                        foreach ($old_vot as $old_key => $old_v) {
                            $old_v_id = $old_v->id;
                            foreach ($vot as $key => $v) {
                                if ($old_v_id == $v->id) {
                                    unset($old_vot[$old_key]);
                                    break;
                                }
                            }
                        }

                        // aggiungo i VOT in testa SOLO se NON ORDINO e NON FILTRO
                        if ($order == '' && $filter == '') {
                            $clienti = $this->_addVotConOfferte($clienti, $vot, $cms_pagina);
                        }

                        foreach ($vot as $v) {

                            $array_ids_vot[] = $v->id;

                            // Aggiorno il conteggio delle offerte se ho delle evidenze
                            // $offerte_count += count($v->offerta);
                            // 1 offertaTopLingua appartiene a 1 offerta
                            $offerte_count += 1;

                        }

                    } elseif (isset($vaat) && !is_null($vaat) && $vaat->keys()->count()) {

                        $v = new VetrinaBambiniGratisTopLinguaController();

                        $old_vaat = $vaat;

                        $vaat = $v->updatePointer($vaat);

                        /////////////////////////////////////////////////////////
                        // trovo i vot che sono NASCOSTI                                              //
                        // cioè i vecchi vot che NON sono tra i nuovi visibili //
                        /////////////////////////////////////////////////////////
                        foreach ($old_vaat as $old_key => $old_v) {
                            $old_v_id = $old_v->id;
                            foreach ($vaat as $key => $v) {
                                if ($old_v_id == $v->id) {
                                    unset($old_vaat[$old_key]);
                                    break;
                                }
                            }
                        }

                        // aggiungo i VOT in testa SOLO se NON ORDINO
                        if ($order == '') {
                            $clienti = $this->_addVotConOfferte($clienti, $vaat, $cms_pagina);
                        }

                        foreach ($vaat as $v) {

                            $array_ids_vaat[] = $v->id;

                            // Aggiorno il conteggio delle offerte se ho delle evidenze
                            //$offerte_count += count($v->offerta);
                            // 1 offertaTopLingua appartiene a 1 offerta
                            $offerte_count += 1;
                        }

                    } elseif (isset($vtt) && !is_null($vtt) && $vtt->keys()->count()) {

                        $v = new VetrinaTrattamentoTopLinguaController();
                        $vtt = $v->updatePointer($vtt);

                        $clienti = $this->_addVetrinaTop($clienti, $vtt);

                    } elseif (isset($evidenze_bb) && !is_null($evidenze_bb) && count($evidenze_bb) && !$pagination) {

                        /**
                         * metto le evidenze in cima in modo random (NON SCALANO DI 1 tra di loro perché non ho la struttura con il campo pointer)
                         * li sostituisco ai clienti
                         */
                        $cms_pagina->evidenza_bb = 1;
                        $clienti = $this->_addEvidenzeBBTop($clienti, $evidenze_bb, $cms_pagina, $locale);

                    }

                    /**
                     * Aggiornamento del numero hotel risultanti dal listing (solo se è cambiato).
                     */

                    // if (isset($n_hotel) /*&& $n_hotel > 0*/) {

                    $options_update = [];
                    if ($n_hotel > 0) $options_update["listing_count"] = $n_hotel;
                    if ($offerte_count > 0) $options_update["n_offerte"] = $offerte_count;
                    if ($prezzo_min > 0) $options_update["prezzo_minimo"] = $prezzo_min;
                    if ($prezzo_max > 0) $options_update["prezzo_massimo"] = $prezzo_max;
    
                    if (!empty($options_update))
                        CmsPagina::where("id", $cms_pagina->id)
                            ->update(
                                $options_update
                            );

                    // }

                }

                /**
                 * SEO Content
                 */

                $seoContent = Self::_getSeoContent($cms_pagina, $locale, $n_hotel, $offerte_count, $prezzo_min, $prezzo_max, $localita_seo, $macro_localita_seo);
                $menu_tematico = $seoContent["menu_tematico"];
                $titolo = $seoContent["titolo"];
                $testo = $seoContent["testo"];
                $seo_title = $seoContent["seo_title"];
                $seo_description = $seoContent["seo_description"];

                if (is_array($ids_send_mail))
                    $ids_send_mail = implode(",", $ids_send_mail);

                /**
                 * Conteggio offerte in evidenza
                 * se sono nel periodo dell'offerta in evidenza AND
                 *
                 * sono un listing per categorie
                 * OR
                 * sono un listing servizio
                 */

                $id_offerta = Utility::checkOfferteInEvidenza($cms_pagina->lang_id);
                if ($id_offerta && (!empty($cms_pagina->listing_categorie) || $cms_pagina->listing_gruppo_servizi_id > 0)) {

                    $parola_chiave = ParolaChiave::with("alias")->find($id_offerta);
                    if (isset($parola_chiave->alias)) {
                        foreach ($parola_chiave->alias as $term) {
                            $terms[] = $term->chiave;
                        }
                    }

                    foreach ($clienti as $cliente) {

                        // ogni cliente può essere SlotVetrina oppure Hotel
                        if ($cliente instanceof App\Hotel) {

                            $cliente->n_off_in_evidenza = 0;

                            // cerco nelle offerte e nei last
                            foreach ($cliente->offerteLast as $offerta) {
                                if (!is_null($offerta->offerte_lingua->first())) {
                                    $off_l = $offerta->offerte_lingua->first();
                                    if (App\OffertaLingua::daOffertaAttiva()->multiTestoOrTitoloLike($terms)->where('id', $off_l->id)->get()->keys()->count()) {
                                        $cliente->n_off_in_evidenza++;
                                    }
                                    //dd($offerta->offerte_lingua->first());
                                }
                            }

                            // cerco nelle offerte TOP
                            foreach ($cliente->offerteTop as $offerta) {
                                if (!is_null($offerta->offerte_lingua->first())) {
                                    $off_l = $offerta->offerte_lingua->first();
                                    if (App\VetrinaOffertaTopLingua::daOffertaAttiva()->multiTestoOrTitoloLike($terms)->where('id', $off_l->id)->get()->keys()->count()) {
                                        $cliente->n_off_in_evidenza++;
                                    }
                                    //dd($offerta->offerte_lingua->first());
                                }
                            }

                            // if($cliente->id == 17) dd($cliente->offerteLast);

                        } elseif ($cliente instanceof App\SlotVetrina) {
                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            //ATTENZIONE QUI NON C'E' l'EAGER LOADING che ha già filtrato le offerte con la parola chiave in evidenza //
                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            // ogni cliente può essere SlotVetrina oppure Hotel

                            $slot_vetrina = $cliente;

                            if (!is_null($slot_vetrina->cliente)) {

                                $slot_vetrina->cliente->n_off_in_evidenza = 0;

                                // cerco nelle offerte e nei last
                                foreach ($slot_vetrina->cliente->offerteLast as $offerta) {
                                    if (!is_null($offerta->offerte_lingua->first())) {
                                        $off_l = $offerta->offerte_lingua->first();
                                        if (App\OffertaLingua::daOffertaAttiva()->multiTestoOrTitoloLike($terms)->where('id', $off_l->id)->get()->keys()->count()) {
                                            $slot_vetrina->cliente->n_off_in_evidenza++;
                                        }
                                        //dd($offerta->offerte_lingua->first());
                                    }
                                }

                                // cerco nelle offerte TOP
                                foreach ($slot_vetrina->cliente->offerteTop as $offerta) {
                                    if (!is_null($offerta->offerte_lingua->first())) {
                                        $off_l = $offerta->offerte_lingua->first();
                                        if (App\VetrinaOffertaTopLingua::multiTestoOrTitoloLike($terms)->where('id', $off_l->id)->get()->keys()->count()) {
                                            $slot_vetrina->cliente->n_off_in_evidenza++;
                                        }
                                        //dd($offerta->offerte_lingua->first());
                                    }
                                }

                                //if($slot_vetrina->cliente->id == 17) dd($slot_vetrina->cliente->offerteLast);
                            }

                        }

                    }

                }

                $google_maps['clienti_ids'] = implode(',', $clienti->pluck('id')->toArray());
                $google_maps['ancora'] = $cms_pagina->ancora;

                $log_view = Config::get("logging.log_view");

                ServerTiming::stop('listing');

                 
                return response()
                    ->view(
                        "cms_pagina_listing.listing_hotel",
                        compact(
                            'briciole',
                            'locale',
                            'cms_pagina',
                            'for_canonical',
                            'language_uri',
                            'actual_link',
                            'referer',
                            'macro_localita_seo',
                            'localita_seo',
                            'google_maps',
                            'selezione_localita',
                            'evidenza_vetrina',
                            'titolo',
                            'testo',
                            'menu_tematico',
                            'seo_title',
                            'seo_description',

                            'listing_gruppo_piscina',
                            'listing_gruppo_benessere',
                            'listing_gruppo_piscina_fuori',
                            'id_offerta',

                            'ids_send_mail',
                            'clienti',
                            'clienti_count',
                            'array_ids_vot',
                            'array_ids_vaat',

                            'offerte_count',
                            'prezzo_min',
                            'prezzo_max',
                            'pagination',
                            'page_number',
                            'previous_page_url',
                            'next_page_url',
                            'order',
                            'filter',

                            'log_view'
                        )
                    );

        }

        /* ------------------------------------------------------------------------------------
         * MAPPE
         * ------------------------------------------------------------------------------------ */

        /*+
        * Mappa le pagine listing
        * @access public
        * @param String $uri
        * @param Request $request
        * @return VIEW
        */

        public function mappa($uri, Request $request)
        {

            return View::make('templates.mappa',
                compact(
                    'uri'
                )
            );

        }

        public function mappaHotel($hotel_id, Request $request)
        {
            $locale = $this->getLocale();

            $cliente = Hotel::with(['stelle', 'localita.macrolocalita'])->findOrFail($hotel_id);
            return View::make('templates.mappa-hotel',
                compact(
                    'cliente',
                    'locale'
                )
            );
        }

        public function mappaRicerca(Request $request)
        {

            $servizi_singoli_ids = Servizio::inRicercaMappa()->pluck('id')->toArray();
            $locale = $this->getLocale();
            $lat = 0;
            $long = 0;
            $macrolocalita_id = 0;
            $localita_id = 0;
            $annuale = 0;
            $categorie = [];
            $gruppi_servizi = [];
            $servizi_non_gruppo = [];
            $clienti_ids_arr = [];
            $clienti_ids = "";
            $ancora = "";
            $localita = "";
            $localita_seo = "";
            $macro_localita_seo = "";
            $cms_pagina_id = 0;
            $adding_group_id = 0;
            $trattamenti = [];
            $parola_chiave = [];
            $listing_offerta_prenota_prima = "";
            $crea_check_pp = "";
            $listing_bambini_gratis = "";
            $crea_check_bg = "";
            $listing_green_booking = "";
            $listing_bonus_vacanze_2020 = "";
            $crea_check_green = "";
            $crea_check_bonus = "";
            $listing_eco_sostenibile = "";
            $crea_check_eco = "";
            $listing_tipologie = "";
            $crea_check_residence = "";
            $reception_24h = 0;
            $label = "";
            $title = ucfirst(Lang::get('listing.vedi_mappa'));
            $start_label = "";
            $parola_chiave_check = null;
            $prenota_prima_check = null;
            $bambini_gratis_check = null;
            $green_check = null;
            $bonus_check = null;
            $eco_check = null;

            /*
            Strutture sempre presenti
            */

            $stelle = Categoria::real()->orderBy('ordinamento')->pluck('nome', 'id');

            //////////////////////////////////////////////////////////////////
            // come servizi di default lavoriamo solo con i servizi Singoli //
            // come gruppo prendiamo solo "Parcheggio"                                            //
            //////////////////////////////////////////////////////////////////
            $gruppi = GruppoServizi::ricercaMappa()->orderBy('order_ricerca_mappa')->get();

            $gruppi_mappa_ids = $gruppi->pluck('id')->toArray();

            /////////////////////////////////////
            // servizi SINGOLI NON RAGGRUPPATI //
            /////////////////////////////////////
            $servizi_s = Servizio::with(
                [
                    'servizi_lingua' => function ($query) use ($locale) {
                        $query->where('lang_id', '=', $locale);

                    },
                ])
                ->whereIn('id', $servizi_singoli_ids)
                ->get();

            foreach ($servizi_s as $s) {
                if ($s->getNomeLocale($locale) != '') {
                    $servizi_singoli[$s->id] = $s->getNomeLocale($locale);
                } else {
                    $servizi_singoli[$s->id] = $s->servizi_lingua->first()->nome;
                }
            }
            /////////////////////////////////////
            // servizi SINGOLI NON RAGGRUPPATI //
            /////////////////////////////////////

            ///////////////////////////////////////////////
            // PAROLE CHIAVE  PER OFFERTE SEMPRE PRESENTI//
            //////////////////////////////////////////////
            $kw_arr = [];

            $kw = ParolaChiave::where('ricerca_mappa', 1)->orderBy('order_ricerca_mappa')->get();

            foreach ($kw as $p) {
                if ($p->isValidInMappa()) {
                    $kw_arr[$p->id] = $p->getNomeLocale($locale);
                }
            }

            ///////////////////////////////////////////////
            // PAROLE CHIAVE  PER OFFERTE SEMPRE PRESENTI//
            //////////////////////////////////////////////

            // default canonical uri
            $request->request->add(['canonical_uri' => '/']);

            // default cms_pagina_uri uri
            $cms_pagina_uri = '/';

            /*
            Strutture sempre presenti
            */

            if ($request->ajax() || $request->isMethod('post') || $request->isMethod('get')) {

                ////////////////////////////////////////////////
                // PASSO LA cms_pagina_id SOLO LA PRIMA VOLTA //
                ////////////////////////////////////////////////
                if ($request->filled('cms_pagina_id') && strval($request->get('cms_pagina_id')) == strval(intval($request->get('cms_pagina_id')))) {

                    /////////////////////////
                    // NOME della località //
                    /////////////////////////
                    if ($request->filled('localita_seo')) {
                        $localita_seo = $request->get('localita_seo');
                    }

                    if ($request->filled('macro_localita_seo')) {
                        $macro_localita_seo = $request->get('macro_localita_seo');
                    }

                    if ($localita_seo != "") {
                        $start_label .= $localita_seo;
                    } else {
                        $start_label .= $macro_localita_seo;
                    }

                    $request->request->add(['start_label' => $start_label]);

                    $cms_pagina_id = $request->get('cms_pagina_id');

                    if ($cms_pagina_id) {
                        $cms_pagina = CmsPagina::find($cms_pagina_id);

                        // Se non esiste la pagina do un 404
                        if (!isset($cms_pagina)) {
                            abort("404");
                        }

                        // verifico che pagina è per mettere i filtri di default nella mappa

                        //////////////
                        // LOCALITA //
                        //////////////
                        if ($cms_pagina->listing_macrolocalita_id > 0) {
                            //////////////////////////////////////////////////
                            // lo metto in requesto per ricompilare il form //
                            //////////////////////////////////////////////////
                            $request->request->add(['macrolocalita_id' => $cms_pagina->listing_macrolocalita_id]);
                        }

                        if ($cms_pagina->listing_localita_id > 0) {
                            //////////////////////////////////////////////////
                            // lo metto in requesto per ricompilare il form //
                            //////////////////////////////////////////////////
                            $request->request->add(['localita_id' => $cms_pagina->listing_localita_id]);

                        }

                        if ($cms_pagina->uri != '') {
                            //////////////////////////////////////////////////
                            // lo metto in requesto per ricompilare il form //
                            //////////////////////////////////////////////////
                            $request->request->add(['cms_pagina_uri' => $cms_pagina->uri]);
                        }

                        // se il template è 'località' allora il canonical è l'uri della cms_pagine
                        // altrimenti lo devo indovinare da: listing_macrolocalita_id, listing_localita_id e lang_id

                        if ($cms_pagina->template == 'localita') {
                            $request->request->add(['canonical_uri' => $cms_pagina->uri]);
                        } else {

                            $cms_pagina_for_canonical = CmsPagina::attiva()->where('template', 'localita')->where('lang_id', $cms_pagina->lang_id);
                            $cms_pagina_for_canonical->where('menu_localita_id', $cms_pagina->listing_localita_id);
                            $cms_pagina_for_canonical->where('menu_macrolocalita_id', $cms_pagina->listing_macrolocalita_id);
                            $cms_pagina_for_canonical = $cms_pagina_for_canonical->first();

                            if (!is_null($cms_pagina_for_canonical)) {
                                $request->request->add(['canonical_uri' => $cms_pagina_for_canonical->uri]);
                            } else {
                                // provo a cercarla solo con la macro
                                $cms_pagina_for_canonical = CmsPagina::attiva()->where('template', 'localita')->where('lang_id', $cms_pagina->lang_id);
                                $cms_pagina_for_canonical->where('menu_macrolocalita_id', $cms_pagina->listing_macrolocalita_id);
                                $cms_pagina_for_canonical = $cms_pagina_for_canonical->first();

                                if (!is_null($cms_pagina_for_canonical)) {
                                    $request->request->add(['canonical_uri' => $cms_pagina_for_canonical->uri]);
                                } else {
                                    $request->request->add(['canonical_uri' => 'italia/hotel_riviera_romagnola.html']);
                                }
                            }

                        }

                        ///////////////
                        // CATEGORIA //
                        ///////////////
                        if ($cms_pagina->listing_categorie) {
                            //////////////////////////////////////////////////
                            // lo metto in requesto per ricompilare il form //
                            //////////////////////////////////////////////////
                            $request->request->add(['categorie' => explode(',', $cms_pagina->listing_categorie)]);

                        }

                        if ($cms_pagina->listing_tipologie && $cms_pagina->listing_tipologie == '2') {
                            //////////////////////////////////////////////////
                            // lo metto in requesto per ricompilare il form //
                            //////////////////////////////////////////////////
                            $request->request->add(['listing_tipologie' => $cms_pagina->listing_tipologie]);

                        }

                        /////////////
                        // SERVIZI //
                        /////////////
                        if ($cms_pagina->listing_gruppo_servizi_id) {

                            ////////////////////////////////////////////////////////
                            // SE non appartiene ai gruppi di ricerca lo aggiungo //
                            ////////////////////////////////////////////////////////
                            if (!in_array($cms_pagina->listing_gruppo_servizi_id, $gruppi_mappa_ids)) {
                                $adding_group_id = $cms_pagina->listing_gruppo_servizi_id;
                                $adding_group = GruppoServizi::find($adding_group_id);
                                $gruppi->push($adding_group);
                            }

                            //////////////////////////////////////////////////
                            // lo metto in requesto per ricompilare il form //
                            //////////////////////////////////////////////////
                            $request->request->add(['gruppi_servizi' => explode(',', $cms_pagina->listing_gruppo_servizi_id)]);

                        }

                        ////////////
                        // ANCORA //
                        ////////////
                        $ancora = $cms_pagina->ancora;

                        //////////////
                        // LOCALITA //
                        //////////////
                        if ($cms_pagina->listing_localita_id > 0) {
                            $localita = $cms_pagina->listingLocalita->nome;

                        } elseif ($cms_pagina->listing_macrolocalita_id > 0) {
                            $localita = $cms_pagina->listingMacroLocalita->nome;

                        }

                    }

                    /////////////////
                    // TRATTAMENTI //
                    /////////////////

                    if ($cms_pagina->listing_trattamento) {
                        //////////////////////////////////////////////////
                        // lo metto in requesto per ricompilare il form //
                        //////////////////////////////////////////////////
                        $request->request->add(['trattamenti' => explode(',', $cms_pagina->listing_trattamento)]);
                    }

                    /////////////////////////////
                    // OFFERTE - PAROLE CHIAVE //
                    /////////////////////////////
                    if ($cms_pagina->listing_parolaChiave_id) {

                        //////////////////////////////////////////////////////////////////////////////
                        // lo metto in requesto per ricompilare il form SE NON E' TRA LE KW FISSE   //
                        // se è tra le fisse la metto comunque in request per trovarla già checkata //
                        //////////////////////////////////////////////////////////////////////////////
                        $request->request->add(['parola_chiave' => explode(',', $cms_pagina->listing_parolaChiave_id)]);
                        if (!array_key_exists($cms_pagina->listing_parolaChiave_id, $kw_arr)) {
                            $request->request->add(['crea_check_off' => $cms_pagina->listing_parolaChiave_id]);
                        }

                    }

                    ///////////////////////////
                    // OFFERTE PRENOTA PRIMA //
                    ///////////////////////////
                    if ($cms_pagina->listing_offerta_prenota_prima) {
                        $request->request->add(['listing_offerta_prenota_prima' => $cms_pagina->listing_offerta_prenota_prima]);
                        $request->request->add(['crea_check_pp' => $cms_pagina->listing_offerta_prenota_prima]);
                    }

                    if ($cms_pagina->listing_bambini_gratis) {
                        //////////////////////////////////////////////////
                        // lo metto in requesto per ricompilare il form //
                        //////////////////////////////////////////////////
                        $request->request->add(['listing_bambini_gratis' => $cms_pagina->listing_bambini_gratis]);
                        $request->request->add(['crea_check_bg' => $cms_pagina->listing_bambini_gratis]);
                    }

                    ///////////////////
                    // GREEN BOOKING //
                    ///////////////////
                    if ($cms_pagina->listing_green_booking) {
                        //////////////////////////////////////////////////
                        // lo metto in requesto per ricompilare il form //
                        //////////////////////////////////////////////////
                        $request->request->add(['listing_green_booking' => $cms_pagina->listing_green_booking]);
                        $request->request->add(['crea_check_green' => $cms_pagina->listing_green_booking]);
                    }

                    ///////////////////
                    // BONUS VACANZE //
                    ///////////////////
                    if ($cms_pagina->listing_bonus_vacanze_2020) {
                        //////////////////////////////////////////////////
                        // lo metto in requesto per ricompilare il form //
                        //////////////////////////////////////////////////
                        $request->request->add(['listing_bonus_vacanze_2020' => $cms_pagina->listing_bonus_vacanze_2020]);
                        $request->request->add(['crea_check_bonus' => $cms_pagina->listing_bonus_vacanze_2020]);
                    }

                    /////////////////////
                    // ECO SOTENIBILE  //
                    /////////////////////
                    if ($cms_pagina->listing_eco_sostenibile) {
                        //////////////////////////////////////////////////
                        // lo metto in requesto per ricompilare il form //
                        //////////////////////////////////////////////////
                        $request->request->add(['listing_eco_sostenibile' => $cms_pagina->listing_eco_sostenibile]);
                        $request->request->add(['crea_check_eco' => $cms_pagina->listing_eco_sostenibile]);
                    }
                }
                // if c'è cms_pagina_id

                /*echo "<pre>";
                print_r($request->all());
                echo "</pre>";*/

                // se non ho settato il trattamento dalla pagina cms lo faccio adesso
                if (!$request->filled('trattamenti')) {
                    if (Utility::isValidMappaServizio("trattamenti")) {
                        $request->request->add(['trattamenti' => ['1']]);
                    }
                }

                if ($request->filled('start_label')) {
                    $start_label = $request->get('start_label');
                    $title .= ' ' . $start_label;
                    //$label .= '<a class="label">'.$request->get('start_label').'</a>';
                }

                if ($request->filled('cms_pagina_uri')) {
                    $cms_pagina_uri = $request->get('cms_pagina_uri');
                }

                if ($request->filled('canonical_uri')) {
                    $canonical_uri = $request->get('canonical_uri');
                }

                if ($request->filled('lat')) {
                    $lat = $request->get('lat');
                    if (!is_numeric($lat)) {
                        Session::flash('flash_message', 'Alcuni parametri forniti alla mappa sono errati!');
                        Session::flash('flash_message_important', true);
                        return redirect('/', 301);
                    }
                }

                if ($request->filled('long')) {
                    $long = $request->get('long');
                    if (!is_numeric($long)) {
                        Session::flash('flash_message', 'Alcuni parametri forniti alla mappa sono errati!');
                        Session::flash('flash_message_important', true);
                        return redirect('/', 301);
                    }
                }

                if ($request->filled('ancora')) {
                    $ancora = $request->get('ancora');
                }

                if ($request->filled('macrolocalita_id')) {
                    $macrolocalita_id = $request->get('macrolocalita_id');
                }

                if ($request->filled('localita')) {
                    $localita = $request->get('localita');
                }

                if ($request->filled('adding_group_id') && $request->get('adding_group_id') > 0) {
                    $adding_group_id = $request->get('adding_group_id');

                    ////////////////////////////////////////////////////////
                    // SE non appartiene ai gruppi di ricerca lo aggiungo //
                    ////////////////////////////////////////////////////////
                    $adding_group = GruppoServizi::find($adding_group_id);
                    $gruppi->push($adding_group);
                }

                if ($request->filled('localita_id')) {
                    $localita_id = $request->get('localita_id');
                }

                if ($request->filled('listing_tipologie')) {
                    $listing_tipologie = $request->get('listing_tipologie');
                    $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">Residence</a>';
                }

                if ($request->filled('categorie')) {
                    $stelle_arr = $stelle->toArray();
                    $stelle_label_arr = [];
                    $categorie = $request->get('categorie');
                    foreach ($categorie as $cat) {
                        $stelle_label_arr[] = $stelle_arr[$cat];
                    }
                    $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">' . ucfirst(Lang::get('labels.menu_cat')) . ':</a> <span class=" stelle">' . implode('| ', $stelle_label_arr) . '</span>';
                }

                if ($request->filled('trattamenti')) {
                    $trattamenti = $request->get('trattamenti');
                    $t_arr = ["ai" => trans('hotel.trattamento_ai'), "pc" => trans('hotel.trattamento_pc'), "mp" => trans('hotel.trattamento_mp'), "bb" => trans('hotel.trattamento_bb'), "sd" => trans('hotel.trattamento_sd')];
                    $t_arr_for_label = [];
                    foreach ($trattamenti as $value) {
                        if (key_exists($value, $t_arr)) {
                            $t_arr_for_label[] = $t_arr[$value];
                        }
                    }
                    if (count($t_arr_for_label)) {
                        $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">' . ucfirst(Lang::get('labels.menu_trat')) . ':</a> ' . implode(',', $t_arr_for_label);
                    }
                }

                if ($request->filled('parola_chiave')) {
                    $parola_chiave = $request->get('parola_chiave');
                    $all_kw = ParolaChiave::whereIn('id', $parola_chiave)->get();

                    $kw_for_label = [];

                    foreach ($all_kw as $kw) {
                        $kw_for_label[] = $kw->getNomeLocale($locale);
                    }
                    if (count($kw_for_label)) {
                        $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">' . ucfirst(Lang::get('labels.menu_offer')) . ':</a> ' . implode(', ', $kw_for_label);
                    }
                }

                if ($request->filled('listing_offerta_prenota_prima')) {
                    $listing_offerta_prenota_prima = $request->get('listing_offerta_prenota_prima');
                    $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">' . ucfirst(Lang::get('labels.menu_offer')) . ':</a> ' . ucfirst(Lang::get('labels.prenota'));
                }

                $parola_chiave_check = [];
                if ($request->filled('crea_check_off')) {
                    $crea_check_off = $request->get('crea_check_off');
                    $kw = ParolaChiave::find($crea_check_off);
                    $parola_chiave_check[$kw->id] = $kw->getNomeLocale($locale);

                    if (isset($ancora) && $kw->getNomeLocale($locale) == '') {
                        $parola_chiave_check[$kw->id] = $ancora;
                    }

                }
                $prenota_prima_check = [];
                if ($request->filled('crea_check_pp')) {
                    $crea_check_pp = $request->get('crea_check_pp');
                    $prenota_prima_check['listing_offerta_prenota_prima'] = $crea_check_pp;
                    if (isset($ancora)) {
                        $prenota_prima_check['listing_offerta_prenota_prima'] = $ancora;
                    }
                }

                // la label per i gruppi di servizi e per i servizi singoli è LA STESSA
                $gruppi_for_label = [];
                if ($request->filled('gruppi_servizi')) {
                    $gruppi_s = GruppoServizi::whereIn('id', $request->get('gruppi_servizi'))->get();

                    foreach ($gruppi_s as $g) {
                        $gruppi_for_label[] = $g->getNomeLocale($locale);
                    }

                    $gruppi_servizi = implode(',', $request->get('gruppi_servizi'));
                    //dd($gruppi_servizi);
                }

                if ($request->filled('servizi_non_gruppo')) {
                    $servizi_non_gruppo = $request->get('servizi_non_gruppo');
                    foreach ($servizi_non_gruppo as $id) {
                        $this_servizio = Servizio::find($id);
                        if ($this_servizio) {
                            if ($this_servizio->getNomeLocale($locale) != '') {
                                $gruppi_for_label[] = $this_servizio->getNomeLocale($locale);
                            } else {
                                $gruppi_for_label[] = $this_servizio->translate($locale)->first()->nome;
                            }
                        }

                    }
                }

                if ($request->filled('reception_24h')) {
                    $reception_24h = $request->get('reception_24h');
                    $gruppi_for_label[] = "Reception H24";
                }

                if (count($gruppi_for_label)) {
                    $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">' . ucfirst(Lang::get('labels.menu_serv')) . ':</a> ' . implode(', ', $gruppi_for_label);
                }

                if ($request->filled('annuale')) {
                    $annuale = $request->get('annuale');
                    $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">' . ucfirst(trans('listing.filtri_apertura')) . ':</a> ' . trans('listing.annuale');
                }

                if ($request->filled('listing_bambini_gratis')) {
                    $listing_bambini_gratis = $request->get('listing_bambini_gratis');
                    $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">' . trans('hotel.n_off') . ':</a>' . trans('hotel.offerte');
                }

                $bambini_gratis_check = [];
                if ($request->filled('crea_check_bg')) {
                    $crea_check_bg = $request->get('crea_check_bg');
                    $bambini_gratis_check['listing_bambini_gratis'] = ucfirst(trans('hotel.offerte'));
                }

                if ($request->filled('listing_green_booking')) {
                    $listing_green_booking = $request->get('listing_green_booking');
                    $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">Green Booking Hotel</a>';
                }

                $green_check = [];
                if ($request->filled('crea_check_green')) {
                    $crea_check_green = $request->get('crea_check_green');
                    $green_check['listing_green_booking'] = 'Green Booking Hotel';
                }

                if ($request->filled('listing_bonus_vacanze_2020')) {
                    $listing_bonus_vacanze_2020 = $request->get('listing_bonus_vacanze_2020');
                    $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">Bonus vacanze Hotel</a>';
                }

                $bonus_check = [];
                if ($request->filled('crea_check_bonus')) {
                    $crea_check_bonus = $request->get('crea_check_bonus');
                    $bonus_check['listing_bonus_vacanze_2020'] = 'Bonus vacanze Hotel';
                }

                if ($request->filled('listing_eco_sostenibile')) {
                    $listing_eco_sostenibile = $request->get('listing_eco_sostenibile');
                    $label .= "&nbsp;&nbsp;&nbsp;&nbsp;" . '<a class="label">' . trans('hotel.eco') . '</a>';
                }

                $eco_check = [];
                if ($request->filled('crea_check_eco')) {
                    $crea_check_eco = $request->get('crea_check_eco');
                    $eco_check['listing_eco_sostenibile'] = trans('hotel.eco');
                }

                $request->flash();

            }

            /**
             * Appending Values To JSON
             * Occasionally, when casting models to an array or JSON, you may wish to add attributes that do not have a corresponding column in your database. To do so, first define an accessor for the value:
             */

            $clienti = Hotel::with(
                [
                    'stelle',
                    'localita.macrolocalita',
                ]
            )
                ->select(
                    'id',
                    'nome',
                    'indirizzo',
                    'localita_id',
                    'categoria_id',
                    'mappa_latitudine',
                    'mappa_longitudine',
                    'distanza_spiaggia',
                    'distanza_staz',
                    'distanza_fiera',
                    'distanza_centro',
                    'tmp_punti_di_forza_it',
                    'tmp_punti_di_forza_en',
                    'tmp_punti_di_forza_fr',
                    'tmp_punti_di_forza_de',
                    'listing_img',
                    'prezzo_min',
                    'prezzo_max'
                )
                ->attivo();

            if ($localita_id > 0) {
                $clienti = $clienti->listingLocalita($localita_id);
            } elseif ($macrolocalita_id > 0) {
                $clienti = $clienti->listingMacrolocalita($macrolocalita_id);
            }

            if (!empty($categorie)) {
                $clienti = $clienti->listingCategorie(implode(',', $categorie));
            }

            if ($listing_tipologie != '') {
                $clienti = $clienti->listingTipologie($listing_tipologie);
            }

            if (!empty($trattamenti) && $trattamenti != ['1']) {
                $clienti = $clienti->listingTrattamentoNew(implode(',', $trattamenti));
            }

            if (!empty($gruppi_servizi)) {
                $clienti = $clienti->listingMultiGruppiServizi($gruppi_servizi, $macrolocalita_id);
            }

            if (!empty($servizi_non_gruppo)) {
                $clienti = $clienti->listingMultiServiziSingoli($servizi_non_gruppo, $macrolocalita_id);
            }

            if (!empty($parola_chiave)) {
                $clienti = $clienti->listingMultiParolaChiaveOfferteAttive(implode(',', $parola_chiave), 'it');
            }

            if ($annuale > 0) {
                $clienti = $clienti->ListingAnnuali($annuale);
            }

            if ($reception_24h) {
                $clienti = $clienti->reception24h();
            }

            if (!empty($listing_offerta_prenota_prima)) {
                $clienti = $clienti->listingOffertaPrenotaPrima($listing_offerta_prenota_prima, 'it');
            }

            if ($listing_bambini_gratis) {
                $clienti = $clienti->listingBambiniGratis($listing_bambini_gratis);
            }

            if ($listing_green_booking) {
                $clienti = $clienti->listingGreenBooking($listing_green_booking);
            }

            if ($listing_bonus_vacanze_2020) {
                $clienti = $clienti->listingBonusVacanze($listing_bonus_vacanze_2020);
            }

            if ($listing_eco_sostenibile) {
                $clienti = $clienti->listingEcoSostenibile($listing_eco_sostenibile);
            }

            if ($request->isMethod('post')) {
                //dd(($clienti->toSql()));
            }

            $clienti_get = $clienti->get();

            $new_clienti = [];

            foreach ($clienti_get as $cliente) {

                /*
                $tmp_punti_di_forza_it = str_replace('"','',$cliente->tmp_punti_di_forza_it);
                $tmp_punti_di_forza_it = str_replace('\\','',$tmp_punti_di_forza_it);
                $tmp_punti_di_forza_it = str_replace('\'','',$tmp_punti_di_forza_it);
                $tmp_punti_di_forza_it = str_replace('&','',$tmp_punti_di_forza_it);
                $array_punti_di_forza = explode(',', strtolower($tmp_punti_di_forza_it));
                $cliente->tmp_punti_di_forza_it = implode(', ' , $array_punti_di_forza);
                */

                foreach (Utility::linguePossibili() as $lingua) {
                    $col = 'tmp_punti_di_forza_' . $lingua;
                    // tolgo il carattere " dai punti di forza
                    // quando faccio toJson viene aggiunta la proprietà 'punti' che ritorna la colonna tmp_punti_di_forza_it con il valore cambiato!!
                    // NON SALVARE SUL DB !!!!
                    $$col = str_replace('"', '', $cliente->$col);
                    $$col = str_replace('\\', '', $$col);
                    $$col = str_replace('\'', '', $$col);
                    $$col = str_replace('&', '', $$col);
                    $array_punti_di_forza = explode(',', strtolower($$col));

                    $cliente->$col = implode(', ', $array_punti_di_forza);

                }

                // distanza centro
                // quando faccio toJson viene aggiunta la proprietà 'label_centro' che ritorna la colonna distanza_centro con il valore cambiato!!
                // NON SALVARE SUL DB !!!!
                $distanza_centro = "";

                if (Utility::getDistanzaDalCentroPoi($cliente) == Lang::get('labels.in_centro')) {
                    $distanza_centro = Lang::get('labels.in_centro');
                } else {
                    $distanza_centro = Lang::get('labels.centro') . ': ' . Utility::getDistanzaDalCentroPoi($cliente);
                }

                $cliente->distanza_centro = $distanza_centro;

                // immagine
                // quando faccio toJson viene aggiunta la proprietà 'img_mappa_listing' che ritorna la colonna listing_img con il valore cambiato!!
                // NON SALVARE SUL DB !!!!
                $listing_img = Utility::asset($cliente->getListingImg("220x148", true, $cliente->listing_img));

                $cliente->listing_img = $listing_img;

                $new_clienti[] = $cliente;
            }

            $clienti_count = count($new_clienti);
            $clientiJson = json_encode($new_clienti);

            /////////////////////////////////////////////////////////////
            // Aggiungo i punti di forza come Json al json degli hotel //
            /////////////////////////////////////////////////////////////

            //dd($clientiJson);

            //dd($gruppi);

            /////////////
            // OWN POI //
            /////////////

            $own_poi = MappaRicercaPoi::with(
                ['poi_lingua' => function ($query) use ($locale) {
                    $query
                        ->where('lang_id', '=', $locale);
                },
                ]
            )->get();

            $own_poiJson = json_encode($own_poi);

            /////////////
            // OWN POI //
            /////////////

            if ($request->ajax()) {
                echo "$clientiJson";
            } else {
                return View::make('templates.mappa-ricerca',
                    compact(
                        'title',
                        'locale',
                        'lat',
                        'long',
                        'macrolocalita_id',
                        'localita_id',
                        'clientiJson',
                        'own_poiJson',
                        'clienti_count',
                        'stelle',
                        'gruppi',
                        'servizi_singoli',
                        'clienti_ids',
                        'ancora',
                        'cms_pagina_id',
                        'cms_pagina_uri',
                        'canonical_uri',
                        'localita',
                        'adding_group_id',
                        'trattamenti',
                        'parola_chiave',
                        'parola_chiave_check',
                        'kw_arr',
                        'listing_offerta_prenota_prima',
                        'prenota_prima_check',
                        'crea_check_pp',
                        'listing_bambini_gratis',
                        'bambini_gratis_check',
                        'crea_check_bg',
                        'listing_green_booking',
                        'crea_check_green',
                        'green_check',
                        'listing_bonus_vacanze_2020',
                        'crea_check_bonus',
                        'bonus_check',
                        'listing_eco_sostenibile',
                        'crea_check_eco',
                        'eco_check',
                        'listing_tipologie',
                        'reception_24h',
                        'label',
                        'start_label'
                    )
                );
            }

        }

        /* ------------------------------------------------------------------------------------
         * STRADARIO
         * ------------------------------------------------------------------------------------ */

        /**
         * Vista filtrata della CMS Pagina Index
         *
         * @access public
         * @param String $uri
         * @param Request $request
         * @return VIEW
         */

        public function stradario($uri, Request $request)
        {

            $ip = Utility::get_client_ip();
            $uri = "stradario/" . $uri;

            $onlymap = $request->get("map");

            $selezione_localita = "Riviera Romagnola";
            $macro_localita_seo = $selezione_localita;
            $localita_seo = $selezione_localita;

            $offerte_count = 0;
            $prezzo_max = 0;
            $prezzo_min = 0;
            $clienti_nuovo = array();

            $order = $request->get('order');
            $filter = $request->get('filter');
            $page = $request->get("page");

            /**
             * Ho bisogno che order non sia mai uguale a 0 in partenza se no ho un errore
             */

            if ($order == "0") {
                $order = "";
            }

            /*+
            * Trovo la pagina
            */

            $cms_pagina = CmsPagina::where('uri', $uri)->attiva()->first();

            if (!$cms_pagina) {
                abort("404");
            }

            $locale = $cms_pagina->lang_id;
            $for_canonical = Utility::getPublicUri($uri);

            /**
             * Trovo lo strade
             */

            $strade = Self::_getStradeFromCmsPagine($locale, $cms_pagina->listing_localita_id, $cms_pagina->listing_macrolocalita_id);
            $count = count($strade);

            if ($cms_pagina->listing_macrolocalita_id > 0 && $cms_pagina->listing_localita_id == 0) {

                $macrolocalita = Macrolocalita::find($cms_pagina->listing_macrolocalita_id);
                $localita = $macrolocalita;

                $selezione_localita = $macrolocalita->nome;
                $macro_localita_seo = $macrolocalita->nome;
                $localita_seo = $macrolocalita->nome;

            } elseif ($cms_pagina->listing_macrolocalita_id > 0 && $cms_pagina->listing_localita_id > 0) {

                $macrolocalita = Macrolocalita::find($cms_pagina->listing_macrolocalita_id);
                $localita = Localita::find($cms_pagina->listing_localita_id);

                $selezione_localita = $localita->nome;
                $macro_localita_seo = $macrolocalita->nome;
                $localita_seo = $localita->nome;

            }

            /*
            * Faccio questa query solo se cono nella pagona stradario
            * altrimenti prendo tutto in JS dal listing attivo
            */

            if (!$onlymap) {
                $clienti = Self::_getListingFromStrade($strade, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);
            } else {
                $clienti = collect([]);
            }

            $coordinate = $this->_getGeoCoords($localita);
            $map_lat_lon = implode(",", $coordinate);

            $google_maps = ["coords" => $coordinate, "hotels" => $clienti];
            $ids_send_mail = [];

            $menu_localita = Utility::getMenuLocalita($locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);

            $offerte_count = 0;

            foreach ($clienti as $cliente) {

                if (is_array($cliente)) {
                    $cliente = $cliente[0];
                }

                foreach ($cliente as $hotel) {

                    array_push($ids_send_mail, $hotel->id);
                    array_push($clienti_nuovo, $hotel);

                    //$offerte_count += $hotel->last->count() + $hotel->offerte->count();

                    if ($hotel->prezzo_max > $prezzo_max) {
                        $prezzo_max = $hotel->prezzo_max;
                    }

                    if ($hotel->prezzo_min > 0 && (!$prezzo_min || $hotel->prezzo_min < $prezzo_min)) {
                        $prezzo_min = $hotel->prezzo_min;
                    }

                }
            }

            $clienti_count = count($ids_send_mail);
            $clienti = $clienti_nuovo;

            $arrayChiavi = array("{HOTEL_COUNT}", "{OFFERTE_COUNT}", "{PREZZO_MIN}", "{PREZZO_MAX}", "{LOCALITA}", "{MACRO_LOCALITA}", "{CURRENT_YEAR}", "{CURRENT-YEAR}");
            $arrayValori = array($clienti_count, $offerte_count, $prezzo_min, $prezzo_max, $localita_seo, $macro_localita_seo, date("Y")+Utility::fakeNewYear(), date("Y")+Utility::fakeNewYear());

            $titolo = Utility::replacePlaceholder($arrayChiavi, $cms_pagina->h1, $arrayValori);
            $testo = Utility::replacePlaceholder($arrayChiavi, $cms_pagina->descrizione_1, $arrayValori);
            $seo_title = Utility::replacePlaceholder($arrayChiavi, $cms_pagina->seo_title, $arrayValori);
            $seo_description = Utility::replacePlaceholder($arrayChiavi, $cms_pagina->seo_description, $arrayValori);

            if (!$cms_pagina->menu_riviera_romagnola) {
                $menu_tematico = Utility::getMenuTematico($locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id);
            } else {
                $menu_tematico = Utility::getMenuTematico($locale, Utility::getMacroRR(), Utility::getMicroRR());
            }

            return View::make('cms_pagina_stradario.stradario',
                compact(

                    'locale',
                    'cms_pagina',
                    'menu_localita',
                    'onlymap',

                    'titolo',
                    'testo',
                    'seo_title',
                    'seo_description',
                    'for_canonical',

                    'strade',
                    'clienti',
                    'google_maps',

                    'offerte_count',
                    'prezzo_max',
                    'prezzo_min',

                    'count',
                    'clienti_count',

                    'ip',
                    'macrolocalita',
                    'localita',
                    'macro_localita_seo',
                    'localita_seo',
                    'ids_send_mail',
                    'selezione_localita',

                    'order',
                    'filter',
                    'menu_tematico',
                    'map_lat_lon'

                )
            );

        }

    /* ------------------------------------------------------------------------------------
     * METODI PUBBLICI (ajax)
     * ------------------------------------------------------------------------------------ */

        /**
         * filtra chiamata ajax per preferiti nei listing e nelle località
         *
         * @param  Request $request [description]
         * @return [type]           [description]
         */

        public function preferiti_listing(Request $request)
        {

            $array_ids_hotel_vetrina = [];
            $array_hotel_slot = [];
            $cms_pagina_id = $request->get('cms_pagina_id');
            $id_to_send = $request->get('id_to_send');
            $evidenza_vetrina = false;
            $order = $request->get('order') or '0';
            $filter = $request->get('filter') or '0';
            $listing_preferiti = $request->get('listing_preferiti') or '0';
            $cms_pagina = CmsPagina::find($cms_pagina_id);

            if (!$cms_pagina) {
                abort("404");
            }

            $locale = $cms_pagina->lang_id;
            App::setlocale($locale);

            /*
            * La pagina può contenere UNA vetrina E un listing (ENTRAMBE per le microlocalita)
            * per ordinare TUTTO devo PRIMA UNIRE QUESTI 2
            *
            * 1) Trovo gli di degli hotel che hanno la vetrina
            * 2) trovo tutti gli hotel con i filtri della pagina (se c'è il listing lo becco) ed in più quelli con gli id tra quelli che hanno la vetrina
            */
            if ($cms_pagina->vetrina_id) {

                if (Session::has('vetrina')) {
                    $vetrina = Session::get('vetrina');
                } else {

                    $vetrina = Vetrina::with([
                        'slots' => function ($query) use ($locale) {
                            $query->withClienteEagerLoaded($locale);
                            $query->where("attiva", "=", "1");
                            $query->orderBy('posizione', 'asc');
                        },
                    ])
                        ->find($cms_pagina->vetrina_id);
                }

                foreach ($vetrina->slots as $slot) {

                    if (!is_null($slot->cliente)) {
                        $array_ids_hotel_vetrina[] = $slot->cliente->id;

                        /**
                         * associo all'id cliente lo slot che aveva
                         */

                        $array_hotel_slot[$slot->cliente->id] = $slot;
                    }
                }
            }

            /**
             * ATTENZIONE POTREI NON AVERE HOTEL DEL LISTING !!!
             */

            $clienti_hotel = $this->getListingAndVetrineFiltrate($cms_pagina, $locale, $order, $filter, $array_ids_hotel_vetrina, $listing_preferiti);

            /*
            * Questi sono tutti oggetti hotel
            * Quelli che derivano dagli slot li devo ritrsformare in slot
            */

            $clienti = collect(array());

            foreach ($clienti_hotel as $hotel) {
                if (in_array($hotel->id, $array_ids_hotel_vetrina)) {
                    $clienti->push($array_hotel_slot[$hotel->id]);
                } else {
                    $clienti->push($hotel);
                }
            }

            $log_view = Config::get("logging.log_view");

            return View::make(
                'cms_pagina_listing.clienti',
                compact(
                    'clienti',
                    'locale',
                    'filter_text',
                    'id_to_send',
                    'evidenza_vetrina',
                    'cms_pagina',
                    'log_view'
                )
            );

        }

       

}

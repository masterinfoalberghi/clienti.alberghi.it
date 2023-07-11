<?php

namespace App\Http\Controllers;

use App\Macrolocalita;
use App\Localita;
use App\Utility;
use App\Hotel;
use App\PuntoForza;
use App\CmsPagina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class FilterController extends Controller
{   

    const CATEGORIE = [
        "1" => "★",
        "2" => "★★",
        "3" => "★★★",
        "6" => "★★★ <sup>Sup</sup>",
        "4" => "★★★★",
        "5" => "★★★★★",
        "*" => "labels.filter_category"
    ];

    /* ------------------------------------------------------------------------------------
     * METODI PRIVATI
     * ------------------------------------------------------------------------------------ */

    /**
     * Esegue la query e trova o conta tutti gli hotel filtrati 
     * 
     * @access public
     * @param Request $request
     * @param String $macrolocalita_ids: 11
     * @param String $trattamenti_keys: *
     * @param String $categorie_keys: *
     * @param Integer $bonus_vacanza_keys: 0
     * @param String $annuale_keys: *
     * @param String $gruppo_servizi_keys: *
     * @param String $cancellazione_gratuita_keys: *
     * @param String $rating_keys: *
     * @param String $order_by: NULL
     * @return Object
     */

    private static function _countHotel(
        $counting = true, 
        $paginate = false, 
        $macrolocalita_ids = 11, 
        $trattamenti_keys = "*", 
        $categorie_keys = "*", 
        $bonus_vacanza_keys = 0, 
        $annuale_keys = "*", 
        $gruppo_servizi_keys = "*", 
        $cancellazione_gratuita_keys = "*", 
        $rating_keys = "*", 
        $order_by = null) {
        
        /** Controlli omogeneità */
        // if (strpos($macrolocalita_ids,"+"))     $macrolocalita_ids   = explode("+", $macrolocalita_ids);    else $macrolocalita_ids     = [$macrolocalita_ids];
        // if (strpos($trattamenti_keys,"+"))      $trattamenti_keys    = explode("+", $trattamenti_keys);     else $trattamenti_keys      = [$trattamenti_keys];
        // if (strpos($categorie_keys,"+"))        $categorie_keys      = explode("+", $categorie_keys);       else $categorie_keys        = [$categorie_keys];
        // if (strpos($gruppo_servizi_keys,"+"))   $gruppo_servizi_keys = explode("+", $gruppo_servizi_keys);  else $gruppo_servizi_keys   = [$gruppo_servizi_keys];
        // if (strpos($rating_keys,"+"))           $rating_keys         = explode("+", $rating_keys);          else $rating_keys           = [$rating_keys];

        /** congteggio */
        $count = new Hotel();
        $with = [];
        
        /** Se non sono un conteggio prendo anche le immagini */
        if ($counting == false) $with[] = "numero_immagini_gallery";

        /** Se selezioni la cancellazione allora pre ndo la tabella caparre */
        if ($cancellazione_gratuita_keys != "*") $with[] = "caparraGratuita";

        /** Se ho un with[] lo aggiungo alla query originale */
        if (!empty($with)) $count = $count->with($with);

        /** Se ho dei servizi li aggiungo alla query */
        if ($gruppo_servizi_keys != ["*"]) $count = $count->listingGruppoServizi($gruppo_servizi_keys);
        
        /** 
         * Se non sono riviera romagnola 
         * 
         * @Lucio 8/3/2021 
         * Nota: Ha voluto togliere dalla parte visiva la scela delle localita
         * Lascio la logica ma nasconso la colonna
         */

        if ($macrolocalita_ids != [11]):
            $localita_ids = Localita::whereIn("macrolocalita_id", $macrolocalita_ids)->pluck("id")->toArray();
            $count = $count->whereIn("localita_id", $localita_ids);
        endif;

        /** Se ho uno o più categorie le aggiungo alla query */
        if ($categorie_keys != ["*"]) {
            $count = $count->where(function ($query) use ($categorie_keys) {
                foreach($categorie_keys as $t_key):
                    $query = $query->orWhere("categoria_id", $t_key);
                endforeach;
            });
        }

        /** Se ho uno o più trattamenti li aggiungo alla query */
        if ($trattamenti_keys != ["*"]) {
            $count = $count->where(function ($query) use ($trattamenti_keys) {
                foreach($trattamenti_keys as $t_key):
                    $query = $query->orWhere($t_key, 1);
                endforeach;
            });
        }
        
        /** Se hi dei servizi li aggiungo alla query */
        if ($bonus_vacanza_keys == 1) $count = $count->where("bonus_vacanze_2020", 1);
        if ($annuale_keys != "*")     $count = $count->where("annuale", $annuale_keys);
        
        /** Se ho un rating lo aggiungo alla query */

        if ($rating_keys != ["*"]) {
            $count = $count->where(function ($query) use ($rating_keys) {
                foreach($rating_keys as $t_key):
                    $query = $query->orWhereBetween("rating_ia", [(int)$t_key, (int)$t_key+1]); 
                endforeach;
            });
        }

        /** 
         * Ordinamento 
         */

        if (is_null($order_by) && $counting == false)
            $count = $count->inRandomOrder();

        elseif ($order_by == "nome")
            $count = $count->orderBy("nome");

        elseif ($order_by == "categoria_asc")
            $count = $count->orderByRaw("FIELD(categoria_id, 1,2,3,6,4,5)");

        elseif ($order_by == "categoria_desc")
            $count = $count->orderByRaw("FIELD(categoria_id, 5,4,6,3,2,1)");

        elseif ($order_by == "prezzo_min") {

            $count = $count->orderBy("prezzo_min", "ASC");  
            $count = $count->orderBy("prezzo_max", "ASC");  

        } elseif ($order_by == "prezzo_max") {

            $count = $count->orderBy("prezzo_max", "DESC");  
            $count = $count->orderBy("prezzo_min", "DESC");  

        }
        
        // dump($count->attivo()->toSql());
        // dump($count->attivo()->get()->count());

        /** 
         * Paginazione 
         */

        if ($counting == true && $paginate == false)
            return $count->attivo()->get()->count();

        else if ($counting == false && $paginate == false) 
            return $count->attivo()->get();

        else if ($counting == false && $paginate == true) 
            return $count->attivo()->paginate(25);

    }

    /* ------------------------------------------------------------------------------------
     * METODI PUBBLICI (VIEWS)
     * ------------------------------------------------------------------------------------ */

    /**
     * VIEW
     * Mostra la pagina dei filtri
     * 
     * ATTENZIONE:
     * Da mobile è una pagina fisica, da desktop/tablet invece viene caricata via ajax
     * Questa pagina deve sempre contenere il meta no-index
     * 
     * 
     * @access public
     * @param Request $request
     * @param String $macrolocalita_ids: 11
     * @param String $trattamenti_keys: *
     * @param String $categorie_keys: *
     * @param Integer $bonus_vacanza_keys:0
     * @param String $annuale_keys: *
     * @param String $gruppo_servizi_keys: *
     * @param String $cancellazione_gratuita_keys: *
     * @param String $rating_keys: *
     * @return JSON
     */

    public function index(
        Request $request, 
        $macrolocalita_ids = 11, 
        $trattamenti_keys = "*", 
        $categorie_keys = "*", 
        $bonus_vacanza_keys = 0, 
        $annuale_keys = "*", 
        $gruppo_servizi_keys = "*", 
        $cancellazione_gratuita_keys = "*", 
        $rating_keys = "*") {
        
        $locale = $this->getLocale();
  
        /** Controlli omogeneità */
        if (strpos($macrolocalita_ids,"+"))     $macrolocalita_ids   = explode("+", $macrolocalita_ids);    else $macrolocalita_ids     = [$macrolocalita_ids];
        if (strpos($trattamenti_keys,"+"))      $trattamenti_keys    = explode("+", $trattamenti_keys);     else $trattamenti_keys      = [$trattamenti_keys];
        if (strpos($categorie_keys,"+"))        $categorie_keys      = explode("+", $categorie_keys);       else $categorie_keys        = [$categorie_keys];
        if (strpos($gruppo_servizi_keys,"+"))   $gruppo_servizi_keys = explode("+", $gruppo_servizi_keys);  else $gruppo_servizi_keys   = [$gruppo_servizi_keys];
        if (strpos($rating_keys,"+"))           $rating_keys         = explode("+", $rating_keys);          else $rating_keys        = [$rating_keys];

        /** Filtri */
        $macrolocalita       = Macrolocalita::with(["localita"])->get();       
        $trattamenti         = Utility::getTrattamentiNomi();
        $trattamenti["*"]    = __("labels.filter_meal_plan");
        $categorie           = Self::CATEGORIE;
        $gruppo_servizi      = Utility::getGruppiServizi($locale);
        $gruppo_servizi["*"] = __("labels.filter_service");

        /** Conteggio */
        $count = Self::_countHotel(
            true, 
            false, 
            $macrolocalita_ids, 
            $trattamenti_keys, 
            $categorie_keys, 
            $bonus_vacanza_keys, 
            $annuale_keys, 
            $gruppo_servizi_keys, 
            $cancellazione_gratuita_keys,
            $rating_keys
        );

        /** View */
        return View::make('templates.filter', [
            "locale"                      => $locale,
            "macrolocalita_ids"           => $macrolocalita_ids,
            "trattamenti_keys"            => $trattamenti_keys,
            "categorie_keys"              => $categorie_keys,
            "annuale_keys"                => $annuale_keys,
            "cancellazione_gratuita_keys" => $cancellazione_gratuita_keys,
            "bonus_vacanza_keys"          => $bonus_vacanza_keys,
            "gruppo_servizi_keys"         => $gruppo_servizi_keys,
            "trattamenti"                 => $trattamenti,
            "macrolocalita"               => $macrolocalita,
            "categorie"                   => $categorie,
            "gruppo_servizi"              => $gruppo_servizi,
            "rating_keys"                 => $rating_keys,
            "count"                       => $count
        ]);

    }

    /**
     * VIEW
     * Trova tutti gli slot del listing per la visualizzazione degli hotel filtrati
     * 
     * @access public
     * @param Request $request
     * @param String $macrolocalita_ids: 11
     * @param String $trattamenti_keys: *
     * @param String $categorie_keys: *
     * @param Integer $bonus_vacanza_keys: 0
     * @param String $annuale_keys: *
     * @param String $gruppo_servizi_keys: *
     * @param String $cancellazione_gratuita_keys: *
     * @param String $rating_keys: *
     * @return JSON
     */

    public function listing(

        Request $request, 
        $macrolocalita_ids = 11, 
        $trattamenti_keys = "*", 
        $categorie_keys = "*", 
        $bonus_vacanza_keys = 0, 
        $annuale_keys = "*", 
        $gruppo_servizi_keys = "*", 
        $cancellazione_gratuita_keys = "*",
        $rating_keys = "*") {
        
        /** Controlli omogeneità */
        $locale            = $this->getLocale();
        $order_by          = $request->get("order");
        $titolo            = __("listing.filter_title");
        $description       = __("listing.filter_list");
        $seo_title         = __("listing.filter_title");
        $seo_description   = __("listing.filter_title");
        $map_disabled      = true;
        $pagination        = false;
        $page_number       = null;
        $previous_page_url = null;
        $next_page_url     = null;
        $filter_text       = "";
        $ids_send_mail     = "";
        $order             = "";
        $filter            = "";
        
        /** Controlli omogeneità */
        if (strpos($macrolocalita_ids,"+"))     $macrolocalita_ids   = explode("+", $macrolocalita_ids);    else $macrolocalita_ids     = [$macrolocalita_ids];
        if (strpos($trattamenti_keys,"+"))      $trattamenti_keys    = explode("+", $trattamenti_keys);     else $trattamenti_keys      = [$trattamenti_keys];
        if (strpos($categorie_keys,"+"))        $categorie_keys      = explode("+", $categorie_keys);       else $categorie_keys        = [$categorie_keys];
        if (strpos($gruppo_servizi_keys,"+"))   $gruppo_servizi_keys = explode("+", $gruppo_servizi_keys);  else $gruppo_servizi_keys   = [$gruppo_servizi_keys];
        if (strpos($rating_keys,"+"))           $rating_keys          = explode("+", $rating_keys);           else $rating_keys            = [$rating_keys];
        
        $detect = new \Detection\MobileDetect;
        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'desktop');

        /** 
         * Cerco il backurl.
         * Non sono Riviera romagnola 
         */
        if (count($macrolocalita_ids) == 1 && $macrolocalita_ids[0] != 11):
            $backurl = CmsPagina::select("uri")
                ->where("template", "localita")
                ->where("listing_macrolocalita_id", $macrolocalita_ids[0])
                ->where("lang_id", $locale)
                ->first();
            if ($deviceType == "phone")
                $menu_tematico = Utility::getMenuTematicoMobile($locale, $macrolocalita_ids[0]);
            else
                $menu_tematico = Utility::getMenuTematico($locale, $macrolocalita_ids[0]);
        else:
            /** Sono riviera romagnola */
            $backurl = CmsPagina::select("uri")
                ->where("template", "localita")
                ->where("listing_macrolocalita_id", 11)
                ->where("listing_localita_id", 49)
                ->where("lang_id", $locale)
                ->first();
            if ($deviceType == "phone")
                $menu_tematico = Utility::getMenuTematicoMobile($locale, Utility::getMacroRR(), Utility::getMicroRR());
            else
                $menu_tematico = Utility::getMenuTematico($locale, Utility::getMacroRR(), Utility::getMicroRR());
        endif;
        
        $titleMacro = Macrolocalita::whereIn("id", $macrolocalita_ids)->pluck("nome")->toArray();
        $titleMacro = " a " . implode(", ", $titleMacro);

        /** 
         * Conteggio 
         */

        $clienti         = Self::_countHotel(false, true, $macrolocalita_ids, $trattamenti_keys, $categorie_keys, $bonus_vacanza_keys, $annuale_keys, $gruppo_servizi_keys, $cancellazione_gratuita_keys, $rating_keys, $order_by );
        $count_clienti   = Self::_countHotel(true, false, $macrolocalita_ids, $trattamenti_keys, $categorie_keys, $bonus_vacanza_keys, $annuale_keys, $gruppo_servizi_keys, $cancellazione_gratuita_keys, $rating_keys );
        $ids_send_mail   = Self::_countHotel(false, false, $macrolocalita_ids, $trattamenti_keys, $categorie_keys, $bonus_vacanza_keys, $annuale_keys, $gruppo_servizi_keys, $cancellazione_gratuita_keys, $rating_keys);
        $titolo          = $count_clienti . " " . $titolo . $titleMacro;
        $seo_title       = $count_clienti . " " . $seo_title . $titleMacro;
        $seo_description = $count_clienti . " " . $seo_description . $titleMacro;;
        $coordinate      = Utility::getGenericGeoCoords();
        $google_maps     = ["coords" => $coordinate, "hotels" => $clienti];

        /** verifico se ho passato un numero di pagina inesistente */
        if ($clienti instanceof Paginator || $clienti instanceof LengthAwarePaginator) {

            if ($request->get('page') && $request->get('page') > $clienti->lastPage())
                return redirect($backurl->uri);

            $pagination        = true;
            $page_number       = $clienti->currentPage();
            $previous_page_url = $clienti->previousPageUrl();
            $next_page_url     = $clienti->nextPageUrl();

        }

        /** Ids Send Mail */
        $ids_send_mail = $clienti->pluck("id")->toArray();
        $flipped       = array_flip($ids_send_mail);
        $count         = count($flipped) < 25 ? count($flipped) : 25;
        $ids_send_mail = array_rand($flipped, $count);

        if (is_array($ids_send_mail))
            $ids_send_mail = implode(",",$ids_send_mail);

        /** View */
        return View::make('cms_filter_listing.cms_filter_listing', [
            "locale"                     => $locale,
            "order"                      => $order,
            "filter"                     => $filter,
            "cms_pagina"                 => NULL,
            "ids_send_mail"              => $ids_send_mail,
            "pagination"                 => $pagination,
            "filter_text"                => $filter_text,
            "titolo"                     => $titolo,
            "description"                => $description,
            "seo_title"                  => $seo_title,
            "seo_description"            => $seo_description,
            "map_disabled"               => $map_disabled,
            "trattamenti_keys"           => $trattamenti_keys,
            "categorie_keys"             => $categorie_keys,
            "annuale_keys"               => $annuale_keys,
            "cancellazione_gratuita_keys" => $cancellazione_gratuita_keys,
            "bonus_vacanza_keys"         => $bonus_vacanza_keys,
            "gruppo_servizi_keys"        => $gruppo_servizi_keys,
            "rating_keys"                => $rating_keys,
            "clienti"                    => $clienti,
            "backurl"                    => $backurl,
            "google_maps"                => $google_maps,
            "order_by"                   => $order_by,
            'menu_tematico'              => $menu_tematico
        ]);

    }

    /**
     * Conta il numero di hotel coni filtri applicati
     * 
     * @access public
     * @param Request $request
     * @return JSON
     */

    public function filterCount(Request $request) {

        /** Recupero dati */
        $macrolocalita_ids           = $request->get("macrolocalita_ids");
        $trattamenti_keys            = $request->get("trattamenti_keys");
        $categorie_keys              = $request->get("categorie_keys");
        $gruppo_servizi_keys         = $request->get("gruppo_servizi_keys");
        $rating_keys                 = $request->get("rating_keys");

        if (strpos($macrolocalita_ids,"+"))     $macrolocalita_ids   = explode("+", $macrolocalita_ids);    else $macrolocalita_ids     = [$macrolocalita_ids];
        if (strpos($trattamenti_keys,"+"))      $trattamenti_keys    = explode("+", $trattamenti_keys);     else $trattamenti_keys      = [$trattamenti_keys];
        if (strpos($categorie_keys,"+"))        $categorie_keys      = explode("+", $categorie_keys);       else $categorie_keys        = [$categorie_keys];
        if (strpos($gruppo_servizi_keys,"+"))   $gruppo_servizi_keys = explode("+", $gruppo_servizi_keys);  else $gruppo_servizi_keys   = [$gruppo_servizi_keys];
        if (strpos($rating_keys,"+"))           $rating_keys         = explode("+", $rating_keys);          else $rating_keys           = [$rating_keys];

        $bonus_vacanza_keys          = $request->get("bonus_vacanza_keys") ? $request->get("bonus_vacanza_keys") : "*";
        $annuale_keys                = $request->get("annuale_keys") ? $request->get("annuale_keys") : "*";
        $cancellazione_gratuita_keys = $request->get("cancellazione_gratuita_keys");

        /** Conteggio */
        $count = Self::_countHotel(
            true, 
            false, 
            $macrolocalita_ids, 
            $trattamenti_keys, 
            $categorie_keys, 
            $bonus_vacanza_keys, 
            $annuale_keys, 
            $gruppo_servizi_keys, 
            $cancellazione_gratuita_keys,
            $rating_keys);
        
        

        /** JSON */
        return response()
            ->json(['count' => $count]);

    }

}

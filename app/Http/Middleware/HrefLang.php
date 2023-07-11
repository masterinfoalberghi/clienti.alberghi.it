<?php

namespace App\Http\Middleware;

use App\CmsPagina;
use App\Utility;
use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


/**
 * HrefLang class.
 */
class HrefLang
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	 
	public function handle($request, Closure $next)
	{
		
		$href_lang = "<head>";
		$other_pages = [];
		$path = $request->path();
		$response = $next($request);

		/**
	     * SONO NELLA PAGINAZIONE E NON CI VUOLE l'HREF LANG
	     * tutte le pagine della paginazione hanno il canonical a quella senza il parametro pagina
	     * ed è lei ad avere l'href_lang
	     */
	    
		if (!is_null($request->query('page')))
			return $response;

		$query_string = parse_url($request->fullUrl(), PHP_URL_QUERY);
		$last_segment = $request->segment(count($request->segments()));
		
		// Cache disabilitata per admin

		$forceCache = false;
		$forceDebug = false;

		if ($request->segment(1) == "admin") {

			$forceCache = true;
			$forceDebug = true;

		}
		
		// se è un file che devo scaricare

		if ($response instanceof BinaryFileResponse)
			return $response;

		// Altrimenti


        
		$content = $response->content();
		
		if ($request->segment(1) == 'ing' || $request->segment(1) == 'fr' || $request->segment(1) == 'ted') {

			$in_lingua = Utility::getAppLocaleFromUrlLocale($request->segment(1));
			$path_no_lingua = str_replace($request->segment(1).'/', "", $path);

		} else {

			$in_lingua = 'it';
			$path_no_lingua = $path;

		}


		if ( $last_segment == 'hotel.php') {
			
			$id = $request->get("id");
			$href_lang .= "<link rel='alternate' hreflang='x-default' href='".Utility::getUrlWithLang("it" , "/" . $path_no_lingua, true)."?id=" . $id ."'' />";

			foreach (Utility::linguePossibili() as $lang)
				$href_lang .= "<link rel='alternate' hreflang='".$lang."' href='".Utility::getUrlWithLang($lang, "/" . $path_no_lingua, true)."?id=" . $id ."'' />";
			
		} else {
			
			if ($request->segment(1) != "admin") {

				/**
				 * La recupero dalla cache 
				 */
				 
				$key = "page_" . str_replace("/" , "_" , $path);
	
				if (!$cms_pagina = Utility::activeCache($key, "Pagina (MID)", $forceCache, $forceDebug)) {
	
					$cms_pagina = CmsPagina::where('uri', $path)->attiva()->first();
					Cache::put($key, $cms_pagina, Carbon::now()->addDays(1));
	
				}
				
				/**
				 * Se non esiste pagina allora restituisco la richeista originale
				 */
				
				if (!$cms_pagina)
					return $response;
	
				/**
				 * Recupero le href_lang dalla cache
				 */
	
				$id_pagina = $cms_pagina->id;
				$locale = $cms_pagina->lang_id;
	
				$key = "href_lang_" . $locale . "_" . $id_pagina;
				
				if (!$other_pages = Utility::activeCache($key, "Href Lang (MID)", $forceCache, $forceDebug)) {
					
					if ($cms_pagina->alternate_uri != "")
						$other_pages = CmsPagina::where("alternate_uri", $cms_pagina->alternate_uri)->get();
						
					Cache::put($key, $other_pages, Carbon::now()->addDays(15));
					
				}
				
				

				/**
				 * Costruisco i link
				 */
				
				if (isset($other_pages) && count($other_pages))
					foreach ($other_pages as $page) {

						if ($page->lang_id == "it")
							$href_lang .= "<link rel='alternate' hreflang='x-default' href='".url($page->uri)."' />";
									
						$href_lang .= "<link rel='alternate' hreflang='".$page->lang_id."' href='".url($page->uri)."' />";

					}
				
			}
			
		}
		
		/**
		 * Aggiungo tutto all'head della pagina 
		 */
		
		if ($href_lang != "<head>")
			$content = str_ireplace("<head>", $href_lang, $content);

		$response->setContent($content);
			
		return $response;

	}


}

	
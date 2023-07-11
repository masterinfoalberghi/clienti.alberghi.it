<?php

/**
 *
 * View composer per render selectLocalita form mail multipla:
 * crea l'array da dare in pasto alla select per creare la select multipla delle localita tramite lo script jquery.multiselect.min.js
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\Macrolocalita;
use App\Utility;
use App\CookieIA;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;

class SearchFirstComposer
{
	
	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	public function compose(View $view) 
	{
        $key = "macro_micro_norr_real";
        
        if (!$macrolocalita_search_first = Utility::activeCache($key, "Cache Menu No Macrolocalita")) {
			$macrolocalita_search_first = Macrolocalita::noRR()->with('localita')->real()->get();
			Utility::putCache($key, $macrolocalita_search_first);
        } 
		
		$prefill = CookieIA::getCookiePrefill();
		
		$view->with([
			'macrolocalita_search_first' => $macrolocalita_search_first,
			'prefill' => $prefill
		]);
		
    }
    
}
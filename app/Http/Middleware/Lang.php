<?php

/**
 * Lang
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Middleware;

use App;
use App\Utility;
use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Request;

class Lang
{

	/**
	 * ottiene la route (QUINDI deve essere un route Middleware e non global, altrimenti l'object route è NULL)
	 * e verifica se c'è il parametro locale (se non c'è lo setta a 'it' e lo mette in Session
	 * INOLTRE lo toglie dai parametri della richiesta in modo che le firme dei metodi dei controller non abbiano mai questo parametro
	 * e non si debba gestire il caso in cui esiste, cioè non venga passato via url)
	 *
	 * @param  Request  $request
	 * @param  Closure  $next
	 * @return Closure $next
	 */
	 
	public function handle($request, Closure $next)
	{
		
		$route = $request->route();
		$route->parameter('locale');

		is_null($route->parameter('locale')) ? $locale = 'it' : $locale = $route->parameter('locale');
		App::setlocale(Utility::getAppLocaleFromUrlLocale($locale));
		$route->forgetParameter('locale');

		return $next($request);
		
	}


}

<?php

/**
 * CheckDispositivo
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Middleware;

use App\Hotel;
use Closure;
// use Illuminate\Support\Facades\Input;

class CheckHotelDisattivato
{

	/**
	 * Controllo se un hotel è disattivato 
	 *
	 * Verifico se c'è l'id e l'hotel corrispondente è disattivata e mando ad un metodo per gestire questo
	 * altrimenti lascio passare
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	 
	public function handle($request, Closure $next)
	{
		
		$route = $request->route();
        $id = $request->get("id");
        
		/**
		 * Ho sbagliato formato ID
		 */
		 
		if (is_null($id) || !is_numeric($id)) {

			// Run controller and method
			$controller = \App()->make('App\Http\Controllers\HotelController');
			return $controller->callAction('index_errato', $parameters = array('id' => $id));
			
		}

		/**
		 * Se passo uno o più zeri davanti al numero deve dare 404 
		 */

		if ($id[0] == 0)
			return abort("404");

		/**
		 * 404 per gli HOTEL DI MONTAGNA
		 * Gli ID sono stati tutti riassegnati a hotel della Riviera
		 */
		 
		/*if ( in_array($id,[410,543,806]) )
			return abort("404");*/
		
		/**
		 * Cerco l'hotel
		 */
		 	
		$hotel = Hotel::find($id);
		
		if (is_null($hotel)) {

			// Run controller and method
			$controller = \App()->make('App\Http\Controllers\HotelController');
			return $controller->callAction('index_errato', $parameters = array('id' => $id));
		}

		/**
		 * Se è tutto ok 
		 */ 
		 
		if ($hotel->attivo)
			return $next($request);

		else {

			/**
			 * VAdo alla pagina disabilitato
			 */

			// Run controller and method
			$controller = \App()->make('App\Http\Controllers\HotelController');
			return $controller->callAction('index_disabilitato', $parameters = array('id' => $id));
		}
		
	}


}

<?php

/**
 * CheckDispositivo
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Middleware;

use Closure;
use Redirect;

class CheckDispositivo
{
	
	/**
	 * Check dispositivo device
	 * 
	 * @access public
	 * @param mixed $request
	 * @param Closure $next
	 * @param mixed $who (default: null)
	 * @param int $redirect (default: 0)
	 * @return Closure $next
	 */
	 
	public function handle($request, Closure $next, $who = null, $redirect = 0)
	{

		$detect = new \Detection\MobileDetect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'desktop');
		$route = $request->route();

		if ($who != null && strpos($who,$deviceType) !== false) {

			return $next($request);

		} else if ($who != null && strpos($deviceType, $who) === false) {

				if ($redirect == 1)
					return Redirect::to('/hotel.php?id=' . $request->get("id"), 301);
				else
					return abort("404");

			} else {

			return $next($request);

		}

	}


}

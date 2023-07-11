<?php 

/**
 * LimitForIP
 *
 * @author Info Alberghi Srl
 * 
 */

namespace App\Http\Middleware;

use App\Utility;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;

class LimitForIP {

	/**
	 * Limita la route per gli IP validi ( es: nei CRON )
	 *
	 * @param  Request  $request
	 * @param  Closure  $next
	 * @return Closure $next
	 */
	 
	public function handle($request, Closure $next)
	{
		$ip = Utility::get_client_ip();

		if (!in_array( $ip,Utility::validIP())) 
			abort(404);

		
		return $next($request);
		
	}

}

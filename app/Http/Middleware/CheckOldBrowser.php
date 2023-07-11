<?php
	
/**
 * CheckOldBrowser
 *
 * @author Info Alberghi Srl
 * 
 */

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Request;
use Browser;

class CheckOldBrowser
{
    /**
     * Se no sono un browser moderno allora vado alla pagina /old-browser-update
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return Closure $next
     *
     * Esempi di utilizzo : https://github.com/hisorange/browser-detect
     *
     */
    public function handle($request, Closure $next)
    {	
	    $url = Request::url();
		
	    if (Browser::isIEVersion(8, true) && !Browser::isBot() && !strpos($url,"old-browser-update") && strpos($_SERVER["REQUEST_URI"], "/admin") === false) {
			return Redirect::to('/old-browser-update', 302); 
		} else {
			return $next($request);
		}
		
    }
    
}


<?php
namespace App\Http\Middleware;

use App\CookieIA;
use Closure;
use Cookie;
use Illuminate\Support\Facades\Session;

class CheckSendCookie
{
	    
    /**
     * Verifica se devo o non devo scrivere/aggiornare il cookie e il database
     *
     * @param  mixed $request
     * @param  mixed $next
     * @return void
     */
    public function handle($request, Closure $next)
    {

        $prefill = array();

        /** Cancello i vecchi cookie */ 
        //CookieIA::deleteOldCookiePrefill();

        if (strpos($_SERVER["REQUEST_URI"], "/admin") === false) {

			/**
			 * Se ho un passaggio di dti o una sessione attiva
			 */

            if ($request->input() || Session::has('ids_to_fill_cookie')) {

				$cookie_version = env("COOKIE_VERSION", 0);

				/** Setto l'oggetto prefill */
				$prefill = CookieIA::setCookiePrefill($request);

                /** Scrive il prefill nel DB usando "codice_cookie" come chiave e ritorna il prefill che contiene SOLO la chiave "codice_cookie" */
                $prefill = CookieIA::setPrefillDB($prefill);

                /**
				 * questo cookie verrò aggiunto alla risposta, alla fine dell'esecuzione della richiesta
				 * ma quando vado da QUI dentro l'applicazione nel mio controller questo cookie ANCORA non c'è!!!
				 * leggo il cookie associato alla richiesta NON QUESTO!!!!
				 * 
				 * Spira tra 4 setttimane
				 */

                if (isset($prefill))
                    return $next($request)->withCookie(cookie("prefill_v" . $cookie_version, serialize($prefill), 40320));


            }

        }

        return $next($request);

    }
    
    /**
     * terminate
     *
     * @param  mixed $request
     * @param  mixed $response
     * @return void
     */
    public function terminate($request, $response)
    {

    }

}

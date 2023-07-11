<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class CheckWhatsAppShare
{
    /**
     * Conta il ROI da share
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     
    public function handle($request, Closure $next)
    {

		if (isset($request) && isset($request->ws)) {
			
			/**
			 * Tolgo il parametro DALL'URL
			 */
			
			$codice = $request->ws;
			$query = "ws=" . $codice;
			$url = $request->fullUrl();
			
			$url = str_replace("&" . $query, "", $url);
			$url = str_replace("?" . $query, "", $url);
			
			/**
			 * Scrivo sul database il numero
			 */
			
			DB::statement("UPDATE tblStatsHotelShareRead SET roi = roi + 1 WHERE uri = '" . $url . "'");					
			DB::statement("UPDATE tblStatsHotelShare SET roi = roi + 1 WHERE codice = '" . $codice . "'");
			return response('Loading page...<script>window.location.href="'.$url.'"</script>', 200);					
							
		}
    
        return $next($request);
        
    }
}

<?php
	
/**
 * Headers ( ex AccessControlAllowOriginMiddleware + httpVary )
 *
 * @author Info Alberghi Srl
 * 
 */
 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Headers
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
        
        $http_origin = $request->headers->get('Origin');
				$response = $next($request);
		
		/**
		 * Gestione dell'HTTP Vary
		 */
		 
		/**
		 * Se sono un file ..
		 */
		 
		if ($response instanceof BinaryFileResponse) 
            return $response;
		
		/**
		 * Aggiungo gli Header
		 */
		 
        $response->header('Vary', 'User-Agent');
				
		if ($http_origin == "http://www.info-alberghi.com" || $http_origin == "https://www.info-alberghi.com"|| $http_origin == "https://clienti.info-alberghi.com"){  
			
		   	$response->header('Access-Control-Allow-Origin' , $http_origin)
		   			 ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
					 ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin');
					 
		}
		
		
        
		return $response;
        
    }
    
}


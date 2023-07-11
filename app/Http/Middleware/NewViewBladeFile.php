<?php

/**
 * NewViewBladeFile
 *
 * @author Info Alberghi Srl
 * 
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class NewViewBladeFile
{
    /**
     * Aggiunge nuovi tipi di file a Blade per essere importati come include
     * 
     * @param  Request $request
     * @param  Closure $next
     * @return Closure $next;
     */
    
	public function __construct () {
	    
	    View::addExtension('min.css','blade');
		View::addExtension('min.js','blade');
	    
    }
    
    public function handle($request, Closure $next)
    {
        
        return $next($request);
        
    }
    
   
}

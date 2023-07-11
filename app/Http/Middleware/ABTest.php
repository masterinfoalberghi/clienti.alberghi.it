<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Request;

class ABTest
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
        if (Session::get("sf") === NULL) {

			$search_first = $request->get("sf");
            Session::put("sf", $search_first);
            
		}             
        return $next($request);
    }
}

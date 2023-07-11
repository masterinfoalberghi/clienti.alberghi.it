<?php

/**
 * CouponAttivoMiddleware
 *
 * @author Info Alberghi Srl
 * 
 */

namespace App\Http\Middleware;

use App\Hotel;
use Closure;
use SessionResponseMessages;
use Auth;



class CouponAttivoMiddleware
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
       
        $id = 0;
       
		if(Auth::user()->hasRole(["admin", "operatore"]))
			$id = Auth::user()->getUiEditingHotelId();
		else
			$id = Auth::user()->hotel_id;
        
        if(!$id)
        
            abort(404);
            
        else {

            $cliente = Hotel::find($id);
            
            if ($cliente->couponAttivi->count()) {

                SessionResponseMessages::add("error", "ATTENZIONE: Hai gia un coupon online! Puoi Modificarlo oppure archiviarlo e successivamente creare un nuovo coupon");
                return SessionResponseMessages::redirect("admin/coupon", $request);
            } else
            
               return $next($request);        
          
        }

    }
    
}

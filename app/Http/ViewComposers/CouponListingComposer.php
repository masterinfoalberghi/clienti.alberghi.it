<?php

/**
 *
 * View composer per render coupon:
 * @parameters: cliente
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;

class CouponListingComposer
  {
  
  /**
   * Bind data to the view.
   *
   * @param  View  $view
   * @return void
   */
  public function compose(View $view) 
    {
    
    $coupon = NULL;

    $viewdata = $view->getData();
    
    $cliente = $viewdata['cliente'];
    
    $coupon = $cliente->coupon->first();

    $view->with([
                'coupon' => $coupon,
                'id' => $cliente->id
                ]);
    }
  }

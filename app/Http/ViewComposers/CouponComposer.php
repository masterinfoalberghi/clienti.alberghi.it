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

use App\Utility;
use Illuminate\Contracts\View\View;

class CouponComposer
  {
  
  /**
   * Bind data to the view.
   *
   * @param  View  $view
   * @return void
   */
  public function compose(View $view) 
    {

    $viewdata = $view->getData();
    
    $cliente = $viewdata['cliente'];


    
    $titolo = '';

    if (isset($viewdata['titolo'])) {
      
      $titolo = $viewdata['titolo'];
      if ($titolo != '') {
        $titolo = strtoupper($titolo);
      }

    }



    // attivi, disponibili e fruibili
    $coupon = $cliente->coupon;


    $view->with([
                'coupon' => $coupon,
                'titolo' => $titolo
                ]);
    }
  }

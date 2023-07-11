<?php

/**
 *
 * View composer per render stelle associate all'hotel:
 * @parameters: categoria_id, cliente
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class StelleComposer
  {
  
  /**
   * Bind data to the view.
   *
   * @param  View  $view
   * @return void
   */
  public function compose(View $view) 
    {
    
    $offers = array();

    $viewdata = $view->getData();

    $cliente = $viewdata['cliente'];
    $categoria_id = $viewdata['categoria_id'];
    
    $view->with([
                'hotel_id' => $cliente->id,
                'categoria_id' => $categoria_id
                ]);
    }
    
  }

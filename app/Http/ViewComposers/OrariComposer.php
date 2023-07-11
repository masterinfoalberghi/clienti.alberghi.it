<?php

/**
 *
 * View composer per render snippet orari dell'hotel:
 * @parameters: cliente, locale, titolo
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class OrariComposer
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
    $locale = $viewdata['locale'];

    $titolo = isset($viewdata['titolo']) ? $viewdata['titolo'] : '';

    if (isset($titolo) && $titolo != '') {
      $titolo = strtoupper($titolo);
    } else {
      $titolo = '';
    }

  
    $view->with([
                'cliente' => $cliente,
                'titolo' => $titolo,
                'locale' => $locale
                ]);
    }
    
  }

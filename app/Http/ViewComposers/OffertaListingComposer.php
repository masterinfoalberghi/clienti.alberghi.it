<?php

/**
 *
 * View composer per render della offerta nel listing:
 * @parameters: cliente , locale, tipo_offerta
 *
 *
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;

class OffertaListingComposer
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
    $locale = $viewdata['locale'];
    $tipo_offerta = $viewdata['tipo_offerta'];
    
    if ($tipo_offerta == 'offerta') 
      {
      
      // ATTENZIONE: deve essere "offerte" perché le entry dell'eager loading sono "offerte" e "offerte.offerte_lingua"
      $offerte = $cliente->offerte->first();
      
      if (!is_null($offerte)) 
        {
        $off = $offerte->offerte_lingua->first();
        } 
      else
        {
        $off = NULL;
        }
      
      $view->with(['offerta' => $offerte, 'offerta_lingua' => $off, 'id' => $cliente->id]);
      } 
      
    elseif ($tipo_offerta == 'lastminute')
      {
      
      // ATTENZIONE: deve essere "last" perché le entry dell'eager loading sono "last" e "last.offerte_lingua"
      $last = $cliente->last->first();
      
      if (!is_null($last)) 
        {
        $l = $last->offerte_lingua->first();
        } 
      else
        {
        $l = NULL;
        }
      
      $view->with(['offerta' => $last, 'offerta_lingua' => $l, 'id' => $cliente->id]);
      }
    }
  }

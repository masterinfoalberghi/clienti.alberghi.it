<?php

/**
 *
 * View composer per render partenza ed arrivo con datepicker con il relativo js in mail_scheda, mail_multipla, mail_wishlist
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\MailMultipla;
use App\Utility;
use App\CookieIA;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;

class FormDatePickerComposer
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
		
    //$prefill = isset($viewdata['prefill']) ? $viewdata['prefill'] : [];
    
    $prefill = CookieIA::getCookiePrefill();

    // questa variabile viene passata dalla scheda_hotel che ha una visualizzazione del form e quindi del datepicker 
    // assolutamente personalizzato
    $scheda_hotel = isset($viewdata['scheda_hotel']) ? $viewdata['scheda_hotel'] : 0;

    // con questa variabile stabilisco se i campi sono obbligatori e setto l'attributo html5 required nel input text
    $required = isset($viewdata['required']) ? $viewdata['required'] : 0;

    $view->with(['prefill' => $prefill, 'scheda_hotel' => $scheda_hotel, 'required' => $required]);
    
    }
  }

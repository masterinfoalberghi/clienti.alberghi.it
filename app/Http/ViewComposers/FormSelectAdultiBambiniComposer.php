<?php

/**
 *
 * View composer per render adulti, bambini, campi età (dinamici) con il relativo js in mail_scheda, mail_multipla, mail_wishlist
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\MailMultipla;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;

class FormSelectAdultiBambiniComposer
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
    $adulti_select = MailMultipla::$adulti_select;
    $bambini_select = MailMultipla::$bambini_select;
    $adulti_over_select = MailMultipla::$adulti_over_select;
    $prefill = isset($viewdata['prefill']) ? $viewdata['prefill'] : [];

    // questa variabile viene passata dalla scheda_hotel che ha una visualizzazione del form e quindi del datepicker 
    // assolutamente personalizzato
    $scheda_hotel = isset($viewdata['scheda_hotel']) ? $viewdata['scheda_hotel'] : 0;

    $t = 1;
    foreach($adulti_select as $as) {
      $adulti_select[$t] = str_replace(["As", "Ao"], [__("labels.adulti"),__("labels.adulto")], $adulti_select[$t]);
      $t++;
    }

    $t = 0;
    foreach($bambini_select as $bs) {
      $bambini_select[$t] = str_replace(["Bs", "Bo"], [__("labels.bambini"),__("labels.bambino")], $bambini_select[$t]);
      $t++;
    }

    $view->with([
                'adulti_select' => $adulti_select,
                'bambini_select' => $bambini_select,
                'adulti_over_select' => $adulti_over_select,
                'prefill' => $prefill,
                'scheda_hotel' => $scheda_hotel,
                ]);
    }
  }

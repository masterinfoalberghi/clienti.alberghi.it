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

class FormSelectAdultiBambiniMobileComposer
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

    $adulti_over_select = MailMultipla::$adulti_over_select;
    $bambini_select = MailMultipla::$bambini_select;
    $prefill = isset($viewdata['prefill']) ? $viewdata['prefill'] : [];
    $view->with([
                'adulti_select' => $adulti_select,
                'adulti_over_select' => $adulti_over_select,
                'bambini_select' => $bambini_select,
                'prefill' => $prefill
                ]);
    }
  }

<?php

/**
 *
 * View composer per render selectLocalita form mail multipla:
 * crea l'array da dare in pasto alla select per creare la select multipla delle localita tramite lo script jquery.multiselect.min.js
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\Macrolocalita;
use App\Utility;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;

class MailMultiplaSelectLocalitaComposer
  {
  
  /**
   * Bind data to the view.
   *
   * @param  View  $view
   * @return void
   */
  public function compose(View $view) 
    {
    
    $macro = Macrolocalita::with('localita')->real()->where('id','!=',11)->get();

    $view->with([
                'macro' => $macro
                ]);
    }
  }

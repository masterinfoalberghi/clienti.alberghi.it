<?php

/**
 *
 * View composer per render vetrina della localita:
 * @parameters:  $vetrina ricavata dall'index del controller del cms (CmsPagineController)
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\Http\Controllers\VetrinaController;
use App\Localita;
use App\Utility;
use Request;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class VetrinaComposer {
  
  /**
   * Bind data to the view.
   *
   * @param  View  $view
   * @return void
   */
  public function compose(View $view) 
    {
    
	    $viewdata = $view->getData();
	    
		/**
		 * Se ho un ordine non aggiorno il pointer
		 * Altrimenti i primi potrei non vederli mai
		 */
	
		$order = Request::get("order");
	  $vetrina = $viewdata['vetrina'];
	  
		if (is_null($vetrina))  {
			
	        $slots = New Collection;
	        $slots_vuoti = New Collection;
	        
      	} else {

	        /**
	         * LA vetrina che ottengo da $viewdata ha già gli slot aeagerloadati
	         * QUINDI aggiorno solo i puntatori
	         */
	         
	        $v = new VetrinaController();
	        $slots = $vetrina->slots;
	        
	        if ($order == "")
	        	{
		        $slots = $v->updatePointer($slots);
	        	}


      	}
      	
      	$view->with(['slots' => $slots]);
		
		
    
    }

}

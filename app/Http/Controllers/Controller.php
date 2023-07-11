<?php
namespace App\Http\Controllers;

use App;
use Auth;
use App\Localita;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;


abstract class Controller extends BaseController
  {
  
  use DispatchesJobs, ValidatesRequests;
  
  private $lat_fallback;
  private $long_fallback;
  private $zoom_fallback;
  
  ////////////////////////////////////////////////////
  // I can call these functions in every controller //
  ////////////////////////////////////////////////////
 
  
  /**
   * [getLocale legge il locale della configurazione corrente eventualmente settato dal middleware Lang]
   * @return [type] [description]
   */
   protected function getLocale() 
    {
    return App::getLocale();
    }
    
   protected function getHotelId()
    {

    /*
     * Puoi accedere all'interfaccia di editing delle informazioni di un hotel solo se:
     * - fai login come hotel
     * - fai login come admin e impersonifichi un hotel
     * altrimenti non devi poter procedere!
     */

    $id = 0;
    if(Auth::user()->hasRole(["admin", "operatore"]))
      $id = Auth::user()->getUiEditingHotelId();
    elseif (Auth::user()->hasRole(["commerciale"]))
      $id = Auth::user()->getCommercialeUiEditingHotelId();
    else
      $id = Auth::user()->hotel_id;

    if(!$id)
      {
        abort(404);
      }
    else
      {
        return $id; 
      }
    }

    
        
  }
  
  

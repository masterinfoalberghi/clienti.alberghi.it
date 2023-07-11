<?php

/**
 * Serie di utilties
 *
 * @author Luca Battarra
 */

use App\User;
use App\Hotel;
use App\Utility;
use App\Localita;
use App\Categoria;
use App\CmsPagina;
use Carbon\Carbon;
use App\Macrolocalita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use BeyondCode\ServerTiming\Facades\ServerTiming;

class Utilities
{
  function serverTiming($method, $msg)
  {
    return ServerTiming::$method($msg);
  }

  function localitaName($l) 
  {
    if (gettype(json_decode($l)) == 'object') {
      return json_decode($l)->it;
    } else {
      return $l;
    }
  }


}
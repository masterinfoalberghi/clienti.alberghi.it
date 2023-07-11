<?php
namespace App\Http\Controllers;

use App\Events\VetrinaClickHandler;
use App\Events\VstClickHandler;
use App\Http\Controllers\Controller;
use App\SlotVetrina;
use App\Utility;
use App\Vetrina;
use App\VetrinaServiziTopLingua;
use Illuminate\Support\Facades\Event;
use Redirector;
use Request;
use Symfony\Component\Routing\Matcher\redirect;


class VetrinaServiziTopLinguaController extends Controller
  {

  private function _checkParams($hotel_id,$vst_id)
    {
      $vstl = VetrinaServiziTopLingua::whereHas('vetrina', function($query) use ($hotel_id) {
              $query
                ->where('hotel_id',$hotel_id)
                ->where('mese','like','%'.date('n').'%');
      })->find($vst_id);

      return is_null($vstl) ? false : $vstl;
    }
  
  public function contaClick($hotel_id,$vst_id) 
    {
      $locale = $this->getLocale();
      
      $vstl = $this->_checkParams($hotel_id,$vst_id);

      if (!$vstl) 
        {
        return redirect(url(Utility::getLocaleUrl($locale).'/'),'301');
        } 
      else 
        {  
        $ref = Request::server('HTTP_REFERER');
        $ua = Request::server('HTTP_USER_AGENT');
        $ip = Utility::get_client_ip();

        //////////////////////////////////////////////////////
        // dall'id del vst devo trovare la pagina associata //
        //////////////////////////////////////////////////////

        $pagina_id = $vstl->pagina_id;


        // contaclick sulla vetrina Top
        ///////////////////////////////////////////////////////////////////////////////////////
        // lo creo come Event perché se lo devo spostare dal click ho già separato la logica //
        ///////////////////////////////////////////////////////////////////////////////////////
        
        Event::dispatch(new VstClickHandler(compact('hotel_id', 'pagina_id' ,'ref','ua','ip')));


        return redirect(url(Utility::getLocaleUrl($locale).'hotel.php?id='.$hotel_id),'301');
        }


    }
    




  
  /**
   * [updatePointer prende una collection di VetrinaServiziTopLingua (quelle online sulla pagina), ne seleziona N a partire dal puntatire, sposta il puntatore]
   * @param  [type] $vst [description]
   * @return [type]      [description]
   */
  public function updatePointer($vst, $uri = "")
    {
    if(empty($uri))
      {
      // n di vst da considerare dal pointer
      define("N_VST_PAGINA", 2);
      }
    else
      {
      if($uri == 'hotel-parcheggio/rimini.php')
        {
        // n di vst da considerare dal pointer
        define("N_VST_PAGINA", 3);
        }
      else
        {
        define("N_VST_PAGINA", 2);
        }
      }


      // final vst 
      $f_vst = [];

      // se ho trovato il pointer
      $trovato = 0;
      
      // se ho messo a 1 il vst successivo
      $settato_nuovo_pointer = 0;
      

      foreach ($vst as $v) 
        {
          if (!$trovato) 
            {
              if ($v->pointer) 
                {
                
                $f_vst[] = $v;  
                
                $v->update(['pointer' => 0]);
                
                $trovato = 1;
                }
            }
            else
            {
              if(count($f_vst) == 1 && count($f_vst) < $vst->count()) 
                { 
                  $v->update(['pointer' => 1]); 
                  $settato_nuovo_pointer = 1;
                  $f_vst[] = $v;
                }
              elseif (count($f_vst) < N_VST_PAGINA && count($f_vst) < $vst->count()) 
                {
                  $f_vst[] = $v;
                }
            }
          
        }

      ///////////////////////////////////////////////////////////////////////////////////////////////////////
      // se esco dal loop e non ho settato il nuovo pointer, vuole dire che l'ho trovato all'ultimo vst !! //
      ///////////////////////////////////////////////////////////////////////////////////////////////////////
      
      //////////////////////////////////////////////////////////////////////////////////////////////////////////
      // se esco dal loop e non ho raggiunto il numero di vst devo continuare                                 //    
      // MA SOLO SE IL NUMERO DI vst CHE HO PER QUESTA PAGINA E' ALMENO UGUALE AL NUMERO CHE DEVO RAGGIUNGERE //
      //////////////////////////////////////////////////////////////////////////////////////////////////////////

      if ( !$settato_nuovo_pointer || (count($f_vst) < N_VST_PAGINA && count($f_vst) < $vst->count()) )
        {

        foreach ($vst as $v) 
          {
            if (!$settato_nuovo_pointer) 
              {

              $settato_nuovo_pointer = 1;
              $vst->first()->update(['pointer' => 1]);

              if (count($f_vst) < N_VST_PAGINA && count($f_vst) < $vst->count()) 
                {
                $f_vst[] = $v;
                }
              
              
              }
            elseif ( count($f_vst) < N_VST_PAGINA  && count($f_vst) < $vst->count() ) 
              {
               $f_vst[] = $v;
              }
            
          }
        }

      return collect($f_vst);

    }



  } // end class

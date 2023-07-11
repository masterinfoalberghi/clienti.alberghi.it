<?php
namespace App\Http\Controllers;

use App\Events\VetrinaClickHandler;
use App\Events\VttClickHandler;
use App\Http\Controllers\Controller;
use App\SlotVetrina;
use App\Utility;
use App\Vetrina;
use App\VetrinaTrattamentoTopLingua;
use Illuminate\Support\Facades\Event;
use Redirector;
use Request;
use Symfony\Component\Routing\Matcher\redirect;


class VetrinaTrattamentoTopLinguaController extends Controller
  {


  private function _checkParams($hotel_id,$vtt_id)
    {
      $vttl = VetrinaTrattamentoTopLingua::whereHas('vetrina', function($query) use ($hotel_id) {
              $query
                ->where('hotel_id',$hotel_id)
                ->where('mese','like','%'.date('n').'%');
      })->find($vtt_id);

      return is_null($vttl) ? false : $vttl;
    }
  
  public function contaClick($hotel_id,$vtt_id) 
    {
      $locale = $this->getLocale();
      
      $vttl = $this->_checkParams($hotel_id,$vtt_id);

      if (!$vttl) 
        {
        return redirect(url(Utility::getLocaleUrl($locale).'/'),'301');
        } 
      else 
        {
        $ref = Request::server('HTTP_REFERER');
        $ua = Request::server('HTTP_USER_AGENT');
        $ip = Utility::get_client_ip();

        //////////////////////////////////////////////////////
        // dall'id del vtt devo trovare la pagina associata //
        //////////////////////////////////////////////////////

        $pagina_id = $vttl->pagina_id;


        // contaclick sulla vetrina Top
        ///////////////////////////////////////////////////////////////////////////////////////
        // lo creo come Event perché se lo devo spostare dal click ho già separato la logica //
        ///////////////////////////////////////////////////////////////////////////////////////
        
        Event::dispatch(new VttClickHandler(compact('hotel_id', 'pagina_id' ,'ref','ua','ip')));


        return redirect(url(Utility::getLocaleUrl($locale).'hotel.php?id='.$hotel_id),'301');
        }


    }




  
  /**
   * [updatePointer prende una collection di VetrinaTrattamentoTopLingua (quelle online sulla pagina), ne seleziona N a partire dal puntatire, sposta il puntatore]
   * @param  [type] $vtt [description]
   * @return [type]      [description]
   */
  public function updatePointer($vtt)
    {

    // n di vtt da considerare dal pointer
    define("N_VTT_PAGINA", 2);


      // final vtt 
      $f_vtt = [];

      // se ho trovato il pointer
      $trovato = 0;
      
      // se ho messo a 1 il vtt successivo
      $settato_nuovo_pointer = 0;
      

      foreach ($vtt as $v) 
        {
          if (!$trovato) 
            {
              if ($v->pointer) 
                {
                
                $f_vtt[] = $v;  
                
                $v->update(['pointer' => 0]);
                
                $trovato = 1;
                }
            }
            else
            {
              if(count($f_vtt) == 1 && count($f_vtt) < $vtt->count()) 
                { 
                  $v->update(['pointer' => 1]); 
                  $settato_nuovo_pointer = 1;
                  $f_vtt[] = $v;
                }
              elseif (count($f_vtt) < N_VTT_PAGINA && count($f_vtt) < $vtt->count()) 
                {
                  $f_vtt[] = $v;
                }
            }
          
        }

      ///////////////////////////////////////////////////////////////////////////////////////////////////////
      // se esco dal loop e non ho settato il nuovo pointer, vuole dire che l'ho trovato all'ultimo vtt !! //
      ///////////////////////////////////////////////////////////////////////////////////////////////////////
      
      //////////////////////////////////////////////////////////////////////////////////////////////////////////
      // se esco dal loop e non ho raggiunto il numero di vtt devo continuare                                 //    
      // MA SOLO SE IL NUMERO DI vtt CHE HO PER QUESTA PAGINA E' ALMENO UGUALE AL NUMERO CHE DEVO RAGGIUNGERE //
      //////////////////////////////////////////////////////////////////////////////////////////////////////////

      if ( !$settato_nuovo_pointer || (count($f_vtt) < N_VTT_PAGINA && count($f_vtt) < $vtt->count()) )
        {

        foreach ($vtt as $v) 
          {
            if (!$settato_nuovo_pointer) 
              {

              $settato_nuovo_pointer = 1;
              $vtt->first()->update(['pointer' => 1]);

              if (count($f_vtt) < N_VTT_PAGINA && count($f_vtt) < $vtt->count()) 
                {
                $f_vtt[] = $v;
                }
              
              
              }
            elseif ( count($f_vtt) < N_VTT_PAGINA  && count($f_vtt) < $vtt->count() ) 
              {
               $f_vtt[] = $v;
              }
            
          }
        }

      return collect($f_vtt);

    }



  } // end class

<?php
namespace App\Http\Controllers;

use App\Events\VaatClickHandler;
use App\Events\VetrinaClickHandler;
use App\Http\Controllers\Controller;
use App\SlotVetrina;
use App\Utility;
use App\Vetrina;
use App\VetrinaBambiniGratisTopLingua;
use App\VetrinaOffertaTopLingua;
use Illuminate\Support\Facades\Event;
use Redirector;
use Request;
use Symfony\Component\Routing\Matcher\redirect;


class VetrinaBambiniGratisTopLinguaController extends Controller {
	
	private function _checkParams($hotel_id,$vaat_id) {
		$vaatl = VetrinaBambiniGratisTopLingua::whereHas('offerta', function($query) use ($hotel_id) {
			$query
				->where('hotel_id',$hotel_id)
				->where('mese','like','%'.date('n').'%');
			})->find($vaat_id);
		return is_null($vaatl) ? false : $vaatl;
	}
	
	public function contaClick($hotel_id, $vaat_id, $offer_id = 0) {

		$locale = $this->getLocale();
		$vaatl = $this->_checkParams($hotel_id,$vaat_id);
		
		if (!$vaatl) 
			return redirect(url(Utility::getLocaleUrl($locale).'/'),'301');
			
		else {

			$ref = Request::server('HTTP_REFERER');
			$ua = Request::server('HTTP_USER_AGENT');
			$ip = Utility::get_client_ip();

			$pagina_id = $vaatl->pagina_id;

			Event::dispatch(new VaatClickHandler(compact('hotel_id', 'pagina_id' ,'ref','ua','ip')));
			
			$url = Utility::getLocaleUrl($locale).'hotel.php?id='.$hotel_id.'&children-offers';
			if ($offer_id != 0)
				$url .= "#" . $offer_id;
				
			return redirect($url ,'301');

		}

	}
  
	/**
	 * [updatePointer prende una collection di VetrinaBambiniGratisTopLingua (quelle online sulla pagina), ne seleziona N a partire dal puntatire, sposta il puntatore]
	 * @param  [type] $vaat [description]
	 * @return [type]	  [description]
	 */
  	public function updatePointer($vaat) {

	// n di vaat da considerare dal pointer
	define("N_VAAT_PAGINA", 2);


	  // final vaat 
	  $f_vaat = [];

	  // se ho trovato il pointer
	  $trovato = 0;
	  
	  // se ho messo a 1 il vaat successivo
	  $settato_nuovo_pointer = 0;
	  

	  foreach ($vaat as $v) 
		{
		  if (!$trovato) 
			{
			  if ($v->pointer) 
				{
				
				$f_vaat[] = $v;  
				
				$v->update(['pointer' => 0]);
				
				$trovato = 1;
				}
			}
			else
			{
			  if(count($f_vaat) == 1 && count($f_vaat) < $vaat->count()) 
				{ 
				  $v->update(['pointer' => 1]); 
				  $settato_nuovo_pointer = 1;
				  $f_vaat[] = $v;
				}
			  elseif (count($f_vaat) < N_VAAT_PAGINA && count($f_vaat) < $vaat->count()) 
				{
				  $f_vaat[] = $v;
				}
			}
		  
		}

	  ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  // se esco dal loop e non ho settato il nuovo pointer, vuole dire che l'ho trovato all'ultimo vaat !! //
	  ///////////////////////////////////////////////////////////////////////////////////////////////////////
	  
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////
	  // se esco dal loop e non ho raggiunto il numero di vaat devo continuare								 //	
	  // MA SOLO SE IL NUMERO DI vaat CHE HO PER QUESTA PAGINA E' ALMENO UGUALE AL NUMERO CHE DEVO RAGGIUNGERE //
	  //////////////////////////////////////////////////////////////////////////////////////////////////////////

	  if ( !$settato_nuovo_pointer || (count($f_vaat) < N_VAAT_PAGINA && count($f_vaat) < $vaat->count()) )
		{

		foreach ($vaat as $v) 
		  {
			if (!$settato_nuovo_pointer) 
			  {

			  $settato_nuovo_pointer = 1;
			  $vaat->first()->update(['pointer' => 1]);

			  if (count($f_vaat) < N_VAAT_PAGINA && count($f_vaat) < $vaat->count()) 
				{
				$f_vaat[] = $v;
				}
			  
			  
			  }
			elseif ( count($f_vaat) < N_VAAT_PAGINA  && count($f_vaat) < $vaat->count() ) 
			  {
			   $f_vaat[] = $v;
			  }
			
		  }
		}

	  return collect($f_vaat);

	}



  } // end class

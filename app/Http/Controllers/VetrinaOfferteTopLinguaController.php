<?php
namespace App\Http\Controllers;

use App\Events\VetrinaClickHandler;
use App\Events\VotClickHandler;
use App\Http\Controllers\Controller;
use App\SlotVetrina;
use App\Utility;
use App\Vetrina;
use App\VetrinaOffertaTop;
use App\VetrinaOffertaTopLingua;
use Illuminate\Support\Facades\Event;
use Redirector;
use Request;
use Symfony\Component\Routing\Matcher\redirect;


class VetrinaOfferteTopLinguaController extends Controller
{

	private function _checkParams($hotel_id, $vot_id)
	{
		$votl = VetrinaOffertaTopLingua::whereHas('offerta', function($query) use ($hotel_id)
			{
				$query
				->where('hotel_id', $hotel_id)
				->where('mese', 'like', '%'.date('n').'%');
			})->find($vot_id);

		return is_null($votl) ? false : $votl;
	}


	public function contaClick($hotel_id, $vot_id, $offer_id = 0)
	{
		$locale = $this->getLocale();
		$votl = $this->_checkParams($hotel_id, $vot_id);

		if (!$votl)
			return redirect(url(Utility::getLocaleUrl($locale).'/'), '301');
			
		else {
			
			$vot = VetrinaOffertaTop::find($votl->master_id);	
			$ref = Request::server('HTTP_REFERER');
			$ua = Request::server('HTTP_USER_AGENT');
			$ip = Utility::get_client_ip();

			//////////////////////////////////////////////////////
			// dall'id del vot devo trovare la pagina associata //
			//////////////////////////////////////////////////////

			$pagina_id = $votl->pagina_id;

			// contaclick sulla vetrina Top
			///////////////////////////////////////////////////////////////////////////////////////
			// lo creo come Event perché se lo devo spostare dal click ho già separato la logica //
			///////////////////////////////////////////////////////////////////////////////////////

			Event::dispatch(new VotClickHandler(compact('hotel_id', 'pagina_id' , 'ref', 'ua', 'ip')));

			($vot->tipo == 'offerta') ? $tipo =  'offers' : $tipo = $vot->tipo;
			
			
			
			$url = Utility::getLocaleUrl($locale).'hotel.php?id='.$hotel_id.'&' .$tipo;
			if ($offer_id != 0)
				$url .= "#" . $offer_id;
			
			return redirect($url, '301');

		}

	}





	/**
	 * [updatePointer prende una collection di VetrinaOffertaTopLingua (quelle online sulla pagina), ne seleziona N a partire dal puntatire, sposta il puntatore]
	 * @param  [type] $vot [description]
	 * @return [type]      [description]
	 */
	public function updatePointer($vot)
	{

		// n di vot da considerare dal pointer
		define("N_VOT_PAGINA", 2);


		// final vot
		$f_vot = [];

		// se ho trovato il pointer
		$trovato = 0;

		// se ho messo a 1 il vot successivo
		$settato_nuovo_pointer = 0;


		foreach ($vot as $v) {
			if (!$trovato) {
				if ($v->pointer) {

					$f_vot[] = $v;

					$v->update(['pointer' => 0]);

					$trovato = 1;
				}
			}
			else {
				if (count($f_vot) == 1 && count($f_vot) < $vot->count()) {
					$v->update(['pointer' => 1]);
					$settato_nuovo_pointer = 1;
					$f_vot[] = $v;
				}
				elseif (count($f_vot) < N_VOT_PAGINA && count($f_vot) < $vot->count()) {
					$f_vot[] = $v;
				}
			}

		}

		///////////////////////////////////////////////////////////////////////////////////////////////////////
		// se esco dal loop e non ho settato il nuovo pointer, vuole dire che l'ho trovato all'ultimo vot !! //
		///////////////////////////////////////////////////////////////////////////////////////////////////////

		//////////////////////////////////////////////////////////////////////////////////////////////////////////
		// se esco dal loop e non ho raggiunto il numero di vot devo continuare                                 //
		// MA SOLO SE IL NUMERO DI VOT CHE HO PER QUESTA PAGINA E' ALMENO UGUALE AL NUMERO CHE DEVO RAGGIUNGERE //
		//////////////////////////////////////////////////////////////////////////////////////////////////////////

		if ( !$settato_nuovo_pointer || (count($f_vot) < N_VOT_PAGINA && count($f_vot) < $vot->count()) ) {

			foreach ($vot as $v) {
				if (!$settato_nuovo_pointer) {

					$settato_nuovo_pointer = 1;
					$vot->first()->update(['pointer' => 1]);

					if (count($f_vot) < N_VOT_PAGINA && count($f_vot) < $vot->count()) {
						$f_vot[] = $v;
					}


				}
				elseif ( count($f_vot) < N_VOT_PAGINA  && count($f_vot) < $vot->count() ) {
					$f_vot[] = $v;
				}

			}
		}

		return collect($f_vot);

	}



} // end class

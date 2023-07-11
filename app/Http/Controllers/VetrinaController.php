<?php
namespace App\Http\Controllers;

use App\Events\VetrinaClickHandler;
use App\Http\Controllers\Controller;
use App\SlotVetrina;
use App\Utility;
use App\Vetrina;
use Illuminate\Support\Facades\Event;
use Redirector;
use Request;
use Config;
use Symfony\Component\Routing\Matcher\redirect;


class VetrinaController extends Controller
{
	public function contaClick($hotel_id, $slot_id, $vetrina_id)
	{
		$ref = Request::server('HTTP_REFERER');
		$ua = Request::server('HTTP_USER_AGENT');
		$ip = Utility::get_client_ip();


		$locale = $this->getLocale();


		// contaclick sulla vetrina
		///////////////////////////////////////////////////////////////////////////////////////
		// lo creo come Event perché se lo devo spostare dal click ho già separato la logica //
		///////////////////////////////////////////////////////////////////////////////////////
		Event::dispatch(new VetrinaClickHandler(compact('slot_id', 'vetrina_id', 'hotel_id', 'ref', 'ua', 'ip')));

		return redirect(url(Utility::getLocaleUrl($locale).'hotel.php?id='.$hotel_id), '301');

	}


	public function updatePointerSlotVetrina(Vetrina $vetrina, $listing_categorie = 0, $locale = 'it')
	{
			
			$slots = $vetrina
				->getSlots($listing_categorie)
				->withClienteEagerLoaded($locale)
				->orderBy('posizione', 'asc')
				->get();

			$slots = $this->updatePointer($slots);
				
		return $slots;

	}


	public function updatePointer($slots)
	{


		/* setto 2 array che mi definiscono gli slot prima del pointer (che vanno messi in fondo) e gli altri */

		$dopo_il_pointer = array();
		$prima_del_pointer = array();
		$trovato = 0;

		foreach ($slots as $slot) {
		
			if (!$trovato) {
				
				if ($slot->pointer) {
					
					/* l'ho trovato nn lo cerco più */
					$trovato = 1;

					/* il prossimo è quello che avrà il pointer = 1*/
					$slot->update(['pointer' => 0]);
				}

				$prima_del_pointer[] = $slot;
			}
			
			else {
				
				/*il primo dopo il pointer è quello a cui deve puntare il pointer successivo*/
				if (count($dopo_il_pointer) == 0) {
					$slot->update(['pointer' => 1]);
				}
				
				$dopo_il_pointer[] = $slot;
			}



		} // end loop slots


		/* SE esco dal loop con l'array dopo_il_pointer[] vuoto, vuol dire che quello che aveva il pointer era l'ultimo e quindi adesso lo devo mettere al PRIMO */

		if (count($dopo_il_pointer) == 0 && count($prima_del_pointer) > 0) {
			$prima_del_pointer[0]->update(['pointer' =>1]);
		}


		$slots = collect(array_merge($dopo_il_pointer, $prima_del_pointer));


		return $slots;
	}



} // end class

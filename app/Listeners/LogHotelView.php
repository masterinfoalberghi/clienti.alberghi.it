<?php

namespace App\Listeners;

use Request;
use Config;
use App\Events\HotelViewHandler;
use App\Hotel;
use App\CookieIA;
use App\StatHotel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogHotelView
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  HotelViewHandler  $event
     * @return void
     */
     
    public function handle(HotelViewHandler $event)
    {
	    

		// Quando abbiamo diviso la scheda in varie parti abbiamo aggiunto gli eventi su tutte le parti ( pagine ) nuove.
		// Quindi togliamo il conteggio se sono in una pagina che ha referer hotel.php

		if (($event->dati['referer'] && !strrpos($event->dati['referer'], "/hotel.php")) || !$event->dati['referer']) {

	    
			if (!$event->dati['referer'] )
				$event->dati['referer'] = "diretto"; 
			
			/**
			 * Trovo il codice univoco del cliente che ho nei cookie
			 */

			$cookie = CookieIA::getCookiePrefill();
			$codice_cookie = $cookie["codice_cookie"];
					
			/**
			 * Cerco il codice di questo hotel Hotel_ID 
			 */
			 
			if (Config::get("hotel.blocco_click_su_ip")) {
			 
				$controllo = StatHotel::where("codice_cookie", $codice_cookie)
								->where("hotel_id", $event->dati['cliente']->id)
								->where("created_at", ">=", date("Y-m-d 00:00:00"))
								->first();
				
				if (is_null($controllo)) {

					$hotel = Hotel::find($event->dati['cliente']->id);
			        $numero_click = $hotel->numero_click;
			        $hotel->numero_click = $numero_click + 1;
			        $hotel->save();

				}
								
			} else {
				
				$hotel = Hotel::find($event->dati['cliente']->id);
				$numero_click = $hotel->numero_click;
				$hotel->numero_click = $numero_click + 1;
				$hotel->save();
				
			}

			/**
			 * Aggiorno il numero_click solo che nell'arco dell 24 ore precedenti 
			 * non ho un utente con lo stesso codice per evitare che qualche furbo non voglia
			 * Cliccare l'hotel del rivale per non farlo abbassare nel linsting
			 */
			 
		
			

			/**
			 * Scrivo sempre le statistiche correttamente
			 */

	        $stat = StatHotel::create([

                'hotel_id' => $event->dati['cliente']->id,
                'lang_id' => $event->dati['locale'],
                'referer' => $event->dati['referer'],
                'useragent' => (string)$event->dati['ua'],
                'codice_cookie' => $codice_cookie,
                'IP' => (string)$event->dati['ip']
                
            ]);


	        

        } 

    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\GruppoServiziCovid;
use Illuminate\Http\Request;

class ServiziCovidController extends AdminBaseController
{
    public function index()
    {
			$hotel_id = $this->getHotelId();
			$hotel = Hotel::find($hotel_id);

			// array con id_servizio => distanza
			// [
			// 	9 => "50",
			// 	10 => null,
			// 	12 => null,
			// 	....
			// ]
			
			try {
				$servizi_ids = $hotel->serviziCovid->pluck('pivot.distanza','id')->toArray();
	
				$gruppi_servizi_covid = GruppoServiziCovid::with('servizi')->get();
					
				return view('admin.servizi-covid', compact("gruppi_servizi_covid", "servizi_ids"));

			} catch (\Exception $e) {
				
				echo "Errore. Manca la tabella ?? ";
			
			}



    }


    public function store(Request $request)
    {
			$hotel_id = $this->getHotelId();
			// inserisco gli ID
			$hotel = Hotel::find($hotel_id);

			if ($request->has('servizio_covid') && count($request->servizio_covid)) {
				
				$hotel->serviziCovid()->sync($request->servizio_covid);

				// modifico quelli che hanno il campo pivot
				foreach ($request->servizio_covid as $servizio_id) {

					if ( $request->has('distanza_'.$servizio_id) && $request->get('distanza_' . $servizio_id) != '')  {

						$hotel->serviziCovid()->updateExistingPivot( $servizio_id, ['distanza' => $request->get('distanza_'.$servizio_id)] ) ;
						
					}

				}


			} else  {

				$hotel->serviziCovid()->detach();
			
			}





			return redirect('admin/servizi-hotel/servizi-covid')->with('status', 'Lista servizi aggiornata correttamente');

    }
}

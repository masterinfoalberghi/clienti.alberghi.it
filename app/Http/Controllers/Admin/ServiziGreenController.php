<?php

namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\GruppoServiziGreen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiziGreenController extends Controller
{
    public function index() {

        $hotel_id = $this->getHotelId();
        $hotel = Hotel::find($hotel_id);


        try {
            $servizi_ids = $hotel->serviziGreen->pluck('pivot.altro', 'id')->toArray();

            $gruppi_servizi_green = GruppoServiziGreen::with('servizi')->get();

            return view('admin.servizi-green', compact("gruppi_servizi_green", "servizi_ids"));
        } catch (\Exception $e) {

            echo "Errore. Manca la tabella ?? ";
        }
    }



    public function store(Request $request) {

        $hotel_id = $this->getHotelId();
        // inserisco gli ID
        $hotel = Hotel::find($hotel_id);

        if ($request->has('servizio_green') && count($request->servizio_green)) {

            $hotel->serviziGreen()->sync($request->servizio_green);

            // modifico quelli che hanno il campo pivot
            foreach ($request->servizio_green as $servizio_id) {

                if (
                    $request->has('altro_' . $servizio_id) && $request->get('altro_' . $servizio_id) != ''
                ) {

                    $hotel->serviziGreen()->updateExistingPivot($servizio_id, ['altro' => $request->get('altro_' . $servizio_id)]);
                }
            }
        } else {

            $hotel->serviziGreen()->detach();
        }


        return redirect('admin/servizi-hotel/servizi-green')->with('status', 'Lista servizi aggiornata correttamente');
    }


}

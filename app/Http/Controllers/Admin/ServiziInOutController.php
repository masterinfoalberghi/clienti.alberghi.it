<?php

namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\GruppoServiziInOut;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiziInOutController extends AdminBaseController
{
    function index()
    {

        $hotel_id = $this->getHotelId();
        $hotel = Hotel::with('serviziInOut')->find($hotel_id);

        


        try {

            /**
             * Costruisco un array con key = id_servizio
             * 
             * 
                array:3 [▼
                    1 => array:3 [▼
                        "valore_ora" => null
                        "valore_minuti" => null
                        "opzione" => "come da accordi"
                    ]
                    2 => array:3 [▼
                        "valore_ora" => null
                        "valore_minuti" => null
                        "opzione" => "gratis"
                    ]
                    3 => array:3 [▼
                        "valore_ora" => "13"
                        "valore_minuti" => "0"
                        "opzione" => "gratis"
                    ]
                ]
             * 
             */

            $servizi_ids = [];

            foreach ($hotel->serviziInOut as $servizio) {

                //$dati['valore'] = $servizio->pivot->valore;
                if (!is_null($servizio->pivot->valore_1)) {
                    list($ora, $minuti) = explode(':', $servizio->pivot->valore_1);
                    $dati['valore_1_ora'] = $ora;
                    $dati['valore_1_minuti'] = $minuti;
                } else {
                    $dati['valore_1_ora'] = null;
                    $dati['valore_1_minuti'] = null;
                }
                if (!is_null($servizio->pivot->valore_2)) {
                    list($ora, $minuti) = explode(':', $servizio->pivot->valore_2);
                    $dati['valore_2_ora'] = $ora;
                    $dati['valore_2_minuti'] = $minuti;
                } else {
                    $dati['valore_2_ora'] = null;
                    $dati['valore_2_minuti'] = null;
                }
                $dati['opzione'] = $servizio->pivot->opzione;
                $servizi_ids[$servizio->id] = $dati;
            }

            //dd($servizi_ids);

            $gruppi_servizi_inout = GruppoServiziInOut::with('servizi')->get();



            return view('admin.servizi-inout', compact("gruppi_servizi_inout", "servizi_ids"));
        } catch (\Exception $e) {

            echo "Errore. Manca la tabella ?? ";
        }
    }

    public function store(Request $request)
    {

        $hotel_id = $this->getHotelId();
        $hotel = Hotel::with('serviziInOut')->find($hotel_id);

    
        //dd($request->all());
        if ($request->has('servizio_inout') && count($request->servizio_inout)) {

            $hotel->serviziInOut()->sync($request->servizio_inout);

            // modifico quelli che hanno il campo pivot options_
            foreach ($request->servizio_inout as $servizio_id) {

                $to_update = [];

                if ($request->get('options_' . $servizio_id) != '') {
                    $to_update['opzione'] = $request->get('options_' . $servizio_id);
                }

                if ($request->get('da_ora_' . $servizio_id) != '') {
                    $minuti = $request->get('da_minuti_' . $servizio_id) == '' ? '00' : $request->get('da_minuti_' . $servizio_id);
                    $to_update['valore_1'] = $request->get('da_ora_' . $servizio_id) . ':' . $minuti;
                }

                if ($request->get('a_ora_' . $servizio_id) != '') {
                    $minuti = $request->get('a_minuti_' . $servizio_id) == '' ? '00' : $request->get('a_minuti_' . $servizio_id);

                    $to_update['valore_2'] = $request->get('a_ora_' . $servizio_id) . ':' . $minuti;
                }

                if (!empty($to_update)) {
                    $hotel->serviziInOut()->updateExistingPivot($servizio_id, $to_update);
                }
            }
        } else {

            $hotel->serviziInOut()->detach();
        }

        return redirect('admin/servizi-hotel/servizi-in-out')->with('status', 'Lista servizi aggiornata correttamente');

        
    }
}

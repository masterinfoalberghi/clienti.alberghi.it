<?php

namespace App\Http\Controllers\Admin;

use Auth;
Use Carbon\Carbon;
use App\TassaSoggiorno;
use Illuminate\Http\Request;
use SessionResponseMessages;

class TassaSoggiornoController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

		if (Auth::user()->hasRole("hotel"))
			$hotel_id = Auth::user()->hotel_id;
		else
			$hotel_id = Auth::user()->getUiEditingHotelId();

		if (!$hotel_id)
			SessionResponseMessages::add('error', "Per entrare nelle 'Tassa soggiorno' devi prima selezionare un hotel.");
		else { 
			
			$data = [];
			$data['id'] = $hotel_id;
			$results = TassaSoggiorno::where('hotel_id', '=', $hotel_id)->first();
			
			if ($results) {
			
				$data['attiva'] = $results->attiva;
				$data['applicata'] = $results->applicata;
				$data['record'] = $results;
				
			} else {
				
				$data['attiva'] = false;
				$data['applicata'] = false;
				$data['record'] = [];
				$data['record']["inclusa"] = false;
				$data['record']["applicata"] = false;
				$data['record']["valore"] = 0;
				$data['record']["bambini_esenti"] = false;
				$data['record']["eta_bambini_esenti"] = 0;
				
				$data['record']["validita_data"] = 0;
				$data['record']["data_iniziale"] = "2000-01-01";
				$data['record']["data_finale"] = "2000-01-01";
				$data['record']["max_giorni"] = 0;
				
			}
			
		}
		
		if (SessionResponseMessages::hasErrors())
			return SessionResponseMessages::redirect('admin', $request);
		else
			return view('admin.tassa-soggiorno', compact('data'));
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if ($request->method() == 'POST') {
			
			$tassasoggiorno = TassaSoggiorno::where("hotel_id", $request->input('id'))->first();
			
			if (!$tassasoggiorno) 
				$tassasoggiorno = new TassaSoggiorno;
				
			$tassasoggiorno->hotel_id = $request->input('id');
			$tassasoggiorno->attiva = $request->input('attiva')  ? $request->input('attiva') : false;
			$tassasoggiorno->applicata = $request->input('applicata') ? $request->input('applicata') : false;
			$tassasoggiorno->inclusa = $request->input('inclusa') ? $request->input('inclusa') : false;
			$tassasoggiorno->valore = $request->input('valore');
			
			$tassasoggiorno->bambini_esenti = $request->input('bambini_esenti') ? $request->input('bambini_esenti') : false;
			$tassasoggiorno->eta_bambini_esenti = $request->input('eta_bambini_esenti');
			$tassasoggiorno->validita_data =  $request->input('validita_data') ? $request->input('validita_data') : false;

			if ($request->input('data_iniziale'))
				$tassasoggiorno->data_iniziale = Carbon::createFromFormat("d/m/Y", $request->input('data_iniziale'));
			else 
				$tassasoggiorno->data_iniziale = Carbon::now();
			
			if ($request->input('data_finale'))
				$tassasoggiorno->data_finale = Carbon::createFromFormat("d/m/Y", $request->input('data_finale'));
			else 
				$tassasoggiorno->data_finale = Carbon::now();
				
			$tassasoggiorno->max_giorni = $request->input('max_giorni');
			$tassasoggiorno->save();
			
			SessionResponseMessages::add('success', 'Tassa di soggiorno aggiornata con successo');
			
		}
        
		return SessionResponseMessages::redirect("admin/tasse-soggiorno", $request);
		
    }


}

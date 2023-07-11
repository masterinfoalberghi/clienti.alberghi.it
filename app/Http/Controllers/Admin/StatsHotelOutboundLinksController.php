<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StatsHotelOutboundLinkRead;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
use SessionResponseMessages;

class StatsHotelOutboundLinksController extends Controller
{
	
	protected $lowest_year = 0;

	protected function getAllYears()
	{
		
		$years = StatsHotelOutboundLinkRead::distinct('anno')
				->orderBy('anno', 'desc')
				->pluck('anno', 'anno');

		return $years;
		
	}

	protected function getAllMonthShort()
	{
	
		$mesi = [];
		foreach (range(1, 12) as $monthNumber) {
			$date = new Carbon();
			$date->setDate(2015, $monthNumber, 1);
			$mesi[$monthNumber] = $date;
		}
		return $mesi;
	
	}

	public function form()
	{
		
		$data = [];
		$stats = [];
		$data['anni'] = $this->getAllYears();
		return view('admin.stats_outbound_links', compact('data', 'stats'));
		
	}



	public function results(Request $request)
	{

		$request->flash();
		
		if(Auth::user()->hasRole(["admin", "operatore", "commerciale"]))
			$hotel_id = (int)$request->input('hotel');
			
		elseif(Auth::user()->hasRole(["hotel"]))
			$hotel_id = Auth::user()->hotel_id;
		
		if (empty($hotel_id)) {
				
			SessionResponseMessages::add ('error', 'Devi selezionare almeno un hotel');
			return SessionResponseMessages::redirect($request->getUri(), $request);
			
		}
		
		if (!empty($request->input('date_range'))) {
			
			$date_range = explode(" - ", $request->input('date_range'));
			$date_from = Carbon::createFromFormat("d/m/Y", $date_range[0]);
			$date_to = Carbon::createFromFormat("d/m/Y", $date_range[1]);
			
		} else {
			
			SessionResponseMessages::add ('error', 'Entrambe le date devono essere compilate');
			return SessionResponseMessages::redirect($request->getUri(), $request);
			
		}

		$stats = [];

		$query = DB::table("tblStatsHotelOutboundLinkRead")
			->selectRaw('anno,mese,giorno,hotel_id,visits')
			->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
			->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
			->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))');
			
		if($hotel_id)
			$query = $query->where("hotel_id", $hotel_id);
			
		//dd($query->toSql());
		$results = $query->get();
		
		/*dd($results);
		0 => {#1062 ▼
		     +"anno": 2018
		     +"mese": 1
		     +"giorno": "1"
		     +"hotel_id": 1654
		     +"visits": 2
		   }
		   1 => {#1063 ▼
		     +"anno": 2018
		     +"mese": 1
		     +"giorno": "2"
		     +"hotel_id": 1654
		     +"visits": 1
		   }*/

		if (count($results) == 0) {
			SessionResponseMessages::add ('error', 'Nessun dato trovato per questo intervallo');
			return SessionResponseMessages::redirect($request->getUri(), $request);
		}
		
		$lastMonth = "";
		$stats_list = array();	
		$totale = 0;
		
		$super_totale = 0;

		foreach ($results as $result):
			
			$cdate =  Carbon::createFromFormat('Y-m-d', $result->anno."-".$result->mese."-".$result->giorno);
			
			if ($lastMonth == "")
				$lastMonth = $cdate->format("M");
			
			if ($lastMonth != $cdate->format("M") ):
			 	$lastMonth = $cdate->format("M");
				$totale = 0;
			endif;


			if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")]))
				$stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] = 0;
			
			$stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] += $result->visits;
			$totale += (int)$result->visits;
			$super_totale += (int)$result->visits;
						
			$stats[$cdate->format("Y")][$cdate->format("M")]['totale'] = $totale;

			//echo "Totale ". $cdate->format("M") . " = " .$totale . "<br>";
			
			
		endforeach;
		
		// Normalizzo
		
		$da = $date_from;
		$a = $date_to;
			
		for($cdate = $da; $cdate <= $a; $cdate->addDay()) {
						
			if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")]))
				$stats[$cdate->format("Y")][$cdate->format("M")]['giorni'][$cdate->format("d")] = 0;	
			
			if (!isset($stats[$cdate->format("Y")][$cdate->format("M")]['totale']))
				$stats[$cdate->format("Y")][$cdate->format("M")]['totale'] = 0;
				
		}
		
		//dd($stats);
		return view('admin.stats_outbound_links', compact('stats', 'super_totale'));
		
		
	}
	

}

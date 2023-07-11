<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Hotel;
use App\Utility;
use App\Vetrina;
use App\CmsPagina;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SessionResponseMessages;

class StatsVetrineController extends Controller
{

	private static $anni = [];
	protected $lowest_year = 0;

	/**
	 * Construst function
	 */
	
	public function __construct()
	{
		for ($i=0; $i < 4; $i++)	{ 
			$anno = date('Y') +1 -$i;
			self::$anni[] = $anno;
		}
	}

	protected function getMesi() 
	{
		return [1 => "Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"];
	}
	
	protected function getLaravelVetrine() 
	{
		
		$data = Vetrina::all()->pluck('nome', 'id')->toArray();
		ksort($data);
		return $data;
		
	}

	protected function getLaravelHotels()
	{
		
		$data = [];
		$hotels = Hotel::pluck('nome', 'id');		
		foreach($hotels as $id => $nome) 
		{
			$data[$id] = $id . ' - ' .$nome;
		}
		return $data;
		
	}

	public function getPages()
	{
		$data = CmsPagina::all()->pluck('uri', 'id')->toArray();
		asort($data);
		return $data;
	}

	public function getPagesStats($table)
	{

		$ids = DB::table($table)->distinct()->pluck('pagina_id');
		$data = CmsPagina::whereIn('id', $ids)->pluck('uri', 'id')->toArray();
		asort($data);
		return $data;

	}

	/* --------------------------------------------------------------------------------- 
	 * VIew
	 * --------------------------------------------------------------------------------- */

	public function laravelEraForm()
	{
		
		$data = [];
		// $stats = [];
		//$stats_chart = [];
		$vetrine = [];
		$totali = 0;
		$raggruppa = "";
		$totali_click = 0;
		$number_day = 1;
		$data['vetrine'] = $this->getLaravelVetrine();
		return view('admin.stats_vetrine', compact( 'data', 'vetrine', 'totali', 'raggruppa', 'totali_click', 'number_day'));
		
	}

	public function vetrinePagineForm()
	{
		
		$data = [];
		$stats = [];
		$stats_chart = [];
		$data['pages'] = $this->getPages();
		return view('admin.stats_vetrine_pagine', compact('data', 'stats','stats_chart'));
		
	}

	public function vetrinePagineFormSimple()
	{
		
		$stats = [];
		$stats_chart = [];
		$min_val_year = end(self::$anni);
		$max_val_year = reset(self::$anni);
		return view('admin.stats_vetrine_pagine_simple', compact('stats','min_val_year','max_val_year','stats_chart'));
		
	}
	
	public function votVetrinePagineFormPage()
	{
		
		$stats = [];
		$stats_chart = [];
		$pages = $this->getPagesStats("tblStatsVotRead");
		$min_val_year = end(self::$anni);
		$max_val_year = reset(self::$anni);
		return view('admin.stats_vetrine_pagine_page', compact('stats','min_val_year','max_val_year','pages','stats_chart'));
		
	}

 	public function vstVetrinePagineFormPage()
	{
	
		$stats = [];
		$stats_chart = []; 
		$pages = $this->getPagesStats("tblStatsVstRead");
		$min_val_year = end(self::$anni);
		$max_val_year = reset(self::$anni);
		return view('admin.stats_vetrine_pagine_page', compact('stats','min_val_year','max_val_year','pages','stats_chart'));
	
	}

	public function vttVetrinePagineFormPage()
	{
	
		$stats = [];
		$stats_chart = [];
		$pages = $this->getPagesStats("tblStatsVttRead");
		$min_val_year = end(self::$anni);
		$max_val_year = reset(self::$anni);
		return view('admin.stats_vetrine_pagine_page', compact('stats','min_val_year','max_val_year','pages','stats_chart'));
	
	}

	public function vaatVetrinePagineFormPage()
	{
		
		$stats = [];
		$stats_chart = [];
		$pages = $this->getPagesStats("tblStatsVaatRead");
		$min_val_year = end(self::$anni);
		$max_val_year = reset(self::$anni);
		return view('admin.stats_vetrine_pagine_page', compact('stats','min_val_year','max_val_year','pages','stats_chart'));
	
	}
	
	/* --------------------------------------------------------------------------------- 
	 * RESULT CALL
	 * --------------------------------------------------------------------------------- */
	
	public function vaatResults(Request $request)
	{
		return $this->vetrinePagineResults($request, "tblStatsVaatRead");
	}
	
	public function vaatResultsSimple(Request $request)
	{
		return $this->vetrinePagineResultsSimple($request, "tblStatsVaatRead");
	}
	
	public function vaatResultsPage(Request $request)
	{
		return $this->vetrinePagineResultsPage($request, "tblStatsVaatRead");
	}

	public function votResults(Request $request)
	{
		return $this->vetrinePagineResults($request, "tblStatsVotRead");
	}
	
	public function votResultsSimple(Request $request)
	{
		return $this->vetrinePagineResultsSimple($request, "tblStatsVotRead");
	}
	
	public function votResultsPage(Request $request)
	{
		return $this->vetrinePagineResultsPage($request, "tblStatsVotRead");
	}
	
	public function vstResults(Request $request)
	{
		return $this->vetrinePagineResults($request, "tblStatsVstRead");
	}
	
	public function vstResultsSimple(Request $request)
	{
		return $this->vetrinePagineResultsSimple($request, "tblStatsVstRead");
	}
	
	public function vstResultsPage(Request $request)
	{
		return $this->vetrinePagineResultsPage($request, "tblStatsVstRead");
	}

	public function vttResults(Request $request)
	{
		return $this->vetrinePagineResults($request, "tblStatsVttRead");
	}
	
	public function vttResultsSimple(Request $request)
	{
		return $this->vetrinePagineResultsSimple($request, "tblStatsVttRead");
	}
	
	public function vttResultsPage(Request $request)
	{
		return $this->vetrinePagineResultsPage($request, "tblStatsVttRead");
	}
	
	/* --------------------------------------------------------------------------------- 
	 * RESULT
	 * --------------------------------------------------------------------------------- */
	
	public function laravelEraResults(Request $request)
	{
		
		$data = [];
		$stats = [];
		$request->flash();
		$data['vetrine'] = $this->getLaravelVetrine();

		/*
		 * Arriva nel formato
		 * "17 Hotel Sabrina"
		 * quindi passandolo per il casting int ottengo: 17
		 */

		$hotel_id = (int)$request->input('hotel');
		$vetrina_id = $request->input('vetrina_id');
		$raggruppa = $request->input('raggruppa');
			
		$aggregazione = "d";  //$request->input('aggregazione');
		if ($raggruppa)
			$aggregazione = "m";
		
		$date_range = [];
		
		if (!empty($request->input('date_range'))) {
			
			$date_range = explode(" - ", $request->input('date_range'));
			$date_from = Carbon::createFromFormat("d/m/Y", $date_range[0]);
			$date_to = Carbon::createFromFormat("d/m/Y", $date_range[1]);
			$number_day = $date_to->diffInDays($date_from);
			
		} else {
			
			SessionResponseMessages::add ('error', 'Entrambe le date devono essere compilate');
			return SessionResponseMessages::redirect("/admin/stats/vetrine/laravel-era", $request);
			
		}
		
		if (empty($hotel_id)) {
				
			SessionResponseMessages::add ('error', 'Selezionare almeno un hotel');
			return SessionResponseMessages::redirect("/admin/stats/vetrine/laravel-era", $request);
			
		}

		$groupby = null;
		if($aggregazione === 'y')
			$groupby = "DATE_FORMAT(created_at, '%Y')";
			
		if($aggregazione === 'm')
			$groupby = "DATE_FORMAT(created_at, '%m/%Y')";
			
		if($aggregazione === 'd')
			$groupby = "DATE_FORMAT(created_at, '%d/%m/%Y')";

		$query = DB::table("tblStatsVetrineRead")
			->where('created_at', '>=', $date_from->toDateString())
			->where('created_at', '<=', $date_to->toDateString());
			
		if($hotel_id)
			$query->where("hotel_id", $hotel_id);
			
		if($vetrina_id)
			$query->where("vetrina_id", $vetrina_id);

		$query
			->select(DB::raw("SUM(visits) as n, $groupby as periodo, hotel_id, vetrina_id"))
			->groupBy(DB::raw("periodo, hotel_id, vetrina_id"))
			->orderBy("created_at", "asc");

		$results = $query->get();
		
		if (count($results) == 0) {
			SessionResponseMessages::add ('error', 'Nessun dato trovato per questo intervallo');
			return SessionResponseMessages::redirect($request->getUri(), $request);
		}

		$mesi = $this->getMesi();

		$stats = [];
		$stats_chart = [];
		$vetrine = [];
		$totali = [];
		
		$totali_click = 0;
		
				
		foreach($results as $r)
		{
			$periodo_a = explode("/", $r->periodo);	
			
			if (!$raggruppa) {			
				
				$stats[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$r->vetrina_id] = $r->n;
				$stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$r->vetrina_id] = $r->n;
				if (!in_array($r->vetrina_id, $vetrine)) $vetrine[] = $r->vetrina_id;
				
			} else {
				
				$stats[$periodo_a[1]][$periodo_a[0]][$r->vetrina_id] = $r->n;
				$stats_chart[$periodo_a[1]][$periodo_a[0]][$r->vetrina_id] = $r->n;
				if (!in_array($r->vetrina_id, $vetrine)) $vetrine[] = $r->vetrina_id;
				
			}
			
			$totali_click += $r->n;

		}
		
		/**
		 * COmpleto i fgiorni
		 */
		
		 foreach($vetrine as $vetrina) {
			
 			for($date = $date_from; $date->lte($date_to); $date->addDay()) {
 				
				$periodo_a = explode("/", $date->format("d/m/Y"));
				
				if (!$raggruppa) {		
											
	 				if (!isset($stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$vetrina]))
	 					$stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$vetrina] = 0;
					
					ksort($stats_chart[$periodo_a[2]][$periodo_a[1]]);	
					ksort($stats_chart[$periodo_a[2]]);	
					
				} else {
					
					if (!isset($stats_chart[$periodo_a[1]][$periodo_a[0]][$vetrina]))
	 					$stats_chart[$periodo_a[1]][$periodo_a[0]][$vetrina] = 0;
						
					ksort($stats_chart[$periodo_a[1]][$periodo_a[0]]);	
					ksort($stats_chart[$periodo_a[1]]);			
								
				}
				
				
 	    	}
			
			ksort($stats_chart);	
			
 		}
		
		return view('admin.stats_vetrine', compact( 'data', 'stats', 'stats_chart', 'vetrine', 'mesi', 'totali', 'raggruppa', 'totali_click', 'number_day'));
		
	}

	public function vetrinePagineResults(Request $request, $table)
	{
		
		$data = [];
		$stats = [];
		$request->flash();
		$data['pages'] = $this->getPages();
		
		$hotel_id = (int)$request->input('hotel');
		$page_id = $request->input('pagina_id');
		$aggregazione = "d"; //$request->input('aggregazione');
		$date_range = [];
	
		if (!empty($request->input('date_range')))
		{
			
			$date_range = explode(" - ", $request->input('date_range'));
			$date_from = Carbon::createFromFormat("d/m/Y", $date_range[0]);
			$date_to = Carbon::createFromFormat("d/m/Y", $date_range[1]);
			
		} else {
			
			SessionResponseMessages::add ('error', 'Entrambe le date devono essere compilate');
			return SessionResponseMessages::redirect($request->getUri(), $request);
			
		}
		
		if (empty($hotel_id)) {
			
			SessionResponseMessages::add ('error', 'Selezionare almeno un hotel');
			return SessionResponseMessages::redirect($request->getUri(), $request);
			
		}
		
		$groupby = null;
		if($aggregazione === 'y')
			$groupby = "DATE_FORMAT(created_at, '%Y')";
			
		if($aggregazione === 'm')
			$groupby = "DATE_FORMAT(created_at, '%m/%Y')";
			
		if($aggregazione === 'd')
			$groupby = "DATE_FORMAT(created_at, '%d/%m/%Y')";

		$query = DB::table($table)
			->where('created_at', '>=', $date_from->toDateString())
			->where('created_at', '<=', $date_to->toDateString());
			
		if($hotel_id)
			$query->where("hotel_id", $hotel_id);
			
		$query
			->select(DB::raw("SUM(visits) as n, $groupby as periodo, hotel_id, pagina_id"))
			->groupBy(DB::raw("periodo, hotel_id, pagina_id"))
			->orderBy("created_at", "asc")
			->orderBy("pagina_id", "asc");

		$results = $query->get();
			
		if (count($results) == 0) {
			
			SessionResponseMessages::add ('error', 'Nessun dato trovato per questo intervallo');
			return SessionResponseMessages::redirect($request->getUri(), $request);
			
		}
		
		$mesi = $this->getMesi();
		$stats = [];
		$stats_chart = [];
		$pagine = [];
			
		foreach($results as $r) {
			
			$periodo_a = explode("/", $r->periodo);			
			$stats[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$r->pagina_id] = $r->n;
			$stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$r->pagina_id] = $r->n;
			if (!in_array($r->pagina_id, $pagine)) $pagine[] = $r->pagina_id;
						
		}
		
		foreach($pagine as $pagina) {
			for($date = $date_from; $date->lte($date_to); $date->addDay()) {
				$periodo_a = explode("/", $date->format("d/m/Y"));			
				if (!isset($stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$pagina]))
					$stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$pagina] = 0;
	    	}
		}
					
		return view('admin.stats_vetrine_pagine', compact( 'data', 'stats', 'stats_chart', 'mesi', 'pagine'));
		
	}

	public function vetrinePagineResultsSimple(Request $request, $table)
	{
	
		$request->flash();
		$hotel_id = (int)$request->input('hotel');
		$anno = $request->get('anno');
		
		// aggiungo uno '0' davati ai mesi da 1 a 9
		$mese =	strlen($request->get('mese')) == 1 ? '0'.$request->get('mese') : $request->get('mese');

		// livello di aggregazione mensile
		$groupby = "DATE_FORMAT({$table}.created_at, '%m/%Y')";
		
		$query = DB::table($table)
			->join('tblCmsPagine', $table.'.pagina_id', '=', 'tblCmsPagine.id')
			->where($table.'.created_at', '>=', $anno.'-'.$mese.'-01')
			->where($table.'.created_at', '<=', $anno.'-12-31');
			
		if($hotel_id)
			$query->where($table.".hotel_id", $hotel_id);

		$query
			->select(DB::raw("SUM($table.visits) as n, $groupby as periodo, $table.hotel_id, $table.pagina_id, SUBSTRING_INDEX(tblCmsPagine.uri, CASE tblCmsPagine.lang_id WHEN 'it' THEN 'it/'	WHEN 'en' THEN 'ing/' WHEN 'fr' THEN 'fr/' WHEN 'de' THEN 'ted/' END,-1) as my_url"))
			->groupBy(DB::raw("periodo, $table.hotel_id, my_url"))
			->orderBy("{$table}.created_at", "asc");

		$results = $query->get();
		
		if (count($results) == 0) {
			SessionResponseMessages::add ('error', 'Nessun dato trovato per questo intervallo');
			return SessionResponseMessages::redirect($request->getUri(), $request);
		}
		
		$mesi = $this->getMesi();

		// se non filtro per hotel
		// e voglio raggruppare i risultati di ogni mese per hotel
		$devo_raggruppare = (!$hotel_id && $request->filled('raggruppa')); 

		// voglio un array con [$id_hotel => nome_hotel] 
		// in modo da non fare n query per ogni id_hotel
		if ($devo_raggruppare)
			$id_nomi_hotel = $this->getLaravelHotels();
			
		
		$stats_chart = [];
		$stats = [];
		$totali = [];
		$gruppi = [];
		
		foreach($results as $r)
		{
			
			$periodo = $r->periodo;
			$tmp = explode("/", $r->periodo);
			$periodo = $mesi[(int)$tmp[0]]." ".$tmp[1];
			
			
			
			if(!isset($stats[$r->my_url][$periodo])) {
				$stats[$r->my_url][$periodo] = 0;
			}
			
			if(!isset($stats_chart[$periodo])) {
				$stats_chart[$periodo] = 0;
			}

			$stats[$r->my_url][$periodo] += $r->n;
			$stats_chart[$periodo] += $r->n;
	
						
			if ($devo_raggruppare) {
					
				if(!isset($gruppi[$r->my_url][$periodo][$id_nomi_hotel[$r->hotel_id]]))
					$gruppi[$r->my_url][$periodo][$id_nomi_hotel[$r->hotel_id]] = 0;

				$gruppi[$r->my_url][$periodo][$id_nomi_hotel[$r->hotel_id]] += $r->n;
				
			} 

			if(!isset($totali[$r->my_url]))
				$totali[$r->my_url] = 0;

			$totali[$r->my_url] += $r->n;

		}

		$min_val_year = end(self::$anni);
		$max_val_year = reset(self::$anni);
		
		// foreach($vetrine as $vetrina) {
		// 
		//    for($date = $date_from; $date->lte($date_to); $date->addDay()) {
		// 
		// 	   $periodo_a = explode("/", $date->format("d/m/Y"));
		// 
		// 	   if (!$raggruppa) {		
		// 
		// 		   if (!isset($stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$vetrina]))
		// 			   $stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$vetrina] = 0;
		// 
		// 		   ksort($stats_chart[$periodo_a[2]][$periodo_a[1]]);	
		// 		   ksort($stats_chart[$periodo_a[2]]);	
		// 
		// 	   } else {
		// 
		// 		   if (!isset($stats_chart[$periodo_a[1]][$periodo_a[0]][$vetrina]))
		// 			   $stats_chart[$periodo_a[1]][$periodo_a[0]][$vetrina] = 0;
		// 
		// 		   ksort($stats_chart[$periodo_a[1]][$periodo_a[0]]);	
		// 		   ksort($stats_chart[$periodo_a[1]]);			
		// 
		// 	   }
		// 
		// 
		//    }
		// 
		//    ksort($stats_chart);
		
		// dd($stats);

		return view('admin.stats_vetrine_pagine_simple', compact('stats', 'stats_chart', 'devo_raggruppare', 'hotel_id', 'gruppi', 'totali','min_val_year','max_val_year'));
		
	}
	
	public function vetrinePagineResultsPage(Request $request, $table)
	{
		
		
		
		$request->flash();

		// Per scegliere la pagina da analizzare
		// considero tutte le pagine loggate nella tabella 
		$pages = $this->getPagesStats($table);

		///////////////////////////////////////////////////////////////////////////////////////////
		// ATTENZIONE: non devo filtrare per pagina_id perché se no filtro per lingua
		// devo trovare l'URL, togliere il prefisso della lingua e filtrare con quello nel join
		// tra la tabella delle stats e la tblCmsPagine //
		///////////////////////////////////////////////////////////////////////////////////////////
	
		$pagina_id = $request->input('pagina_id');

		if (empty($pagina_id)) {
			
			SessionResponseMessages::add ('error', 'Selezionare una pagina');
			return SessionResponseMessages::redirect($request->getUri(), $request);
			
		}

		$page_url = CmsPagina::find($pagina_id)->uri;

		/////////////////////////////////////////
		// devo strippare ing/ fr/ oppure ted/ //
		/////////////////////////////////////////
		/////////////////////////////////////////
		if(strpos($page_url,'ing/') !== false || strpos($page_url,'ted/') !== false)
			$page_url = substr($page_url, 4);
			
		elseif(strpos($page_url,'fr/') !== false)
			$page_url = substr($page_url, 3);

		$anno = $request->get('anno');
		
		// aggiungo uno '0' davati ai mesi da 1 a 9
		$mese =	strlen($request->get('mese')) == 1 ? '0'.$request->get('mese') : $request->get('mese');
		

		// livello di aggregazione mensile
		$groupby = "DATE_FORMAT({$table}.created_at, '%m/%Y')";
		 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// ATTENZIONE: devo metterlo in join con la tblCmsPagine per prendere gli url senza fr/ ing/ ted/ e poi raggruppare per URL //
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$query = DB::table($table)
			->join('tblCmsPagine', $table.'.pagina_id', '=', 'tblCmsPagine.id')
			->where($table.'.created_at', '>=', $anno.'-'.$mese.'-01')
			->where($table.'.created_at', '<=', $anno.'-12-31');
			
		$query
			->select(DB::raw("SUM($table.visits) as n, $groupby as periodo, $table.hotel_id, SUBSTRING_INDEX(tblCmsPagine.uri, CASE tblCmsPagine.lang_id WHEN 'it' THEN 'it/'	WHEN 'en' THEN 'ing/' WHEN 'fr' THEN 'fr/' WHEN 'de' THEN 'ted/' END,-1) as my_url"))
			->where("uri",'like', '%'.$page_url)
			->groupBy(DB::raw("periodo, $table.hotel_id, my_url"))
			->orderBy("$table.created_at", "asc");

		$results = $query->get();
		
		if (count($results) == 0) {
			SessionResponseMessages::add ('error', 'Nessun dato trovato per questo intervallo');
			return SessionResponseMessages::redirect($request->getUri(), $request);
		}
		
		$mesi = $this->getMesi();
		$stats = [];
		$totali = [];
		$hotel_ids = [];
		$hotels = [];
		
		foreach($results as $r) {

			$periodo = $r->periodo;
			$tmp = explode("/", $r->periodo);
			$periodo = $mesi[(int)$tmp[0]]." ".$tmp[1];
			
			$stats[$r->my_url][$periodo][$r->hotel_id] = $r->n;

			if(!isset($totali[$r->my_url][$r->hotel_id]))
				$totali[$r->my_url][$r->hotel_id] = 0;

			$totali[$r->my_url][$r->hotel_id] += $r->n;
			$hotel_ids[$r->hotel_id] = $r->hotel_id;

		}

		if($hotel_ids) {
			
			$hotels_tmp = Hotel::whereIn("id", $hotel_ids)->pluck('nome', 'id')->toArray();
			foreach($hotels_tmp as $hotel_id => $hotel_name)
				$hotels[$hotel_id] = $hotel_name;

		}

		$min_val_year = end(self::$anni);
		$max_val_year = reset(self::$anni);

		return view('admin.stats_vetrine_pagine_page', compact('stats', 'totali','min_val_year','max_val_year','pages','hotels','pagina_id'));
		
	}

}

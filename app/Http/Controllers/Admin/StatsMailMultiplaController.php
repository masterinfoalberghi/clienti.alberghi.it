<?php

/**
 * StatsMailMultiplaController
 *
 * @author Info Alberghi Srl
 *
 */
 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\StatsHotelMailMultipla;
use \App\MailMultipla;
use \App\Hotel;
use \Carbon\Carbon;
use DB;
use SessionResponseMessages;

class StatsMailMultiplaController extends Controller
{

	protected $lowest_year = 0;
  
  
  
	/* ------------------------------------------------------------------------------------
	 * METODI PRIVATI
	 * ------------------------------------------------------------------------------------ */



	/**
	 * Prende gli anni per i filtri
	 * 
	 * @access private
	 * @param bool $with_zero (default: false) 
	 * @return array $years
	 */
	 
	private function getAllYears($with_zero = false)
	{
		$years = [];

		if ($with_zero)
			$years[] = 'Tutti';

		if (!$this->lowest_year)
			$this->lowest_year = MailMultipla::selectRaw('YEAR(created_at) AS anno_vecchio')
			->orderBy('id', 'asc')
			->first()['anno_vecchio'];

		$current_date = Carbon::now();

		foreach (range($this->lowest_year, $current_date->year) as $year)
			$years[$year] = $year;

		krsort($years);

		return $years;
		
	}
	
	protected function getMesi() 
	{
		 return [1 => "Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"];
	}
	
	private static function _newTipologie($tipologie) {
		
		$tipologie_array = [];
		$t = 0;
		foreach($tipologie as $tipo):
			$tipologie_array[$t] = ucfirst($tipo);
			$t++;
		endforeach;
		
		return $tipologie_array;
		
	}
	
	public function multipla(Request $request) {
		
		$data = [];
		$stats = [];
		$stats_chart = [];
		$hotel_id = [];
		$data['tipologie'] = self::_newTipologie(MailMultipla::getTipologieSelezionabili());
		return view('admin.stats_mail_multiple', compact('data', 'stats', 'stats_chart', 'hotel_id'));
		
	}

	public function multiplaResult(Request $request)
	{

		$data = [];
		$stats = [];
		$stats_chart = [];
		$request->flash();
		$data['tipologie'] = self::_newTipologie(MailMultipla::getTipologieSelezionabili());
		$hotel_id = (int)$request->input('hotel');
		$tipologia = $request->input('tipologia');
		$raggruppa = $request->input('raggruppa');
		$results_doppie = [];
		$mesi = $this->getMesi();
		
		if (!empty($request->input('date_range'))) {
			
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
		
		$query = DB::table("tblStatsMailMultipleRead")
			->selectRaw('anno,mese,giorno,tipologia,hotel_id,conteggio')
			->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) >=  "' . $date_from->toDateString() . '"')
			->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) <=  "' . $date_to->toDateString() . '"')
			->orderByRaw('DATE(CONCAT(anno,"-",mese,"-",giorno))');
		
		if($hotel_id)
			$query = $query->where("hotel_id", $hotel_id);
						
		if ($tipologia == "-1") {
			
			// Tutto quanto
			
		} else if ($tipologia == "-2") {
			
			$query = $query->whereRaw("(tipologia = 'doppia' OR tipologia = 'doppia par')");
			
		} else {
			
			if ($tipologia == 0)
				$query = $query->where("tipologia",  "normale");
			else if ($tipologia == 1)
				$query = $query->where("tipologia",  "wishlist");
			else if ($tipologia == 2)
				$query = $query->where("tipologia",  "mobile");
			
		}
		
		// $sql = $query->toSql();
		// $bindings = $query->getBindings();
		// dd($sql, $bindings);
		
		$results = $query->get();
		
		if (count($results) == 0) {
			SessionResponseMessages::add ('error', 'Nessun dato trovato per questo intervallo');
			return SessionResponseMessages::redirect($request->getUri(), $request);
		}
		
		$stats = [];
		$stats_chart = [];
		$totali_raggruppati = [];
		$totali = [];
		
		foreach($results as $r)
		{	
			
			if ($r->tipologia == "doppia par")
				$r->tipologia = "doppia";
				
			$stats_chart[$r->anno][$r->mese][$r->giorno][$r->tipologia] = $r->conteggio;
			
			if (!isset($totali_raggruppati[$r->anno]))
				$totali_raggruppati[$r->anno] = 0;
			
			if (!isset($totali[$r->anno][$r->mese]))
				$totali[$r->anno][$r->mese] = 0;
				
			$totali[$r->anno][$r->mese] += $r->conteggio;
			$totali_raggruppati[$r->anno] += $r->conteggio;
			
		}
		
		
		
		for($date = $date_from; $date->lte($date_to); $date->addDay()) {
			
			$periodo_a = explode("/", $date->format("d/m/Y"));
			
			foreach(array("normale", "wishlist", "mobile", "doppia") as $tipo) {
				if (!isset($stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$tipo])) {
					$stats_chart[$periodo_a[2]][$periodo_a[1]][$periodo_a[0]][$tipo] = 0;	
				}
				ksort($stats_chart[$periodo_a[2]][$periodo_a[1]]);	
				ksort($stats_chart[$periodo_a[2]]);	
			}
			
		}
		
		ksort($stats_chart);	
	
		return view('admin.stats_mail_multiple', compact('data', 'stats', 'stats_chart', 'hotel_id', 'mesi', 'totali','totali_raggruppati','raggruppa'));
		
	}

	
	/**
	 * Ritorna le statistiche giornaliere
	 * 
	 * @access public
	 * @return view
	 */
	 
	public function giornaliere(Request $request)
	{
		$data['anni'] = $this->getAllYears();
		$data['tipologie'] = MailMultipla::getTipologieSelezionabili();

		$stats = [];
		
		/**
		 * Se ho dei dati da processare
		 */
			 
		if ($request->method() == 'POST') {
			
			$tipologia = $request->input('tipologia');

			if ($tipologia == -1)
				$tipologia_selezionata = -1;
			else
				$tipologia_selezionata = $data['tipologie'][$tipologia];

			/**
			 * Costruisco la query
			 */

			$results = StatsHotelMailMultipla::with(['clienti'])
				->whereHas('clienti', function($q)
					{
						$q->attivo();
					})
				->tipologia([$tipologia_selezionata])
				->groupBy('anno', 'mese', 'giorno')
				->selectRaw('anno, mese, giorno, SUM(n_mail) AS n_mail')
				->get();

			$stats = [];
			$stats['results'] = [];
			$stats['totale'] = 0;
				
			/**
			 * Strutturo i dati
			 */
			 
			foreach ($results as $row_result) {
				$stats_date = Carbon::create($row_result->anno, $row_result->mese, $row_result->giorno);

				$stats['results'][] = [
					'giorno' => $stats_date->format("Y-m-d"),
					'n_mail' => $row_result->n_mail
				];
				$stats['totale'] += $row_result->n_mail;
			}

			$request->flash();
		}

		return view('admin.stats_mail_multiple_giornaliere', compact('data', 'stats'));
		
	}


}

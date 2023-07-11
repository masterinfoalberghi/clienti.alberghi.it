<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Localita;
use App\Hotel;
use App\Macrolocalita;
use App\CmsPagina;
use Carbon\Carbon;
use Illuminate\Http\Request;

class v2_IAStatsOfferteController extends AdminBaseController
{
	
	public function __construct () {
		DB::connection()->enableQueryLog();
	}
	
	/**
	 * Trovo tutte le pagina
	 * @param  [type] $table [description]
	 * @return [type]        [description]
	 */
	
	public function getPagesStats($table)
	{
		
		$table .= "ReadV2"; 
		$ids = DB::table($table)->distinct()->pluck('pagina_id');
		$data = CmsPagina::whereIn('id', $ids);
				
		$data = $data->orderBy("id", "lang_id")->pluck('uri', 'id')->toArray();
		
		asort($data);		
		return $data;
		
	}
	
	/** 
	 * Media click annui su tt le vetrine
	 */
	
	private static function getAnno_ClickTotali($table, $date_from, $date_to, $pagina_id, $device) 
	{
		
		$table .= "ReadV2"; 
		$Anno_ClickTotali = 
		
		DB::table($table)
			->select(DB::raw('sum(visits) as conteggio'))
			->where("created_at", ">=", $date_from )
			->where("created_at", "<=", $date_to );
			
			if ($pagina_id != "")
				$Anno_ClickTotali = $Anno_ClickTotali->where("pagina_id", $pagina_id);
			
			if ($device != "")
				$Anno_ClickTotali = $Anno_ClickTotali->where("device", $device);
				
			$Anno_ClickTotali = $Anno_ClickTotali->first();
										
		return $Anno_ClickTotali;
		
	}
	
	
	/**
	 * Trovo il conteggio dei click per tipologia di offerta
	 * trovo il conteggio totale dei click per localita
	 */
	
	private static function getAnno_mediaClickPerLocalita ($table, $date_from, $date_to, $pagina_id, $device)
	{
		
		$table .= "ReadV2"; 
		$Anno_mediaClickPerLocalita = 
		
		DB::table($table)
		->select(DB::raw('sum(visits) as conteggio, hotel_id, tblMacrolocalita.id as idMacro, tblMacrolocalita.nome as nome'))
			->where($table . ".created_at", ">=", $date_from )
			->where($table . ".created_at", "<=", $date_to );
			
			if ($pagina_id != "")
				$Anno_mediaClickPerLocalita = $Anno_mediaClickPerLocalita->where("pagina_id", $pagina_id);
			
			if ($device != "")
				$Anno_mediaClickPerLocalita = $Anno_mediaClickPerLocalita->where("device", $device);
			
			$Anno_mediaClickPerLocalita = 
			
			$Anno_mediaClickPerLocalita->groupBy("tblMacrolocalita.nome", "hotel_id")
				->join('tblHotel' , function ($join) use ($table) {
					 $join->on($table . '.hotel_id', '=', 'tblHotel.id');
				})
				->join('tblLocalita' , function ($join) {
						 $join->on('tblLocalita.id', '=', 'tblHotel.localita_id');
					})
					->join('tblMacrolocalita' , function ($join) {
							 $join->on('tblMacrolocalita.id', '=', 'tblLocalita.macrolocalita_id');
						})
						->orderBy("tblMacrolocalita.id")
						->get();
									
		$pMacroId = 0;
		$Anno_mediaClickPerLocalitaArr = [];
		foreach($Anno_mediaClickPerLocalita as $item):

			if ($pMacroId != $item->idMacro) {
				
				$pMacroId = $item->idMacro;			
				$Anno_mediaClickPerLocalitaArr[$pMacroId] = [];
				$Anno_mediaClickPerLocalitaArr[$pMacroId]["nome"] = "";
				$Anno_mediaClickPerLocalitaArr[$pMacroId]["conteggio"] = 0;
				$Anno_mediaClickPerLocalitaArr[$pMacroId]["hotels"] = 0;
				$Anno_mediaClickPerLocalitaArr[$pMacroId]["percentuale"] = 0;
				
			}
			
			$Anno_mediaClickPerLocalitaArr[$pMacroId]["nome"] = $item->nome;
			$Anno_mediaClickPerLocalitaArr[$pMacroId]["hotels"] += 1;
			$Anno_mediaClickPerLocalitaArr[$pMacroId]["conteggio"] += $item->conteggio;
				
		endforeach;	
		
		return $Anno_mediaClickPerLocalitaArr;
		
	}
	
	/** 
	 * trovo il conteggio totale dei click per hotel
	 */
	
	private static function getAnno_mediaClickPerMese ($table, $date_from, $date_to, $pagina_id, $Anno_ClickTotali, $device)
	{
	
		$table .= "ReadV2"; 
		$Anno_mediaClickPerMese = DB::table($table)
										->select(DB::raw('sum(visits) as conteggio, MONTH(created_at) as mese'))
										->where("created_at", ">=", $date_from )
										->where("created_at", "<=", $date_to );
										
										if ($pagina_id != "")
											$Anno_mediaClickPerMese = $Anno_mediaClickPerMese->where("pagina_id", $pagina_id);
											
										if ($device != "")
											$Anno_mediaClickPerMese = $Anno_mediaClickPerMese->where("device", $device);
											
										$Anno_mediaClickPerMese = $Anno_mediaClickPerMese->groupBy(DB::raw('MONTH(created_at)'))->get();
		
		$t = 0;
		foreach($Anno_mediaClickPerMese as $item):
			
			$Anno_mediaClickPerMese[$t]->percentuale = round(($item->conteggio / $Anno_ClickTotali->conteggio * 100),2);
			$Anno_mediaClickPerMese[$t]->mese = Carbon::parse(date('Y') . "-" . $item->mese . "-1")->format("F");
			$t++;
			
		endforeach;
	
		return $Anno_mediaClickPerMese;
	
	}
	
	
	
	/**
	 * --- VIEW ---
	 */
	
	
	/**
	 * Pagina visualizzazione offerte
	 */
	
	public function offerteGenerale (Request $request) {
		
		$lang = "";
		$pagina_id = "";
		$device = "";
		$table = "tblStatsVetrine";
		
		$devices = ["phone" => "Telefono", "tablet" => "Tablet", "desktop" => "Computer"];
		$tables = ["tblStatsVetrine" => "Vetrine Top", "tblStatsVot" => "Offerte Top", "tblStatsVst" => "Servizi Top", "tblStatsVtt" => "Trattamenti Top", "tblStatsVaat" => "Bambini gratis Top" ];
		
		if (isset($request->pagina_id) && $request->pagina_id != "")
			$pagina_id = $request->pagina_id;
			
		if (isset($request->pagina_id) && $request->pagina_id != "")
			$lang = $request->lang;
			
		if (isset($request->device) && $request->device != "")
			$device = $request->device;
			
		if (isset($request->table) && $request->table != "")
			$table = $request->table;
		
		if (!empty($request->input('date_range'))) {
			
			$date_range = explode(" - ", $request->input('date_range'));
			$date_from = Carbon::createFromFormat("d/m/Y", $date_range[0]);
			$date_to = Carbon::createFromFormat("d/m/Y", $date_range[1]);

			$date_from2 = Carbon::createFromFormat("d/m/Y", $date_range[0])->subYear();
			$date_to2 = Carbon::createFromFormat("d/m/Y", $date_range[1])->subYear();
			
		} else {
			
			$date_from = Carbon::now()->subDay(7);
			$date_to = Carbon::now();

			$date_from2 = Carbon::now()->subDay(7)->subYear();
			$date_to2 = Carbon::now()->subYear();
			
		}
		
		$yearSelected = $date_from->format("Y");
		$yearReferer = $date_from2->format("Y");
		
		$pages = Self::getPagesStats("tblStatsVot");
		
		/**
		 * Prendo il numero di hotel per Macro localita
		 */
		
		/** --- Costruzione statistiche --- **/	
		
		$Anno_ClickTotali  = Self::getAnno_ClickTotali($table, $date_from, $date_to, $pagina_id, $device);
		$Anno_ClickTotali2 = Self::getAnno_ClickTotali($table, $date_from2, $date_to2, $pagina_id, $device);
																		
		$Anno_mediaClickPerMese = Self::getAnno_mediaClickPerMese($table, $date_from, $date_to, $pagina_id, $Anno_ClickTotali, $device);
		$Anno_mediaClickPerMese2 = Self::getAnno_mediaClickPerMese($table, $date_from2, $date_to2,  $pagina_id ,$Anno_ClickTotali2, $device);
		
		$Anno_mediaClickPerLocalitaArr  = Self::getAnno_mediaClickPerLocalita($table, $date_from, $date_to, $pagina_id, $device);
		$Anno_mediaClickPerLocalitaArr2 = Self::getAnno_mediaClickPerLocalita($table, $date_from2, $date_to2,  $pagina_id, $device);
		
		// $queries = DB::getQueryLog();
		// dd($request->all(), $queries);
												
		// dd(
		// 
		// 	$Anno_ClickTotali,
		// 	$Anno_ClickTotali2,
		// 	$Anno_mediaClickPerMese,
		// 	$Anno_mediaClickPerMese2,
		// 	$Anno_mediaClickPerLocalitaArr,
		// 	$Anno_mediaClickPerLocalitaArr2
		// );
		
		return view('admin.v2_IAStatsOfferte',
			compact(
				
				"device_id",
				"pagina_id",
				"table",
				
				"pages",
				"devices",
				"tables",
				
				"yearSelected",
				"yearReferer",
				
				"Anno_ClickTotali",  
				"Anno_ClickTotali2",  
				"Anno_mediaClickPerMese",
				"Anno_mediaClickPerMese2",
				"Anno_mediaClickPerLocalitaArr",
				"Anno_mediaClickPerLocalitaArr2"
			)
		);		
		
	}
	
}

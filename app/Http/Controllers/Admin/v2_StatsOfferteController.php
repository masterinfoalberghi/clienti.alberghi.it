<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Localita;
use App\Hotel;
use App\Macrolocalita;
use App\CmsPagina;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class v2_StatsOfferteController extends AdminBaseController
{

	var $evidenze_crm_parchi;
	var $evidenze_crm_fiera;

	//? switch tabelle delle stats ReadV2 ==> ReadV3 ==> .....
	var $table_stats_suffix;
	
	public function __construct () {
		$this->evidenze_crm_parchi = array('MIRABILANDIA','AQUAFAN','OLTREMARE','ITALIA IN MINIATURA','ACQUARIO DI CATTOLICA','FIABILANDIA');
		$this->evidenze_crm_fiera = array('OFFERTE FIERA');
		$this->table_stats_suffix = 'ReadV3';
	}
	
	/**
	 * Trovo tutte le pagina
	 * @param  [type] $table [description]
	 * @return [type]        [description]
	 */
	

	public function cambiaTipoVetrinaAjax(Request $request)
		{

			$table = $request->get('table');
			$lang = $request->get('lang');
			$macro = $request->get('macro');
			$pagina_id = $request->get('pagina_id');

			$pages = $this->getPagesStats($table,$lang, $macro);	

			$options = "<option value=''>Seleziona una pagina</option>";

			foreach ($pages as $id => $uri) 
				{
				$options .= "<option value=".$id;
				if ($pagina_id == $id) {
					$options .= "selected='selected'";					
				}

				$options .= ">".$uri."</option>";
				}
			
			echo $options;

		}

	public function getPagesStats($table, $lang = 'it', $macro="rimini")
	{
		$data = [];
		
		if ($table == "tblStatsVetrine")
			$pluck = "vetrina_id";
			
		else
			$pluck = "pagina_id";
			
		$table .= $this->table_stats_suffix; 
		$ids = DB::table($table)->distinct()->pluck($pluck);



		//? trovo l'id della Macro
		$id_macro = optional(Macrolocalita::where('nome', 'like' ,'%' . $macro .'%')->first())->id;


		//dd($id_macro);
		

		$pagine = CmsPagina::select('id','uri','tipo_evidenza_crm', 'listing_macrolocalita_id')->whereIn('id', $ids)->where('lang_id', $lang)->orderBy("uri")->get();

		// le pagine sono filtrate X macro OPPURE SONO QUELLE TRASVERSALI
		$scartate = [];
		foreach ($pagine as $pagina) 
			{
			if(
				$pagina->listing_macrolocalita_id == $id_macro || 
				strpos($pagina->uri, strtolower(Str::slug($macro , '-'))) !== false || 
				in_array($pagina->tipo_evidenza_crm, $this->evidenze_crm_parchi) || 
				in_array($pagina->tipo_evidenza_crm, $this->evidenze_crm_fiera)
			 )
				{
				$data[$pagina->id] = $pagina->uri . ' (' . $pagina->tipo_evidenza_crm . ')';	

				} else {
					$scartate[] = $pagina->uri;
				}
			}
		
		//dd($scartate);
		
		return $data;
		
	}
	
	/**
	 * Trovo tutte le offerte pubblicate per periodo di tempo scelto
	 * @param  [type] $table [description]
	 * @return [type]        [description]
	 */
	
	public function getAllOfferPerPeriod($table, $date_from, $date_to, $pagina_id )
	{
    // la coppia hotel_id, pagia_id mi identifica un'evidenza univoca
    // ogni riga è una evidenza cliccata almeno una volta (per essere nella tabella)
    
    // SELECT distinct hotel_id, pagina_id 
    // FROM `tblStatsVotReadV2`
    // WHERE `tblStatsVotReadV2` . `created_at` >= '2018-06-01 17:06:46' and `tblStatsVotReadV2` . `created_at` <= '2018-08-30 17:06:46'
    // group by hotel_id, pagina_id
    
    // DEVO CONTRARE IL n. DI RIGHE PER AVERE IL NUMERO DELLE EVIDENZE


  $table .= $this->table_stats_suffix; 
		$data = DB::table($table)
				->select(DB::raw("distinct hotel_id, SUM(visits) as somma"))
				->where($table . ".created_at", ">=", $date_from )
        ->where($table . ".created_at", "<=", $date_to );

    if ($pagina_id != ""){
      $data = $data->where("pagina_id", $pagina_id);
    }

    	/*$query = $data->groupBy('hotel_id', 'pagina_id');
    	dd(str_replace_array('?', $query->getBindings(), $query->toSql()));*/

    $data = $data->groupBy('hotel_id')->orderBy('somma', 'desc')->get();


		 return $data;
		
  }
  
  /*
  a partire da week-end/rimini-week-end.php
  devo trovare che la macro è Rimini e quindi id = 1
   */
  private function _guessMacroFronUri($uri_pagina, $tipo_evidenza_crm = '')
  {
    

  	if( in_array($tipo_evidenza_crm, $this->evidenze_crm_parchi) )
  		return -1;

  	if( in_array($tipo_evidenza_crm, $this->evidenze_crm_fiera) )
  		{
  		return -2;
  		}

    foreach (Macrolocalita::pluck('nome', 'id') as $id => $nome) 
      {
      if(strpos($uri_pagina, strtolower(Str::slug($nome , '-'))) !== false)
        {
        return $id;
        }
      }

  }

	
	/** 
	 * Media click annui su tt le vetrine
	 */
	
	private static function getAnno_ClickTotali($table, $date_from, $date_to, $pagina_id, $device) 
	{	

		// ? Avoid error "$this cannot be used in static methods"
		$this_stats = new v2_StatsOfferteController;
		$table .= $this_stats->table_stats_suffix; 

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
		// ? Avoid error "$this cannot be used in static methods"
		$this_stats = new v2_StatsOfferteController;
		$table .= $this_stats->table_stats_suffix; 
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

		// ? Avoid error "$this cannot be used in static methods"
		$this_stats = new v2_StatsOfferteController;
		$table .= $this_stats->table_stats_suffix; 
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
		
		$lang = "it";
		$pagina_id = "173"; // week-end rimini
		$device = "";
		$devices = ["phone" => "Telefono", "tablet" => "Tablet", "desktop" => "Computer"];
		$macro = "Rimini";
		$tables = ["tblStatsVot" => "Offerte Top", "tblStatsVst" => "Servizi Top", "tblStatsVtt" => "Trattamenti Top", "tblStatsVaat" => "Bambini gratis Top" ];

		


		if (isset($request->table) && $request->table != "")
			{
			$table = $request->table;
			}
		else
		 {
			$table = "tblStatsVot";
		 }
			
		if (isset($request->pagina_id) && $request->pagina_id != "")
			$pagina_id = $request->pagina_id;

		if (isset($request->pagina_id) && $request->pagina_id != "")
			$lang = $request->lang;
		
		if (isset($request->macro) && $request->macro != "")
			$macro = $request->macro;

		$pages = Self::getPagesStats($table, $lang, $macro);
		
			
		if (isset($request->device) && $request->device != "")
			$device = $request->device;
					
		if (!empty($request->input('date_range'))) {
			
			$date_range = explode(" - ", $request->input('date_range'));
			$date_from = Carbon::createFromFormat("d/m/Y", $date_range[0]);
			$date_to = Carbon::createFromFormat("d/m/Y", $date_range[1]);

			$date_from2 = Carbon::createFromFormat("d/m/Y", $date_range[0])->subYear();
			$date_to2 = Carbon::createFromFormat("d/m/Y", $date_range[1])->subYear();
			
		} else {
			
			$date_from = Carbon::createFromFormat('Y-m-d', date('Y').'-06-01');
			$date_to = Carbon::createFromFormat('Y-m-d', date('Y').'-08-31');

			$date_from2 = Carbon::createFromFormat('Y-m-d', date('Y').'-06-01')->subYear();
			$date_to2 = Carbon::createFromFormat('Y-m-d', date('Y').'-08-31')->subYear();

		}
		




		$yearSelected = $date_from->format("Y");
		$yearReferer = $date_from2->format("Y");

		
    $anno_corrente_dal = $date_from->format("Y");
    $anno_corrente_al = $date_to->format("Y");

    // intervallo di ricerca è nello stesso anno quindi POSSO CONSIDERARE LA TABELLA DEI COSTI DI QUELL'ANNO
    if($anno_corrente_dal == $anno_corrente_al)
      {

      if($anno_corrente_dal == date('Y'))
        {
        $anno_costi = "";  
        }
      elseif($anno_corrente_dal == date('Y')-1)
        {
        $anno_costi = date('Y') -1;
        }
      else
        {
        $anno_costi = null;
        }

      }
    else 
      {
      $anno_costi = null;
      }
    
		/** --- Costruzione statistiche --- **/	
		
		// numero totale di vetrineTop  (di una pagina)
    $q_AllOfferPerPeriod = Self::getAllOfferPerPeriod($table, $date_from, $date_to, $pagina_id);

    //dd($q_AllOfferPerPeriod);

    $AllOfferPerPeriod = $q_AllOfferPerPeriod->count();
    
		// numero totale di vetrineTop  (di una pagina) (anno prima)
		$q_AllOfferPerPeriod2 = Self::getAllOfferPerPeriod($table, $date_from2, $date_to2, $pagina_id);

		$AllOfferPerPeriod2 = $q_AllOfferPerPeriod2->count();
		
    // numero di click totali su tutte le vetrineTop (di una pagina)
    $Anno_ClickTotali  = Self::getAnno_ClickTotali($table, $date_from, $date_to, $pagina_id, $device);
    
    // numero di click totali su tutte le vetrineTop (di una pagina) (anno prima)
    $Anno_ClickTotali2 = Self::getAnno_ClickTotali($table, $date_from2, $date_to2, $pagina_id, $device);
    
    // numero di click totali su tutte le vetrineTop (di una pagina) distribuiti per mese
		$Anno_mediaClickPerMese = Self::getAnno_mediaClickPerMese($table, $date_from, $date_to, $pagina_id, $Anno_ClickTotali, $device);
    
    // numero di click totali su tutte le vetrineTop (di una pagina) distribuiti per mese (anno prima)
    $Anno_mediaClickPerMese2 = Self::getAnno_mediaClickPerMese($table, $date_from2, $date_to2,  $pagina_id ,$Anno_ClickTotali2, $device);
    



    // numero di click totali su tutte le vetrineTop (di una pagina) distribuiti per località
    $Anno_mediaClickPerLocalitaArr  = Self::getAnno_mediaClickPerLocalita($table, $date_from, $date_to, $pagina_id, $device);
    
    // numero di click totali su tutte le vetrineTop (di una pagina) distribuiti per località (anno prima)
    $Anno_mediaClickPerLocalitaArr2 = Self::getAnno_mediaClickPerLocalita($table, $date_from2, $date_to2,  $pagina_id, $device);
  

    $costo_per_click = 0;
    
    if(!empty($pagina_id))
    {

    $pagina = CmsPagina::find($pagina_id);
    
    $uri_pagina = $pagina->uri;
    $tipo_evidenza_crm = $pagina->tipo_evidenza_crm;

    // COSTI
    // tabella evidenze_for_export_to_ia


    // devo trovare le evidenze in base alla pagina ed alla località
      
    $id_macro = $this->_guessMacroFronUri($uri_pagina, $tipo_evidenza_crm);

		if (is_null($id_macro)) {
				$id_macro = $pagina->listing_macrolocalita_id;
		}
    

    $mese_inizio = $date_from->month;
    $mese_fine = $date_to->month;

    //dd(str_replace_array('?', $costi->getBindings(), $costi->toSql()));
    
    // week-end/misano-adriatico-week-end.php
    
    
    if(!is_null($anno_costi))
      {
      $costi_non_attendibili = false;
      
      if(empty($anno_costi))
        {
        $tabella_costi = 'evidenze_for_export_to_ia';
        }
      else
        {
        $tabella_costi = 'evidenze_for_export_to_ia_'.$anno_costi;
        }
      
      }
    else
      {
      $tabella_costi = 'evidenze_for_export_to_ia';
      $costi_non_attendibili = true;
      }

    

    $slots = DB::table($tabella_costi)
        ->where('id_macro',$id_macro)
        ->where('nome',$tipo_evidenza_crm)
        ->where('costo','>',0) // vendibile
        ->where('id_hotel','>',0) // venduto a qualcuno
        ->where('id_mese','>=', $mese_inizio)
        ->where('id_mese','<=', $mese_fine)
        ->get();

     $costo = 0;

     //costo_totale
     if($slots->count())
     	{
     	foreach ($slots as $slot) 
     		{
     		$costo += $slot->costo;
     		}
     	}


     	if($Anno_ClickTotali->conteggio > 0)
     	{
     	// costo_totale/numero_di_click = cpc
     	$costo_per_click = $costo/$Anno_ClickTotali->conteggio;
     	}
     	else
     	{
     	$costo_per_click = -1;
     	}

    }


		
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

    $request->flash(); // Memorizza le request

    $macros = Macrolocalita::noRR()->pluck('nome', 'nome');

    
		return view('admin.v2_IAStatsOfferte',
			compact(

				"pagina_id",
				"table",
				
				"pages",
				"devices",
				"tables",
				"macro",
				"macros",
				
				"yearSelected",
				"yearReferer",
				"date_from",
				"date_to",
				
				"AllOfferPerPeriod",
				"AllOfferPerPeriod2",

				"q_AllOfferPerPeriod",
				"q_AllOfferPerPeriod2",

				"Anno_ClickTotali",  
				"Anno_ClickTotali2",  
				"Anno_mediaClickPerMese",
				"Anno_mediaClickPerMese2",
				"Anno_mediaClickPerLocalitaArr",
				"Anno_mediaClickPerLocalitaArr2",
				"costo",
        "costo_per_click",
        
        "costi_non_attendibili",
        "tabella_costi"
			)
		);		
		
	}
	
	
}

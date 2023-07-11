<?php

/**
 *
 * View composer per render listino standard:
 * @parameters: cliente, titolo
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ListinoComposer
{

	/*
	 *
	 * Crea il listino per il file composer
	 *
	 * @$view
	 *
	 */
	 
	public function compose(View $view)
	{
		
		$listini_standard = array();
		$viewdata = $view->getData(); // View
		$locale = $viewdata['locale'];
		$titolo = isset($viewdata['titolo']) ? $viewdata['titolo'] : '';
		
		$trattamenti = ['prezzo_sd', 'prezzo_bb', 'prezzo_mp', 'prezzo_pc', 'prezzo_hb', 'prezzo_fb', 'prezzo_ai','*'];
		$cliente = $viewdata['cliente']; // hotel
		$key = "listino_strandard_" . $cliente->id . "_" . $locale; // cache key
		
		// Se non esiste cache
		if (!$listini_standard = Utility::activeCache($key, "Cache Listino")) {
			
			// Prendo i listini con le offerte
			$listini = Utility::getListinoWithOfferte($cliente->id, $locale);
			//$listini_standard[] = $listini->listini; 
			
			// trovo l'anno del listino attraverso gli anni dei periodi
			$anni = array();
			$count = 0 ;
			
			// Ciclo sui listino
			foreach ($listini->listini as $listino) {
				
				// Attaco le offerte al listino
				$periodo_dal = $listino->periodo_dal;
				$periodo_al = $listino->periodo_al;
				
				foreach($trattamenti as $trattamento) {
					
					// Ciclo sui last minute
					$offerte = array("lastminute" => 0, "offerta" => 0, "bambinigratis" => 0, "prenotaprima" => 0);
					$evidenze = array("lastminute" => 0, "offerta" => 0, "bambinigratis" => 0, "prenotaprima" => 0);
										
					$offerte["lastminute"] 		= Utility::getOfferteMatch($listini->last, $trattamento, $periodo_dal, $periodo_al); // lastminute
					$offerte["offerta"] 		= Utility::getOfferteMatch($listini->offerte, $trattamento, $periodo_dal, $periodo_al); // offerte
					$offerte["prenotaprima"] 	= Utility::getOfferteMatchNoFormula($listini->offertePrenotaPrima, $trattamento, $periodo_dal, $periodo_al); // prenotaprima
					$offerte["bambinigratis"] 	= Utility::getOfferteMatchNoFormula($listini->bambiniGratisAttivi, $trattamento, $periodo_dal, $periodo_al); // prenotaprima
					
					foreach($listini->offerteTop as $ot):
						$evidenze[$ot["tipo"]] = Utility::getOfferteMatchTop( $ot, $trattamento, $periodo_dal, $periodo_al);  // offertetop
					endforeach;
					$evidenze["bambinigratis"] = Utility::getOfferteMatchNoFormula($listini->offerteBambiniGratisTop, $trattamento, $periodo_dal, $periodo_al); // bambini gratis
											
					$nofferte = $offerte["lastminute"] + $offerte["offerta"] + $offerte["bambinigratis"] + $offerte["prenotaprima"] + $evidenze["lastminute"] + $evidenze["offerta"] + $evidenze["bambinigratis"] + $evidenze["prenotaprima"];
          
          /**
           * ATTENZIONE
           * nel listino si chiama prezzo_mp_min/_max e prezzo_pc_min/_max
           * però lo devo associate all'array con chiave prezzo_fb e prezzo_hb perché è con queste chiavi che trovo
           * TUTTE le offerte
           * 
           */

          if ($trattamento == 'prezzo_fb') 
            {
            $prezzo = $listini->listini[$count]['prezzo_pc']['prezzo'];
            } 
          elseif($trattamento == 'prezzo_hb') 
            {
            $prezzo = $listini->listini[$count]['prezzo_mp']['prezzo'];
            } 
          else 
            {
            $prezzo = $listini->listini[$count][$trattamento];
            }

          
					$listini->listini[$count][$trattamento] = array("prezzo" => $prezzo ,  "nofferte" => $nofferte, "offerte" => $offerte, "evidenze" => $evidenze);
									
				}
				
				$count++;
				
				// Aggiorno il titolo del listino
				$anno_da = Utility::getLocalDate($listino->periodo_dal, '%Y');
				$anno_a = Utility::getLocalDate($listino->periodo_al, '%Y');
				
				if (!in_array($anno_da, $anni)) {//s
					$anni[] = $anno_da;
				}

				if (!in_array($anno_a, $anni)) {
					$anni[] = $anno_a;
				}

			}
			
			//dd($listini->listini);
			$listini_standard[] = $listini->listini; 
      //dd($listini_standard);
      
			// Aggiorno il titolo
			if (empty($anni)) {
				$anno_listino = date('Y', time());
			}
			else {
				$anno_listino = implode(' - ', $anni);
			}

			if (isset($titolo) && $titolo != '') {
				$listini_standard[] = strtoupper($titolo). '  ' .$anno_listino;
			} else {
				$listini_standard[] = '';
			}
			
			// MEtto in cache
			Cache::put($key, $listini_standard, Carbon::now()->addDays(1));
			
		} 
	
		//echo "<pre>" . print_r( $listini_standard[0],1) . "</pre>";
		//die();
			
		$view->with([
			'listini' =>  $listini_standard[0],
      'titolo' => $listini_standard[1],
      'locale' => $locale
			]);

	}


}

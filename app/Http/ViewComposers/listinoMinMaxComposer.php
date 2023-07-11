<?php

/**
 *
 * View composer per render listino MinMax (prezzi min e max):
 * @parameters: cliente, titolo
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use App\Utility;
use Illuminate\Support\Facades\Cache;

class listinoMinMaxComposer
{

	
	/*
	 *
	 * Crea il listino minimi e massimi per il file composer
	 *
	 * @$view
	 *
	 */

	
	public function compose(View $view)
	{
		
		$listini_minmax = array();
		$viewdata = $view->getData();
		$cliente = $viewdata['cliente'];
		$locale = $viewdata['locale'];
		
		$titolo = isset($viewdata['titolo']) ? $viewdata['titolo'] : '';
		$key = "listino_minmax_" . $cliente->id . "_" . $locale;
		$trattamenti = ['prezzo_sd', 'prezzo_bb', 'prezzo_mp', 'prezzo_pc', 'prezzo_hb', 'prezzo_fb', 'prezzo_ai','*'];
				
		if (isset($titolo) && $titolo != '') {
			$titolo = strtoupper($titolo);
		} else {
			$titolo = '';
		}
		
		$anni = array();
		$count = 0;
		
		if (!$listini_minmax = Utility::activeCache($key, "Cache Listino MinMax")) {
			
      $listinoMinMax = Utility::getListinoWithOfferte($cliente->id, $locale);
      
			$listini_minmax[] = $listinoMinMax->listiniMinMax; 
			$count = 0 ;
			
			foreach ($listinoMinMax->listiniMinMax as $listino) {
				
				// Attaco le offerte al listino
				$periodo_dal = $listino->periodo_dal;
				$periodo_al = $listino->periodo_al;
				
				foreach($trattamenti as $trattamento) {
					
					// Ciclo sui last minute
					$offerte = array("lastminute" => 0, "offerta" => 0, "bambinigratis" => 0, "prenotaprima" => 0);
					$evidenze = array("lastminute" => 0, "offerta" => 0, "bambinigratis" => 0, "prenotaprima" => 0);
					
          $offerte["lastminute"] 		= Utility::getOfferteMatch($listinoMinMax->last, $trattamento, $periodo_dal, $periodo_al); // lastminute

          $offerte["offerta"] 		= Utility::getOfferteMatch($listinoMinMax->offerte, $trattamento, $periodo_dal, $periodo_al); // offerte
         
          $offerte["prenotaprima"] 	= Utility::getOfferteMatchNoFormula($listinoMinMax->offertePrenotaPrima, $trattamento, $periodo_dal, $periodo_al); // prenotaprima
          
          $offerte["bambinigratis"] 	= Utility::getOfferteMatchNoFormula($listinoMinMax->bambiniGratisAttivi, $trattamento, $periodo_dal, $periodo_al); // prenotaprima
        
					foreach($listinoMinMax->offerteTop as $ot):
						$evidenze[$ot["tipo"]] = Utility::getOfferteMatchTop( $ot, $trattamento, $periodo_dal, $periodo_al);  // offertetop
					endforeach;
					
					$evidenze["bambinigratis"] = Utility::getOfferteMatchNoFormula($listinoMinMax->offerteBambiniGratisTop, $trattamento, $periodo_dal, $periodo_al); // bambini gratis
					
					$nofferte = $offerte["lastminute"] + $offerte["offerta"] + $offerte["bambinigratis"] + $offerte["prenotaprima"] + $evidenze["lastminute"] + $evidenze["offerta"] + $evidenze["bambinigratis"] + $evidenze["prenotaprima"];
					
          $prezzo = array();
          /**
           * ATTENZIONE
           * nel listino si chiama prezzo_mp_min/_max e prezzo_pc_min/_max
           * però lo devo associate all'array con chiave prezzo_fb e prezzo_hb perché è con queste chiavi che trovo
           * TUTTE le offerte
           * 
           */
          if ($trattamento == 'prezzo_fb') 
            {
            $new_trattamento = 'prezzo_pc';
            $prezzo["min"] = $listinoMinMax->listiniMinMax[$count][$new_trattamento . "_min"];
            $prezzo["max"] = $listinoMinMax->listiniMinMax[$count][$new_trattamento . "_max"];	
            } 
          elseif ($trattamento == 'prezzo_hb') 
            {
            $new_trattamento = 'prezzo_mp';
            $prezzo["min"] = $listinoMinMax->listiniMinMax[$count][$new_trattamento . "_min"];
            $prezzo["max"] = $listinoMinMax->listiniMinMax[$count][$new_trattamento . "_max"];	
            }
          else 
            {
            $prezzo["min"] = $listinoMinMax->listiniMinMax[$count][$trattamento . "_min"];
            $prezzo["max"] = $listinoMinMax->listiniMinMax[$count][$trattamento . "_max"];					
            }
          
          
          $listinoMinMax->listiniMinMax[$count][$trattamento] = array("prezzo" => $prezzo ,  "nofferte" => $nofferte, "offerte" => $offerte, "evidenze" => $evidenze);
								
				}
				
				$count++;

				// Aggiorno il titolo del listino
				$anno_da = Utility::getLocalDate($listino->periodo_dal, '%Y');
				$anno_a = Utility::getLocalDate($listino->periodo_al, '%Y');
				
				if (!in_array($anno_da, $anni)) {
					$anni[] = $anno_da;
				}

				if (!in_array($anno_a, $anni)) {
					$anni[] = $anno_a;
				}
								
      }
      
      //dd($listinoMinMax->listiniMinMax);


      // ATTENZIONE:  
			
			// Aggiorno il titolo
			if (empty($anni)) {
				$anno_listino = date('Y', time());
			}
			else {
				$anno_listino = implode(' - ', $anni);
			}

			if (isset($titolo) && $titolo != '') {
				$listini_minmax[] = strtoupper($titolo). '  ' .$anno_listino;
			} else {
				$listini_minmax[] = '';
			}

			Cache::put($key, $listini_minmax, Carbon::now()->addDays(1));

		} 
		$view->with([
			'listinoMinMax' => $listini_minmax[0],
			'titolo' => $listini_minmax[1]
		]);

	}


}

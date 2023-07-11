<?php

/**
 *
 * View composer per render punti di forza:
 * @parameters: cliente, locale
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\PuntoForzaLingua;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

class PuntiDiForzaComposer
{

	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	 
	public function compose(View $view)
	{

		$viewdata = $view->getData();
		$cliente = $viewdata['cliente'];
		$locale = $viewdata['locale'];

		$titolo = isset($viewdata['titolo']) ? $viewdata['titolo'] : '';

		if (isset($titolo) && $titolo != '') {
			$titolo = strtoupper($titolo);
		} else {
			$titolo = '';
		}

		/**
	     * Siccome il listing dei punti di forza nella versione mobile sono differenti tra listing e scheda
	     * (anche come numero, nel listing ne faccio vedere solo 6, passo questa variabile dalla scheda hotel per differenziare la vista del composer)
	     */
	     
		$in_hotel = isset($viewdata['in_hotel']) ? $viewdata['in_hotel'] : 0;
		$hotel_simili = isset($viewdata['hotel_simili']) ? $viewdata['hotel_simili'] : 0;
		
		$array_punti_di_forza = array();
		
		if ($in_hotel)
			
			foreach ($cliente->puntiDiForza as $puntiDiForza) {
                
                if(isset($puntiDiForza->puntiDiForza_lingua->first()->nome))
                    $pdf_name = $puntiDiForza->puntiDiForza_lingua->first()->nome;
                else
                    $pdf_name = "";
				
				if ($pdf_name != "") {
					$pdf = "";
					
					if ($puntiDiForza->evidenza)  
						$pdf = '<b class="evidenziato">';
					
					$pdf .= $pdf_name;
					
					if ($puntiDiForza->evidenza)  
						$pdf .= '</b>';
						
					$array_punti_di_forza[] = strtolower($pdf);
				}
				
			}
			
		else {
			
			
			/**
			 * LEGGO I PUNTI DI FORZA DALLE COLONNE TEMPORANEE 
             * tmp_punti_di_forza_it,tmp_punti_di_forza_en,.... della tabella tblHotel
			 */
			 
			$column_name = "tmp_punti_di_forza_$locale";
			$array_punti_di_forza = explode(',', strtolower($cliente->$column_name));
			
		}
				
		$view->with(['array_punti_di_forza' => $array_punti_di_forza, 'titolo' => $titolo, 'in_hotel' => $in_hotel, 'hotel_simili' => $hotel_simili]);
	}


}

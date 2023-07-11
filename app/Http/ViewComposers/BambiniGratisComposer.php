<?php

/**
 *
 * View composer per render bambino gratis:
 * @parameters: cliente
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\Utility;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BambiniGratisComposer
{


	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	public function compose(View $view)
	{

		$periodi = array();
		$servizi_finali = array();
		$viewdata = $view->getData();
		$cliente = $viewdata['cliente'];
		$locale = $viewdata['locale'];
		$titolo = isset($viewdata['titolo']) ? $viewdata['titolo'] : '';
		
		if (isset($titolo) && $titolo != '') {
			$titolo = strtoupper($titolo);
		} else {
			$titolo = '';
		}

		$nbg = isset($viewdata['nbg']) ? $viewdata['nbg'] : 0;

		$key = "bambini_items_" . $cliente->id . "_" . $locale;
		$array_bambini_items = [];

		if (!$array_bambini_items = Utility::activeCache($key, "CacheBambini Items")) {
					
			$periodiBambiniGratis = $cliente->bambiniGratisAttivi()->orderBy('valido_dal')->get();

			$count = 0;

			foreach ($periodiBambiniGratis as $periodo) {

				$periodi[$count]['id'] = $periodo->id;
				$periodi[$count]['fino_a_anni'] = $periodo->_fino_a_anni($locale);
				$periodi[$count]['anni_compiuti'] = $periodo->_anni_compiuti($locale);
				$periodi[$count]['dal'] = $periodo->valido_dal; //Utility::getLocalDate($periodo->valido_dal, '%d %b');
				$periodi[$count]['al']  = $periodo->valido_al;  //Utility::getLocalDate($periodo->valido_al, '%d %b');
				
				if (!is_null($periodo->translate($locale)->first()))
					{
					$periodo_liungua = $periodo->translate($locale)->first();
					} 
				else
					{
					$periodo_liungua = $periodo;
					}


				$periodi[$count]['approvata']= $periodo_liungua->approvata;
				
				if (!is_null($periodo_liungua->data_approvazione)) {
					$periodi[$count]['data_approvazione']= Utility::getLocalDate($periodo_liungua->data_approvazione, '%e %B %Y ore %T');
				} else {
					$periodi[$count]['data_approvazione']= null;
				}

				if (!is_null($periodo->translate($locale)->first())) 
					{
					$periodi[$count]['note'] = $periodo->translate($locale)->first()->note;
					} 
				else 
					{
					$periodi[$count]['note'] = "";
					}
				
				$count++;
				
			} // end for

			$array_bambini_items[] = $periodi;

			// devo trovare i servizi per bambini gratis

			if (count($periodi)) {
				
				$servizi = $cliente->serviziPerBambini;
				foreach ($servizi as $s) {
					$servizi_finali[] = $s->translate($locale)->first()->nome . ' ' . $s->pivot->note;
				}

				$servizi = $cliente->serviziPrivatiPerBambini;

				if (count($servizi)) 
					foreach ($servizi as $s)
						if (isset($s->translate($locale)->first()->nome))
							$servizi_finali[] = $s->translate($locale)->first()->nome;

			}

			$array_bambini_items[] = $servizi_finali;

			Utility::putCache($key, $array_bambini_items);

		} 

		$view->with([
			'periodi' => $array_bambini_items[0],
			'titolo' => $titolo,
			'servizi_finali' => $array_bambini_items[1],
			'nbg' => $nbg,
			'locale' => $locale
			]);
	}


}

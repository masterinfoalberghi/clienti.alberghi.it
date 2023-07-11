<?php

/**
 * View composer per render delle offerte e dei last oppure di uno solo dei due in base al parametro tipo:
 *
 * @parameters: cliente , locale, titolo, tipo
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;

class OfferteComposer
{

	/**
	 * Aggiunge delle variabili alla View
	 *
	 * @param  View  $view
	 * @return void
	 */
	 
	public function compose(View $view)
	{

		$offers = array();
		$tipo = "";

		$viewdata = $view->getData();

		$cliente = $viewdata['cliente'];
		$locale = $viewdata['locale'];

		$titolo = isset($viewdata['titolo']) ? $viewdata['titolo'] : '';

		if (isset($titolo) && $titolo != '')
			$titolo = strtoupper($titolo);
		else
			$titolo = '';

		$offerte = $cliente->offerte;
		$offerteTop = $cliente->offerteTopOS;

		$view->with([
			'offers' => $offerte,
			'offersTop' => $offerteTop,
			'titolo' => $titolo,
			'tipo' => $tipo,
			'hotel' => $cliente
			]);
	}


}

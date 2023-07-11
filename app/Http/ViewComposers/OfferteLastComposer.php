<?php

/**
 * View composer per render delle offerte e dei last oppure di uno solo dei due in base al parametro tipo:
 *
 * @parameters: cliente , locale, titolo, tipo
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;

class OfferteLastComposer
{

	/**
	 * Aggiunge delle variabili alla View
	 *
	 * @param  View  $view
	 * @return View
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

		$tipo = $viewdata['tipo'];

		if ($tipo == 'lastminute')
			$offerteLast = $cliente->last;
		elseif ($tipo == 'offerta')
			$offerteLast = $cliente->offerte;
		else
			$offerteLast = $cliente->offerte;

		$offerteTop = $cliente->offerteTopLast;

		$view->with([
			'offers' => $offerteLast,
			'offersTop' => $offerteTop,
			'titolo' => $titolo,
			'tipo' => $tipo,
			'hotel' => $cliente
			]);
			
	}


}

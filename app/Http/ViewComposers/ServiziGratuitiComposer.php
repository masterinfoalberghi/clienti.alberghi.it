<?php

/**
 *
 * View composer per render servizi gratuiti associati all'hotel:
 * @parameters: cliente, locale, titolo
 *
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;

class ServiziGratuitiComposer
{

	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	 
	public function compose(View $view)
	{

		$offers = array();
		$viewdata = $view->getData();
		$cliente = $viewdata['cliente'];
		$locale = $viewdata['locale'];

		$titolo = isset($viewdata['titolo']) ? $viewdata['titolo'] : '';

		if (isset($titolo) && $titolo != '') {
			$titolo = strtoupper($titolo);
		} else {
			$titolo = '';
		}

		$servizi = $cliente->serviziGratuiti;
		$servizi_privati_gratuiti = $cliente->serviziPrivatiGratuiti;

		$view->with([
			'all_servizi' => $servizi,
			'servizi_privati_gratuiti' => $servizi_privati_gratuiti,
			'titolo' => $titolo,
			'locale' => $locale
			]);
	}


}

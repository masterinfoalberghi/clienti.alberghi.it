<?php

/**
 *
 * View composer per render bambino gratis:
 * @parameters: cliente
 *
 */

namespace App\Http\ViewComposers;

use App\Utility;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;

class BambiniGratisListingComposer
{

	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	public function compose(View $view)
	{

		$bambino_gratis = NULL;
		$viewdata = $view->getData();
		$cliente = $viewdata['cliente'];
		$bambino_gratis = $cliente->bambiniGratisAttivi->first();
		
		$view->with([
			'bambino_gratis' => $bambino_gratis,
			'id' => $cliente->id
			]);
	}


}

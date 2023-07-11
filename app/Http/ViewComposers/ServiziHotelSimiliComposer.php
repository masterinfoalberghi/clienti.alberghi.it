<?php

/**
 *
 * View composer per render servizi associati all'hotel:
 * @parameters: h, , limit locale
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\CategoriaServizi;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Utility;

class ServiziHotelSimiliComposer
{
	
	/**
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */
	public function compose(View $view)
	{

		$servizi_hotel_simile = [];
		$viewdata = $view->getData();
		$cliente = $viewdata['h'];
		$locale = $viewdata['locale'];
		$limit = $viewdata['limit'];

		$key = "servizi_hotel_simile_" . $id . "_" . $locale;

		if (!$servizi_hotel_simile = Utility::activeCache($key, "Cache Hotel Simili")) {
			
			$servizi_cliente = $cliente->servizi()->with(
				['servizi_lingua' => function ($query) use ($locale)
				{
					$query->where('lang_id', '=', $locale);
				}


				])
			->orderBY('admin_position')
			->take($limit)
			->get();

			foreach ($servizi_cliente as $servizi) {
				$servizi_hotel_simile[] = $servizi->servizi_lingua->first()->nome;
			}

			Utility::putCache($key, $servizi_hotel_simile);

		} 

		$view->with([
			'servizi' => $servizi_hotel_simile
		]);
	}


}

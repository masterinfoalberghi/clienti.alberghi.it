<?php

/**
 *
 * View composer per render servizi gratuiti associati all'hotel:
 * @parameters: cliente, locale, titolo
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\ImmagineGallery;
use App\Utility;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HotelGalleryComposer
{	
	/** 
	 * Bind data to the view.
	 *
	 * @param  View  $view
	 * @return void
	 */

	public function compose(View $view)
	{
		$immagini_gallery = [];
		$viewdata = $view->getData();
		$cliente = $viewdata['cliente'];
		$locale = $viewdata['locale'];
		$pathDeviceType = Config::get('view.pathDeviceType');
		
		if ($pathDeviceType == 'phone_views') {
			$key = "gallery_items_mobile_" . $cliente->id . "_" . $locale;
		} else {
			$key = "gallery_items_desktop_" . $cliente->id . "_" . $locale;
		}
		
		if (!$immagini_gallery = Utility::activeCache($key, "Cache Gallery Items")) {
			
			$gallery = $cliente->immaginiGallery->sortBy('position');
			
			if ($pathDeviceType == 'phone_views') {
				
				foreach ($gallery as $img) {

					$retina = Utility::asset($img->getImg("720x400", true));
					$large = Utility::asset($img->getImg("360x200", false));
					$featured = Utility::asset($img->getImg("360x320", true));
					$caption = ($img->immaginiGallery_lingua->isEmpty()) ? '' : $img->immaginiGallery_lingua->first()->caption;
					$immagini_gallery[] = [$large, $retina, $caption, $featured];
					//echo "<pre>" . print_r($immagini_gallery,1) . "</pre>";
				}
				
			} else {
				
				foreach ($gallery as $img) {
					$retina = Utility::asset($img->getImg("1750x972", true));
					$large = Utility::asset($img->getImg("800x538", true));
					$thumb = Utility::asset($img->getImg("113x99", true));

					$caption = ($img->immaginiGallery_lingua->isEmpty()) ? '' : $img->immaginiGallery_lingua->first()->caption;
					$immagini_gallery[$thumb] = [$thumb, $large, $retina, $caption];
				}
				
			}
			Utility::putCache($key, $immagini_gallery);
		} 
		
		$codice = "";
		
		if (!is_null($cliente->descrizioneHotel) && $cliente->descrizioneHotel->video_url != '' ) {
			$codice = explode("/",  $cliente->descrizioneHotel->video_url);
			$codice = end( $codice );
		}
	
		$view->with([
			'immagini_gallery' => $immagini_gallery,
			'pathDeviceType' => $pathDeviceType,
			'hotel_id' => $cliente->id,
			'hotel_nome' => $cliente->nome,
			'codice' => $codice
		]);
	}
}

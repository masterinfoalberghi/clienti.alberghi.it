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

use App\Http\Controllers\Admin\InfoBenessereController;
use App\PuntoForzaLingua;
use App\Utility;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class InfoBenessereComposer
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

		$infoBenessere = $cliente->infoBenessere;


		/* CAMBIO LE LABEL IN BASE AL DISPOSITIVO */
		$detect = new \Detection\MobileDetect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet_views.' : 'phone_views.') : 'views.');
		
		if ($deviceType != "phone_views.")
			$class = "evidenziato";
		else
			$class = "";
		
		$info = [];

		// riga Centro benessere:
		
		if ($detect->isMobile())
			$info_centro_b = $infoBenessere->sup .' mq';
		else
			$info_centro_b = Lang::get('listing.sup') .' ' . $infoBenessere->sup .' mq';

		if ($infoBenessere->area_fitness && $infoBenessere->sup_area_fitness > 0) 
		
			$info_centro_b .= '&nbsp;+ '.$infoBenessere->sup_area_fitness . ' mq ' . Lang::get('listing.area_fit');

		$info[Lang::get('listing.centro_b')] = $info_centro_b;

		// riga Apertura
		$info_apertura = [];
		
		if ($infoBenessere->aperto_annuale) {
			$info_apertura = Lang::get('listing.annuale');
		}
		elseif (!empty($infoBenessere->aperto_dal) && !empty($infoBenessere->aperto_al)) {
			$mesi = Utility::mesi();
			$info_apertura = Lang::get('listing.'.$mesi[$infoBenessere->aperto_dal]) . ' - ' .Lang::get('listing.'.$mesi[$infoBenessere->aperto_al]);
		}
		
		if ($info_apertura)
			$info[Lang::get('listing.apertura')] = $info_apertura;
		
		
		
		// riga posizione
		if ($infoBenessere->in_hotel) 
			$info_posizione = Lang::get('listing.in_hotel');
		else
			$info_posizione = Lang::get('listing.a') . ' ' . $infoBenessere->distanza_hotel . ' '	.Lang::get('listing.metri_da');
			
		$info[Lang::get('listing.posizione')] = $info_posizione;
		
		
		// riga caratteristiche
		// la chiave è numerica in modo che viene renderizzata senza ul - li
		$info_caratt = [];
		
		$bc = new InfoBenessereController;
		
		// caratteristiche
		$caratt_fields = $bc->getCaratt_fields();

		foreach ($caratt_fields as $field) 
			{
			if ($infoBenessere->$field) 
				{
				$info_caratt[] = Lang::get('listing.'.$field);
				}
			}
			
		$car = implode(', ',$info_caratt);
		
		//////////////////////////////////////////////////////////////////////////////////////////
		//ATTENZIONE: Aggiungo alla stringa delle caratteristiche la stringa delle peculiarità  //
		//////////////////////////////////////////////////////////////////////////////////////////
		if (empty($car)) 
			{
			$car = $infoBenessere->peculiarita;
			} 
		else 
			{
			if (!empty($infoBenessere->peculiarita)) 
				{
				$car .= ', '.$infoBenessere->peculiarita;
				} 
			}


		$info[Lang::get('listing.caratteristiche')] =  "";
		
		if ($car)
			{
			if ($deviceType == "phone_views.") 
				{
				$info[Lang::get('listing.caratteristiche')] = Utility::getExcerpt($car, $limit = 20, $strip = true);
				} 
			else 
				{
				$info[Lang::get('listing.caratteristiche')] = $car;
				}				
			}
		
		// riga costo
		if ($infoBenessere->a_pagamento) {
			
			$tipo_costo = $infoBenessere->a_pagamento ? Lang::get('listing.p') : Lang::get('listing.g');
			$info[Lang::get('listing.caratteristiche')] .= ', <b class="' . $class . '">' . $tipo_costo . '</b>';
			
		}
		
		// riga età minima
		if($infoBenessere->eta_minima) {
			
			$info_eta = $infoBenessere->eta_minima;
			$info[Lang::get('listing.caratteristiche')] .= ', <b class="' . $class . '">' . Lang::get('listing.eta_min') . ' ' . $info_eta . " " .Lang::get('labels.anni') . '</b>';
			
		}
		
		if($infoBenessere->obbligo_prenotazione) {
			$info[Lang::get('listing.caratteristiche')] .= ', <b class="' . $class . '">' . Lang::get('listing.obbligo_prenotazione') . '</b>';
		}
		
		$uso_in_esclusiva = "";
		if ($infoBenessere->uso_in_esclusiva == 0) 
			$uso_in_esclusiva .= "";
		elseif ($infoBenessere->uso_in_esclusiva == 1)
			$uso_in_esclusiva .= Lang::get('listing.uso_in_esclusiva');
		else
			$uso_in_esclusiva .= Lang::get('listing.uso_in_esclusiva') .": ". Lang::get('listing.a_richiesta');
			
			
		if ($uso_in_esclusiva != "")
			$info[Lang::get('listing.caratteristiche')] .= ', <b class="' . $class . '">' . $uso_in_esclusiva. '</b>';

				
		$label_spa = "";

		foreach ($cliente->servizi as $servizio)
			if ($servizio->servizi_lingua->first()->nome == 'spa fuori hotel')
				$label_spa = "SPA " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri') . " " . Lang::get('labels.dall_hotel');

		$view->with(['info' => $info, 'label_spa' => $label_spa]);
		
	}


}

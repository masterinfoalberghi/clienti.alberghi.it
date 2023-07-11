<?php

/**
 *
 * View composer per render servizi associati all'hotel:
 * @parameters: cliente, locale, titolo
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use App\CategoriaServizi;
use App\Http\Controllers\Admin\InfoBenessereController;
use App\Http\Controllers\Admin\InfoPiscinaController;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;

class ServiziComposer
{	
	
	/**
	 * Crea i servizi
	 * 
	 * @access public
	 * @param View $view
	 * @return void
	 */
	 
	public function compose(View $view)
	{

		$offers = array();
		$note_servizi = array();
		$viewdata = $view->getData();
		$cliente = $viewdata['cliente'];
		$id_cliente = $cliente->id;
		$locale = $viewdata['locale'];
		$titolo = isset($viewdata['titolo']) ? strtoupper($viewdata['titolo']) : '';

		/**
		 * Servizi associati all'hotel
		 */
		 
		$key = "CategoriaServizi_" . $cliente->id . "_" . $locale;

		if (!$cat_servizi = Utility::activeCache($key, "Cache Categoria Servizi")) {
						
			$servizi_ids = $cliente->servizi()->nuovo()->get()->pluck('id')->toArray();

			/**
			 * note dei servizi associati all'hotel del tipo $note_servizi[id_servizio] = "nota"
			 */
	
			if ($locale == 'it')
				foreach ($cliente->servizi as $servizio)
					$note_servizi[$servizio->id] = $servizio->pivot->note;
					
			else {

				$note_lang = 'note_'.$locale;
				foreach ($cliente->servizi as $servizio)
					$note_servizi[$servizio->id] = $servizio->pivot->$note_lang;
					
			}
			
			$categorie = CategoriaServizi::notListing()->has('servizi')
				->with([
					'servizi',
					'servizi.servizi_lingua' => function ($query) use ($locale, $servizi_ids) {
						
						$query->where('lang_id', '=', $locale);
												
					},
					'servizi.gruppo',
					'serviziPrivati' => function ($query) use ($id_cliente, $locale){
						
						$query->with(['servizi_privati_lingua' => function($q)  use ($locale) {
							$q->where('lang_id', '=', $locale);
						}])
						->where('hotel_id', $id_cliente);
					}
	
	
					])
				->orderBY('position')->get();
			

			$cat_servizi = array();

			foreach ($categorie as $categoria) {
				
				$servizi_associati = array();
				
				foreach ($categoria->servizi as $servizio)
					if (in_array($servizio->id, $servizi_ids)) {
						
						if (isset($servizio->servizi_lingua->first()->nome)) {
							$servizio_text = $servizio->servizi_lingua->first()->nome;
							if (isset($note_servizi[$servizio->id]))
								$servizio_text .= ' '. $note_servizi[$servizio->id];

							$servizi_associati[] = $servizio_text;
						}

					}

				foreach ($categoria->serviziPrivati as $servizioPriv)
					if (isset($servizioPriv->servizi_privati_lingua->first()->nome))
						$servizi_associati[] = $servizioPriv->servizi_privati_lingua->first()->nome;
				/**
				 * se è la categoria "Servizi per disabili" memorizzo i serviziPrivati perché poi li aggiungo ai 4 statici
			 	 */
			 	 
				$servizi_associati_disabili = [];
				if ($categoria->nome == 'Servizi per disabili') 
					foreach ($categoria->serviziPrivati as $servizioPriv)
						if (isset($servizioPriv->servizi_privati_lingua->first()->nome))
							$servizi_associati_disabili[] = $servizioPriv->servizi_privati_lingua->first()->nome;

				if (count($servizi_associati))
					$cat_servizi[$categoria->getNomeFrontEnd()] = $servizi_associati;

			}


			/*
			"
			cat_servizi
			
			Servizi per disabili" => array:1 [▼
			  0 => "servizi per disabili "
			]
			"accessibilita hotel" => array:2 [▼
			  0 => "ingresso situato al piano terra con rampa"
			  1 => "piattaforma elevatrice per raggiungere tutti i piani"
			]
			"accesso camere" => array:1 [▼
			  0 => "ascensore a norma per disabili "
			]
			"camera accessibile" => array:1 [▼
			  0 => "spazio di manovra per sedia a rotelle "
			]
			"bagno" => array:2 [▼
			  0 => "doccia con accesso per sedie a rotelle "
			  1 => "possibilità di sedia per doccia e maniglioni (su richiesta)"
			]
			
			*/
			$servizi_disabili = [];
			foreach ($cat_servizi as $key => $value_arr) 
				{
				
				if(in_array($key, ['accessibilita hotel','accesso camere','camera accessibile','bagno']) )
					{
					foreach ($value_arr as $value) 
						{

						if($key == 'camera accessibile')
							{
							$value .= " in camera";
							}
						$servizi_disabili[] = $value;
						
						}
						unset($cat_servizi[$key]);
					}
				
				}



			/**
			 * ATTENZIONE: la categoria "Servizi per disabili" che ha solo il servizio "servizi per disabili" 
			 * serve solo per sapere se l'hotel è nella ricerca per disabili oppure no
			 */ 
			 
			 
			if(count($servizi_disabili)){
				$cat_servizi['Servizi per disabili'] = $servizi_disabili;
			}
			//////////////////////////////////////////////////////////////////////////////
			// ATTENZIONE VOGLIO RICAVARE la categoria "SERVIZI PISCINA" da InfoPiscina //
			//////////////////////////////////////////////////////////////////////////////
			if (!is_null($cliente->infoPiscina) && $cliente->infoPiscina->sup > 0) 
				{
				
				$infoPiscina = $cliente->infoPiscina;

				/* CAMBIO LE LABEL IN BASE AL DISPOSITIVO */
				$detect = new \Detection\MobileDetect;
				$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet_views.' : 'phone_views.') : 'views.');
				$info = [];
				
				
				// riga Piscina:
				if ($detect->isMobile()) {
					$info_piscina = $infoPiscina->sup .' mq';
				} else {
					$info_piscina = Lang::get('listing.sup') .' ' . $infoPiscina->sup .' mq';
				}
				

				if ($infoPiscina->h_min > 0 || $infoPiscina->h_max >0) {
					$info_piscina .=  ", " . Lang::get('listing.altezza')  . ' min ' . $infoPiscina->h_min . ' cm - max ' . $infoPiscina->h_max . ' cm';
				}
				else {
					$info_piscina .=  ", " . Lang::get('listing.altezza') . ' '. $infoPiscina->h . ' cm';
				}

				$info[Lang::get('listing.piscina')] = $info_piscina;


				// riga Apertura
				$info_apertura = "";
				if ($infoPiscina->aperto_annuale) {
					$info_apertura = Lang::get('listing.annuale');
				}
				elseif (!empty($infoPiscina->aperto_dal) && !empty($infoPiscina->aperto_al)) {
					$mesi = Utility::mesi();
					$info_apertura = Lang::get('listing.'.$mesi[$infoPiscina->aperto_dal]) . ' - ' .Lang::get('listing.'.$mesi[$infoPiscina->aperto_al]);
				}
				
				if ($info_apertura)
					$info[Lang::get('listing.apertura')] = $info_apertura;
				

				// riga Posizione e ore di sole
				$info_posizione = [];

				$pc = new InfoPiscinaController;
				$pos_fields = $pc->getPos_fields();

				$info_posizione_str = "";
				foreach ($pos_fields as $field)
					if ($infoPiscina->$field)
						$info_posizione_str .= Lang::get('listing.'.$field);
				
				if ($info_posizione_str)
					$info[Lang::get('listing.pos')] = $info_posizione_str;
				
				
				// Ore di sole
				
				$info_posizione_str = "";
				
				if ($detect->isMobile()) 
					$label_sole = 'listing.ore_sole_mobile';
				else
					$label_sole = 'listing.ore_sole';
						
				// ore di sole
				if ($infoPiscina->espo_sole_tutto_giorno)
					$info_posizione_str = Lang::get('listing.espo_sole_tutto_giorno');
				elseif ($infoPiscina->espo_sole > 0) 
					$info_posizione_str = $infoPiscina->espo_sole;
				
				if ($info_posizione_str)
					$info[Lang::get($label_sole)]= $info_posizione_str;
				
				
						
				// caratteristiche
				$caratt_fields = $pc->getCaratt_fields();

				$info_caratt = [];
				foreach ($caratt_fields as $field)
					if ($infoPiscina->$field)
						$info_caratt[] = strtolower(Lang::get('listing.'.$field));
				
				if (count($info_caratt)> 0)
					$info_caratt[0] = ucfirst($info_caratt[0]);
				
				// se ho lettini prendisole li aggiungo qui
				if ($infoPiscina->lettini_dispo > 0)
					$info_caratt[] = $infoPiscina->lettini_dispo . ' ' . Lang::get('listing.lettini');

				$info_caratt_str = implode(", " , $info_caratt);



				//////////////////////////////////////////////////////////////////////////////////////////
				//ATTENZIONE: Aggiungo alla stringa delle caratteristiche la stringa delle peculiarità  //
				//////////////////////////////////////////////////////////////////////////////////////////
				if (empty($info_caratt_str)) 
					{
					$info_caratt_str = $infoPiscina->peculiarita;
					} 
				else 
					{
					if (!empty($infoPiscina->peculiarita)) 
						{
						$info_caratt_str .= ', '.$infoPiscina->peculiarita;
						} 
					}
				 
				if (!empty($info_caratt_str)) 
					{
					if (!$detect->isMobile())
						$info[Lang::get('listing.caratteristiche')] = $info_caratt_str;
					else
						$info[Lang::get('listing.caratter')] = $info_caratt_str;
					}


				$serviziPiscina = [];
				foreach ($info as $label => $value) 
					{
					$serviziPiscina[] = $label . ': ' . $value;
					}

				if ( $infoPiscina->vasca_bimbi_sup > 0 ) 
					{
					$serviziPiscina[] = Lang::get('listing.vasca_b');	
					}

				if ($infoPiscina->vasca_idro_posti_dispo > 0) 
					{
					$serviziPiscina[] = Lang::get('listing.vasca_idro');
					}

				$cat_servizi['servizi piscina'] = $serviziPiscina;
				
			}

			//////////////////////////////////////////////////////////////////////////////////
			// ATTENZIONE VOGLIO RICAVARE la categoria "SERVIZI BENESSERE" da InfoBenessere //
			//////////////////////////////////////////////////////////////////////////////////
			if (!is_null($cliente->infoBenessere) && $cliente->infoBenessere->sup > 0) 
				{
				$infoBenessere = $cliente->infoBenessere;

				/* CAMBIO LE LABEL IN BASE AL DISPOSITIVO */
				$detect = new \Detection\MobileDetect;
				$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet_views.' : 'phone_views.') : 'views.');

				$info = [];

				// riga Centro benessere:
				
				if ($detect->isMobile())
					$info_centro_b = $infoBenessere->sup .' mq';
				else
					$info_centro_b = Lang::get('listing.sup') .' ' . $infoBenessere->sup .' mq';
					


				if ($infoBenessere->area_fitness && $infoBenessere->sup_area_fitness > 0) 
				
					$info_centro_b .= ', '.$infoBenessere->sup_area_fitness . ' mq ' . Lang::get('listing.area_fit');

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
				
				// riga costo
				if ($infoBenessere->a_pagamento) {
					$tipo_costo = $infoBenessere->a_pagamento ? Lang::get('listing.p') : Lang::get('listing.g');
					$info[Lang::get('listing.costo')] = $tipo_costo;
				}
				
				// riga posizione
				if ($infoBenessere->in_hotel) 
					$info_posizione = Lang::get('listing.in_hotel');
				else
					$info_posizione = Lang::get('listing.a') . ' ' . $infoBenessere->distanza_hotel . ' '	.Lang::get('listing.metri_da');
					
				$info[Lang::get('listing.posizione')] = $info_posizione;
				
				
				// riga età minima
				if($infoBenessere->eta_minima) {
					$info_eta = $infoBenessere->eta_minima;
					$info[Lang::get('listing.eta_min')] = $info_eta;
				}
				
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

				if ($car)
				$info[Lang::get('listing.caratteristiche')] = $car;
				
				$info[Lang::get('listing.obbligo_prenotazione_mobile')] = $infoBenessere->obbligo_prenotazione ? Lang::get('listing.si') : Lang::get('listing.no');
				
				$uso_in_esclusiva = "";
				if ($infoBenessere->uso_in_esclusiva == 0) 
					$uso_in_esclusiva .= Lang::get('listing.no');
				elseif ($infoBenessere->uso_in_esclusiva == 1)
					$uso_in_esclusiva .= Lang::get('listing.si');
				else
					$uso_in_esclusiva .= Lang::get('listing.a_richiesta');

				$info[Lang::get('listing.uso_in_esclusiva')] = $uso_in_esclusiva;

				
				$serviziBenessere = [];
				foreach ($info as $label => $value) 
					{
					$serviziBenessere[] = $label . ': ' . $value;
					}


				$cat_servizi['servizi benessere'] = $serviziBenessere;

				}


			Cache::put($key, $cat_servizi, Carbon::now()->addDays(1));

		} 
		$view->with([
			'cat_servizi' => $cat_servizi,
			'titolo' => $titolo
		]);
	}


}

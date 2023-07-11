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

use App\Http\Controllers\Admin\InfoPiscinaController;
use App\PuntoForzaLingua;
use App\Utility;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class InfoPiscinaComposer
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
		$infoPiscina = $cliente->infoPiscina;

		/* CAMBIO LE LABEL IN BASE AL DISPOSITIVO */
		$detect = new \Detection\MobileDetect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet_views.' : 'phone_views.') : 'views.');


		//$key = "piscina_" . $cliente->id . "_" . $locale;
		
		//if (!$piscina = Utility::activeCache($key, "Cache Piscina Composer")) {
		
			$info = [];
			
			/**
			 * Info generiche piscina
			 */
			
			$info_piscina = Lang::get('listing.sup') .' ' . $infoPiscina->sup .' mq';
			
			if ($infoPiscina->h_min > 0 || $infoPiscina->h_max >0)
				$info_piscina .=  ", " . Lang::get('listing.altezza')  . ' min ' . $infoPiscina->h_min . ' cm - max ' . $infoPiscina->h_max . ' cm';
				
			else 
				$info_piscina .=  ", " . Lang::get('listing.altezza') . ' '. $infoPiscina->h . ' cm';
	
			$info[Lang::get('listing.piscina')] = $info_piscina;
	
			/**
			 * Apertura
			 */
			 
			$info_apertura = "";
			$mesi = Utility::mesi();
			
			if ($infoPiscina->aperto_annuale)
				$info_apertura = Lang::get('listing.annuale');
			
			elseif (!empty($infoPiscina->aperto_dal) && !empty($infoPiscina->aperto_al))
				$info_apertura = Lang::get('listing.'.$mesi[$infoPiscina->aperto_dal]) . ' - ' .Lang::get('listing.'.$mesi[$infoPiscina->aperto_al]);
				
			if ($info_apertura)
				$info[Lang::get('listing.apertura')] = $info_apertura;

			/**
			 * Ore di sole
			 */
			 
			$info_posizione_str = "";
			$label_sole = 'listing.ore_sole';
					
			if ($infoPiscina->espo_sole_tutto_giorno)
				$info_posizione_str = Lang::get('listing.espo_sole_tutto_giorno');
				
			elseif ($infoPiscina->espo_sole > 0) 
				$info_posizione_str = $infoPiscina->espo_sole . " " . Lang::get($label_sole);

			if ($info_posizione_str)
				$info[Lang::get($label_sole)]= $info_posizione_str;

				
			/*if ($info_posizione_str)
			 	$info[Lang::get('listing.apertura')] .= ", " . $info_posizione_str;*/
			
			/**
			 * Posizione
			 */
			 
			$info_posizione = [];
	
			$pc = new InfoPiscinaController;
			$pos_fields = $pc->getPos_fields();
			$info_posizione_str = "";

			if ($cliente->notePiscina):
				$info[Lang::get('listing.pos')] = '<b>' . $cliente->notePiscina . "</b>";
				
			else:
				
				foreach ($pos_fields as $field){

					if ($infoPiscina->$field){
						$info_posizione_str .= Lang::get('listing.'.$field);
					}
				}

				if ($info_posizione_str)
					{
					$info[Lang::get('listing.pos')] = $info_posizione_str;
					}

				
				// check suggerimento visibile
				$field_pos = 'suggerimento_posizione_'.$locale;

				if($infoPiscina->suggerimento_visibile && $infoPiscina->$field_pos != '')
					{	
					$info[Lang::get('listing.pos')] == '' ? $info[Lang::get('listing.pos')] = $infoPiscina->$field_pos : $info[Lang::get('listing.pos')] .= ' - ' . $infoPiscina->$field_pos;
					}

			endif;
				
			
			
								
			/**
			 * Caratteristiche
			 */
			 
			$caratt_fields = $pc->getCaratt_fields();
	
			$info_caratt = [];
			foreach ($caratt_fields as $field)
				if ($infoPiscina->$field)
					$info_caratt[] = strtolower(Lang::get('listing.'.$field));
			
			if (count($info_caratt)> 0)
				$info_caratt[0] = ucfirst($info_caratt[0]);
			
			/**
			 * Se ho lettini prendisole li aggiungo qui
			 */
			 
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

					
			/**
			 * Vasca bambini
			 */
			
			$info_vasca = [];
			$info_vasca_str = "";
			
			if ( $infoPiscina->vasca_bimbi_sup > 0 ) {
				
				$info_vasca["-"] = Lang::get('listing.vasca_b');  
	
				if ( $infoPiscina->vasca_bimbi_sup > 0 )
					$info_vasca_str  .= $infoPiscina->vasca_bimbi_sup . ' mq';
	
				if ($infoPiscina->vasca_bimbi_h > 0)
					$info_vasca_str .= ', ';
	
				if ($infoPiscina->vasca_bimbi_h > 0)
					$info_vasca_str .= Lang::get('listing.altezza') . ' ' . $infoPiscina->vasca_bimbi_h . ' cm';
				
				$info_vasca[Lang::get('listing.vasca_b')] = ucfirst(strtolower($info_vasca_str));
				
				if ($infoPiscina->vasca_bimbi_riscaldata > 0)
					$info_vasca_risc = Lang::get('listing.si');
				else
					$info_vasca_risc = Lang::get('listing.no');
				
				$info_vasca[Lang::get('listing.riscaldata')] = ucfirst(strtolower($info_vasca_risc));
				
			}
			
			/**
			 * Vasca idro
			 */
			
			// Vasca bimibi e idro
			$info_idro = [];
			$info_idro_str = "";
			$info_idro_posizione_str = "";
			
			if ($infoPiscina->vasca_idro_posti_dispo > 0) {
							
				$info_idro["-"] = Lang::get('listing.vasca_idro');  
	
				if ($infoPiscina->vasca_idro_n_dispo > 0)
					$info_idro[Lang::get('listing.vasca_idro_disp')] = ucfirst(strtolower($infoPiscina->vasca_idro_n_dispo));
					
	
				if ($infoPiscina->vasca_idro_posti_dispo > 0) 
					$info_idro_str = $infoPiscina->vasca_idro_posti_dispo . ' '. Lang::get('listing.posti');	
		
				$info_idro[Lang::get('listing.vasca_idro')] = ucfirst(strtolower($info_idro_str));
				
				if ($infoPiscina->vasca_pagamento > 0)
					$info_idro_str = Lang::get('listing.si');
				else
					$info_idro_str = Lang::get('listing.no');
					
				$info_idro[Lang::get('listing.pagamento')] = ucfirst(strtolower($info_idro_str));
				
				if ($infoPiscina->vasca_idro_riscaldata > 0)
					$info_idro_str = Lang::get('listing.si');
				else
					$info_idro_str = Lang::get('listing.no');
				
				$info_idro[Lang::get('listing.riscaldata')] = ucfirst(strtolower($info_idro_str));
				
				$pos_fields = $pc->getPos_fields_vasca_idro();
				
				foreach ($pos_fields as $field)
					{
					if ($infoPiscina->$field)
						{
						if($field != 'vasca_idro_pos_esterna')
							{
							$info_idro_posizione_str = Lang::get('listing.' . str_replace("vasca_idro_","",$field));
							}
						else
							{
							$info_idro_posizione_str = Lang::get('listing.' . $field);
							}
						}
					}
				
				if ($info_idro_posizione_str)
					$info_idro[Lang::get('listing.pos')] = ucfirst(strtolower($info_idro_posizione_str));
			
			}
			
			

			$label_piscina = "";
			foreach ($cliente->servizi as $servizio) {
				if ($servizio->servizi_lingua->first()->nome == 'piscina fuori hotel')
					$label_piscina = Lang::get('listing.piscina') . " " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri') . " " . Lang::get('labels.dall_hotel');
			}

			foreach ($cliente->servizi as $servizio) {
				if ( $servizio->servizi_lingua->first()->nome == 'piscina in spiaggia')
					$label_piscina = Lang::get('listing.piscina') . " " . Lang::get('listing.pos_spiaggia') . " " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri') . " " . Lang::get('labels.dall_hotel');
			}
			
			$piscina = [];
			$piscina[0] = $info;
			$piscina[1] = $info_vasca;
			$piscina[2] = $info_idro;
			$piscina[3] = $label_piscina;
			
		//	Utility::putCache($key, $piscina);
			
		//}
				
		$view->with(['info' => $piscina[0], 'info_vasca' => $piscina[1], 'info_idro' => $piscina[2],'label_piscina' => $piscina[3]]);
	}


}

<?php

/**
 * HomeController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers;

use App;
use Request;
use Session;
use App\CmsPagina;
use App\BambinoGratis;
use App\Coupon;
use App\Utility;
use App\CookieIA;
use App\GruppoServizi;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\Macrolocalita;
use App\Localita;
use App\Offerta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{ 


	/* ------------------------------------------------------------------------------------
	 * METODI PRIVATI
	 * ------------------------------------------------------------------------------------ */

	/**
	 * RIMOSSA
	 * Prende le località interessanti per il cliente dai cookie
	 *
	 * @access private
	 * @param string $locale (default: "it")
	 */
	
	private function _getInteressi($locale) 
	{
		 //$interessi = CookieIA::getCookieLocalita();
	}

	/**
	 * Raccoglie tutti i links da mettere in fondo alla homepage
	 *
	 * @access private
	 * @param string $locale (default: "it")
	 * @return array $homepage
	 */

	private static function _getNews($locale ="it")
	{

		$news = array();
		$news["items"] = array();

		/**
		 * Trovo le news della homepage
		 */

		$key = "news_home_" . $locale;

		if (!$news = Utility::activeCache($key, "Cache News Homepage")) {

			$newsDB = DB::table('tblSpotPagine')
			->select(
				'tblCmsPagine.id',
				'tblCmsPagine.uri',
				'tblCmsPagine.listing_count',
				'tblCmsPagine.n_offerte',
				'tblCmsPagine.prezzo_minimo',
				'tblCmsPagine.prezzo_massimo',
				'tblCmsPagine.listing_localita_id',
				'tblCmsPagine.listing_macrolocalita_id',
				'tblSpotPagine.spot_h1',
				'tblSpotPagine.spot_h2',
				'tblSpotPagine.spot_descrizione',
				'tblSpotPagine.spot_immagine',
				'tblSpotPagine.spot_visibile_dal',
				'tblSpotPagine.spot_visibile_al',
				'tblSpotPagine.spot_visibile_ricorsivo'
			)
			->join('tblCmsPagine', function( $join ) use ( $locale )
				{

					$join->on('tblCmsPagine.id', '=', 'tblSpotPagine.id_pagina')
					->where('tblCmsPagine.lang_id', '=', $locale)
					->where('tblCmsPagine.attiva', '=', 1);

				})
			->where('tblSpotPagine.spot_attivo', '=', 3)
			->orderBy('tblSpotPagine.spot_ordine', "DESC")
			->get();

			/**
			 * Metto a posto gli oggetti in una array controllando che non siano scadute
			 */
		
			if ($newsDB):
				foreach ($newsDB as $new):
					
					$localita = $new->listing_localita_id !=0 ? Localita::find($new->listing_localita_id)->nome : "";
					$macrolocalita = $new->listing_macrolocalita_id != 0 ? Macrolocalita::find($new->listing_macrolocalita_id)->nome : "";
					
					/**
					 * Controllo la visibilita dell'evento
					 */
	
					$controlloDate = Utility::isValidMenu($new->spot_visibile_dal,  $new->spot_visibile_al, null,  $new->spot_visibile_ricorsivo );
	
					if ($controlloDate) {
		
						$_news = array();
						$_news["id_page"] 		= $new->id;
						$_news["titolo"] 		= Utility::replacePlaceholder(
									[
										"{HOTEL_COUNT}",
										"{OFFERTE_COUNT}",
										"{PREZZO_MIN}",
										"{PREZZO_MAX}",
										"{MACRO_LOCALITA}",
										"{LOCALITA}",
                                        "{CURRENT_YEAR}",
                                        "{CURRENT-YEAR}"
									],
									
									$new->spot_h1,
									 
									[
									 	$new->listing_count,
									 	$new->n_offerte,
									 	$new->prezzo_minimo,
									 	$new->prezzo_massimo,
									 	$localita,
									 	$macrolocalita,
                                        date("Y")+Utility::fakeNewYear(),
                                        date("Y")+Utility::fakeNewYear()
									 	
									 ]
								);
						$_news["sottotitolo"] 	= Utility::replacePlaceholder(
									[
										"{HOTEL_COUNT}",
										"{OFFERTE_COUNT}",
										"{PREZZO_MIN}",
										"{PREZZO_MAX}",
										"{MACRO_LOCALITA}",
										"{LOCALITA}",
										"{CURRENT_YEAR}",
										"{CURRENT-YEAR}"
									],
									
									$new->spot_h2,
									 
									[
									 	$new->listing_count,
									 	$new->n_offerte,
									 	$new->prezzo_minimo,
									 	$new->prezzo_massimo,
									 	$localita,
									 	$macrolocalita,
									 	date("Y")+Utility::fakeNewYear(),
									 	date("Y")+Utility::fakeNewYear()
									 	
									 ]
								);
								
						$_news["image"]  		= $new->spot_immagine;
						$_news["link"]   		= $new->uri;
						$_news["testo"] 		= Utility::replacePlaceholder(
									[
										"{HOTEL_COUNT}",
										"{OFFERTE_COUNT}",
										"{PREZZO_MIN}",
										"{PREZZO_MAX}",
										"{MACRO_LOCALITA}",
										"{LOCALITA}",
										"{CURRENT_YEAR}",
										"{CURRENT-YEAR}"
									],
									
									$new->spot_descrizione,
									 
									[

									 	$localita,
									 	$macrolocalita,
									 	date("Y")+Utility::fakeNewYear(),
									 	date("Y")+Utility::fakeNewYear()
									 	
									 ]
								);
						$news["items"][] 		= $_news;
		
					}
		
					endforeach;
				endif;

			Utility::putCache($key, $news); //Utility::putCache($key, $homepage);

		}

		return $news;

	}		
	
	/**
	 * Raccoglie tutti i links da mettere nelle offerte in homepage
	 *
	 * @access private
	 * @param string $locale (default: "it")
	 * @return array $homepage
	 */

	private static function _getOffers($locale ="it")
	{

		$offers = array();
		$offers["items"] = array();

		/**
		 * Trovo le news della homepage
		 */

		$key = "offers_home_" . $locale;

		if (!$offers = Utility::activeCache($key, "Cache Offerte Homepage")) {

			$offersDB = DB::table('tblSpotPagine')
			->select(
				'tblCmsPagine.id',
				'tblCmsPagine.uri',
				'tblCmsPagine.listing_count',
				'tblCmsPagine.n_offerte',
				'tblCmsPagine.prezzo_minimo',
				'tblCmsPagine.prezzo_massimo',
				'tblCmsPagine.listing_localita_id',
				'tblCmsPagine.listing_macrolocalita_id',
				'tblCmsPagine.prezzo_massimo',
				'tblSpotPagine.spot_h1',
				'tblSpotPagine.spot_h2',
				'tblSpotPagine.spot_descrizione',
				'tblSpotPagine.spot_immagine',
				'tblSpotPagine.spot_visibile_dal',
				'tblSpotPagine.spot_visibile_al',
				'tblSpotPagine.spot_colore',
				'tblSpotPagine.spot_visibile_ricorsivo'
			)
			->join('tblCmsPagine', function( $join ) use ($locale)
				{

					$join->on('tblCmsPagine.id', '=', 'tblSpotPagine.id_pagina')
					->where('tblCmsPagine.lang_id', '=', $locale)
					->where('tblCmsPagine.attiva', '=', 1);

				})
			->where('tblSpotPagine.spot_attivo', '=', 2)
			->orderBy('tblSpotPagine.spot_ordine', "ASC")
			->get();

			/**
			 * Metto a posto gli oggetti in una array controllando che non siano scadute
			 */
			 			 
			if ($offersDB):
				foreach ($offersDB as $offer):
					
					$localita = $offer->listing_localita_id !=0 ? Localita::find($offer->listing_localita_id)->nome : "";
					$macrolocalita = $offer->listing_macrolocalita_id != 0 ? Macrolocalita::find($offer->listing_macrolocalita_id)->nome : "";



					/**
					 * Controllo la visibilita dell'evento
					 */
					$controlloDate = Utility::isValidMenu($offer->spot_visibile_dal,  $offer->spot_visibile_al, null, $offer->spot_visibile_ricorsivo );
				


					if ($controlloDate) {

						$_offer = array();
						$_offer["id_page"] = $offer->id;
						$_offer["sottotitolo"] = Utility::replacePlaceholder(
								[
									"{HOTEL_COUNT}",
									"{OFFERTE_COUNT}",
									"{PREZZO_MIN}",
									"{PREZZO_MAX}",
									"{MACRO_LOCALITA}",
									"{LOCALITA}",
									"{CURRENT_YEAR}",
									"{CURRENT-YEAR}"
								],
								
								$offer->spot_h2,
								 
								[
								 	$offer->listing_count,
								 	$offer->n_offerte,
								 	$offer->prezzo_minimo,
								 	$offer->prezzo_massimo,
								 	$localita,
								 	$macrolocalita,
								 	date("Y")+Utility::fakeNewYear(),
								 	date("Y")+Utility::fakeNewYear()
								 	
								 ]
							);
						$_offer["titolo"] = Utility::replacePlaceholder(
								[
									"{HOTEL_COUNT}",
									"{OFFERTE_COUNT}",
									"{PREZZO_MIN}",
									"{PREZZO_MAX}",
									"{MACRO_LOCALITA}",
									"{LOCALITA}",
									"{CURRENT_YEAR}",
									"{CURRENT-YEAR}"
								],
								
								$offer->spot_h1,
								 
								[
								 	$offer->listing_count,
								 	$offer->n_offerte,
								 	$offer->prezzo_minimo,
								 	$offer->prezzo_massimo,
								 	$localita,
								 	$macrolocalita,
								 	date("Y")+Utility::fakeNewYear(),
								 	date("Y")+Utility::fakeNewYear()
								 	
								 ]
							);
						$_offer["image"]  = $offer->spot_immagine;
						$_offer["link"]   = $offer->uri;
						$_offer["testo"]  = Utility::replacePlaceholder(
								[
									"{HOTEL_COUNT}",
									"{OFFERTE_COUNT}",
									"{PREZZO_MIN}",
									"{PREZZO_MAX}",
									"{MACRO_LOCALITA}",
									"{LOCALITA}",
									"{CURRENT_YEAR}",
									"{CURRENT-YEAR}",
								],
								
								$offer->spot_descrizione,
								 
								[
								 	$offer->listing_count,
								 	$offer->n_offerte,
								 	$offer->prezzo_minimo,
								 	$offer->prezzo_massimo,
								 	$localita,
								 	$macrolocalita,
								 	date("Y")+Utility::fakeNewYear(),
								 	date("Y")+Utility::fakeNewYear()
								 	
								 ]
							);
							
						$_offer["colore"]  = $offer->spot_colore;
						$_offer["listing_count"]  = $offer->listing_count;
						$offers["items"][] = $_offer;
		
					}
	
				endforeach;
			endif;

			Utility::putCache($key, $offers); //Utility::putCache($key, $homepage);

		}

		return $offers;

	}

	/**
	 * Sceglie la homepage da mostrare in base agli eventi.
	 *
	 * @access private
	 * @param string $locale (default: "it")
	 * @return array $homepage
	 */

	private static function _getEvidence($locale ="it")
	{
		
		$homepage = array();
		$homepage["template"] = "megaspot";
		$homepage["items"] = array();
		$trovato = false;
		
		/**
		 * Trovo gli spot della homepage
		 */

		$key = "evidenze_home_" . $locale;

		if (!$home = Utility::activeCache($key, "Cache Evidenze Homepage")) {
			
			$date = DB::table('tblSpotPagine')
			->select(
				'tblCmsPagine.id',
				'tblCmsPagine.uri',
				'tblSpotPagine.spot_h1',
				'tblSpotPagine.spot_h2',
				'tblSpotPagine.spot_descrizione',
				'tblSpotPagine.spot_immagine',
				'tblSpotPagine.spot_visibile_dal',
				'tblSpotPagine.spot_visibile_al',
				'tblSpotPagine.spot_visibile_ricorsivo'
			)
			->join('tblCmsPagine', function( $join ) use ($locale)
				{
					//$locale = Self::getLocale();
					$join->on('tblCmsPagine.id', '=', 'tblSpotPagine.id_pagina')
					->where('tblCmsPagine.lang_id', '=', $locale)
					->where('tblCmsPagine.attiva', '=', 1);

				})
			->where('tblSpotPagine.spot_attivo', '=', 1)
			->orderBy('tblSpotPagine.spot_visibile_al', "ASC")
			->get();
			
			/**
			 * Metto a posto gli oggetti in una array controllando che non siano scadute
			 */

			foreach ($date as $data):
				
				/**
				 * Controllo la visibilita dell'evento
				 */
				
				$controlloDate = Utility::isValidMenu($data->spot_visibile_dal,  $data->spot_visibile_al, null, $data->spot_visibile_ricorsivo );
				
				if ($controlloDate) {
						
					$_homepage = array();
					$_homepage["titolo"] = $data->spot_h1;
					$_homepage["id_page"] = $data->id;
					$_homepage["sottotitolo"] = str_replace("{CURRENT_YEAR}", date("Y")+Utility::fakeNewYear(), $data->spot_h2);
					$_homepage["image"]  = $data->spot_immagine;
					$_homepage["link"]   = $data->uri;
					$_homepage["testo"]  = $data->spot_descrizione;
					
					$valori = CmsPagina::where("uri", $_homepage["link"])->first();	
					$arrayChiavi = array("{HOTEL_COUNT}", "{OFFERTE_COUNT}", "{PREZZO_MIN}", "{PREZZO_MAX}", "{CURRENT_YEAR}", "{CURRENT-YEAR}");
					$arrayValori = array($valori["listing_count"], $valori["n_offerte"], $valori["prezzo_minimo"], $valori["prezzo_massimo"], date("Y")+Utility::fakeNewYear(), date("Y")+Utility::fakeNewYear());
					
					$_homepage["titolo"] = Utility::replacePlaceholder($arrayChiavi, $_homepage["titolo"] , $arrayValori);
					$_homepage["sottotitolo"] = Utility::replacePlaceholder($arrayChiavi, $_homepage["sottotitolo"] , $arrayValori);
					$_homepage["testo"]  = Utility::replacePlaceholder($arrayChiavi, $_homepage["testo"] , $arrayValori);
					
					$homepage["items"][] = $_homepage;
			
				}
		
				$homepage["template"] = "megaspot";

			endforeach;
	
			Utility::putCache($key, $homepage); //Utility::putCache($key, $homepage);
			
		} else
			
			$homepage = $home;
				
		return $homepage;

	}

	
		
	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( VIEWS )
	 * ------------------------------------------------------------------------------------ */


	/**
	 * Homepage
	 *
	 * @access public
	 * @return View
	 */

	public function index()
	{
		
		$locale = $this->getLocale();
		$valoriHomepage = Self::getValoriHomepage($locale);
		$menu_localita = Utility::getMenuLocalita($locale);
		$template_homepage = Self::_getEvidence($locale);
		$offers_homepage = Self::_getOffers($locale);
		$news_homepage = Self::_getNews($locale);

		//? Ogni Macrolocalita deveo contenere queste nuove info
		// ? n. hotel
		// ? n. hotel bonus vacanze
		// ? n. hotel misure prevenzione covid (AL MOMENTO NO SONO TROPPO POCHI 355 !!)
		// ? n. hotel cancellazione flessibile (1 2 3 4 7 9)
		// ? n. offerte

		$macro = Macrolocalita::with(
															[
															'localita' => function($q) {
																	//$q->withCount(['hotel_bonus_vacanze', 'clientiAttivi']);
																	$q->select('id','macrolocalita_id');
																	$q->withCount(['hotel_bonus_vacanze']);
															},
															'localita.clientiAttivi' => function ($q) {
																$q->select('id', 'localita_id');
																$q->withCount(['caparreAttiveFlessibili', 'serviziCovid']);
															}]
														)
														->real()
														->get();
		$new_values = [];

		$tot_n_hotel_bonus_vacanze_macro = 0;
		$tot_n_hotel_caparre_flessibili = 0;
		$tot_n_servizi_covid = 0;

		foreach ($macro as $m) {
			
			$n_hotel_bonus_vacanze_macro = 0;
			$n_hotel = 0;
			$n_hotel_caparre_flessibili = 0;
			$n_servizi_covid = 0;
			foreach ($m->localita as $l) {
				//$n_hotel += $l->clienti_attivi_count;
				$n_hotel_bonus_vacanze_macro += $l->hotel_bonus_vacanze_count;
				foreach ($l->clientiAttivi as $h) {
					if($h->caparre_attive_flessibili_count){
						$n_hotel_caparre_flessibili ++ ;
					}
					$n_servizi_covid += $h->servizi_covid_count;
				}
			}

			//$new_values[$m->id]['n_hotel'] = $n_hotel;
			$new_values[$m->id]['n_hotel_bonus_vacanze_macro'] = $n_hotel_bonus_vacanze_macro;
			$new_values[$m->id]['n_hotel_caparre_flessibili'] = $n_hotel_caparre_flessibili;
			$new_values[$m->id]['n_servizi_covid'] = $n_servizi_covid;

			if($m->id != Utility::getIdMacroPesaro()) {
				$tot_n_hotel_bonus_vacanze_macro += $n_hotel_bonus_vacanze_macro;
				$tot_n_hotel_caparre_flessibili += $n_hotel_caparre_flessibili;
				$tot_n_servizi_covid += $n_servizi_covid;
			}
		}
		
		$new_values[Utility::getMacroRR()]['n_hotel_bonus_vacanze_macro'] += $tot_n_hotel_bonus_vacanze_macro;
		$new_values[Utility::getMacroRR()]['n_hotel_caparre_flessibili'] += $tot_n_hotel_caparre_flessibili;
		$new_values[Utility::getMacroRR()]['n_servizi_covid'] += $tot_n_servizi_covid;

		//dd($new_values);

		///////////////////////////////////////////////////////////////////////////////
		// ATTENZIONE 22/02/19 Lucio vuole sostituire i valori dei prezzi minimi con //
		// dei valori statici //
		////////////////////////

		$static_prices = [
			"Rimini" => "14.00",
			"Riccione" => "18.00",
			"Cesenatico" => "16.00",
			"Bellaria" => "21.00",
			"Cervia" => "20.00",
			"Igea marina" => "19.00",
			"Cattolica" => "20.00",
			"Gabicce" => "25.00",
			"Lidi Ravennati" => "20.00",
			"Misano Adriatico" => "20.00",
			"Milano Marittima" => "25.00",
			"Cattolica" => "20.00",
			"Pesaro" => "31.00",
			"Riviera Romagnola" => "14.00"
		];

		/**
		 * ATTENZIONE 22/02/19 Lucio vuole sostituire i valori dei prezzi minimi con dei valori statici
		 */

		$new_value_arr = [];
		foreach ($valoriHomepage['macro'] as $value_arr) 
		{
			foreach ($static_prices as $loc => $price) 
				if($value_arr["nome"] == $loc)
					$value_arr["prezzo_min"] = $price;
			$new_value_arr[] = $value_arr;
		}

		$prefill = CookieIA::getCookiePrefill();
		$valoriHomepage['macro'] = $new_value_arr;
		$cms_pagina = new \App\CmsPagina();
		
		return View::make(
			'cms_homepage.home',
			compact(
				'cms_pagina',
				'locale',
				'template_homepage',
				'offers_homepage',
				'news_homepage',
				'valoriHomepage',
				'prefill',
				'new_values'
			)
		);

	}
	
	/**
	 * Trova i valori per la 404
	 * 
	 * @access public
	 * @static
	 * @param String $locale
	 * @return Array
	 */
	 
	public static function _404($locale)
	{
		
		$valoriHomepage = Self::getValoriHomepage($locale);
		$menu_localita = Utility::getMenuLocalita($locale);

		$template_homepage = Self::_getEvidence($locale);
		$offers_homepage = Self::_getOffers($locale);
		$news_homepage = Self::_getNews($locale);
		
		return array($locale, $valoriHomepage, $template_homepage, $offers_homepage, $news_homepage, $menu_localita);
		
	}

	

    /**
     * Cancella la cache.
     * 
     * @access public
     * @static
     */
     
    public static function cache() 
    {
	   
	   Cache::flush();
	   return redirect('/', 301); 

    }


	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI
	 * ------------------------------------------------------------------------------------ */



	/**
	 * Trova i valori della hoemapge
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function getValoriHomepage($locale)
	{
		
		$key = "n_hotel_hp_$locale";


		$hotels = Hotel::attivo()->select('id', 'green_booking', 'eco_sostenibile')->get();


		$n_hotel_tot = $hotels->count();

		$n_hotel_gb = $hotels->where("green_booking", 1)->count();

		$n_hotel_es = $hotels->where("eco_sostenibile", 1)->count();

		// if (!$n_hotel_tot = Utility::activeCache($key, "Cache Num. Hotel Homepage ")) {

		// 	$n_hotel_tot = Hotel::attivo()->count();
		// 	Utility::putCache($key, $n_hotel_tot);

		// } 
		
		// $key = "n_hotel_green_booking_$locale";

		// if (!$n_hotel_gb = Utility::activeCache($key, "Cache Num. Hotel Homepage GB")) {
			
		// 	$n_hotel_gb = Hotel::attivo()->where("green_booking", 1)->count();
		// 	Utility::putCache($key, $n_hotel_gb);

		// }
		
		// $key = "n_hotel_eco_sostenibili_$locale";

		// if (!$n_hotel_es = Utility::activeCache($key, "Cache Num. Hotel Homepage ES")) {
			
		// 	$n_hotel_es = Hotel::attivo()->where("eco_sostenibile", 1)->count();
		// 	Utility::putCache($key, $n_hotel_es);

		// }
		
		$key = "macro_hp_$locale";
		
		if (!$macro_localita = Utility::activeCache($key, "Cache Macro Homepage")) {
			
			$macro = Macrolocalita::with([
				'conNumeri' => function($query) use ($locale) {

					$query->attiva()
						  ->where("menu_localita_id", 0)
						  ->lingua($locale);

				},
			])
			->real()
			->orderBy('id', 'asc')
			->get();

			//dump($macro);

			/**
			 * Costruzione Homepage
			 */
			
			$im = []; 
			$rr_prezzo_min = 0;
			$cambio_layout = 0;
			$macro_localita = [];
	
			foreach ($macro as $m):

				$macro_localita_items['id'] = $m->id;
				
				$n_hotel = 0;
				$prezzo_min = 0;
				$n_offerte  = 0;
				$copia = 0;

				if (isset($m->conNumeri[0])) {

					$n_hotel = $m->conNumeri[0]->listing_count;
					$prezzo_min = $m->conNumeri[0]->prezzo_minimo;
					$n_offerte = $m->conNumeri[0]->n_offerte;

				} 
							
				$rr_prezzo_min = ($prezzo_min < $rr_prezzo_min || $rr_prezzo_min == 0) ? $prezzo_min : $rr_prezzo_min;
				
				$macro_localita_items["link"] = Utility::getUrlWithLang($locale,"/". $m->linked_file);
				$macro_localita_items["image"] = $m->image;
				$macro_localita_items["nome"] = $m->nome;
				$macro_localita_items["n_hotels"] = $n_hotel;
				$macro_localita_items["n_offerte"] = $n_offerte;
				$macro_localita_items["prezzo_min"] = $prezzo_min;
				
				array_push($macro_localita,$macro_localita_items);

				/**
				 * Igea Marina e la copia di Bellaria 
				 * Nella struttura informativa igea marina e una locolita di Bellaria
				 * Ma siccome su internet haun dignita sua allora in home la mettiamo come MAcro
				 */

				if ($m->nome == "Bellaria") {

					$macro_localita_items["link"] = Utility::getUrlWithLang($locale,"/hotel/igea-marina.php");			
					$macro_localita_items["nome"] = "Igea marina";
					array_push($macro_localita,$macro_localita_items);
				
				} 
				
			endforeach;

			Utility::putCache($key, $macro_localita);
			
		} 
				
		/**
		 * Mixo le localita
		 */

		if (is_array($macro_localita)) {

			$pesaro = array_pop($macro_localita);
			$riviera = array_pop($macro_localita);				
			$rimini = array_shift($macro_localita);
			$riccione = array_shift($macro_localita);
			array_push($macro_localita,$pesaro );
			shuffle($macro_localita);		
			array_unshift($macro_localita,$rimini,$riccione );
			array_push($macro_localita,$riviera );

		}


		$key = "n_off_hp_$locale";

		if (!$n_off = Utility::activeCache($key, "Cache Num. Offerte Homepage")) {
						
			$off = Offerta::whereHas('cliente', function($query)
				{$query->attivo();})->attiva()->tipo('offerta')->groupBy('hotel_id')->get();
			$n_off = $off->count();
			
			Utility::putCache($key, $n_off);
			
		} 
		

		$key = "n_last_hp_$locale";

		if (!$n_last = Utility::activeCache($key, "Cache Num. Last Homepage")) {
		
			$last = Offerta::whereHas('cliente', function($query)
				{$query->attivo();})->attiva()->tipo('lastminute')->groupBy('hotel_id')->get();
			$n_last = $last->count();
			Utility::putCache($key, $n_last);
			
		}

		

		// $key = "n_bg_$locale";

		// if (!$n_bg = Utility::activeCache($key, "Cache Num. Bambini Gratis Homepage")) {

		// 	$bg = BambinoGratis::whereHas('cliente', function($query)
		// 		{$query->attivo();})->attivo()->groupBy('hotel_id')->get();
		// 	$n_bg = $bg->count();

		// 	Utility::putCache($key, $n_bg);

		// } 

		$n_bg = 312;

		

		//$key = "n_clienti_piscina_$locale";

		// if (!$n_clienti_piscina = Utility::activeCache($key, "Cache Num. Hotel Piscina Homepage")) {

		// 	$g = GruppoServizi::with('servizi.clienti_attivi')->find(8);

		// 	$clienti_piscina = [];
		// 	foreach ($g->servizi as $servizi) {
		// 		// i dei clienti che hanno questo servizio
		// 		$clienti_piscina = array_merge($clienti_piscina, $servizi->clienti_attivi->pluck('id')->toArray());
		// 	}

		// 	$clienti_piscina = array_unique($clienti_piscina);

		// 	// dd($clienti_piscina);

		// 	$n_clienti_piscina = count($clienti_piscina);
		// 	Utility::putCache($key, $n_clienti_piscina);

		//   	}


		$n_clienti_piscina = 395;

		// $key = "n_clienti_bene_$locale";

		// if (!$n_clienti_bene = Utility::activeCache($key, "Cache Num. Hotel Benessere Homepage")) {

		// 	$g = GruppoServizi::with('servizi.clienti_attivi')->find(7);

		// 	$clienti_bene = [];
		// 	foreach ($g->servizi as $servizi) {
		// 		// id dei clienti che hanno questo servizio
		// 		$clienti_bene = array_merge($clienti_bene, $servizi->clienti_attivi->pluck('id')->toArray());
		// 	}

		// 	$clienti_bene = array_unique($clienti_bene);

		// 	$n_clienti_bene = count($clienti_bene);
		// 	Utility::putCache($key, $n_clienti_bene);

		// } 

		$n_clienti_bene = 99;


		// $key = "n_clienti_pet_$locale";

		// if (!$n_clienti_pet = Utility::activeCache($key, "Cache Num. Hotel Pet Homepage")) {
			
		// 	$g = GruppoServizi::with('servizi.clienti_attivi')->find(4);

		// 	$clienti_pet = [];
		// 	foreach ($g->servizi as $servizi) {
		// 		// id dei clienti che hanno questo servizio
		// 		$clienti_pet = array_merge($clienti_pet, $servizi->clienti_attivi->pluck('id')->toArray());
		// 	}

		// 	$clienti_pet = array_unique($clienti_pet);
		// 	$n_clienti_pet = count($clienti_pet);
		// 	Utility::putCache($key, $n_clienti_pet);
			
		// } 
		
		//? ATTENZIONE 13/04/2021 - Query molto dispendiosa, mettiamo valore fisso per verificare online
		$n_clienti_pet = 1106;

		return [

			'n_hotel_tot' => $n_hotel_tot,
			'n_hotel_gb' => $n_hotel_gb,
			'n_hotel_es' => $n_hotel_es,
			'macro' => $macro_localita,
			'n_off' => $n_off,
			'n_last' => $n_last,
			'n_bg' => $n_bg,
			'n_clienti_piscina' => $n_clienti_piscina,
			'n_clienti_bene' => $n_clienti_bene,
			'n_clienti_pet' => $n_clienti_pet

		];

	}


}

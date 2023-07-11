<?php

/**
 * HotelController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers;

use App;
use Input;
use Config;
use Browser;
use Request;
use App\Hotel;
use App\Utility;
use App\CookieIA;
use App\Localita;
use App\CmsPagina;
use Carbon\Carbon;
use App\MailScheda;
use App\MailMultipla;
use App\HotelPreferito;
use App\StatsHotelCall;
use App\ImmagineGallery;
use App\AcceptancePrivacy;
use App\StatsHotelWhatsapp;
use App\StatsHotelOutboundLink;
use App\Events\HotelViewHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

use App\Exceptions\HotelNotExistsException;
use App\Http\Controllers\CmsPagineController;
use App\Http\Controllers\MailSchedaController;

class HotelController extends Controller
{



	/* ------------------------------------------------------------------------------------
	 * METODI PRIVATI
	 * ------------------------------------------------------------------------------------ */



	/**
	 * Trova gli hotel simili in un raggio predefinito
	 *
	 * @access protected
	 * @param CmsPagina $cms_pagina
	 * @param int $filters (default: 0)
	 * @param int $checkForUpselling (default: 0)
	 * @param int $limit (default: 5)
	 * @param string $locale (default: 'it')
	 * @return void
	 * @author  Giovanni
	 */

	private static function _getListingSimili(CmsPagina $cms_pagina, $filters = 0, $checkForUpselling = 0, $limit = 5, $locale = 'it')
	{

		$clienti =  collect([]);

		$hotel =
			Hotel::withListingSimiliEagerLoading($locale)
			->attivo()
			->exclude($filters)
			->listingMacrolocalita($cms_pagina->listing_macrolocalita_id)
			->listingLocalita($cms_pagina->listing_localita_id)
			->listingTipologie($cms_pagina->listing_tipologie)
			->listingCategorie($cms_pagina->listing_categorie)
			->listingTrattamentoNew($cms_pagina->listing_trattamento)
			->listingWhatsapp($cms_pagina->listing_whatsapp)
			->listingAnnuali($cms_pagina->listing_annuali)
			->listingParolaChiave($cms_pagina->listing_parolaChiave_id, $locale)
			->listingGruppoServizi($cms_pagina->listing_gruppo_servizi_id,$cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id)
			->listingBambiniGratis($cms_pagina->listing_bambini_gratis)
			->raggio($filters)
			->upselling($checkForUpselling)
			->get();

		$clienti = $hotel->shuffle();

		//$clienti = $clienti->slice(1,$limit);
		$clienti = $clienti->slice(0,$limit-1);

		return $clienti;

	}


	/**
	 * questo metodo cerca gli hotel simili a quello dato (id passato in $filters)
	 * confrontando categoria e località di quello passato senza fare riferimento alla riga della tblCmsPagine che ha
	 * l'uri come il referer (perché sono nel caso in cui il refer non esista)
	 *
	 * @access public
	 * @param int $filters (default: 0)
	 * @param int $limit (default: 5)
	 * @param string $locale (default: 'it')
	 * @return void
	 */

	private static function _getListingSimiliNoUrl($filters = 0, $limit = 6, $locale = 'it')
	{

		$id_hotel = $filters['id'];

		$original_hotel = Hotel::find($id_hotel);
		$hotel =
			Hotel::withListingSimiliEagerLoading($locale)
			->attivo()
			->exclude($filters)
			->listingLocalita($original_hotel->localita_id)
			->listingTipologie($original_hotel->tipologia_id)
			->listingCategorie($original_hotel->categoria_id)
			->raggio($filters)
			->get();

		$clienti = $hotel->shuffle();

		//$clienti = $clienti->slice(1,$limit);
		$clienti = $clienti->slice(0,$limit-1);

		return $clienti;

	}


	/**
	 * Trova gli hotel simili.
	 *
	 * @access public
	 * @param string $ref (default: '')
	 * @param mixed $filters (default: [])
	 * @param string $loc (default: 'it')
	 * @param int $checkForUpselling (default: 0)
	 * @param int $limit (default: 5)
	 * @return void
	 */

	private static function _indexSimili($ref = '', $filters = [], $loc = 'it', $checkForUpselling = 0, $limit = 6)
	{

		$uri = str_replace(url('/'), '', $ref);

		if(substr($uri, 0, 1) === '/')	
			{
			$uri = ltrim($uri, '/');
			}

		$referer_page = CmsPagina::where('uri', $uri)->attiva()->first();
		
		if (!$referer_page) {

			$clienti = Self::_getListingSimiliNoUrl($filters, $limit, $loc);

		} else {

			$locale = $referer_page->lang_id;
			App::setlocale($locale);
			$clienti = Self::_getListingSimili($referer_page, $filters, $checkForUpselling, $limit, $locale);

		}

		return $clienti;
	}


	/**
	 * Trovo gli hotel simili
	 *
	 * @access private
	 * @static
	 * @param mixed $id
	 * @param mixed $locale
	 * @param mixed $cliente
	 * @param string $ref (default: "")
	 * @return void
	 */

	private static function _hotelSimili ($id, $locale, $cliente, $ref = "", $limit = 6) 
	{

		$key = "hotel_simili_" . $id . "_"  . $locale;
		$hotel_simili_array = [];

		///////////////////////////////////////////////
		// ATTENZIONE TOLGO LA CACHE DA HOTEL SIMILI //
		// @luigi 20/12/17													 //
		///////////////////////////////////////////////
		//if ( !$hotel_simili_array = Utility::activeCache($key, "Cache Hotel simili") ) {

			/**
			 * ricavo gli hotel simili
			 */

			$cms = new CmsPagineController();
			$lat = $cliente->mappa_latitudine;
			$lon = $cliente->mappa_longitudine;

			if ($lat == 0) {
				$lat = $cliente->localita->latitudine;
				$lon = $cliente->localita->longitudine;
			}

			/**
			 * nel trovare gli hotel simili devo scartare quello corrente e trovare un intorno
			 * che dipende dalla lat e dalla long di quello corrente
			 */

			$filters = ['id' => $id, 'lat' => $lat, 'long' => $lon, 'raggio' => 0.8];



			if ($ref != "") {

				$hotel_simili_array[] = Self::_indexSimili($ref, $filters, $locale, 0, $limit);
				$hotel_simili_array[] = Utility::getCausaleSimili($ref);

			} else {

				$hotel_simili_array[] = Self::_getListingSimiliNoUrl($filters, $limit, $locale);
				$hotel_simili_array[] = Lang::get('labels.hotel_simili');

			}



			//Utility::putCache($key, $hotel_simili_array);

		//}



		return $hotel_simili_array;

	}

	/**
	 * Trova la galleria per la scheda.
	 *
	 * @access private
	 * @param mixed $id
	 * @return void
	 */

	private function _gallery($id)
	{

		$locale = $this->getLocale();
		$key = "hotel_" . $id . "_"  . $locale;

		/**
		 * la gallery
		 */

		if (!$hotel = Utility::activeCache($key, "Cache Gallery")) {

			$hotel = $this->_getHotel($key, $locale, $id);
			Utility::putCache($key, $hotel);

		}

		$immagini_gallery = [];

		$key = "gallery_items_" . $id ."_" . $locale;

		if (!$immagini_gallery = Utility::activeCache($key, "Cache Gallery Items")) {

			$gallery = $hotel->immaginiGallery->sortBy('position');

			foreach ($gallery as $img) {

				$featured = Utility::asset($img->getImg("360x320", true));
				$retina = Utility::asset($img->getImg("720x400", false));
				$large = Utility::asset($img->getImg("360x200", false));

				$caption = ($img->immaginiGallery_lingua->isEmpty()) ? '' : $img->immaginiGallery_lingua->first()->caption;
				$immagini_gallery[] = [$large, $retina, $caption, $featured];

			}

			Utility::putCache($key, $immagini_gallery);

		}

		return $immagini_gallery;

	}


	/**
	 * Briciole scheda
	 *
	 * @access private
	 * @param Hotel $cliente
	 * @param String $locale
	 * @return Array
	 */

	private function _briciole($cliente, $locale)
	{

		$briciole = array();
		$key = "briciole_" . $cliente->id . "_"  . $locale;
		$base_url = "/";

		if ($locale != "it")
			$base_url = "/" . Utility::getUrlLocaleFromAppLocale($locale);

		if (!$briciole = Utility::activeCache($key, "Cache Briciole")) {

			$id_loc = $cliente->localita_id;
			$id_macro = Localita::find($id_loc)->macrolocalita->id;

			$briciole['Home'] = url($base_url);

			if ($id_macro != Utility::getIdMacroPesaro()) { 
				
				$briciole['Hotel Riviera Romagnola'] = Utility::getUrlWithLang($locale, '/italia/hotel_riviera_romagnola.html');
			
			}

			/**
			 * pagina macro
			 */

			$pagina_macro = CmsPagina::where('template', 'localita')
				->where('lang_id', $locale)
				->where('menu_localita_id', 0)
				->where('menu_macrolocalita_id', $id_macro)
				->first();

			if (is_null($pagina_macro))
				return [];
			else
				$briciole[$pagina_macro->ancora] = url($pagina_macro->uri);

			/**
			 * Pagina micro
			 */

			$pagina_micro = CmsPagina::where('template', 'localita')
				->where('lang_id', $locale)
				->where('menu_localita_id', $id_loc)
				->first();

			if (!is_null($pagina_micro)) {

				/**
				 * pagina micro se esiste
				 */

				$briciole[$pagina_micro->ancora] = url($pagina_micro->uri);

				$pagina_listing_micro = CmsPagina::where('template', 'listing')
					->where('lang_id', $locale)
					->where('listing_localita_id', $id_loc)
					->where("listing_gruppo_servizi_id", 0)
					->whereRaw("( (listing_categorie != '' AND listing_categorie REGEXP '^-?[0-9]+$') OR (listing_tipologie != '' AND listing_tipologie REGEXP '^-?[0-9]+$') )")
					->where('listing_categorie', $cliente->categoria_id)
					->first();

					/**
					 * pagina listing micro
					 */

				if (!is_null($pagina_listing_micro))
					$briciole[$pagina_listing_micro->ancora] = url($pagina_listing_micro->uri);

			} else {

				/**
				 * ECCEZIONE PER CERVIA
				 */

				if ($id_loc == 25) {

					/**
					 * pagina listing macro
					 */

					$pagina_listing_macro = CmsPagina::where('template', 'listing')

						->where('lang_id', $locale)
						->where('listing_macrolocalita_id', $id_macro)
						->where('listing_localita_id', 0) // se non metto questo vincolo prende pinrella-di-cervia che è il first()
						->where("listing_gruppo_servizi_id", 0)
						->whereRaw("( (listing_categorie != '' AND listing_categorie REGEXP '^-?[0-9]+$') OR (listing_tipologie != '' AND listing_tipologie REGEXP '^-?[0-9]+$') )")
						->where('listing_categorie', $cliente->categoria_id)
						->first();

				}
				else {

					/**
					 * pagina listing macro
					 */

					$pagina_listing_macro = CmsPagina::where('template', 'listing')
						->where('lang_id', $locale)
						->where('listing_macrolocalita_id', $id_macro)
						->where("listing_gruppo_servizi_id", 0)
						->whereRaw("( (listing_categorie != '' AND listing_categorie REGEXP '^-?[0-9]+$') OR (listing_tipologie != '' AND listing_tipologie REGEXP '^-?[0-9]+$') )")
						->where('listing_categorie', $cliente->categoria_id)
						->first();

				}


				/**
				 * pagina listing micro
				 */

				if (!is_null($pagina_listing_macro))
					$briciole[$pagina_listing_macro->ancora] = url($pagina_listing_macro->uri);

			}

			Utility::putCache($key, $briciole);

		}

		return $briciole;

	}

	/**
	 * Hotel completo con tutte le sua dipendenze.
	 *
	 * @access private
	 * @param string $key
	 * @param string $locale
	 * @param mixed $ids_send_mail
	 * @return Hotel
	 */

	private function _getHotel($key, $locale, $ids_send_mail)
	{

		if (!$cliente = Utility::activeCache($key, "Cache Hotel")) {

			$cliente = Hotel::with([
				'localita.macrolocalita',
				// 'stelle',
				'puntiDiForza.puntiDiForza_lingua' => function($query) use ($locale)
				{
					$query->where('lang_id', '=', $locale);
				},
				'poi.poi_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'descrizioneHotel.descrizioneHotel_lingua' => function($query) use ($locale)
				{
					$query->where('lang_id', '=', $locale);
				},
				'offerte'  => function($query)
				{
					$query
					->attiva()
					->orderBy('valido_dal', 'asc');
				},
				'offerte.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'immaginiGallery.immaginiGallery_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'last'  => function($query)
				{
					$query
					->attiva()
					->orderBy('valido_dal', 'asc');
				},
				'last.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'bambiniGratisAttivi',
				'offerteBambiniGratisTop'  => function($query)
				{
					$query
					->attiva();
				},
				'offerteBambiniGratisTop.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'offertePrenotaPrima'  => function($query)
				{
					$query
					->attiva()
					->orderBy('prenota_entro', 'asc');
				},
				'offertePrenotaPrima.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'offerteTop'  => function($query)
				{
					$query
					->visibileInScheda()
					->attiva();
				},
				'offerteTop.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'listini' => function($query) use ($locale)
				{
					$query
					->attivo()
					->nonNullo();
				},
				'listiniMinMax' => function($query) use ($locale)
				{
					$query
					->attivo()
					->nonNullo();
				},
				'listiniCustom' => function($query) use ($locale)
				{
					$query
					->attivo()
					->orderBy('position', 'asc');
				},
				'listiniCustom.listiniCustomLingua' => function($query) use ($locale)
				{
					$query
					->daVisualizzare($locale);
				},

				'servizi.servizi_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'serviziGratuiti.servizi_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'serviziPrivatiGratuiti.servizi_privati_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'notaListino' => function($query)
				{
					$query
					->where('attivo' , '=', 1);
				},
				'notaListino.noteListino_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				}
			

				])
                ->withCount(['caparreAttive'])
			->where(function ($query)
				{
					$query->where('attivo', 1)
					->orWhere('attivo', -1);
				});

			if (is_array($ids_send_mail)) {

				$cliente = $cliente
				->whereIn('id', $ids_send_mail)
				->get();

			} else {
				$cliente = $cliente
				->find($ids_send_mail);
			}

			Utility::putCache($key, $cliente);

		}

		return $cliente;

	}

	/**
	 * Il testo del banner covid ( non si sa perchè non sia stato messo nelle traduzioni)
	 * @access private
	 * @param string $locale
	 * @return String
	 */

	private function _getTestoCovidBanner($locale = 'it')
	{

		$testo_covid_banner['it'] = "Alcuni servizi ed alcuni spazi, dentro e fuori la struttura, potrebbero non essere disponibili a causa delle limitazioni imposte dalle autorità competenti.";
		$testo_covid_banner['en'] = "Due to some limitations imposed by the local authorities, some facilities and services inside and outside the premises might not be available.";
		$testo_covid_banner['fr'] = "Certains services et certains espaces, à l'intérieur et à l'extérieur de la structure, peuvent ne pas être disponibles en raison des limitations imposées par les autorités compétentes.";
		$testo_covid_banner['de'] = "Aufgrund einiger von den örtlichen Behörden auferlegter Einschränkungen sind einige Einrichtungen und Dienstleistungen innerhalb und außerhalb der Räumlichkeiten möglicherweise nicht verfügbar.";
		return $testo_covid_banner[$locale];
		
	}

	/**
	 * Il testo del banner covid per il mobile ( non si sa perchè non sia stato messo nelle traduzioni)
	 * @access private
	 * @param string $locale
	 * @return String
	 */

	private function _getTestoCovidBannerMobile($locale = 'it')
	{

		$testo_covid_banner['it'] = "Alcuni servizi ed alcuni spazi, dentro e fuori la struttura, potrebbero non essere disponibili a causa delle limitazioni imposte dalle autorità competenti.";
		$testo_covid_banner['en'] = "Due to some limitations imposed by the local authorities, some facilities and services inside and outside the premises might not be available.";
		$testo_covid_banner['fr'] = "Certains services et certains espaces, à l'intérieur et à l'extérieur de la structure, peuvent ne pas être disponibles en raison des limitations imposées par les autorités compétentes.";
		$testo_covid_banner['de'] = "Aufgrund einiger von den örtlichen Behörden auferlegter Einschränkungen sind einige Einrichtungen und Dienstleistungen innerhalb und außerhalb der Räumlichkeiten möglicherweise nicht verfügbar.";
		return $testo_covid_banner[$locale];

	}



	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( VIEWS )
	 * ------------------------------------------------------------------------------------ */




	/**
	 * Confronta più hotel tra loro
	 *
	 * @access public
	 * @param Request $request
	 * @return View
	 */

	public function compare(Request $request)
	{


		$ids_send_mail = Request::get("ids_send_mail");
		$url_compare = \URL::current();
		$referer_compare = \URL::previous();

		$locale = $this->getLocale();

		/*
		Pagina senza parametri per canonical
		 */

		if (!isset($ids_send_mail))
			return View::make('hotel.compare', compact('locale'));

		$key = "hotel_compare_" . $ids_send_mail . "_"  . $locale;
		$title_referer = Request::get("title");
		$clienti = $this->_getHotel($key, $locale, explode(",", $ids_send_mail));

		$lt = false;
		$pp = false;
		$of = false;
		$bg = false;

		$t = 0;
		foreach($clienti as $cliente):
			
			
			
		   /**
			* Accorpamenti
		 	*/

			$accorp = [];

			if ($cliente->aperto_eventi_e_fiere)
				$accorp[] = Lang::get("hotel.aperto_eventi_e_fiere");

			if ($cliente->aperto_pasqua)
				$accorp[] = Lang::get("hotel.aperto_pasqua");

			if ($cliente->aperto_25_aprile)
				$accorp[] = Lang::get("hotel.aperto_25_aprile");

			if ($cliente->aperto_1_maggio)
				$accorp[] = Lang::get("hotel.aperto_1_maggio");

			if ($cliente->apertura_10_settembre)
				$accorp[] = Lang::get("hotel.apertura_10_settembre");

			if ($cliente->aperto_capodanno)
				$accorp[] = Lang::get("hotel.aperto_capodanno");

			$accorp = implode(", ", $accorp);
			if ($accorp)
				$clienti[$t]->aperture = '<br /><small class="label">' . Lang::get("labels.aperto_anche") . ':</small><br /> ' . $accorp;

			$noff 	= $cliente->offerteTop->count();
			$noff  += $cliente->offerte->count();
			$nopp	= $cliente->offertePrenotaPrima->count();
			$nlast 	= $cliente->last->count();
			$nbg 	= $cliente->bambiniGratisAttivi->count();
			$nbg 	= $cliente->offerteBambiniGratisTop->count() + $nbg;

			foreach($cliente->offerteTop as $ot) {

				if ($ot->tipo == "lastminute") {
					$nlast++;
					$noff--;
				}

				if ($ot->tipo == "prenotaprima") {
					$nopp++;
					$noff--;
				}
			}

			if ($noff > 0)
				$of = true;

			if ($nlast > 0)
				$lt = true;

			if ($nbg > 0)
				$bg = true;

			if ($nopp > 0)
				$pp = true;
				
			$tassaSoggiorno = App\TassaSoggiorno::getTassaLabel($cliente->id); 
			unset($tassaSoggiorno[0]); 
				
			$clienti[$t]->noff 	= $noff;
			$clienti[$t]->nlast = $nlast;
			$clienti[$t]->nopp 	= $nopp;
			$clienti[$t]->nbg 	= $nbg;
			$clienti[$t]->ts 	= implode(", ", $tassaSoggiorno);

			$t++;

		endforeach;

		if (is_null($clienti))
			return View::make('hotel.compare', compact('locale'));

		/**
		 * Proprietà confrontabili
		 */

		$proprieta = array(
			"immagine" =>     			"x", // Se metto una x non scrivo la voce
			"posizione" =>     			Lang::get("listing.pos"),
			"poi" =>      				Lang::get("labels.9punti_forza"),
			"annuale" =>     			Lang::get("hotel.apertura"),
			"tassaSoggiorno" =>			Lang::get("labels.tassa_soggiorno"),
			"lastminute" =>    			Lang::get("hotel.last"),
			"prenotaprima" =>     		Lang::get("hotel.offerte_prenota_prima_box"),
			"bambinigratis" =>   		Lang::get("hotel.offerte_bg_top_box"),
			"offerte" =>      			Lang::get("hotel.offerte_generiche_box"),
			"piscina" =>     			Lang::get("listing.piscina"),
			"spa" =>      				Lang::get("listing.centro_b"),
			"link" =>      				"x"
		);

		$coordinate = Utility::getGenericGeoCoords();
		$google_maps = ["coords" => $coordinate, "hotels" => $clienti];

		return View::make('hotel.compare',
			compact(
				'clienti',
				'proprieta',
				'locale',
				'ids_send_mail',
				'referer_compare',
				'url_compare',
				'title_referer',
				'google_maps',

				'of',
				'lt',
				'pp',
				'bg'
			)
		);

	}

	/**
	 * Hotel disabilitato
	 *
	 * @access public
	 * @param int $id
	 * @return View
	 */

	public function index_disabilitato($id)
	{
		$locale = $this->getLocale();
		$key = "hotel_disabilitato_" . $id . "_" . $locale;

		if (!$cliente = Utility::activeCache($key, "Cache Hotel Disabilitato")) {

			$cliente = Hotel::with([
				'localita.macrolocalita',
				'stelle',
				'poi',
				])
			->find($id);

			Utility::putCache($key, $cliente);

		}

		$hotel_simili_array = Self::_hotelSimili($id, $locale, $cliente, "", 9);

		$hotel_simili = $hotel_simili_array[0];
		$causale_hotel_simili = $hotel_simili_array[1];

		$menu_localita = Utility::getMenuLocalita($locale);
		$title = $cliente->getTitle($locale);
		$description = $cliente->getDescription($locale);

		return View::make(

			'hotel.hotel_disabilitato',
			compact(

				'cliente',
				'title',
				'description',
				'locale',
				'menu_localita',
				'title',
				'description',
				'hotel_simili',
				'causale_hotel_simili'

			)
		);

	}

	/**
	 * Hotel erraro ( inesistente ).
	 *
	 * @access public
	 * @return 404
	 */

	public function index_errato()
	{
		abort('404');
	}

	/**
	 * Metodo deprecato
	 * @access public
	 * @return 404
	 */
	
	public function coupon_mobile() 
	{
		abort('404');
	}

	/**
	 * Visualizza la scheda desktop/tablet.
	 *
	 * @access public
	 * @param int $id
	 * @param DB $query (default: null)
	 * @return View
	 */

	public function index ($id, $query = null)
	{
	    Request::session()->put('last_hotel_page', URL::full());
	  
	    $locale = $this->getLocale();
		$messaggioScrittura = false; // Usato per sapere se ho scritto di recente
		$key = "hotel_" . $id . "_"  . $locale;

		$cliente = $this->_getHotel($key, $locale, $id);
		$img_mappa_localita = "micro_".$cliente->localita->id.".png";
		$language_uri = [];
		$language_uri["it"] = "hotel.php?id=" . $id;
		$language_uri["en"] = "ing/hotel.php?id=" . $id;
		$language_uri["de"] = "ted/hotel.php?id=" . $id;
		$language_uri["fr"] = "fr/hotel.php?id=" . $id;

		if (is_null($cliente))
			throw new HotelNotExistsException;

		else {

			$ua = Request::server('HTTP_USER_AGENT');
			$referer = Request::server('HTTP_REFERER');
			$ip = Utility::get_client_ip();

			if ($ua == NULL || $ua == "")
				$ua = "no_user_agent ( both? )";

			/**
			 * contaclick sulla scheda hotel
			 * incremento del campo numero_click della tabella hotel per ordinamento
			 */

			Event::dispatch(new HotelViewHandler(compact('cliente', 'locale', 'ua', 'referer', 'ip')));

			/**
			 * Hotel Simili
			 */

			$hotel_simili_array = Self::_hotelSimili($id, $locale, $cliente, $referer);

			/**
			 * Prendo i le foto particolari
			 */

			$key = "hotel_extra_" . $id . "_"  . $locale;

			if (!$hotel_extra = Utility::activeCache($key, "Cache Hotel (complementi)")) {

				$hotel_extra = array();
				$hotel_extra[0] = ImmagineGallery::where("gruppo_id",Config::get("services.listing_gruppo_piscina"))->where("hotel_id", $cliente->id)->get();
				$hotel_extra[1] = ImmagineGallery::where("gruppo_id",Config::get("services.listing_gruppo_benessere"))->where("hotel_id", $cliente->id)->get();
				Utility::putCache($key, $hotel_extra);

			}

			/**
			 * Assegnazioni
			 */

			$title = $cliente->getTitle($locale);
			$description = $cliente->getDescription($locale);
			$briciole = $this->_briciole($cliente, $locale);

			$id_localita = $cliente->localita_id;
			$id_macrolocalita = Localita::find($id_localita)->macrolocalita_id;
			$menu_localita = Utility::getMenuLocalita($locale, $id_macrolocalita, $id_localita);

			$fotoPiscina = $hotel_extra[0];
			$fotoSpa = $hotel_extra[1];
			$hotel_simili = $hotel_simili_array[0];
			$causale_hotel_simili = $hotel_simili_array[1];
			$actual_link = Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id , true);

			$prefill = CookieIA::getCookiePrefill();
			$numero_camere = array_key_exists('rooms', $prefill) ? count($prefill['rooms']) : 0;
		
			if ($numero_camere == 0) {

				$numero_camere = 1;
				$prefill["rooms"] = CookieIA::getCookieRoomPrefill();

			}

			$ids_send_mail = $cliente->id;

			/**
			 * Controllo che non sia stato contattato di recente
			 */

			if (array_key_exists('codice_cookie', $prefill))
				$recente = Utility::scrittoDiRecente($prefill['email'], $cliente->id);
			
			/**
			 * Accettazione della privacy
			 */
			
			$privacy = AcceptancePrivacy::getCheckForm($prefill["email"]);
					

			/**
			 * Testo del banner covid in lingua
			 */

			$testo_covid_banner = Lang::get('hotel.testo_covid_banner');

			/**
			 * END Testo del banner covid in lingua
			 */


			return View::make('hotel.hotel',
					compact(
						'actual_link',
                        'user',
						'cliente',
						'language_uri',
						'locale',
						'hotel_simili',
						'causale_hotel_simili',
						'prefill',
						'menu_localita',
						'title',
						'description',
						'briciole',
						'referer',
						'query',
						'numero_camere',
						'messaggioScrittura',
						'fotoPiscina',
						'fotoSpa',
						'recente',
						'ids_send_mail',
						'privacy',
						'img_mappa_localita',
						'testo_covid_banner'
					)
				);

		}

	}




	public function index_slug_url(Request $request, $slug_url) {

		dd($slug_url);

	}



	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( VIEWS MOBILE )
	 * ------------------------------------------------------------------------------------ */



	/**
	 * Visualizza la scheda mobile.
	 *
	 * @access public
	 * @param int $id
	 * @param DB $query (default: null)
	 * @return View
	 */

	public function index_mobile($id, $query = null)
	{

		$locale = $this->getLocale();
		$messaggioScrittura = false;
		$key = "hotel_" . $id . "_" . $locale;

		$cliente = $this->_getHotel($key, $locale, $id);

		$language_uri = [];
		$language_uri["it"] = "hotel.php?id=" . $id;
		$language_uri["en"] = "ing/hotel.php?id=" . $id;
		$language_uri["de"] = "ted/hotel.php?id=" . $id;
		$language_uri["fr"] = "fr/hotel.php?id=" . $id;

		if (is_null($cliente)) {

			throw new HotelNotExistsException;

		} else {

			$ua = Request::server('HTTP_USER_AGENT');
			$referer =  Request::server('HTTP_REFERER');
			$ip = Utility::get_client_ip();

			if ($ua == NULL || $ua == "")
				$ua = "no_user_agent ( both? )";

			/**
			 * contaclick sulla scheda hotel
			 * incremento del campo numero_click della tabella hotel per ordinamento
			 */

			Event::dispatch(new HotelViewHandler(compact('cliente', 'locale', 'ua', 'referer', 'ip')));

			/**
			 * Configurazione dati scheda
			 */

			$title = $cliente->getTitle($locale);
			$description = $cliente->getDescription($locale);
			$briciole = $this->_briciole($cliente, $locale);
			$menu_localita = Utility::getMenuLocalita($locale);

			$gallery_mobile = Self::_gallery($cliente->id);
	
			$fotoSpa = ImmagineGallery::where("gruppo_id",Config::get("services.listing_gruppo_benessere"))->where("hotel_id", $cliente->id)->get();
			$fotoPiscina = ImmagineGallery::where("gruppo_id",Config::get("services.listing_gruppo_piscina"))->where("hotel_id", $cliente->id)->get();

			$actual_link = Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id , true);
			$selezione_localita = $cliente->localita->nome;
			
			
				/**
			 * Testo del banner covid in lingua
			 */
			//$testo_covid_banner = $this->_getTestoCovidBannerMobile($locale);

			$testo_covid_banner = Lang::get('hotel.testo_covid_banner_mobile');


			/**
			 * END Testo del banner covid in lingua
			 */
			
			return View::make(
					'hotel.hotel',
						compact(
							'actual_link',
							'referer',
							'language_uri',
							'cliente',
							'locale',
							'selezione_localita',
							'menu_localita',
							'title',
							'description',
							'briciole',
							'gallery_mobile',
							'messaggioScrittura',
							'fotoPiscina',
							'fotoSpa',
							'testo_covid_banner'
						)
					);

		}



	}


	/**
	 * Visualizza il listino, minimi e massimi, custom e note.
	 *
	 * @access public
	 * @param mixed $hotel_id
	 * @return void
	 */

	public function listino_mobile($hotel_id)
	{

		$locale = $this->getLocale();

		$key = "listino_" . $hotel_id . "_" . $locale;

		if (!$cliente = Utility::activeCache($key, "Cache Listino")) {

			$cliente = Hotel::with([

				'localita.macrolocalita',
				'stelle',
				'listini' => function($query) use ($locale)
				{
					$query
					->attivo()
					->nonNullo();
				},
				'listiniMinMax' => function($query) use ($locale)
				{
					$query
					->attivo()
					->nonNullo();
				},

				'listiniCustom' => function($query) use ($locale)
				{
					$query
					->attivo()
					->orderBy('position', 'asc');
				},

				'listiniCustom.listiniCustomLingua' => function($query) use ($locale)
				{
					$query
					->daVisualizzare($locale);
				},

				'notaListino' => function($query)
				{
					$query
					->where('attivo' , '=', 1);
				},

				'notaListino.noteListino_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				}


				])
			->where(function ($query)
				{
					$query->where('attivo', 1)
					->orWhere('attivo', -1);
				})
			->find($hotel_id);

			Utility::putCache($key, $cliente);

		}

		$menu_localita   = Utility::getMenuLocalita($locale);
		$title     = Lang::get("title.prezzi") . " - " . $cliente->getTitle($locale); /* SEO title */
		$description   = $cliente->getDescription($locale); /* SEO description */
		$selezione_localita = $cliente->localita->nome;
		$ua     = Request::server('HTTP_USER_AGENT');
		$referer    = Request::server('HTTP_REFERER');
		$ip     = Utility::get_client_ip();

		$language_uri = [];
		$language_uri["it"] = "hotel.php?id=" . $hotel_id . "&price-list";
		$language_uri["en"] = "ing/hotel.php?id=" . $hotel_id . "&price-list";
		$language_uri["de"] = "ted/hotel.php?id=" . $hotel_id . "&price-list";
		$language_uri["fr"] = "fr/hotel.php?id=" . $hotel_id . "&price-list";

		if ($ua == NULL || $ua == "")
			$ua = "no_user_agent ( both? )";


		/**
		 * contaclick sulla scheda hotel
		 * incremento del campo numero_click della tabella hotel per ordinamento
		 */

		Event::dispatch(new HotelViewHandler(compact('cliente', 'locale', 'ua', 'referer', 'ip')));
		return View::make('hotel.listino_mobile', compact('language_uri','selezione_localita', 'cliente', 'locale', 'menu_localita', 'title', 'description'));

	}

	/**
	 * Visualizza la mappa .
	 *
	 * @access public
	 * @param mixed $hotel_id
	 * @param int $percorso (default: 0)
	 * @return void
	 */

	public function map_mobile($hotel_id, $percorso = 0)
	{

		$locale = $this->getLocale();
		$key = "mappa_" . $hotel_id . "_" . $locale;

		if (!$cliente = Utility::activeCache($key, "Cache Mappa")) {

			$cliente = Hotel::with([
				'localita.macrolocalita',
				'stelle',
				'poi.poi_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				])
			->where(function ($query)
				{
					$query->where('attivo', 1)
					->orWhere('attivo', -1);
				})
			->find($hotel_id);

			Utility::putCache($key, $cliente);

		}

		$menu_localita = Utility::getMenuLocalita($locale);
		$title = Lang::get("title.mappa") . " - " . $cliente->getTitle($locale);
		$description = $cliente->getDescription($locale);
		$selezione_localita = $cliente->localita->nome;

		$ua = Request::server('HTTP_USER_AGENT');
		$referer = Request::server('HTTP_REFERER');
		$ip = Utility::get_client_ip();

		if ($ua == NULL || $ua == "")
			$ua = "no_user_agent ( both? )";

		$language_uri = [];
		$language_uri["it"] = "hotel.php?id=" . $hotel_id . "&map";
		$language_uri["en"] = "ing/hotel.php?id=" . $hotel_id . "&map";
		$language_uri["de"] = "ted/hotel.php?id=" . $hotel_id . "&map";
		$language_uri["fr"] = "fr/hotel.php?id=" . $hotel_id . "&map";

		/* contaclick sulla scheda hotel
		 * incremento del campo numero_click della tabella hotel per ordinamento
		 */

		Event::dispatch(new HotelViewHandler(compact('cliente', 'locale', 'ua', 'referer', 'ip')));
		return View::make('hotel.map_mobile', compact('language_uri', 'selezione_localita', 'cliente', 'locale', 'menu_localita', 'title', 'description', 'percorso'));

	}


	/**
	 * Visualizza la galleria mobile
	 *
	 * @access public
	 * @param mixed $hotel_id
	 * @return void
	 */

	public function gallery_mobile($hotel_id)
	{

		$locale = $this->getLocale();
		$key = "gallery_" . $hotel_id . "_" . $locale;

		if (!$cliente = Utility::activeCache($key, "Cache Gallery")) {

			$cliente = Hotel::with([
				'localita.macrolocalita',
				'stelle',
				'poi'
				])
			->where(function ($query)
				{
					$query->where('attivo', 1)
					->orWhere('attivo', -1);
				})
			->find($hotel_id);

			Utility::putCache($key, $cliente);

		}

		$menu_localita = Utility::getMenuLocalita($locale);
		$title = Lang::get("title.gallery") ." - " .$cliente->getTitle($locale);
		$description = $cliente->getDescription($locale);
		$gallery_mobile = Self::_gallery($hotel_id);

		//dd($gallery_mobile);
		$selezione_localita = $cliente->localita->nome;

		$ua = Request::server('HTTP_USER_AGENT');
		$referer = Request::server('HTTP_REFERER');
		$ip = Utility::get_client_ip();

		if ($ua == NULL || $ua == "")
			$ua = "no_user_agent ( both? )";

		$language_uri = [];
		$language_uri["it"] = "hotel.php?id=" . $hotel_id . "&gallery";
		$language_uri["en"] = "ing/hotel.php?id=" . $hotel_id . "&gallery";
		$language_uri["de"] = "ted/hotel.php?id=" . $hotel_id . "&gallery";
		$language_uri["fr"] = "fr/hotel.php?id=" . $hotel_id . "&gallery";

		/**
		 * contaclick sulla scheda hotel
		 * incremento del campo numero_click della tabella hotel per ordinamento
		 */

		Event::dispatch(new HotelViewHandler(compact('cliente', 'locale', 'ua', 'referer', 'ip')));
		return View::make('hotel.gallery_mobile', compact('language_uri', 'selezione_localita', 'cliente', 'locale', 'menu_localita', 'title', 'description', 'gallery_mobile'));

	}


	/**
	 * Visualizza le Offerte PP e PPTOP.
	 *
	 * @access public
	 * @param int $hotel_id
	 * @return View
	 */

	public function prenotaprima_mobile($hotel_id)
	{
		$locale = $this->getLocale();
		$key = "prenotaprima_" . $hotel_id . "_" . $locale;

		if (!$cliente = Utility::activeCache($key, "Cache Prenota Prima minute")) {

			$cliente = Hotel::with([

				'localita.macrolocalita',
				'stelle',
				'poi',

				/* OFFERTE PRENOTA PRIMA */
				'offertePrenotaPrima'  => function($query)
				{
					$query
					->attiva()
					->orderBy('prenota_entro', 'asc');
				},

				'offertePrenotaPrima.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				/* OFFERTE PRENOTA PRIMA */
				'offerteTopPP'  => function($query)
				{
					$query
					->attiva()
					->orderBy('prenota_entro', 'asc');
				},

				'offerteTopPP.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				}


				])
			->where(function ($query)
				{
					$query->where('attivo', 1)
					->orWhere('attivo', -1);
				})
			->find($hotel_id);

			Utility::putCache($key, $cliente);

		}

		$menu_localita = Utility::getMenuLocalita($locale);
		$title = Lang::get("title.prenotaprima") . " - " .  $cliente->getTitle($locale);
		$description = $cliente->getDescription($locale);
		$selezione_localita = $cliente->localita->nome;

		$ua = Request::server('HTTP_USER_AGENT');
		$referer = Request::server('HTTP_REFERER');
		$ip =Utility::get_client_ip();

		if ($ua == NULL || $ua == "")
			$ua = "no_user_agent ( bot? )";

		/**
		 * contaclick sulla scheda hotel
		 * incremento del campo numero_click della tabella hotel per ordinamento
		 */

		$language_uri = [];
		$language_uri["it"] = "hotel.php?id=" . $hotel_id . "&prenotaprima";
		$language_uri["en"] = "ing/hotel.php?id=" . $hotel_id . "&prenotaprima";
		$language_uri["de"] = "ted/hotel.php?id=" . $hotel_id . "&prenotaprima";
		$language_uri["fr"] = "fr/hotel.php?id=" . $hotel_id . "&prenotaprima";

		Event::dispatch(new HotelViewHandler(compact('cliente', 'locale', 'ua', 'referer', 'ip')));
		return View::make('hotel.prenotaprima_mobile', compact('language_uri', 'selezione_localita', 'cliente', 'locale', 'menu_localita', 'title', 'description'));


	}


	/**
	 * Visualizza le Offerte LM e LMTOP.
	 *
	 * @access public
	 * @param int $hotel_id
	 * @return View
	 */

	public function lastminute_mobile($hotel_id)
	{

		$locale = $this->getLocale();
		$key = "last_" . $hotel_id . "_" . $locale;

		if (!$cliente = Utility::activeCache($key, "Cache Last minute")) {

			$cliente = Hotel::with([
				'localita.macrolocalita',
				'stelle',
				'last'  => function($query)
				{
					$query
					->attiva()
					->orderBy('valido_dal', 'asc');
				},
				'last.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},
				'offerteTopLast'  => function($query)
				{
					$query
					->attiva()
					->visibileInScheda()
					->orderBy('valido_dal', 'asc');
				},
				'offerteTopLast.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				}


				])
			->where(function ($query)
				{
					$query->where('attivo', 1)
					->orWhere('attivo', -1);
				})
			->find($hotel_id);

			Utility::putCache($key, $cliente);

		}

		$menu_localita = Utility::getMenuLocalita($locale);
		$title = Lang::get("title.lastminute") . " - " .  $cliente->getTitle($locale);
		$description = $cliente->getDescription($locale);
		$selezione_localita = $cliente->localita->nome;

		$ua = Request::server('HTTP_USER_AGENT');
		$referer = Request::server('HTTP_REFERER');
		$ip = Utility::get_client_ip();

		if ($ua == NULL || $ua == "")
			$ua = "no_user_agent ( both? )";

		/**
		 * contaclick sulla scheda hotel
		 * incremento del campo numero_click della tabella hotel per ordinamento
		 */

		$language_uri = [];
		$language_uri["it"] = "hotel.php?id=" . $hotel_id . "&lastminute";
		$language_uri["en"] = "ing/hotel.php?id=" . $hotel_id . "&lastminute";
		$language_uri["de"] = "ted/hotel.php?id=" . $hotel_id . "&lastminute";
		$language_uri["fr"] = "fr/hotel.php?id=" . $hotel_id . "&lastminute";

		Event::dispatch(new HotelViewHandler(compact('cliente', 'locale', 'ua', 'referer', 'ip')));
		return View::make('hotel.lastminute_mobile', compact('language_uri','selezione_localita', 'cliente', 'locale', 'menu_localita', 'title', 'description'));

	}


	/**
	 * Visualizza le offerte Speciali e TOP
	 *
	 * @access public
	 * @param int $hotel_id
	 * @return void
	 */
	public function offerte_mobile($hotel_id)
	{
		$locale = $this->getLocale();
		$key = "offerte_" . $hotel_id . "_" . $locale;

		if (!$cliente = Utility::activeCache($key, "Cache Offerte")) {


			$cliente = Hotel::with([
				'localita.macrolocalita',
				'stelle',
				'offerte'  => function($query)
				{
					$query
					->attiva()
					->orderBy('valido_dal', 'asc');
				},

				'offerteTop'  => function($query)
				{
					$query
          ->attiva()
          ->visibileInScheda();
				},

				'offerteTopOS'  => function($query)
				{
					$query
          ->attiva()
          ->visibileInScheda();
        },
        
        'offerte.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},

				'offerteTop.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},

				'offerteTopOS.offerte_lingua'  => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},

				])
			->where(function ($query)
				{
					$query->where('attivo', 1)
					->orWhere('attivo', -1);
				})
			->find($hotel_id);

			Utility::putCache($key, $cliente);

		}
    
		$menu_localita = Utility::getMenuLocalita($locale);
		$title = Lang::get("title.offerte_scheda") . " - " .   $cliente->getTitle($locale);
		$description = $cliente->getDescription($locale);
		$selezione_localita = $cliente->localita->nome;

		$ua = Request::server('HTTP_USER_AGENT');
		$referer = Request::server('HTTP_REFERER');
		$ip = Request::ip();

		if ($ua == NULL || $ua == "")
			$ua = "no_user_agent ( both? )";

		/**
		 * contaclick sulla scheda hotel
		 * incremento del campo numero_click della tabella hotel per ordinamento
		 */
		$language_uri = [];
		$language_uri["it"] = "hotel.php?id=" . $hotel_id . "&offers";
		$language_uri["en"] = "ing/hotel.php?id=" . $hotel_id . "&offers";
		$language_uri["de"] = "ted/hotel.php?id=" . $hotel_id . "&offers";
		$language_uri["fr"] = "fr/hotel.php?id=" . $hotel_id . "&offers";

		Event::dispatch(new HotelViewHandler(compact('cliente', 'locale', 'ua', 'referer', 'ip')));
		return View::make('hotel.offerte_mobile', compact('selezione_localita', 'cliente', 'locale', 'menu_localita', 'title', 'description'));

	}


	/**
	 * Visualizza le Offerte BB e TOP BB
	 *
	 * @access public
	 * @param int $hotel_id
	 * @return View
	 */

	public function bambinigratis_mobile($hotel_id)
	{
		$locale = $this->getLocale();
		$key = "bambini_" . $hotel_id . "_" . $locale;

		if (!$cliente = Utility::activeCache($key, "Cache Bambini")) {

			$cliente = Hotel::with([
				'localita.macrolocalita',
				'stelle',
				'bambiniGratisAttivi',
				'bambiniGratisAttivi.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},				
				'offerteBambiniGratisTop'  => function($query)
				{
					$query
					->attiva();
				},

				'offerteBambiniGratisTop.offerte_lingua' => function($query) use ($locale)
				{
					$query
					->where('lang_id', '=', $locale);
				},

				])
			->where(function ($query)
				{
					$query->where('attivo', 1)
					->orWhere('attivo', -1);
				})
			->find($hotel_id);

			Utility::putCache($key, $cliente);

		}



		$menu_localita = Utility::getMenuLocalita($locale);
		$title = Lang::get("title.offerte_bambini") . " - " . $cliente->getTitle($locale);
		$description = $cliente->getDescription($locale);
		$selezione_localita = $cliente->localita->nome;

		$ua = Request::server('HTTP_USER_AGENT');
		$referer = Request::server('HTTP_REFERER');
		$ip = Utility::get_client_ip();

		if ($ua == NULL || $ua == "")
			$ua = "no_user_agent ( both? )";

		/**
		 * contaclick sulla scheda hotel
		 * incremento del campo numero_click della tabella hotel per ordinamento
		 */

		$language_uri = [];
		$language_uri["it"] = "hotel.php?id=" . $hotel_id . "&children-offers";
		$language_uri["en"] = "ing/hotel.php?id=" . $hotel_id . "&children-offers";
		$language_uri["de"] = "ted/hotel.php?id=" . $hotel_id . "&children-offers";
		$language_uri["fr"] = "fr/hotel.php?id=" . $hotel_id . "&children-offers";

		Event::dispatch(new HotelViewHandler(compact('cliente', 'locale', 'ua', 'referer', 'ip')));
		return View::make('hotel.bambinigratis_mobile', compact('language_uri', 'selezione_localita', 'cliente', 'locale', 'menu_localita', 'title', 'description'));

	}









	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( AJAX )
	 * ------------------------------------------------------------------------------------ */



	/**
	 * chiamata ajax che conta il click della chat di whatsapp
	 * @param  int $hotel_id
	 * @return mixed
	 */

	public function contaClickWhatsappMeAjax($hotel_id)
	{

		$hotel_id = (int)$hotel_id;

		$hotel = Hotel::find($hotel_id);

		if (!is_null($hotel)) {

			// IP salvato come intero per risparmiare spazio db
			$ip = Utility::get_client_ip();
			$ua = Request::header('User-Agent');
			$ts = Carbon::now();

			$os = \Browser::platformName();

			if ($ua == NULL || $ua == "")
				$ua = Request::server('HTTP_USER_AGENT');

			if ($ua == NULL || $ua == "")
				$ua = "no_user_agent ( both? )";

			/*
				* Salvo il dato singolo
				*/
			$stat = new StatsHotelWhatsapp;
			$stat->hotel_id = $hotel_id;
			$stat->IP = $ip;
			$stat->os = $os;
			$stat->useragent = $ua;
			$stat->created_at = $ts;
			$stat->save();

			/*
				* Aggiorno il dato aggregato
				*/
			// 
			// DB::statement("INSERT INTO tblStatsHotelWhatsappRead (anno, mese, giorno, hotel_id, calls)
			// 					VALUES (".$ts->year.", ".$ts->month.", ".$ts->day.", $hotel_id, 1)
			// 					ON DUPLICATE KEY UPDATE calls = calls + 1");


		} /* non isnull hotel*/

		echo "ok";
	}


	/**
	 * Chiamo l'hotel con un href="tel:" e sul click faccio una chiamata ajax che conta il
	 * click della chiamata
	 *
	 * @access public
	 * @param  int $hotel_id
	 * @return String
	 */

	public function contaClickCallMeAjax($hotel_id)
	{

		$hotel_id = (int)$hotel_id;
		$hotel = Hotel::find($hotel_id);

		if (!is_null($hotel)) {

			/**
			 * IP salvato come intero per risparmiare spazio db
			 */

			$ip = Utility::get_client_ip();
			$ua = Request::header('User-Agent');
			$ts = Carbon::now();

			if ($ua == NULL || $ua == "")
				$ua = Request::server('HTTP_USER_AGENT');

			if ($ua == NULL || $ua == "")
				$ua = "no_user_agent ( both? )";

			/**
			 * Salvo il dato singolo
			 */

			$stat = new StatsHotelCall;
			$stat->hotel_id = $hotel_id;
			$stat->IP = Utility::localIpToNumberIp($ip);
			$stat->useragent = $ua;
			// $stat->os = $os;
			$stat->created_at = $ts;
			$stat->save();

			/**
			 * Aggiorno il dato aggregato
			 */

			DB::statement("INSERT INTO tblStatsHotelCallRead (anno, mese, giorno, hotel_id, calls)
			VALUES (".$ts->year.", ".$ts->month.", ".$ts->day.", $hotel_id, 1)
			ON DUPLICATE KEY UPDATE calls = calls + 1");

		}

		echo "ok";
	}



	/**
	 * Attiva il preferito.
	 *
	 * @access public
	 * @return void
	 */

	public function attivaPreferitoAjax()
    {

	    $ids = Request::get('id');
		$hotel_ids = explode(",", $ids);

    	foreach($hotel_ids as $hotel_id):

	        $hotel = Hotel::find($hotel_id);

	        if (!is_null($hotel)) {

	            $ip = Utility::get_client_ip();

	            /**
		         * se nella tabella tblHotelPreferiti esiste un record con hotel_id, IP **e l'ultimo valore inserito è add**
		         * NON AGGIUNGO IL RECORD in questa tabella e non aggiorno il conteggio nella tabella hotel
		         */

	            $last_pref = HotelPreferito::where('hotel_id', $hotel_id)
					->where('ip',$ip)
					->orderBy('created_at', 'desc')
					->take(1)
					->first();

	            //altrimenti INSERISCO il record con hotel_id, IP, 'add',... e aggiorno il conteggio nella tblHotel
				if( is_null($last_pref) || $last_pref->azione == 'sub' )
				{

					$hotel_preferito = new HotelPreferito;
					$hotel_preferito->ip = $ip;
					$hotel->addCountFavourite();
					$hotel->hotelPreferiti()->save($hotel_preferito);

				}

	        }

		endforeach;

		return CookieIA::setAsFavourite($ids);

    }


	/**
	 * Disattiva il preferito.
	 *
	 * @access public
	 * @return void
	 */

	public function disattivaPreferitoAjax()
	{

		$hotel_id = (int)Request::get('id');
		$hotel = Hotel::find($hotel_id);

		if (!is_null($hotel)) {

			/**
			 * INSERISCO il record con hotel_id, IP, 'sub',... e aggiorno il conteggio nella tblHotel (verificare che il conteggio NON SIA MAI NEGATIVO !!!)
			 */

			$ip = Utility::get_client_ip();

			$hotel_preferito = new HotelPreferito;
			$hotel_preferito->ip = $ip;
			$hotel_preferito->azione = 'sub';
			$hotel->subCountFavourite();
			$hotel->hotelPreferiti()->save($hotel_preferito);

			/**
			 * elimino l'hotel_id nel cookie hotel_preferiti
			 */

			return CookieIA::unsetAsFavourite($hotel_id);

		}

	}


	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( CRON )
	 * ------------------------------------------------------------------------------------ */




	/**
	 * Azzera i click sugli hotel.
	 *
	 * @access public
	 * @return void
	 */

	public function cron() { 
		DB::table('tblHotel')->update(['numero_click' => 0]); 
		DB::table('tblGestioneMultiple')->update(['contact' => 0]); 
	}

	/**
	 * Crea i punti di forza per hotel.
	 *
	 * @access public
	 * @return void
	 */

	public function cronCreatePuntiDiForzaTemp()
	{

		ini_set('max_execution_time', 300);

		$clienti = Hotel::with([
			'puntiDiForza'
			])
		->attivo()
		->get();

		foreach ($clienti as $cliente) {

			$tmp_punti_di_forza_it = '';
			$tmp_punti_di_forza_en = '';
			$tmp_punti_di_forza_fr = '';
			$tmp_punti_di_forza_de = '';

			foreach ($cliente->puntiDiForza as $puntiDiForza) {
				$tmp_punti_di_forza_it .= $puntiDiForza->translate('it')->first()->nome . ',';
				$tmp_punti_di_forza_en .= $puntiDiForza->translate('en')->first()->nome . ',';
				$tmp_punti_di_forza_fr .= $puntiDiForza->translate('fr')->first()->nome . ',';
				$tmp_punti_di_forza_de .= $puntiDiForza->translate('de')->first()->nome . ',';
			}

			$tmp_punti_di_forza_it = rtrim($tmp_punti_di_forza_it, ',');
			$tmp_punti_di_forza_en = rtrim($tmp_punti_di_forza_en, ',');
			$tmp_punti_di_forza_fr = rtrim($tmp_punti_di_forza_fr, ',');
			$tmp_punti_di_forza_de = rtrim($tmp_punti_di_forza_de, ',');


			DB::table('tblHotel')
			->where('id', $cliente->id)
			->update([
				'tmp_punti_di_forza_it' => $tmp_punti_di_forza_it,
				'tmp_punti_di_forza_en' => $tmp_punti_di_forza_en,
				'tmp_punti_di_forza_de' => $tmp_punti_di_forza_de,
				'tmp_punti_di_forza_fr' => $tmp_punti_di_forza_fr,
				]);
		}


	}


	public function cronCreatePuntiDiForzaTempHotel($hotel_id = 0)
	{
		$cliente = Hotel::find($hotel_id);

		if (!is_null($cliente)) {
			
			$tmp_punti_di_forza_it = '';
			$tmp_punti_di_forza_en = '';
			$tmp_punti_di_forza_fr = '';
			$tmp_punti_di_forza_de = '';

			foreach ($cliente->puntiDiForza as $puntiDiForza) {
		
				$tmp_punti_di_forza_it .= $puntiDiForza->translate('it')->first()->nome . ',';
				$tmp_punti_di_forza_en .= $puntiDiForza->translate('en')->first()->nome . ',';
				$tmp_punti_di_forza_fr .= $puntiDiForza->translate('fr')->first()->nome . ',';
				$tmp_punti_di_forza_de .= $puntiDiForza->translate('de')->first()->nome . ',';
		
			}

			$tmp_punti_di_forza_it = rtrim($tmp_punti_di_forza_it, ',');
			$tmp_punti_di_forza_en = rtrim($tmp_punti_di_forza_en, ',');
			$tmp_punti_di_forza_fr = rtrim($tmp_punti_di_forza_fr, ',');
			$tmp_punti_di_forza_de = rtrim($tmp_punti_di_forza_de, ',');

			$cliente->update([
				'tmp_punti_di_forza_it' => $tmp_punti_di_forza_it,
				'tmp_punti_di_forza_en' => $tmp_punti_di_forza_en,
				'tmp_punti_di_forza_de' => $tmp_punti_di_forza_de,
				'tmp_punti_di_forza_fr' => $tmp_punti_di_forza_fr,
			]);

			echo "<h3>CLiente $cliente->nome aggiornato correttamente!</h3>";

		} // if !null cliente

	}

	/**
	 * Manda sul sito di un hotel, registrando il click
	 * @param  int $hotel_id
	 * @return mixed
	 */

	public function outboundLink($hotel_id)
	{

		$hotel_id = (int)$hotel_id;
		$hotel = Hotel::find($hotel_id);

		if (!is_null($hotel) && $hotel->link && $hotel->attivo) {

			$url = $hotel->link;

			/**
			 * IP salvato come intero per risparmiare spazio db
			 */

			$ip = ip2long(Utility::get_client_ip());
			$ua = Request::header('User-Agent');
			$ts = Carbon::now();

			if ($ua == NULL || $ua == "")
				$ua = Request::server('HTTP_USER_AGENT');

			if ($ua == NULL || $ua == "")
				$ua = "no_user_agent ( both? )";

			/**
			 * Salvo il dato singolo
			 */

			$stat = new StatsHotelOutboundLink;
			$stat->hotel_id = $hotel_id;
			$stat->ip = $ip;
			$stat->user_agent = $ua;
			$stat->created_at = $ts;
			$stat->save();

			return Redirect::away($url);

		}

		abort("404");

	}


	public function redirectTo($id=0)
	{

		$locale = $this->getLocale();
		return redirect(url(Utility::getLocaleUrl($locale).'hotel.php?id='.$id));

	}

}

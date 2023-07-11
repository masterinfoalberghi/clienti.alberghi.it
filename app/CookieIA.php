<?php

namespace App;

use App\CookieDB;
use Carbon\Carbon;
use Cookie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Request;

class CookieIA extends Model
{

	public static function getFavourite()
	{

		$cookiev = env("COOKIE_VERSION", 0);
		return Cookie::get('hotelPreferiti_v' . $cookiev);

	}


	public static function isFavourite($id)
	{

		$cookiev = env("COOKIE_VERSION", 0);
		$cookie = CookieIA::getFavourite();

		if (is_null($cookie))
			return false;
		else {
			return in_array($id, explode("," , $cookie));
		}

	}

	public static function setAsFavourite($id)
	{

		$cookiev = env("COOKIE_VERSION", 0);
		$cookie = CookieIA::getFavourite();

		if (is_null($cookie))
			$cookie = Cookie::make('hotelPreferiti_v' . $cookiev, "," . $id . "," , 60*24*7);

		else {

			$ids = explode(",", $id);
			foreach ($ids as $sId):

				$cookie = str_replace("," . $id . "," , ",", $cookie); // prima lo cancello
				$cookie .= $sId . "," ;  // poi lo riscrivo

			endforeach;
			$cookie = Cookie::make('hotelPreferiti_v' . $cookiev, $cookie , 60*24*7);

		}

		$response = Response::json(array('status' => 'ok'));
		$response->headers->setCookie($cookie);
		return $response;

	}

	public static function unsetAsFavourite($id)
	{

		$cookiev = env("COOKIE_VERSION", 0);
		$cookie = CookieIA::getFavourite();
		$cookie = str_replace("," . $id . "," , ",", $cookie);

		$cookie = Cookie::make('hotelPreferiti_v' . $cookiev, $cookie , 60*24*7);

		$response = Response::json(array('status' => 'ok'));
		$response->headers->setCookie($cookie);
		return $response;
	}

	// Cacello i vecchi cookie
	public static function deleteOldCookiePrefill()
	{

		$cookie = env("COOKIE_VERSION_OLD", 0);

		if ( $cookie != 0) {

			$cookie_old = explode(",", $cookie);

			foreach ($cookie_old as $co):

				if (Request::cookie("prefill_v" . $co))
					Cookie::queue(Cookie::forget('prefill_v' . $co));

				Cookie::queue(Cookie::forget('hotelPreferiti_v' . $co));
				Cookie::queue(Cookie::forget('localita_v' . $co));

			endforeach;

		}

	}

	/**
	 * Prendo il cookie delle email
	 * return @Array
	 */

	public static function getCookieRoomPrefill()
	{

		$prefillRooms = array();
		$prefillRooms[0] = array();
		$prefillRooms[0]["checkin"] = date("d/m/Y", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
		$prefillRooms[0]["checkout"] = date("d/m/Y", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
		$prefillRooms[0]["adult"] = 2;
		$prefillRooms[0]["children"] = 0;
		$prefillRooms[0]["age_children"] = "-1,-1,-1,-1,-1,-1";
		$prefillRooms[0]["flex_date"] = 0;
		$prefillRooms[0]["meal_plan"] = "";

		return $prefillRooms;

	}

	/**
	 * [checkForCookie Verifica se c'è il cookie e se ha una corrispondenza sul DB]
	 * @return [type] [prefill]
	 */
	
	public static function checkForCookie()
	{
		$cookie = env("COOKIE_VERSION", 0);

		$prefill = [];

		if (Request::cookie("prefill_v" . $cookie) != "") 
		{

			$new_prefill = Request::cookie("prefill_v" . $cookie);
			$new_prefill =  unserialize($new_prefill);
			$prefill = self::getPrefillDB($new_prefill['codice_cookie']);

		}	 

		return $prefill;

	}

	/**
	 * Prendo il cookie lo ritorno se esiste oppure lo creo nuove se non esiste
	 * return @Array
	 */

	public static function getCookiePrefill()
	{
		
		/* Prendo i dati da i cookie */

		$prefill = self::checkForCookie();

		// il cookie potrebbe esistere MA potrebbero non esserci i dati sul DB, quindi $prefill = [];
		if(empty($prefill))
		{

			$prefill["ids_send_mail"] = "";
			$prefill["macrolocalita"] = "";
			$prefill["codice_cookie"] = Carbon::now()->timestamp . "_" . uniqid(rand(), true);
			$prefill["customer"] = "";
			$prefill["email"] = "";
			$prefill["phone"] = "";
			$prefill["whatsapp"] = NULL;
			$prefill["information"] = "";
			$prefill["tag"] = "";
			$prefill["sender"] = "info-alberghi.com";
			$prefill["language"] = "";
			$prefill["type"] = ""; // Mi serve per fare i post dei checkbox non marcati,va compilato con una array 1,0,0,1,1 etc..
			$prefill["newsletter"] = false;
			$prefill["a_partire_da"] = date("d/m/Y", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
			$prefill["rooms"] = Self::getCookieRoomPrefill();
			$prefill["flex_date"] = 0;

			$prefill['servizi'] = array();
			$prefill["localita_multi"] = array();
			$prefill["cat_1"] = 0;
			$prefill["cat_2"] = 0;
			$prefill["cat_3"] = 0;
			$prefill["cat_4"] = 0;
			$prefill["cat_5"] = 0;
			$prefill["cat_6"] = 0;

			$prefill["f_prezzo_real"] = "";
			$prefill["distanza_centro_real"] = "";
			$prefill["distanza_stazione_real"] = "";
			$prefill["distanza_spiaggia_real"] = "";

		} else {

			if ($prefill["whatsapp"] == 0) $prefill["whatsapp"] = NULL;

		}

		return $prefill;

	}

	/**
	 * Setto il cookie delle email in base ai post	
	 *
	 * @access public
	 * @param  Request $request
	 * @return Array
	 */

	public static function setCookiePrefill($request)
	{

		$my_input = $request->input();
		$prefill = Self::getCookiePrefill();

		/**
		 * se vengo dal listing ed ho postato gli id da visualizzare NON VOGLIO METTERLI NEL COOKIE NON HO SPEDITO A LORO 
		 */

		if ( isset( $my_input['ids_send_mail'] ) && !isset($my_input['no_execute_prefill_cookie']) )
			$prefill['ids_send_mail'] = is_array($my_input['ids_send_mail']) ?  implode(",", $my_input['ids_send_mail']) : $my_input['ids_send_mail'];

		elseif (Session::has('ids_send_mail'))
			$prefill['ids_send_mail'] = is_array(Session::get('ids_send_mail')) ? implode(",", Session::get('ids_send_mail')) : Session::get('ids_send_mail');

		if (isset( $my_input['codice_cookie']))
			$codice_cookie = $my_input['codice_cookie']; // Array
		else
			$codice_cookie = Carbon::now()->timestamp . "_" . uniqid(rand(), true);

		if ( isset( $my_input['macrolocalita'] ) ) { $prefill['macrolocalita'] = $my_input['macrolocalita']; }		
		if ( isset( $my_input['nome'] ) ) { $prefill['customer'] = $my_input['nome']; }
		if ( isset( $my_input['email'] ) ) { $prefill['email'] = $my_input['email']; }
		if ( isset( $my_input['telefono'] ) ) { $prefill['phone'] = $my_input['telefono']; }
		if ( isset( $my_input['whatsapp_check'] ) ) { $prefill['whatsapp'] = $my_input['whatsapp_check']; }
		if ( isset( $my_input['richiesta'] ) ) { $prefill['information'] = $my_input['richiesta']; }
		if ( isset( $my_input['tag'] ) ) { $prefill['tag'] = $my_input['tag']; }
		if ( isset( $my_input['locale'] ) ) { $prefill['language'] = $my_input['locale']; }
		if ( isset( $my_input['newsletter'] ) ) { $prefill['newsletter'] = true; }
		if ( isset( $my_input['type'] ) ) { $prefill['type'] = $my_input['type']; }
		if ( isset( $my_input['flex_date'] ) ) { $prefill['flex_date'] = $my_input['flex_date']; }
		
		/**
		 * Ricerca avanzata
		 */

		if ( isset( $my_input['multiple_loc'] ) ) { $prefill['multiple_loc']     = $my_input['multiple_loc']; }
		if ( isset( $my_input['multiple_loc_single'] ) ) { $prefill['multiple_loc_single']   = $my_input['multiple_loc_single']; }
		if ( isset( $my_input['categoria'] ) ) { $prefill['categoria']     = $my_input['categoria']; }
		if ( isset( $my_input['a_partire_da'] ) ) { $prefill['a_partire_da']     = $my_input['a_partire_da']; }
		if ( isset( $my_input['annuale'] ) ) { $prefill['annuale'] = $my_input['annuale']; }

		/**
		 * Distanze
		 */

		if ( isset( $my_input['f_prezzo_real'] ) ) { $prefill['f_prezzo_real'] = $my_input['f_prezzo_real']; }
		if ( isset( $my_input['distanza_centro_real'] ) ) { $prefill['distanza_centro_real'] = $my_input['distanza_centro_real']; }
		if ( isset( $my_input['distanza_stazione_real'] ) ) { $prefill['distanza_stazione_real'] = $my_input['distanza_stazione_real']; }
		if ( isset( $my_input['distanza_spiaggia_real'] ) ) { $prefill['distanza_spiaggia_real'] = $my_input['distanza_spiaggia_real']; }

		/**
		 * Catgegorie
		 */

		$prefill["cat_1"] = 0;
		$prefill["cat_2"] = 0;
		$prefill["cat_3"] = 0;
		$prefill["cat_4"] = 0;
		$prefill["cat_5"] = 0;
		$prefill["cat_6"] = 0;

		if ( isset( $my_input['categorie'] ) ) {
			foreach ($my_input['categorie'] as $categorie) {
				$prefill['cat_' . $categorie] = 1;
			}

		}

		/**
		 * Servizi
		 */

		$prefill['servizi'] = array();
		if ( isset( $my_input['servizi'] ) ) {
			foreach ($my_input['servizi'] as $servizio) {
				$prefill['servizi'][] = $servizio;
			}

		}

		/**
		 * Localita mail multipla
		 */

		$prefill["localita_multi"] = array();

		if ( isset( $my_input['multiple_loc'] ) ) {
			foreach ($my_input["multiple_loc"] as $singleLoc) {
				array_push($prefill["localita_multi"], $singleLoc);
			}
		}

		/**
		 * Search first
		 */

		if (isset($my_input['search_first_active'])) {

			$prefill['rooms'] = array();
			$prefill['rooms'][0]["checkin"] = $my_input['arrivo'][0];
			$prefill['rooms'][0]["checkout"] = $my_input['partenza'][0];
			$prefill['rooms'][0]["adult"] = $my_input['adulti'][0];
			$prefill['rooms'][0]["children"] = $my_input['bambini'][0];
			$prefill["rooms"][0]["age_children"] = "-1,-1,-1,-1,-1,-1";
			$prefill["rooms"][0]["meal_plan"] = "trattamento_ai";
			$prefill["rooms"][0]["flex_date"] = false;

		}

		/**
		 * Soggiorni
		 */

		if (isset($my_input['numero_camere'])) {

			$prefill['rooms'] = array();

			$checkin 	= $my_input['arrivo']; // Array
			$checkout 	= $my_input['partenza']; // Array
			$meal_plan 	= $my_input['trattamento']; // Array
			$adult 		= $my_input['adulti']; // Array
			$children 	= $my_input['bambini']; // Array

			$age_0 = isset($my_input['eta_0']) ? $my_input['eta_0'] : array();
			$age_1 = isset($my_input['eta_1']) ? $my_input['eta_1'] : array();
			$age_2 = isset($my_input['eta_2']) ? $my_input['eta_2'] : array();
			$age_3 = isset($my_input['eta_3']) ? $my_input['eta_3'] : array();
			$age_4 = isset($my_input['eta_4']) ? $my_input['eta_4'] : array();
			$age_5 = isset($my_input['eta_5']) ? $my_input['eta_5'] : array();

			for ( $i=0;$i<count($meal_plan);$i++ ) {

				$prefill['rooms'][$i] = array();
				$prefill["rooms"][$i]["checkin"]   = $checkin[$i];
				$prefill["rooms"][$i]["checkout"]  = $checkout[$i];
				$prefill["rooms"][$i]["adult"]     = $adult[$i];
				$prefill["rooms"][$i]["children"]  = $children[$i];
				$prefill["rooms"][$i]["meal_plan"] = $meal_plan[$i];
				$prefill["rooms"][$i]["flex_date"] = $prefill["flex_date"];

				$eta_bambini = array();
				if (isset($age_0[$i])) { $eta_bambini[0] = $age_0[$i]; }
				if (isset($age_1[$i])) { $eta_bambini[1] = $age_1[$i]; }
				if (isset($age_2[$i])) { $eta_bambini[2] = $age_2[$i]; }
				if (isset($age_3[$i])) { $eta_bambini[3] = $age_3[$i]; }
				if (isset($age_4[$i])) { $eta_bambini[4] = $age_4[$i]; }
				if (isset($age_5[$i])) { $eta_bambini[5] = $age_5[$i]; }

				$prefill["rooms"][$i]["age_children"] = implode(",", $eta_bambini );

			}
		}

		return $prefill;

	}

	/**
	 * [setPrefillDB Scrive il prefill nel DB usando "codice_cookie" come chiave e ritorna il prefill che contiene SOLO la chiave "codice_cookie"]
	 * @param array $prefill [description]
	 */
	public static function setPrefillDB($prefill = []) 
	{

		$new_prefill = [];
		$codice_cookie = $prefill['codice_cookie'];
		$new_prefill['codice_cookie'] = $codice_cookie;
		
		if(empty(self::getPrefillDB($codice_cookie))) 
			CookieDB::create(['codice_cookie' => $codice_cookie, 'valore' => serialize($prefill)]);
		else
			CookieDB::where('codice_cookie', $codice_cookie)->update(['valore' => serialize($prefill)]);
		
		return $new_prefill;
		
	}

	/**
	 * [getPrefillDB Legge il prefill nel DB usando "codice_cookie" come chiave e ritorna il prefill che contiene TUTTO L'ARRAY PREFILL con cui fare la precompilazione]
	 * @param  [type] $codice_cookie [description]
	 * @return [type]                [description]
	 */
	
	public static function getPrefillDB($codice_cookie)
	{
		try 
		{
			$row = CookieDB::findByCodice($codice_cookie)->first();

			if(is_null($row))
				return [];

			$prefill = unserialize($row->valore);

			if (!array_key_exists('whatsapp', $prefill))
				$prefill["whatsapp"] = 0;

			if (!array_key_exists('flex_date', $prefill))
				$prefill["flex_date"] = 0;

			return $prefill;
		} 
		catch (\Exception $e) 
		{
			config('app.debug_log') ? Log::emergency("\n".'---> Errore getPrefillDB <---'. $e->getMessage() . "\n\n") : "";
			return [];
		}
	}

	public static function getCookieLocalita()
	{

		$cookie = env("COOKIE_VERSION", 0);
		return Cookie::get("localita_v" . $cookie);

	}

	// Setto il cookie localita
	public static function setCookieLocalita($localita_id)
	{

		$cookie = env("COOKIE_VERSION", 0);
		$cookieLocalita = Cookie::get("localita_v" . $cookie);

		if (is_array($cookieLocalita))
			$cookieLocalita = "0," . implode(",", $cookieLocalita);

		$cookieLocalita = explode(",", $cookieLocalita);
		array_push($cookieLocalita, $localita_id);
		$cookieLocalita = array_unique($cookieLocalita);
		$cookieLocalita = implode("," , $cookieLocalita);

		return Cookie::make("localita_v" . $cookie, $cookieLocalita , 60*24*7);

	}


}

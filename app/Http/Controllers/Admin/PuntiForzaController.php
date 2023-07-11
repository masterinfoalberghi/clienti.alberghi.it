<?php

/**
 * StatsMailSchedaController
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Controllers\Admin;

use App\PuntoForza;
use App\PuntoForzaLingua;
use App\Utility;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Langs;
use SessionResponseMessages;
use Illuminate\Support\Facades\Cache;

class PuntiForzaController extends AdminBaseController
{
	

  
	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( VIEWS )
	 * ------------------------------------------------------------------------------------ */


	
	 
	/**
	 * Mostra l'interfaccia per l'editing.
	 * 
	 * @access public
	 * @return View
	 */
	 
	public function edit()
	{
		$hotel_id = $this->getHotelId();

		$punti_forza = PuntoForza::where("hotel_id", $hotel_id)
			->with("puntiDiForza_lingua")
			->get();

		$data = [];
		$i = 1;
		foreach ($punti_forza as $punto_forza) {
			$data["evidenza".$i] = $punto_forza->evidenza;
			foreach ($punto_forza->puntiDiForza_lingua as $punto_forza_lingua)
				$data["punti_forza"][$punto_forza_lingua->lang_id."_".$punto_forza->position] = $punto_forza_lingua->nome;
			$i++;
		}

		/**
		 * Passo alla vista anche il numero massimo di Punti di Forza consentiti
		 */
		 
		$data["MAX_ALLOWED"] = PuntoForza::MAX_ALLOWED;

		$allowPoint =  Utility::getPtiForzaGrouped(1);
		$allowPointLang = array();
		$allowPointLang["it"] = "";
		$allowPointLang["en"] = "";
		$allowPointLang["fr"] = "";
		$allowPointLang["de"] = "";

		foreach ($allowPoint as $ap)
			$allowPointLang[$ap->lang_id] .= "'" . str_replace("'" , "\'", $ap->nome) . "',";

		return view('admin.punti-forza', compact("data", "allowPointLang"));
		
	}

	
	
  
	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI ( CONTROLLERS )
	 * ------------------------------------------------------------------------------------ */



	 
	/**
	 * Salva i dati
	 * 
	 * @access public
	 * @param Request $request
	 * @return void
	 */
	 
	public function store(Request $request)
	{


		$traduci = $request->get("salvatraduci");

		//dd($request->all());



		//? Verifico che ci siano ALMENO i PRIMI 6 pti di forza
		//? Le chiavi della requesta sono del tipo tag_pfit2, dove it è la lingua (en|fr|de) e SE NON E' compilato il n° 5 tag_pfit5 NON VIENE PASSATO



		//? Se INSERISCO UNO NUOVO DEVO FARE SALVA E TRADUCI IN MODO CHE CI SIA IL CONTROLLO SOLO SU IT

			if ($traduci == "traduci") {

				for ($i = 1; $i < 7; $i++) {
					if (!$request->has('tag_pfit'. $i)) {
						SessionResponseMessages::add("error", "Sono obbligatori i PRIMI 6 PUNTI in tutte le lingue! Era mancante il punto $i in lingua it");
						return SessionResponseMessages::redirect("admin/punti-forza", $request);
					}
				}

			} else {
				
				foreach (['it','en','fr','de'] as $lang) {
					for ($i=1; $i < 7; $i++) { 
						if (!$request->has('tag_pf'.$lang.$i)) {
							SessionResponseMessages::add("error", "Sono obbligatori i PRIMI 6 PUNTI in tutte le lingue! Era mancante il punto $i in lingua $lang");
							return SessionResponseMessages::redirect("admin/punti-forza", $request);
						}
					}
				}
			
			}		



	   /**
		* Cancello sempre tutti i record e li inserisco nuovamente
     	*/

		
		$hotel_id = $this->getHotelId();
        Utility::clearCacheHotel($hotel_id);

		$punti_forza = PuntoForza::where("hotel_id", $hotel_id)->get();
	
		
		foreach ($punti_forza as $punto_forza) {
			$punto_forza->delete();
		}

		/**
		 * Ciclo su tutte le posizioni aspettate
		 */
		
		//echo "<pre>" . print_r($request->all(),1) . "</pre>";
		
		for ($i = 1; $i <= PuntoForza::MAX_ALLOWED; $i++) {

			// Inserisco sempre il record "master" (mi costa una query)
			$punto_forza = new PuntoForza;
			$punto_forza->hotel_id = $hotel_id;
			$punto_forza->position = $i;
			if ($request->get("evidenza" . $i))
				$punto_forza->evidenza = $request->get("evidenza" . $i);
			else
				$punto_forza->evidenza = 0;
			$punto_forza->save();

			/*
			 * Ora popolo un array con le traduzioni nelle varie lingue,
			 * in questo modo posso fare una unica query di bulk insert
			 * http://stackoverflow.com/questions/12702812/bulk-insertion-in-laravel-using-eloquent-orm
			 */
			 
			$punti_forza_lingua = [];

			/**
			 * voglio il valore dell'italiano per utilizzarlo come sorgente
			 */
			 
			$key_s = "tag_pfit{$i}";
			$sorgente = $request->input($key_s);

			foreach (Langs::getAll() as $lang) {

				/**
				 * Prendo il valore dal post
				 */
				 
				$value = '';
				$key = "tag_pf{$lang}{$i}";

				/*
				 * The has method returns true
				 * if the value is present and is not an empty string:
        		 */

				$value = $request->input($key);
				
				/**
				 *  se la traduzione non c'è oppure o cliccato la forzatura
				 */
				 
				if (($value == '' && $lang != 'it') || $traduci == "traduci")
					$value = Utility::translate($sorgente, $lang);

				$punti_forza_lingua[] = ["master_id" => $punto_forza->id, "nome" => $value, "lang_id" => $lang];
				
			}

			/**
			 * Mi costa una unica query di bulk insert
			 */
			 
			PuntoForzaLingua::insert($punti_forza_lingua);
		}

		SessionResponseMessages::add("success", "Modifiche salvate con successo.");
		return SessionResponseMessages::redirect("/admin/punti-forza", $request);
		
	}


}

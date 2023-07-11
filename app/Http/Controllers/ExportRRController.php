<?php

namespace App\Http\Controllers;

use App\CategoriaServizi;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Poi;
use App\Servizio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportRRController extends Controller
{

	private $separ1 = "£"; // alcini servizi hanno dentro il carattere | (è usato come sepratore per la visualizzazione mobile)
	private $separ2 = "§"; 
	private $locale = 'it';

	private $foto_path = "http://www.info-alberghi.com/images/gallery/800x538/";


	public function __construct() {
		// Temporarily increase memory limit to 
		ini_set('memory_limit','1024M');
		set_time_limit(600);
	}




	private function _salva($content="")
		{
		Storage::disk('local')->put('hotels_IA.csv',$content);
		}




	// la lista di servizi deve essere del tipo
	// <ul>
	// <li>servizio1</li>
	// <li>servizio2</li>
	// ....
	// </ul>
	private function getList($array_values =  [])
		{
			$gruppo = "";

				if (count($array_values))
					{
					$gruppo .= "<ul>";
					foreach ($array_values as $value) 
						{
						$gruppo .= "<li>" . $value . "</li>";
						}
					$gruppo .= "</ul>";	
					}

			return $gruppo;
		}


	private function getServiziCategoria($id_cliente=0,$servizi_ids=[])
		{
		
		
		$categorie = CategoriaServizi::with([
			'servizi',
			'servizi.servizi_lingua' => function ($query)
			{
				$query->where('lang_id', '=', $this->locale);
			},
			'servizi.gruppo',
			'serviziPrivati' => function ($query) use ($id_cliente)
			{
				$query->with(['servizi_privati_lingua' => function($q)
					{
						$q->where('lang_id', '=', $this->locale); //constraint on servizi_privati_lingua
					}])
				->where('hotel_id', $id_cliente); //constraint on serviziPrivati
			}

			])
		->orderBY('position')->get();


		$cat_servizi = array();

		foreach ($categorie as $categoria) {
			$servizi_associati = array();
			foreach ($categoria->servizi as $servizio) {
				// se è un servizio associato
				if (in_array($servizio->id, $servizi_ids)) {
					$servizi_associati[] = $servizio->servizi_lingua->first()->nome;
				}
			}

			foreach ($categoria->serviziPrivati as $servizioPriv) {
				if (isset($servizioPriv->servizi_privati_lingua->first()->nome))
					$servizi_associati[] = $servizioPriv->servizi_privati_lingua->first()->nome;
			}

			$cat_servizi[$categoria->nome] = $servizi_associati;
		}

		return $cat_servizi;
		}


	private function getServiziForTag()
		{
			// All inclusive (scheda)
			// Animali ammessi 150
			// Benessere 202
			// Capodanno  (scheda)
			// Celiaci 182
			// Disabili 151
			// Dormire e colazione (scheda)
			// Famiglia / culla 143
			// Mezza pensione (scheda)
			// Pasqua (scheda)
			// Pensione completa (scheda)
			// Piscina 200

		$servizi_for_tag_ids = array(150,202,182,151,143,200);
	

		return $servizi_for_tag_ids;
		}

	///////////////////////////////
	// separatore campi "|"			//
	// separatore sottocampi "§" //
	///////////////////////////////
	public function export()
		{
		
		$array_servizi_for_tag = self::getServiziForTag();


		$hotels = Hotel::with([
							'servizi.servizi_lingua' => function($query)
							{
								$query
								->where('lang_id', '=', $this->locale);
							},
							'poi',
							'serviziPerBambini',
							'puntiDiForza.puntiDiForza_lingua' => function($query)
							{
								$query->where('lang_id', '=', $this->locale);
							},
							])
		//->where('id',181)
		->attivo()->get()->take(100);






		$hotel_columns = DB::getSchemaBuilder()->getColumnListing('tblHotel');

		$nomi_poi = Poi::pluck('nome')->toArray();



		///////////////////////////////
		// creo la PRIMA RIGA HEADER //
		///////////////////////////////
		$export_data = implode($this->separ1,$hotel_columns);

		$export_data .= $this->separ1.'FOTO';


		// aggiungo gli header delle categorie
		$categorie = CategoriaServizi::orderBY('position')->pluck('nome');


		foreach ($categorie as $value) 
			{
			$export_data .= $this->separ1.strtoupper($value);
			}

		$export_data .= $this->separ1.'PUNTI DI FORZA';
		$export_data .= $this->separ1.'SERVIZI TAG';

		// aggiungo i nomi dei POI negli header
		foreach ($nomi_poi as $nome) 
			{
			$export_data .= $this->separ1.$nome;
			}


		$export_data .= "\n"; 		
		////////////////////////////////////
		// FINE creo la PRIMA RIGA HEADER //
		////////////////////////////////////

		foreach ($hotels as $hotel) 
			{
			
			////////////////////////
			// DATI SCHEDA HOTEL  //
			////////////////////////
			foreach ($hotel_columns as $column_name) 
				{
				
				// NON ESPORTO LE COLONNE tmp_punti_di_forza (che contengono valori con la ',')
				if (preg_match("/^tmp_punti_di_forza/", $column_name) > 0)
					$export_data .=  ''.$this->separ1;
				else
					$export_data .=  $hotel->$column_name.$this->separ1;

				}
			$export_data = rtrim($export_data,$this->separ1);
			
			//////////////////////////////
			// FINE DATI SCHEDA HOTEL  //
			/////////////////////////////



			//////////
			// FOTO //
			//////////

			$foto = $hotel->immaginiGallery->pluck('foto')->toArray();
			
			if (!is_null($foto) && count($foto)) 
				{
				// aggiungo il path ad ogni foto e le separo con $this->separ2
				$foto_group = $this->foto_path . implode($this->separ2.$this->foto_path,$foto);

				$export_data .=  $this->separ1. $foto_group;
				} 
			else 
				{
				$export_data .=  $this->separ1. '';
				}

			///////////////
			// FINE FOTO //
			//////////////

		
			////////////////////////////////////////
			// SERVIZI RAGGRUPPATI PER CATEGORIA  //
			////////////////////////////////////////
			$id_cliente = $hotel->id;
			$servizi_ids = $hotel->servizi()->nuovo()->get()->pluck('id')->toArray(); 

			$categorie_servizi_hotel = self::getServiziCategoria($id_cliente, $servizi_ids);

			if (count($categorie_servizi_hotel))
				{
				foreach ($categorie_servizi_hotel as $serv_group) 
					{
					$export_data .=  $this->separ1. $this->getList($serv_group);
					}
				}
			else
				{
				$export_data .=  $this->separ1. '';
				}

			/////////////////////////////////////////////
			// FINE SERVIZI RAGGRUPPATI PER CATEGORIA  //
			/////////////////////////////////////////////



			////////////////////
			// PUNTI DI FORZA //
			////////////////////

			$array_punti_di_forza = [];
			foreach ($hotel->puntiDiForza as $puntiDiForza)
				{
				$array_punti_di_forza[] = $puntiDiForza->puntiDiForza_lingua->first()->nome;
				}

			$export_data .=  $this->separ1. $this->getList($array_punti_di_forza);

			/////////////////////////
			// FINE PUNTI DI FORZA //
			////////////////////////


			////////////////////////
			// SERVIZI PER TAG WP //
			////////////////////////

			// l'ultimo campo deve avere gli ID dei servizi associati a questo hotel tra i seguenti
			// All inclusive
			// Animali
			// Benessere
			// Capodanno
			// Celiaci
			// Disabili
			// Dormire e colazione
			// Famiglia
			// Mezza pensione
			// Pasqua
			// Pensione completa
			// Piscina


			$array_servizi = [];
	
			foreach ($hotel->servizi as $servizio_hotel) 
				{
				if (in_array($servizio_hotel->id,$array_servizi_for_tag))
					{
					$array_servizi[] = $servizio_hotel->id;
					}
				}

			$servizi_tag_group = implode($this->separ2,$array_servizi);
			$export_data .=  $this->separ1. $servizi_tag_group;	


			///////////////////////////////
			// PUNTI DI INTERESSI  - POI //
			///////////////////////////////

			$array_poi = [];
			$poi = $hotel->poi;

			foreach ($poi as $p)
				{
			  $array_poi[$p->nome] = $p->pivot->distanza;
				}

			// un hotel potrebbe per errore non avere associato un poi ??
			foreach ($nomi_poi as $nome) 
				{
				isset($array_poi[$nome]) ? $export_data .=  $this->separ1. $array_poi[$nome] : $this->separ1. 0; 
				}


			/////////////////////////////////////
			// FINE PUNTI DI INTERESSI  - POI ///
			/////////////////////////////////////


			$export_data .=  "\n";

			} // foreach hotel

		
			self::_salva($export_data);

		/*return response($export_data)
		  ->header('Content-Type','application/csv')               
		  ->header('Content-Disposition', 'attachment; filename="hotels_IA.csv"')
		  ->header('Pragma','no-cache')
		  ->header('Expires','0');*/    
		
		}



}

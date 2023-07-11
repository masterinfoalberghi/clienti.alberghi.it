<?php

namespace App\Http\Controllers;

# Includes the autoloader for libraries installed with composer
//require __DIR__ . '/vendor/autoload.php';

use App\DescrizioneHotel;
use App\DescrizioneHotelLingua;
use App\Hotel;
use Google\Cloud\Translate\TranslateClient;
use Illuminate\Http\Request;

class TranslateController extends Controller
{
  public function index()
  	{



  		$static_translation = [];

			$static_translation['DESCRIZIONE'] = 'DESCRIPTION';
			$static_translation['POSIZIONE'] = 'LOCATION';
			$static_translation['CAMERE'] = 'ROOMS';
			$static_translation['SERVIZI'] = 'SERVICES';
			$static_translation['CUCINA'] = 'FOOD';
			$static_translation['BAMBINI'] = 'KIDFS';
			$static_translation['PISCINA'] = 'POOL';
			$static_translation['SPIAGGIA'] = 'BEACH';

  		$credentialFile= base_path('GoogleTranslateClient.json');

  		putenv("GOOGLE_APPLICATION_CREDENTIALS=$credentialFile");

  		# Your Google Cloud Platform project ID
  		$projectId = 'api-project-868696641806';

  		# Instantiates a client
  		$translate = new TranslateClient([
  		    'projectId' => $projectId
  		]);


  		
  		$hotel_ids = Hotel::whereHas('localita.macrolocalita', function ($query) {
  		    $query->where('tblMacrolocalita.id', 9)->orWhere('tblMacrolocalita.id', 10);
  		})->where('attivo',1)->pluck('id')->toArray();


  		//dd(str_replace_array('?', $hotel_ids->getBindings(), $hotel_ids->toSql()));



  		$descrizioni_hotel = DescrizioneHotel::with(
  													[
  													'descrizioneHotel_lingua'  => function ($query) {
														    $query->where('lang_id', 'it');
														},
  													]
  												)
  												->whereIn('hotel_id',$hotel_ids)
  												//->limit(2)
  												->get();

  		//dd($descrizioni_hotel);


  		$traduzioni_hotel = [];

  		foreach ($descrizioni_hotel as $descrizioni) 
  			{

	  		$descrizioni_json = json_decode($descrizioni->descrizioneHotel_lingua->first()->testo);

	  		//dd($descrizioni_json);

	  		$source='it';
				# The target language
				$target = 'en';
			
	  		foreach ($descrizioni_json as $key => $desc) 
	  			{

	  			# The text to translate
	  			$testo = $desc->testo;

	  			$translation = $translate->translate($testo, [
	  					'source' => $source,
	  			    'target' => $target
	  			]);


	  			$desc->testo = $translation['text'];

	  			$desc->title = array_key_exists($desc->title, $static_translation) ? $static_translation[$desc->title] : $desc->title;
	  			$desc->subtitle = array_key_exists($desc->subtitle, $static_translation) ? $static_translation[$desc->subtitle] : $desc->subtitle;

	  			}

	  			$traduzioni_hotel[$descrizioni->id] = $descrizioni_json;

	  	
  			}

  			$i=0;
  			foreach ($traduzioni_hotel as $master_id => $traduzione) 
  				{
  				DescrizioneHotelLingua::where('master_id', $master_id)
  				          ->where('lang_id', 'en')
  				          ->update(['testo' => json_encode($traduzione)]);

  				echo "Aggiornati n. hotel $i<br>";

  				$i++;
  				}



  	} 
}

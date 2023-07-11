<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Localita;
use App\APIHotels;
use Illuminate\Http\Request;

class ExportAPIController extends Controller
{
	
    public function export( $task )
	{
		
		if ($task == "hotel") {
			
			$hotels = Hotel::attivo()->orderBy("id")->get();
			$apiHotels = new APIHotels();
			$apiHotels->setConnection("api");
			
			foreach($hotels as $hotel):
				
				echo $hotel->id . "<br />";
				
				$apiHotels->updateOrCreate(
					["hotel_id" => $hotel->id],
					["hotel_id" => $hotel->id, "hotel_name" => $hotel->nome, "hotel_location" => Localita::find($hotel->localita_id)->nome, "active" => $hotel->attivo]
				);
				
			endforeach;
			
		}
		
	}
}

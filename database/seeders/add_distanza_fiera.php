<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Hotel;
use App\Poi;




class add_distanza_fiera extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $hotels = Hotel::all();
        
        $poiFiera = Poi::where("id", 9)->get(); // Fiera di Rimini
         
        foreach($hotels as $hotel):
        	
        	if ($hotel->attivo):
        		
		        	$distanza_fiera = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $poiFiera[0]->lat, $poiFiera[0]->long);
					$hotel->distanza_fiera = round($distanza_fiera / 1000,2);
			else:
			
				$hotel->distanza_fiera = 0;
			
			endif;
			$hotel->save();
			
        endforeach;
        
    }
}

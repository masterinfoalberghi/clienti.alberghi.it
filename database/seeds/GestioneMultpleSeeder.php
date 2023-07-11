<?php

use App\Hotel;
use App\GestioneMultiple;
use Illuminate\Database\Seeder;

class GestioneMultpleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
        $hotels = Hotel::all();
        foreach($hotels as $hotel):
        	GestioneMultiple::insert(["hotel_id" => $hotel->id, "contact" => 0]);
        endforeach;
    }
}

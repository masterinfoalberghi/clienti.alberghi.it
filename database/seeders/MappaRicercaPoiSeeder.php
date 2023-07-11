<?php

namespace Database\Seeders;

use App\MappaRicercaPoi;
use App\MappaRicercaPoiLang;
use Illuminate\Database\Seeder;

class MappaRicercaPoiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	/* Fiera di Rimini */
    	$p = new MappaRicercaPoi;
    	$p->lat = 44.0697014;
    	$p->long = 12.5265833;
    	$p->save();

  		$pl = new MappaRicercaPoiLang;
  		$pl->master_id = $p->id;
  		$pl->lang_id = 'it';
  		$pl->nome = 'Fiera di Rimini';
  		$pl->info_titolo = 'Fiera di Rimini';
  		$pl->info_desc = 'Fiera di Rimini';
  		$pl->save();

  		$pl = new MappaRicercaPoiLang;
  		$pl->master_id = $p->id;
  		$pl->lang_id = 'en';
  		$pl->nome = 'Rimini Fair';
  		$pl->info_titolo = 'Rimini Fair';
  		$pl->info_desc = 'Rimini Fair';
  		$pl->save();

  		$pl = new MappaRicercaPoiLang;
  		$pl->master_id = $p->id;
  		$pl->lang_id = 'fr';
  		$pl->nome = 'Rimini Fair';
  		$pl->info_titolo = 'Rimini Fair';
  		$pl->info_desc = 'Rimini Fair';
  		$pl->save();

  		$pl = new MappaRicercaPoiLang;
  		$pl->master_id = $p->id;
  		$pl->lang_id = 'de';
  		$pl->nome = 'Rimini Messe';
  		$pl->info_titolo = 'Rimini Messe';
  		$pl->info_desc = 'Rimini Messe';
  		$pl->save();
    

    	/* Italia in Miniatura */	
	  	$p = new MappaRicercaPoi;
	  	$p->lat = 44.0902546;
	  	$p->long = 12.5136397;
	  	$p->save();

			$pl = new MappaRicercaPoiLang;
			$pl->master_id = $p->id;
			$pl->lang_id = 'it';
			$pl->nome = 'Italia in Miniatura';
			$pl->info_titolo = 'Italia in Miniatura';
			$pl->info_desc = 'Italia in Miniatura';
			$pl->save();

			$pl = new MappaRicercaPoiLang;
			$pl->master_id = $p->id;
			$pl->lang_id = 'en';
			$pl->nome = 'Italy in Miniature';
			$pl->info_titolo = 'Italy in Miniature';
			$pl->info_desc = 'Italy in Miniature';
			$pl->save();

			$pl = new MappaRicercaPoiLang;
			$pl->master_id = $p->id;
			$pl->lang_id = 'fr';
			$pl->nome = 'L\'Italie en miniature';
			$pl->info_titolo = 'L\'Italie en miniature';
			$pl->info_desc = 'L\'Italie en miniature';
			$pl->save();

			$pl = new MappaRicercaPoiLang;
			$pl->master_id = $p->id;
			$pl->lang_id = 'de';
			$pl->nome = 'Italien in Miniatur';
			$pl->info_titolo = 'Italien in Miniatur';
			$pl->info_desc = 'Italien in Miniatur';
			$pl->save();


				/* Mirabilandia */	
	  	$p = new MappaRicercaPoi;
	  	$p->lat = 44.335871;
	  	$p->long = 12.2669268;
	  	$p->save();

			$pl = new MappaRicercaPoiLang;
			$pl->master_id = $p->id;
			$pl->lang_id = 'it';
			$pl->nome = 'Mirabilandia';
			$pl->info_titolo = 'Mirabilandia';
			$pl->info_desc = 'Mirabilandia';
			$pl->save();

			$pl = new MappaRicercaPoiLang;
			$pl->master_id = $p->id;
			$pl->lang_id = 'en';
			$pl->nome = 'Mirabilandia';
			$pl->info_titolo = 'Mirabilandia';
			$pl->info_desc = 'Mirabilandia';
			$pl->save();

			$pl = new MappaRicercaPoiLang;
			$pl->master_id = $p->id;
			$pl->lang_id = 'fr';
			$pl->nome = 'Mirabilandia';
			$pl->info_titolo = 'Mirabilandia';
			$pl->info_desc = 'Mirabilandia';
			$pl->save();

			$pl = new MappaRicercaPoiLang;
			$pl->master_id = $p->id;
			$pl->lang_id = 'de';
			$pl->nome = 'Mirabilandia';
			$pl->info_titolo = 'Mirabilandia';
			$pl->info_desc = 'Mirabilandia';
			$pl->save();


    }
}

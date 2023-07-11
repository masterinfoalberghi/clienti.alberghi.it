<?php

namespace Database\Seeders;

use App\GruppoServizi;
use App\Utility;
use Illuminate\Database\Seeder;

class GruppoServiziRicercaMappaSeeder extends Seeder
{

    private static $gruppi_ricerca_mappa = ['Celiaci', 'Animali ammessi','Benessere','Piscina','Parcheggio','Disabili'];
	 private static $gruppi_ricerca_lang = ['en', 'fr','de'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     $gs = GruppoServizi::all();
     foreach ($gs as $g) 
     	{
     	if (in_array($g->nome,self::$gruppi_ricerca_mappa)) 
     		{
     		$g->ricerca_mappa = 1;
        }
      foreach (self::$gruppi_ricerca_lang as $lang) 
        {
        $col = 'nome_'.$lang;
        $g->$col = Utility::translate($g->nome, $lang);
        }
     	$g->save();
      }
    }
}

<?php
namespace Database\Seeders;

use App\GruppoServizi;
use App\Utility;
use Illuminate\Database\Seeder;

class GruppoServiziRicercaMappaNomeItSeeder extends Seeder
{

    private static $gruppi_ricerca_mappa = ['Celiaci', 'Animali ammessi','Benessere','Piscina', 'Palestra', 'Giardino',  'Parcheggio','Disabili', 'Congressi'];



    private static $gruppi_ricerca_mappa_it = [
        'Celiaci' => 'Pasti per celiaci|4', 
        'Animali ammessi' => 'Animali ammessi|6',
        'Benessere' => 'Spa / Centro benessere|2',
        'Piscina' => 'Piscina|1', 
        'Palestra' => 'Palestra|8', 
        'Giardino' => 'Giardino|7',
        'Parcheggio' => 'Parcheggio|5',
        'Disabili' => 'Servizi per disabili|3', 
        'Congressi' => 'Sala congressi|9',
        'Bike' => 'Bike hotel|0',
    ];

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
      
        // riazzero tutto
        $g->ricerca_mappa = 0;
     	
        if (in_array($g->nome,self::$gruppi_ricerca_mappa)) 
     		{
     		$g->ricerca_mappa = 1;
            }

        if (array_key_exists($g->nome,self::$gruppi_ricerca_mappa_it))
            {
            $val = self::$gruppi_ricerca_mappa_it[$g->nome];
            list($nome, $order) = explode('|',$val);
            $g->nome_it = $nome;
            $g->order_ricerca_mappa = $order;
            }
        else
            {
            $g->nome_it = $g->nome;
            }

        foreach (self::$gruppi_ricerca_lang as $lang) 
            {
            $col = 'nome_'.$lang;
            $g->$col = Utility::translate($g->nome_it, $lang);
            }
        
         $g->save();
      
      }
    }
}

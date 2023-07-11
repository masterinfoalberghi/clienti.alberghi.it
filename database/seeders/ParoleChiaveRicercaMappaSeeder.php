<?php


namespace Database\Seeders;

use App\ParolaChiave;
use App\Utility;
use Illuminate\Database\Seeder;

class ParoleChiaveRicercaMappaSeeder extends Seeder
{


		private static $servizi_ricerca_mappa_it = [
		        '68' => 'Pasqua|1', 
		     		'11' => '25 Aprile|2',
		     		'6' => '1 Maggio|3',
		     		'4' => '2 Giugno|4',
		     		'26' => 'Notte rosa|5',
		     		'10' => 'Maggio|6',
		     		'9' => 'Giugno|7',
		     		'8' => 'Luglio|8',
		     		'7' => 'Agosto|9',
		     		'7' => 'Agosto|10',
		     		'25' => 'Ferragosto|11',
		     		'25' => 'Ferragosto|11',
		     		'22' => 'Terme|13',
		     		'64' => 'Fiera|14',
		    ];


		private static $servizi_ricerca_lang = ['en', 'fr','de'];



    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$parole =  ParolaChiave::all();
    	foreach ($parole as $p) 
    		{

	        // riazzero tutto
	        $p->ricerca_mappa = 0;


	        if (array_key_exists($p->id,self::$servizi_ricerca_mappa_it))
	            {
	            $p->ricerca_mappa = 1;
	            $val = self::$servizi_ricerca_mappa_it[$p->id];
	            list($nome, $order) = explode('|',$val);
	            $p->nome_it = $nome;
	            $p->order_ricerca_mappa = $order;

	            foreach (self::$servizi_ricerca_lang as $lang) 
		            {
		            $col = 'nome_'.$lang;
		            $p->$col = Utility::translate($p->nome_it, $lang);
		            }
		          $p->save();

	            }

    		}
    }
}

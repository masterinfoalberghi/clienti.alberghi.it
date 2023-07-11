<?php


namespace Database\Seeders;



use App\Servizio;
use Illuminate\Database\Seeder;

class ServiziRicercaMappaSeeder extends Seeder
{

	private static $servizi_ricerca_mappa_it = [
	        '205' => 'Idromassaggio|1', 
	     		'132' => 'WiFi gratis|2',
	     		'187' => 'Aria condizionata camera|3',
	     		'139' => 'Biciclette gratis|4',
	     		'160' => 'Portiere notturno|5'
	    ];


	private static $servizi_ricerca_lang = ['en', 'fr','de'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     $servizi =  Servizio::all();
     foreach ($servizi as $s) 
     	{
      
        // riazzero tutto
        $s->ricerca_mappa = 0;


        if (array_key_exists($s->id,self::$servizi_ricerca_mappa_it))
            {
            $s->ricerca_mappa = 1;
            $val = self::$servizi_ricerca_mappa_it[$s->id];
            list($nome, $order) = explode('|',$val);
            $s->nome_it = $nome;
            $s->order_ricerca_mappa = $order;

            foreach (self::$servizi_ricerca_lang as $lang) 
	            {
	            $col = 'nome_'.$lang;
	            $s->$col = Utility::translate($s->nome_it, $lang);
	            }
	          $s->save();

            }
     
    	}
  	}
}

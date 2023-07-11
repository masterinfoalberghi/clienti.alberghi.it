<?php
namespace Database\Seeders;

use App\CategoriaServizi;
use App\Servizio;
use App\ServizioLingua;
use App\Utility;
use Illuminate\Database\Seeder;



class ancoraServiziDisabili extends Seeder
{
    /**
     * Run the database seeds.
     *
     * ROLLBACK
     *
     * 
     * 1) delete from tblServiziLang where master_id IN ( select id from tblServizi where categoria_id in (12,13,14,15,16) ) 
     *
     * 2) delete from tblServizi where categoria_id in (12,13,14,15,16) 
     *
     * 3) delete from tblCategoriaServizi where id in (12,13,14,15,16)
     *
     *
     * 
     * @return void
     */
    public function run()
    {


	    /////////////////////////////////////////////////////////////////////////////////
    	// aggiorno la posizione dei servizi listing che devono sempre rimanere ULTIMI //
	    /////////////////////////////////////////////////////////////////////////////////
    	CategoriaServizi::where('nome','ListingPiscina')->update(['position' => 90]);
    	CategoriaServizi::where('nome','ListingCentroBenessere')->update(['position' => 100]);



    	$nuove_categorie_disabili = ['Accessibilità hotel', 'Accesso camere', 'Camera accessibile', 'Bagno con WC con maniglioni, lavabo più basso, spazi di manovra e'];
    	


	    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	// per ogni categoria inserisco i servizi in italiano, le traduzioni saranno fatte da admin successivamente  //
	    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	$servizi_categoria = [];

    	$servizi[] = 'ingresso con rampa inclinata';
    	$servizi[] = 'ingresso situato al piano terra';
    	$servizi[] = 'ingresso con piattaforma elevatrice';
    	$servizi_categoria[] = $servizi;

    	unset($servizi);
    	$servizi[] = 'ascensore a norma per disabili';
    	$servizi[] = 'camera situata al piano terra';
    	$servizi[] = 'presenza piattaforma elevatrice interna';
    	$servizi_categoria[] = $servizi;


    	unset($servizi);
			$servizi[] = 'spazio di manovra per sedia a rotelle';
    	$servizi[] = 'spazio di manovra per sedia a rotelle e ausili (maniglie a x cm, interruttori a x cm ecc..)';
    	$servizi_categoria[] = $servizi;    	


    	unset($servizi);
    	$servizi[] = 'vasca da bagno per disabili';
    	$servizi[] = 'doccia con accesso per sedie a rotelle';
    	$servizi[] = 'sedia per doccia';
    	$servizi_categoria[] = $servizi;


    	/**
    	 *
    	 * php artisan db:seed --class=ancoraServiziDisabili
    	 * Array
					(
					    [0] => Array
					        (
					            [0] => ingresso con rampa inclinata
					            [1] => ingresso situato al piano terra
					            [2] => ingresso con piattaforma elevatrice
					        )

					    [1] => Array
					        (
					            [0] => ascensore a norma per disabili
					            [1] => camera situata al piano terra
					            [2] => presenza piattaforma elevatrice interna
					        )

					    [2] => Array
					        (
					            [0] => spazio di manovra per sedia a rotelle
					            [1] => spazio di manovra per sedia a rotelle e ausili (maniglie a x cm, interruttori a x cm ecc..)
					        )

					    [3] => Array
					        (
					            [0] => vasca da bagno per disabili
					            [1] => doccia con accesso per sedie a rotelle
					            [2] => sedia per doccia
					        )

					)



    	 * 
    	 */


    	$pos_init = 9;
    	foreach ($nuove_categorie_disabili as $key => $cat) 
    		{

    		$pos = $pos_init+$key;
    		
            $categoria_servizio = CategoriaServizi::create(['nome' => $cat, 'position' => $pos, 'tipo' => 'disabili']);
    		
		    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    		// questi servizi non sono in nesun gruppo di ricerca; solo il "servizio master" "servizi per disabili" lo è //
		    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    		$gruppo_servizio = 0;  		

        foreach ($servizi_categoria[$key] as $nome_servizio) 
          {
     
                    
            $servizio = Servizio::create(['gruppo_id' => $gruppo_servizio, 'categoria_id' => $categoria_servizio->id ]);
            
            $servizio_lingua = array();

            $servizio_lingua[] = ["master_id" => $servizio->id, "nome" => $nome_servizio, "lang_id" => 'it'];
            
            /*
              Utilizzo le API di google translate per tradurre il nom nelle lingue
            */
            foreach (Utility::linguePossibili() as $lingua) 
              {
              if ($lingua != 'it') 
                {
                $servizio_lingua[] = ["master_id" => $servizio->id, "nome" => Utility::translate($nome_servizio, $lingua), "lang_id" => $lingua];
               }
              }

            ServizioLingua::insert($servizio_lingua);
            
          }

    		} /*end nuove_categorie_disabili*/

    
    } /* end run()*/
} /* end class*/

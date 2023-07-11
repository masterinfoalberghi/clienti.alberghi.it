<?php

use App\CategoriaServizi;
use App\GruppoServizi;
use App\Servizio;
use App\ServizioLingua;
use Illuminate\Database\Seeder;

class addServiziDisabiliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
	    {
	    /////////////////////////////
	    // creo la nuova categoria //
	    /////////////////////////////
	    $categoria_servizio = CategoriaServizi::create(['nome' => 'Servizi per disabili']);

	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    // devo spostare il padre del servizio con nome it "servizi per disabili" in questa nuova categoria (il gruppo di ricerca è già corretto ed è "Disabili") //
	    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $servizio_lang = ServizioLingua::where('lang_id','it')->where('nome','servizi per disabili')->first();

	    if (!is_null($servizio_lang)) 
	    	{
	    	$servizio_id = $servizio_lang->master_id;
	    	$servizio = Servizio::find($servizio_id);
	    	$servizio->categoria_id = $categoria_servizio->id;
	    	$servizio->save();
	    	}	 
	    

	    }
}

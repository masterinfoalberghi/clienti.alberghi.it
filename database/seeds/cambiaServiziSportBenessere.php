<?php

use App\Servizio;
use App\ServizioLingua;
use Illuminate\Database\Seeder;

class cambiaServiziSportBenessere extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    /////////////////////////////////////////////////////////////////////////////////////////
    	// animazione in spiaggia di SPORT E BENESSERE ===> deve andare in Servizi per bambini //
	    ////////////////////////////////////////////////////////////////////////////////////////
    	$servizi_sport = Servizio::with('servizi_lingua')->where('categoria_id', 7)->get();

    	foreach ($servizi_sport as $servizio_sport) 
    		{

    		if ($servizio_sport->translate()->first()->nome == 'animazione in spiaggia') 
    			{
    			$servizio_sport->categoria_id = 3;
    			$servizio_sport->save();
    			} 
    		
    		}


        ///////////////////////////////////////////////////////////////////////
        // piscina di SPORT E BENESSERE ===> deve andare in SERVIZI IN HOTEL //
        ///////////////////////////////////////////////////////////////////////
        foreach ($servizi_sport as $servizio_sport) 
          {

          if ($servizio_sport->translate()->first()->nome == 'piscina' || $servizio_sport->translate()->first()->nome == 'terrazza solarium' || $servizio_sport->translate()->first()->nome == 'palestra' || $servizio_sport->translate()->first()->nome == 'centro benessere / spa ' || $servizio_sport->translate()->first()->nome == 'idromassaggio') 
            {
            $servizio_sport->categoria_id = 2;
            $servizio_sport->save();
            } 
          
          }


	    /////////////////////////////////////////
    	// rinomino "colazione internazionale" //
	    /////////////////////////////////////////
    	$servizi_risto = Servizio::with('servizi_lingua')->where('categoria_id', 5)->get();

    	foreach ($servizi_risto as $servizio_risto) 
    		{

    		if ($servizio_risto->translate()->first()->nome == 'colazione internazionale') 
    			{
    			foreach ($servizio_risto->servizi_lingua as $servizio_lingua) 
    				{
    				switch ($servizio_lingua->lang_id) 
    					{
    					case 'it':
    						$servizio_lingua->nome = 'colazione dolce';
                $servizio_lingua->save();
    						break;
    					case 'fr':
    						$servizio_lingua->nome = 'petit déjeuner sucré';
                $servizio_lingua->save();
    						break;
    					case 'en':
    						$servizio_lingua->nome = 'sweet breakfast';
                $servizio_lingua->save();
    						break;
    					case 'de':
    						$servizio_lingua->nome = 'süßes Frühstück';
                $servizio_lingua->save();
    						break;	
    					}
    				}

          $servizio_risto->save();

          } 
    		
    		}


        //////////////////////////////////////////////////
        // rinomino "animazione" in Servizi per bambini //
        /////////////////////////////////////////////////
        $servizi_bambini = Servizio::with('servizi_lingua')->where('categoria_id', 3)->get();

        foreach ($servizi_bambini as $servizio_bambini) 
          {

          if ($servizio_bambini->translate()->first()->nome == 'animazione') 
            {
            foreach ($servizio_bambini->servizi_lingua as $servizio_lingua) 
              {
              switch ($servizio_lingua->lang_id) 
                {
                case 'it':
                  $servizio_lingua->nome = 'animazione in hotel';
                  $servizio_lingua->save();
                  break;
                case 'fr':
                  $servizio_lingua->nome = 'animation à l\'hôtel';
                  $servizio_lingua->save();
                  break;
                case 'en':
                  $servizio_lingua->nome = 'animation at the hotel';
                  $servizio_lingua->save();
                  break;
                case 'de':
                  $servizio_lingua->nome = 'Animation im Hotel';
                  $servizio_lingua->save();
                  break;  
                }
              }

            $servizio_bambini->save();

            } 
          
          }


	    /////////////////////////////////////////
    	// aggiungo "colazione dolce e salata" //
	    /////////////////////////////////////////
      $already_exists = false;
      foreach ($servizi_risto as $servizio_risto) 
        {
        if ($servizio_risto->translate()->first()->nome == 'colazione dolce e salata') 
          {
          $already_exists = true;
          break;
          }
        }

      if(!$already_exists)
        {

      	$servizio1 = Servizio::create(['categoria_id' => 5]);

      	$servizio_lingua1 = array();
      	$servizio_lingua1[] = ["master_id" => $servizio1->id, "nome" => "colazione dolce e salata", "lang_id" => 'it'];
      	$servizio_lingua1[] = ["master_id" => $servizio1->id, "nome" => "sweet and savory breakfast", "lang_id" => 'en'];
      	$servizio_lingua1[] = ["master_id" => $servizio1->id, "nome" => "petit-déjeuner sucré et salé", "lang_id" => 'fr'];
      	$servizio_lingua1[] = ["master_id" => $servizio1->id, "nome" => "süßes und herzhaftes Frühstück", "lang_id" => 'de'];

      	ServizioLingua::insert($servizio_lingua1);
          
        }



      ///////////////////////////////////////////////
      // cancello la categoria "Sport e Benessere" //
      ///////////////////////////////////////////////

      
      /*foreach ($servizi_sport as $servizio_sport) 
        {
        $servizio_sport->delete(); 
        }*/
        


 	   }
}

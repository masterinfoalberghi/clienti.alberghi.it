<?php



namespace App\Http\Controllers;

use App\CmsPagina;
use App\Hotel;
use App\Http\Requests;
use App\ServizioLingua;

use Illuminate\Http\Request;
use App\StatsHotelMailMultipla;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

/**
 *
 *  nei listini prezzi min e max ed in quello standard trovare il minio ed il massimo e metterlo nella tabella
 */

class ProvaController extends Controller
{
	
 public function mm() {

 	set_time_limit(60); 
 	
 	/**
     * Cosa devo creare
     * 
     * hotel_id / n_mail  / giorno / mese / anno / tipologia
     */

    /**
     * Tronco la tabella delle statistiche
     */

    StatsHotelMailMultipla::query()->truncate();

    /**
     * Prendo tutti gli hotel ( anche i diasattivi ?  )
     */

    //$hotels = Hotel::attivo()->pluck("id")->get();

    /**
     * Prendo la data di partenza
     */

	$startTime = strtotime( '2018-01-07' );
	$endTime = strtotime( '2018-01-07' );

	$hotel = new \stdClass();
	$hotel->id = 17;

	//foreach($hotels as $hotel):

		// Loop between timestamps, 24 hours at a time
		for ( $i = $endTime; $i >= $startTime; $i = $i - 86400 ):

		  	$date0 =  date( 'Y-m-d 00:00:00', $i );
		  	$date24 =  date( 'Y-m-d 23:59:59', $i );

			$tipoMail = DB::table("email_multiple")
				->select("tipologia")
				->where("created_at",">=", $date0)
				->where("created_at","<=", $date24)
				->where("hotel_id", $hotel->id)
         		->get();

	        $tipologie = array();
	        $tipologie["wishlist"] = 0;
	        $tipologie["normale"] = 0;
	        $tipologie["mobile"] = 0;

	        foreach($tipoMail as $tipo) {
	        	$tipologie[$tipo->tipologia] += 1;
	        }

	        foreach($tipologie as $key => $tipologia) {
		        if ($tipologia > 0) {

					echo $hotel->id . " " . 
						 $tipologia . " " . 
						 date( 'd', $i ) . " " . 
						 date( 'm', $i ) . " " . 
						 date( 'Y', $i ) . " " . 
						 $key  . " " . 
						 date("Y-m-d") . " " . 
						 date("Y-m-d") . "<br />";
		       
		        	// DB::table("tblStatsHotelMailMultiple")->insert(
		        	// 	[
		        	// 		"hotel_id" => $hotel->id, 
		        	// 		"n_mail" => $tipoMail[0]->conteggio, 
		        	// 		"giorno" => date( 'd', $i ), 
		        	// 		"mese" => date( 'm', $i ), 
		        	// 		"anno" => date( 'Y', $i ), 
		        	// 		"tipologia" => $tipoMail[0]->tipologia, 
		        	// 		"created_at" => date("Y-m-d"), 
		        	// 		"updated_at" => date("Y-m-d")
		        	// 	]
		        	// );
		        }	
	       	}

	    endfor;

	//endforeach;

 }











 public function prezzi() {	

	$prezzi_clienti = [];
	$desinenze = ['sd','bb','mp','pc','ai'];

    $clienti = Hotel::with([
							'listini',
							'listiniMinMax'
							])
    						->attivo()
    						->get();
    

    ////////////////////////
    // prezzi dal listino //
    ////////////////////////
    
    foreach ($clienti as $cliente) 
    	{
    	if ($cliente->has('listini')) 
    		{
				$prezzi['min'] = '';
				$prezzi['max'] = '';

	    	foreach ($cliente->listini as $riga_listino) 
		    	{
		    	if ($riga_listino->attivo) 
		    		{
			    	foreach ($desinenze as $val)
			    		{
			    		// altrimenti il simbolo '/' viene castato con 0 ed il listino è tutto 0
			    		// c'è un getter che da questo valore
			    		if ($riga_listino['prezzo_'.$val] != '/') 
			    			{

				    		$myval_min = (int) $riga_listino['prezzo_'.$val];
				    		if ($myval_min < $prezzi['min'] || $prezzi['min'] == '') 
				    			{
				    			$prezzi['min'] = $myval_min;
				    			}

			    			$myval_max = (int) $riga_listino['prezzo_'.$val];
			    			if ($myval_max > $prezzi['max'] || $prezzi['max'] == '') 
			    				{
			    				$prezzi['max'] = $myval_max;
			    				}

			    			}

			    		} // end foreach

		    		} // endif attivo

		    	}
		    $prezzi_clienti[$cliente->id] = $prezzi;
    		}
    	} // endfor clienti


	    //////////////////////////////
    	// prezzi dal listinoMinMax //
	    //////////////////////////////

    	foreach ($clienti as $cliente) 
    		{
    		if ($cliente->has('listiniMinMax')) 
    			{
    			$prezzi['min'] = $prezzi_clienti[$cliente->id]['min'];
    			$prezzi['max'] = $prezzi_clienti[$cliente->id]['max'];

		    	foreach ($cliente->listiniMinMax as $riga_listino) 
			    	{
			    	if ($riga_listino->attivo) 
			    		{
				    	foreach ($desinenze as $val)
				    		{
				    		// altrimenti il simbolo '/' viene castato con 0 ed il listino è tutto 0
				    		// c'è un getter che da questo valore
				    		if ($riga_listino['prezzo_'.$val.'_min'] != '/') 
				    			{

					    		$myval_min = (int) $riga_listino['prezzo_'.$val.'_min'];
					    		if ($myval_min < $prezzi['min'] || $prezzi['min'] == '') 
					    			{
					    			$prezzi['min'] = $myval_min;
					    			}

				    			}

				    		if ($riga_listino['prezzo_'.$val.'_max'] != '/') 
				    			{

				    			$myval_max = (int) $riga_listino['prezzo_'.$val.'_max'];
				    			if ($myval_max > $prezzi['max']  || $prezzi['max'] == '') 
				    				{
				    				$prezzi['max'] = $myval_max;
				    				}

				    			}

				    		} // end foreach desinenze

			    		} // endif attivo

			    	}

			    	$prezzi_clienti[$cliente->id] = $prezzi;
    			}
    		}
		    
		    ///////////////////////////////////////////
    		// ottengo una struttura simile a questa //
		    ///////////////////////////////////////////
		    /**
		     *
		     *
		     *13 => array:2 [▼
				    "min" => 35
				    "max" => 55
				  ]
				  17 => array:2 [▼
				    "min" => 28
				    "max" => 61
				  ]
		     * 
		     */
		   

				/////////////////////////////////////////////////////////////////////
				// id degli hotel che non hanno un listino da cui leggere i prezzi //
				/////////////////////////////////////////////////////////////////////
		   	$no_listino_sorgente = [];

		   	foreach ($prezzi_clienti as $id_cliente => $prezzi) 
		   		{

		   		if ($prezzi['min'] == '' && $prezzi['max'] == '') 
		   			{
		   			$no_listino_sorgente[] = $id_cliente;
		   			} 
		   		else 
		   			{
		   			$hotel = Hotel::find($id_cliente);
			   		$hotel->prezzo_min  = $prezzi['min'];
			   		$hotel->prezzo_max  = $prezzi['max'];
			   		$hotel->save();

			   		echo $hotel->id . ": ".$hotel->prezzo_min.", ".$hotel->prezzo_max."<br>";
		   			}
		   		}

		   	echo "<br><br>CLienti che non hanno listino da cui prelevare i prezzi:<br>";
		   	foreach ($no_listino_sorgente as $id_cliente) 
		   		{
		   		echo "$id_cliente<br>";
		   		}

 	}


  

 	public function piscinaFuori()
 		{

    // pagine piscina
    $pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'it')->get();
    
    
    /**
     * URI del tipo
     * 
     //$pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'it')->pluck('uri','id');
     * 177 => "hotel-piscina/rimini-hotel-piscina.php"
     * 331 => "hotel-piscina/riccione-hotel-piscina.php"
     * 377 => "hotel-piscina/bellaria-hotel-piscina.php"
     * 386 => "hotel-piscina/lidi-ravennati-hotel-piscina.php"
     * 438 => "hotel-piscina/cesenatico-hotel-piscina.php"
     * 439 => "hotel-piscina/milano-marittima-hotel-piscina.php"
     * 486 => "hotel-piscina/cervia-hotel-piscina.php"
     * 497 => "hotel-piscina/cattolica-hotel-piscina.php"
     * 511 => "hotel-piscina/gabicce-mare-hotel-piscina.php"
     * 515 => "hotel-piscina/misano-adriatico-hotel-piscina.php"
     * 557 => "hotel-piscina/riviera-romagnola-hotel-piscina.php"
     */

    foreach ($pagine_piscina as $pagina_piscina) 
      {
      $pagina = $pagina_piscina->replicate();
      $pagina->attiva = 0;
      $new_uri = str_replace('-.php','',str_replace('hotel-piscina','', $pagina->uri));
      $pagina->uri = 'hotel-piscina-fuori-struttura'.$new_uri.'.php';
      dd($pagina);
      $pagina->save();
      }

    
 		}

}

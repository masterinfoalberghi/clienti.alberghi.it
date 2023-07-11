<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\CategoriaServizi;
use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CercaHotelRequest;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\RichiestaRicercaAvanzataRequest;
use App\Localita;
use App\Servizio;
use App\Utility;
use App\CookieIA;
use App\CmsPagina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SearchController extends Controller
  {

  private $fasce_prezzo = array();
  
  public function __construct() 
    {
    for ($i = 0; $i < 165; $i = $i + 5) 
      {
      if ($i == 0) 
        {
        $this->fasce_prezzo[$i] = 'Tutte le fasce';
        } 
      elseif ($i >= 25) 
        {
        $this->fasce_prezzo[$i] = $i - 5 . ' - ' . $i;
        }
      }
    }

	public function byName(CercaHotelRequest $request) {
	
  	 
  		return Response::make(view('errors.503'), 503);

		/*
		$hreflang_it = "http://www.info-alberghi.com/trova_hotel.php";
	    $hreflang_en = "http://www.info-alberghi.com/ing/trova_hotel.php";
	    $hreflang_fr = "http://www.info-alberghi.com/fr/trova_hotel.php";
	    $hreflang_de = "http://www.info-alberghi.com/ted/trova_hotel.php";
		
	    $nome_hotel = $request->get('nome_hotel');
	    $locale = $this->getLocale();
		$cms_pagina = new CmsPagina();
		
	    $clienti = Hotel::withListingEagerLoading($locale)
	      ->attivo()
	      ->conNome($nome_hotel)
	      ->orderBy('nome')
	      ->get();    

	    $target = trans("labels.ric_nome");
	    $sub_target = trans("labels.nome_cercato") . ": '". $nome_hotel . "'";
	    $coordinate = Utility::getGenericGeoCoords();
	    $google_maps = ["coords" => $coordinate, "hotels" => $clienti];
		$avanzata = "no";
		
	    return View::make('search.search', compact('hreflang_it','hreflang_en','hreflang_fr','hreflang_de','nome_hotel','clienti', 'target', 'sub_target', 'locale','google_maps','avanzata','cms_pagina'));      
	    */

    }

  public function notFoundByName(Request $request)
  {
  	
	return Response::make(view('errors.503'), 503);
   

  	/*
    $locale = $this->getLocale();
    $clienti = collect([]);
    $target = trans("labels.ric_nome");
    $sub_target = 'no_search';
    $coordinate = Utility::getGenericGeoCoords();
    $google_maps = ["coords" => $coordinate, "hotels" => $clienti];
	$avanzata = "no";
	
    return View::make('search.search', compact('clienti', 'target', 'sub_target', 'locale','google_maps','avanzata'));      
    */


    }    


    /**
     * Visualizza il form per la ricerca avanzata
     *
     * @return Response
     */
    public function ricerca_avanzata() 
    {
	     
		return Response::make(view('errors.503'), 503);

		/* $locale = $this->getLocale();
		$cms_pagina = new CmsPagina();
		$stelle = Categoria::real()->orderBy('ordinamento')->pluck('nome', 'id');
		$advanced_search = 1;
		$f_prezzo = $this->fasce_prezzo;
		$categorie = CategoriaServizi::has('servizi')->with(['servizi', 
		                           'servizi.servizi_lingua' => function ($query) 
		                                  {
		                                  $query->where('lang_id', '=', 'it');
		                                  }, 
		                  'servizi.gruppo',
		                  'serviziPrivati'])
		            ->orderBY('position')->get();
		$prefill = CookieIA::getCookiePrefill();                     
		return View::make('search.form_ricerca_avanzata', compact( 'prefill', 'locale', 'stelle', 'f_prezzo', 'categorie', 'advanced_search','cms_pagina')); */
     
    }
    



	public function richiesta_ricerca_avanzata(RichiestaRicercaAvanzataRequest $request) 
	{
		
		return Response::make(view('errors.503'), 503);

		/*
		$ricerca_str = "";
		$locale = $request->get('locale');
		$loc= $request->get('multiple_loc');		
		$localita_arr = Localita::searchById($loc);
		$localita_tag = [];
		$tag = [];
		$tag_servizi = [];
		$cms_pagina = new CmsPagina();
		
		
		//  Posso selezionare una località per volta quindi la tolgo come opzione
		//$localita_arr = $request->get('multiple_loc');
		//$localita = $localita_arr[0];
		//$localita =  $request->get('multiple_loc');
		
		if (!is_null($localita_arr)) {
			foreach($localita_arr as $loca) {
				$localita_tag[] = "<span>".$loca."</span>";
			}
		}
		
			
		// annuale
		is_null($request->get('annuale')) ? $annuale = 0 :$annuale = 1;
		
		if ($annuale) {
			$tag[] .= "<span>".trans("labels.apertura"). "</span><span>annuale</span>";
		}
	
		// categorie
		$categorie = is_null($request->get('categorie')) ? 0 : implode(',',$request->get('categorie')); 
	
		if ($categorie != 0) {
			$str_cat = "";
			foreach ($request->get('categorie') as $cat) {
				
			$str_cat_star = "";
			
			if($cat == 6):
				$str_cat_star = "★★★ Sup";
			else:
				for ($i=1;$i<=$cat;$i++) {
					$str_cat_star .= '★';
				}
			endif;
				$str_cat .= '<span class="border">' . $str_cat_star . '</span>';
			}
			$str_cat = rtrim($str_cat,',');
			$tag[] = "<span>".trans("listing.categoria")."</span><span>" . $str_cat."</span>";
		}

		
		/* trattamento *
		$request_trattamento = $request->get('trattamento');
		
		
		/* se non seleziono un trattamento cerco su tutti *
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//  not-numerical string compared to zero always returns true (Use comparison operator with type check "===") //
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$trattamento = ($request_trattamento === '0') ? "ai,pc,mp,bb,sd" : substr($request_trattamento,strrpos($request_trattamento, '_')+1);
	
		/* fascia di prezzo*
		$prezzo = $request->get('f_prezzo_real');
		$a_partire_da = is_null($request->get('a_partire_da')) ? "" : $request->get('a_partire_da');
	
		// se NON ho selezionato in trattamento e nemmeno un prezzo e nemmeno un data di partenza
		// ALLORA IGNORO COMPLETAMENTE LA RICERCA NEI LISTINI !!!
		
		if ($prezzo == 0 && $a_partire_da == '') {
			
			$trattamento = 0;
			
		} else {
			
			if ($request_trattamento === '0')  {
				$tag[] = "<span>".trans("labels.menu_trat")."</span><span>tutti</span>";
			}else {
				$tag[] = "<span>".trans("labels.menu_trat")."</span><span>".trans("listing." . str_replace("trattamento_","",$request_trattamento))."</span>";
			}
		
			if($a_partire_da != ""){
				$tag[] = "<span>A partire dal</span><span>".$a_partire_da."</span>";
			}
			
			if ($prezzo != 0) {
				$tag[] =  "<span>". trans("labels.fasce_prezzo"). "</span><span>€ ".$prezzo."</span>";
			}
		
		}
	
		/**
		*
		* FINE - TUTTI QUESTI DATI CONTRIBUISCONO ALLA RICERCA NEL LISTINO
		*
		*
	
	
		/*  distanza centro *
		
		// devo fare in modo di prendere gli hotel che hanno 
		// la distanza dal centro considerando il raggio definito dalla località
		// Hotel::getDistanzaDalCentroPoi()
		
		
		$distanza_centro = $request->get('distanza_centro_real');
		$distanza_spiaggia = $request->get('distanza_spiaggia_real');
		$distanza_stazione = $request->get('distanza_stazione_real');
		
		if ($distanza_centro != 0) {
			$tag[] =  "<span>".trans("labels.distanza_centro")."</span><span>".$distanza_centro ." km</span>";
		}
		
		if ($distanza_stazione != 0) {
			$tag[] =  "<span>".trans("labels.distanza_stazione")."</span><span>".$distanza_stazione ." km</span>";
		}
		
		if ($distanza_spiaggia != 0) {
			$tag[] = "<span>".trans("labels.distanza_spiaggia")."</span><span>".$distanza_spiaggia." m</span>";
		}
	
	
		/* servizi *
		$servizi = is_null($request->get('servizi')) ? 0 : implode(',',$request->get('servizi')); 
		
		
		if ($servizi != 0) {
		
		$str_servizi = "";
			
			foreach ($request->get('servizi') as $servizio_id) 
			{
				$nome_servizio = Servizio::find($servizio_id)->servizi_lingua->first()->nome;
				$tag_servizi[] = $nome_servizio;
			}
		}
		
		$clienti = 
		Hotel::withListingEagerLoading($locale)
			->attivo()
			->listingLocalitaMultiple($loc)
			->listingAnnuali($annuale)
			->listingCategorie($categorie)
			->listingTrattamentoNew($request->get('trattamento'), $prezzo, $a_partire_da, $ricerca_avanzata = 1)
			->listingDistanzaDalCentro($distanza_centro)
			->listingDistanzaDallaSpiaggia($distanza_spiaggia)
			->listingDistanzaDallaStazione($distanza_stazione)
			->listingServizi($servizi)
			->orderBy('nome')
			->get();


			
		// ci sono località che hanno come coordinate 0.0000
		$coordinate = [];
		$coordinate['lat'] = "44.063409";
		$coordinate['long'] = "12.585280";
		$coordinate['zoom'] = 9;
        $google_maps = ["coords" => $coordinate, "hotels" => $clienti];
		
		$target = trans("labels.menu_ric");
		$sub_target = $tag;
		$avanzata = "si";
		
		return View::make('search.search', compact( 'clienti', 'target', 'sub_target','tag_servizi','localita_tag', 'locale', 'avanzata','google_maps','cms_pagina'));
		*/
	
	}



  }

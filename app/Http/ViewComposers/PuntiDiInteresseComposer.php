<?php

/**
*
* View composer per render punti di interesse:
* @parameters: cliente, locale
* 
*
**/




namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Lang;
use App\Utility;

class PuntiDiInteresseComposer
{
    
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $viewdata = $view->getData();
		if (isset($viewdata['cliente']))
        	$cliente = $viewdata['cliente'];
        $locale = $viewdata['locale'];
		
        $titolo = isset($viewdata['titolo']) ? $viewdata['titolo'] : '';

        if (isset($titolo) && $titolo != '') {
           $titolo = strtoupper($titolo);
        } else {
           $titolo = '';
        }
		
		if (isset($cliente)) {
			
			$centro = Utility::getDistanzaDalCentroPoi ($cliente );
			
	        $array_poi = [
	            Lang::get('labels.centro') 		=> [ $centro , $cliente->localita->centro_lat, $cliente->localita->centro_long, $centro],
	            Lang::get('labels.spiaggia') 	=> ['m ' . $cliente->distanza_spiaggia, "", "", ""],
	            Lang::get('labels.stazione') 	=> ['Km ' . $cliente->distanza_staz, $cliente->localita->staz_lat, $cliente->localita->staz_long, $cliente->distanza_staz]
	        ];
	
	        $poi = $cliente->poi;
			
            if($cliente->localita_id == Utility::getIdMicroPesaro()) {
                $poi = $poi->whereNotIn('id',Utility::getIdPoiNotPesaro());
            }      
			
			
	        foreach ($poi as $p)
		        $array_poi[$p->poi_lingua->first()->nome] = ['Km ' . $p->pivot->distanza, $p->lat, $p->long, $p->pivot->distanza];

        } else {
	        
	        $poi = new \stdCLass;
	        $array_poi = [];
	        
        }
    
        $view->with([
            'array_poi' => $array_poi,
            'titolo' => $titolo
        ]);
    }
}
<?php

/**
 *
 * View composer per render listino standard:
 * @parameters: cliente, titolo
 *
 *
 *
 */

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Utility;
use App\ServizioCovidHotel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ServiziCovidComposer
{

	/*
	 *
	 * Crea il listino per il file composer
	 *
	 * @$view
	 *
	 */
	 
	public function compose(View $view)
	{
		
		$listini_standard = array();
		$viewdata = $view->getData(); // View
        $locale = $viewdata['locale'];
        $serviziCovidDb = ServizioCovidHotel::with([
            "servizi" => function($query) {
                $query->with("gruppo");
            }
        ])->where("hotel_id", $viewdata["cliente"]->id)
            ->get();
        
        $serviziCovidResult = [];
        foreach( $serviziCovidDb  as  $servizioCovid ):
            $group = $servizioCovid->servizi->gruppo->{"nome_" . $locale};
            
            if (!isset($serviziCovidResult[$group])) {

                $serviziCovidResult[$group] = [];
            }
            
            $s = $servizioCovid->servizi->{"nome_" . $locale};
            if (isset($servizioCovid->distanza) && !is_null($servizioCovid->distanza)) {
                $s .= ' ' . $servizioCovid->distanza;
                // $s .= " (".$servizioCovid->distanza;
                // if ($servizioCovid->servizi->id == 9)
                //     $s .= " m)";
                // else
                //     $s .= " km)";
            }
            $serviziCovidResult[$group][] = $s;
                    
        endforeach;

        foreach( $serviziCovidResult  as $key => $servizioCovidResult ):
            if (count($servizioCovidResult) == 0)
                unset($serviziCovidResult[$key ]);
        endforeach;
        
		$view->with([
			'serviziCovidResult' =>  $serviziCovidResult,
		]);

	}


}

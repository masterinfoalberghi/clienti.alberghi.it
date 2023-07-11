<?php

namespace App\Http\Controllers\Admin;

use App\CmsPagina;
use App\BambinoGratis;
use App\ParolaChiaveEspansa;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Offerta;
use App\OffertaPrenotaPrima;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OfferteDaAprrovareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    /////////////////////////////
    // TOGLIERE LA LIMITAZIONE //
    /////////////////////////////

    
        // elenco delle offerte/last NON APPROVATE !!
        $offerte = Offerta::whereHas('offerte_lingua', function ($query) {
                        $query->where('lang_id', 'it')->where('approvata', 0);
                    })
                    ->with([
                      'offerte_lingua' => function ($query) {
                          $query->where('lang_id', 'it')->where('approvata',0);
                      },
                      'cliente'
                    ])
                    ->attiva()
                    ->orderBy('updated_at')
                    ->get();
      
        $offertePP = OffertaPrenotaPrima::whereHas('offerte_lingua', function ($query) {
                        $query->where('lang_id', 'it')->where('approvata', 0);
                      })
                      ->with([
                        'offerte_lingua' => function ($query) {
                            $query->where('lang_id', 'it')->where('approvata',0);
                        },
                        'cliente'
                      ])
                      ->attiva()
                      ->orderBy('updated_at')
                      ->get();

        $offerteBB = BambinoGratis::whereHas('offerte_lingua', function ($query) {
                            $query->where('lang_id', 'it')->where('approvata', 0);
                          })
                            ->with([
                    'offerte_lingua' => function ($query) {
                        $query->where('lang_id', 'it')
                              //->where('note' , '!=' , '')
                              ->where('approvata',0);
                    },
                    'cliente'
                    ])
                    ->attivo()
                    ->notArchiviato()
                    ->orderBy('updated_at')
                    ->get();
				
		// Trovo le parole chiave
		
		$paroleChiave = ParolaChiaveEspansa::get();
			 
        return view('admin.offerte_da_approvare_index',compact('offerte','offertePP','offerteBB','paroleChiave'));
        
    }

    public function approvaOffertaAjax($id_offerta = 0)
      {
        $offerta = Offerta::with('offerte_lingua')->find($id_offerta);

        foreach ($offerta->offerte_lingua as $offerta_lingua) 
          {
          $offerta_lingua->approvata = 1;
          $offerta_lingua->data_approvazione = Carbon::now();
          $offerta_lingua->save();
          }

        echo $id_offerta;  
      }

     public function approvaOffertaPPAjax($id_offerta = 0)
      {
        $offerta = OffertaPrenotaPrima::with('offerte_lingua')->find($id_offerta);

        foreach ($offerta->offerte_lingua as $offerta_lingua) 
          {
          $offerta_lingua->approvata = 1;
          $offerta_lingua->data_approvazione = Carbon::now();
          $offerta_lingua->save();
          }

        echo $id_offerta;  
      }

    public function approvaOffertaBBAjax($id_offerta = 0)
     {
       $offerta = BambinoGratis::with('offerte_lingua')->find($id_offerta);

       foreach ($offerta->offerte_lingua as $offerta_lingua) 
         {
         $offerta_lingua->approvata = 1;
         $offerta_lingua->data_approvazione = Carbon::now();
         $offerta_lingua->save();
         }

       echo $id_offerta;  
     }

   
}

<?php

namespace App\Http\Controllers\Admin;

use App\Hotel;
use App\Utility;
use App\GooglePoi;
use App\CategoriaPoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\Admin\AdminBaseController;

class GooglePoiController extends AdminBaseController
{
    public function index()
    {
        $hotel_id = $this->getHotelId();

		$h = Hotel::with('localita')->find($hotel_id);

        $g_poi = $h->goolePoi;

        //dd($g_poi);

        //? Aggiungo la lista dei POI "normali"

        
        $array_poi = [];

        foreach ($h->poi as $p) {
            
            if (is_null($p->categoria)) {
                $data = 
                    [
                        'nome' => $p->poi_it->nome,
                        'lat' => $p->lat,
                        'long' => $p->long,
                        'tempo' => $p->pivot->g_durata,
                        'rotta' => $p->pivot->g_descrizione_rotta,
                        'modo' => $p->pivot->g_modo,
                    ];
                //? metto la distanza di google oppure quella calcolata dal sistema
                $data['distanza'] = empty($p->pivot->g_distanza) ? 'Km ' . $p->pivot->distanza : $p->pivot->g_distanza;

                $array_poi['uncategorized'][] = $data;
            } else {
                
                if (!isset($array_poi[$p->categoria->nome_it])) {
                    $array_poi[$p->categoria->nome_it] = [];
                }
                $data =
                    [
                        'nome' => $p->poi_it->nome,
                        'lat' => $p->lat,
                        'long' => $p->long,
                        'tempo' => $p->pivot->g_durata,
                        'rotta' => $p->pivot->g_descrizione_rotta,
                        'modo' => $p->pivot->g_modo,
                    ];
                //? metto la distanza di google oppure quella calcolata dal sistema
                $data['distanza'] = empty($p->pivot->g_distanza) ? 'Km ' . $p->pivot->distanza : $p->pivot->g_distanza;

                $array_poi[$p->categoria->nome_it][] = $data;

            }
        }


        //? centro, stazione e spiaggia sono distanze di categoria Posizione e Raggiungere la struttura
        //? associate all'hotel e vanno aggiunte "a mano" nella rispettiva categoria
        if (!isset($array_poi['Posizione'])) {
            $array_poi['Posizione'] = [];
        }

        $array_poi['Posizione'][] =
        [
            'nome' => Lang::get('labels.centro'),
            'distanza' => Utility::getDistanzaDalCentroPoi ($h ),
            'lat' => $h->localita->centro_lat,
            'long' => $h->localita->centro_long,
            'tempo' => null,
            'rotta' => null,
            'modo' => null,
        ];

        $array_poi['Posizione'][] =
        [
            'nome' => Lang::get('labels.spiaggia'),
            'distanza' => 'm ' . $h->distanza_spiaggia,
            'lat' => null,
            'long' => null,
            'tempo' => null,
            'rotta' => null,
            'modo' => null,
        ];

        $array_poi['Raggiungere la struttura'][] =
        [
            'nome' => Lang::get('labels.stazione'),
            'distanza' => 'km ' . $h->distanza_staz,
            'lat' => $h->localita->staz_lat,
            'long' => $h->localita->staz_long,
            'tempo' => null,
            'rotta' => null,
            'modo' => null,
        ];



        //dd($array_poi);

        return view('admin.google-poi-index', compact('g_poi', 'array_poi'));

    }


    public function delete($id = 0) {

        GooglePoi::find($id)->delete();

        return redirect('admin/google-poi')->with('status', 'Google POI eliminato correttamente!');

    }
}

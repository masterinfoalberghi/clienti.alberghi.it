<?php

namespace App\Http\Controllers\Admin;

use App\CategoriaPoi;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\PoiRequest;
use App\Localita;
use App\Macrolocalita;
use App\Poi;
use App\PoiLang;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SessionResponseMessages;


class PoiController extends Controller
{
    
    private function _getMacro()
      {
      return Macrolocalita::noRR()->with('localita')->real()->get();
      }

    private function _getPoiList()
      {
      return Poi::with([
              'poi_lingua' => function($query) {
                $query->where('lang_id', '=', 'it')->orderBy('nome','asc');
              },
              'localita'
              ])->get();
      }  



    private function _listaPoiLingua($lang_id = null)
      {

        if(is_null($lang_id))
          {
          
          return Poi::with('poi_lingua')->get();
          
          }
        else
          {
          
          return Poi::with(['poi_lingua' => function($query) use ($lang_id)
            {
              $query->where('lang_id', '=', $lang_id);
            }])->get();
          
          }

      }

    public function index()
      {
      $pois = $this->_listaPoiLingua();
      $data = [];
      foreach ($pois as $poi) { 
        foreach ($poi->poi_lingua as $poi_lingua) 
          $data[$poi_lingua->lang_id][$poi->id] = $poi_lingua->nome;
      }
      $icone = Utility::fontelloIcons();

      return view('admin.poi_edit', compact("data"));
      }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    $macro = $this->_getMacro();
    $poi_list = $this->_getPoiList();
    foreach($poi_list as $poi):
        foreach($poi->localita as $localita):
            if(!is_string($localita->nome)) {
                $loc = json_decode($localita->nome)["it"];
            } else {
                $loc = $localita->nome;
            }
            $localita->nome = $loc;
        endforeach;
    endforeach;
    
    $localita_poi_arr = [];
    $prefill = [];
    $today = Carbon::today();
    $categories = CategoriaPoi::pluck('nome_it', 'id')->toArray();
    $icone = Utility::fontelloIcons();

    return view('admin.poi_form', compact('localita_poi_arr','poi_list','prefill','macro','today', 'categories', 'icone'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PoiRequest $request)
    {

    /* TRANSACTION */
    DB::beginTransaction();
    $status = 'ok';
    try
      {
      $poi = Poi::create($request->except(['localita','nome']));

      $localita_arr = is_null($request->get('localita')) ? [] : $request->get('localita');

      $poi->localita()->sync($localita_arr);

      $poi->save();    

      // inserimento in lingua
      /*
          NON TRADUCO AUTOMATICAMENTE con Google Translate perché al 90% sono nomi propri che non vanno tradotti
      */
      $nome = $request->get('nome');


      foreach (Utility::linguePossibili() as $lingua) 
        {
        $poi_lingua[] = ["master_id" => $poi->id, "nome" => $nome,"lang_id" => $lingua];
        }

      PoiLang::insert($poi_lingua);
      
      DB::commit();
      }
    catch (\Exception $e)
      {
        $status = 'ko';
        DB::rollback();
      }

    
    if ($status == 'ok') 
      {
      SessionResponseMessages::add("success", "Inserimento effettuato con successo.");
      } 
    else 
      {
      SessionResponseMessages::add("error", "Errore !!");
      }
    
    
    return SessionResponseMessages::redirect("admin/poi/create", $request);
    }



    /**
     * Associa i POI ad ogni hotel di una località e ne calcola le distanze
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hotelsMacroLocalita(Request $request, $macrolocalita_id = 0)
      {
      $macro = Macrolocalita::with(['localita'])->find($macrolocalita_id);

      if(!is_null($macro) && !is_null($macro->localita))
        { 
        foreach ($macro->localita as $loc) 
          {
           $this->hotelsLocalita($request,$loc->id,$reload=false);
          } 
        }
      SessionResponseMessages::add("success", "Aggiornamento Hotel/POI effettuato con successo.");
      return SessionResponseMessages::redirect("admin/poi/create", $request);  

      } /* end function*/


    /**
     * Associa i POI ad ogni hotel di una località e ne calcola le distanze
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //! ATTENZIONE: adesso la tabella di pivot contiene anche 
    //! g_distanza, g_durata, g_descrizione_rotta,g_modo che sono aggiunte con un command artisan
    //! quindi non le posso ELIMINARE e va cambiata la logica di questa funzione
    public function hotelsLocalita_OLD(Request $request, $localita_id = 0, $reload=true)
      {
      // trovo gli hotel di questa localita
      
      $localita = Localita::find($localita_id);

      $hotels = $localita->clienti()->attivo()->get();

      $points_of_interest = $localita->poi;

      foreach ($hotels as $hotel) 
        {

        //////////////////////////////////////////////////////////////////////////////////////////////
        // per ogni hotel elimino i poi "vecchi" e gli associo i nuovi POI con le relative distanze //
        //////////////////////////////////////////////////////////////////////////////////////////////

        /*
         * cancello con una query tutti i ganci tra questo Hotel e le Poi,
         * per poi inserirle nuovamente con i valori aggiornati
         */
        //! ATTENZIONE: adesso la tabella di pivot contiene anche 
        //! g_distanza, g_durata, g_descrizione_rotta,g_modo che sono aggiunte con un command artisan
        //! quindi non le posso ELIMINARE
        $hotel->poi()->detach();


        /*
         * Se uso il salvataggio della relazione nativo di Laravel
         * http://laravel.com/docs/5.1/eloquent-relationships#inserting-related-models
         * mi farebbe una query per ogni relazione, preferisco fare una query unica
         */
        $relations = [];
        foreach ($points_of_interest as $point_of_interest) 
          {
          $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $point_of_interest->lat, $point_of_interest->long);

          $relations[] = [
          "hotel_id" => $hotel->id,
          "poi_id" => $point_of_interest->id,
          "distanza" => round($distanza_metri / 1000, 2),
          "created_at" => date("Y-m-d H:i:s"),
          "updated_at" => date("Y-m-d H:i:s")
          ];
          }

        if (count($relations)) 
          {
          /*
           * Non avendo il model HotelPoi, passo direttamente dal Query Builder
           */
          DB::table("tblHotelPoi")->insert($relations);
          }

        //////////////////////////////////////////////////////////////////////////////////////////////
        // per ogni hotel elimino i poi "vecchi" e gli associo i nuovi POI con le relative distanze //
        //////////////////////////////////////////////////////////////////////////////////////////////
        
        } /* end foreach hotel*/

        // aggiorno updated_at località

        $localita->touch();

        if ($reload) 
        {
        SessionResponseMessages::add("success", "Aggiornamento Hotel/POI effettuato con successo.");
        return SessionResponseMessages::redirect("admin/poi/create", $request);  
        }
      
      } /* end function*/


    /**
     * Associa i POI ad ogni hotel di una località e ne calcola le distanze
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hotelsLocalita(Request $request, $localita_id = 0, $reload = true)
    {
      // trovo gli hotel di questa localita

      $localita = Localita::find($localita_id);

      $hotels = $localita->clienti()->attivo()->get();

      $hotels->load('poi');

      $points_of_interest = $localita->poi;

      foreach ($hotels as $hotel) {

            //////////////////////////////////////////////////////////////////////////////////////////////
            // per ogni hotel elimino i poi "vecchi" e gli associo i nuovi POI con le relative distanze //
            //////////////////////////////////////////////////////////////////////////////////////////////

            /*
            * cancello con una query tutti i ganci tra questo Hotel e le Poi,
            * per poi inserirle nuovamente con i valori aggiornati
            */
            //! ATTENZIONE: adesso la tabella di pivot contiene anche 
            //! g_distanza, g_durata, g_descrizione_rotta,g_modo che sono aggiunte con un command artisan
            //! quindi non le posso ELIMINARE PRIMA
            $poi_associated_arr = $hotel->poi->pluck('id')->toArray();

            $relations = [];
            foreach ($points_of_interest as $point_of_interest) {

                  $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $point_of_interest->lat, $point_of_interest->long);
                  
                  //! Il poi può appartenere alla struttura oppure essere NON ancora associato
                  if (in_array($point_of_interest->id, $poi_associated_arr)) {

                      //! il POI appartiene già alla struttura
                      //! trovo il poi associato
                      $associated_poi = $hotel->poi->where('id', $point_of_interest->id)->first();

                      $data_pivot = $associated_poi->pivot->toArray();

                      //dd($data_pivot);
            
                      $data_pivot["distanza"] = round($distanza_metri / 1000, 2);
                      $data_pivot["created_at"] = date("Y-m-d H:i:s");
                      $data_pivot["updated_at"] = date("Y-m-d H:i:s");

                  
                      $relations[] = $data_pivot;
                      
                      //? Aggiorno i dati di pivot 
                      //! Troppe query...sei pazzo ??!!
                      //$hotel->poi()->updateExistingPivot($point_of_interest->id, $data_pivot);
                  
                  } else {

                      //! il POI NON appartiene già alla struttura
                      $relations[] = [
                        "hotel_id" => $hotel->id,
                        "poi_id" => $point_of_interest->id,
                        "distanza" => round($distanza_metri / 1000, 2),
                        "g_distanza" => null,
                        "g_durata" => null, 
                        "g_distanza_numeric" => null, 
                        "g_durata_numeric" => null,
                        "g_descrizione_rotta" => null,
                        "g_modo" => null,
                        "g_url" => null,
                        "created_at" => date("Y-m-d H:i:s"),
                        "updated_at" => date("Y-m-d H:i:s")
                      ];

                  }
            }
            //dd($relations);
            
            $hotel->poi()->detach();
            
            if (count($relations)) {
              /*
                * Non avendo il model HotelPoi, passo direttamente dal Query Builder
                */
              DB::table("tblHotelPoi")->insert($relations);
            }

      } /* end foreach hotel*/

      // aggiorno updated_at località

      $localita->touch();

      if ($reload) {
        SessionResponseMessages::add("success", "Aggiornamento Hotel/POI effettuato con successo.");
        return SessionResponseMessages::redirect("admin/poi/create", $request);
      }
    } /* end function*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
      {
      $poi = Poi::with([
              'poi_lingua' => function($query) {
                $query->lang_id = 'it';
              }
              ])->find($id);

      $poi_nome = $poi->poi_lingua()->first()->nome;

      // localita associate la poi
      $localita_poi_arr = $poi->localita->pluck('id')->toArray();
      
      $macro = $this->_getMacro();
      $poi_list = $this->_getPoiList();
      $prefill = [];
      $today = Carbon::today();
      $categories = CategoriaPoi::pluck('nome_it', 'id')->toArray();

      $icone = Utility::fontelloIcons();

      return view('admin.poi_form', compact('poi', 'poi_nome' ,'localita_poi_arr', 'poi_list', 'prefill','macro','today', 'categories', 'icone'));
      }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
      {
      $poi = Poi::find($id);
      
      $poi->fill($request->except(['localita', 'nome']));
      
      $localita_arr = is_null($request->get('localita')) ? [] : $request->get('localita');
      
      $poi->localita()->sync($localita_arr);

      $poi->save();

      $nome = $request->get('nome');

      foreach (Utility::linguePossibili() as $lingua) {
          PoiLang::updateOrCreate(
            ['master_id' => $poi->id, 'lang_id' => $lingua],
            ["nome" => $nome]
          );
      }

      SessionResponseMessages::add("success", "Modifica effettuata con successo.");
      return SessionResponseMessages::redirect("admin/poi/create", $request); 
      }


      /**
       * Update the specified resource in storage.
       *
       * @param  int  $id
       * @return Response
       */
      public function updateLingua(Request $request)
      {
       
          $pois = $this->_listaPoiLingua();
          
          foreach ($pois as $poi) {
            $nuovo_poi_lingua = [];
            foreach ($poi->poi_lingua as $poi_in_lingua) {

              $original_servizio_lingua = $poi_in_lingua->toArray();
              /*
              $original_servizio_lingua =  array:6 [▼
                "id" => 23537
                "master_id" => 127
                "lang_id" => "it"
                "nome" => "cucina romagnola asdada"
                "created_at" => "-0001-11-30 00:00:00"
                "updated_at" => "-0001-11-30 00:00:00"
              ]
               */
              
              $key = "poi{$poi_in_lingua->lang_id}{$poi->id}";

              if ($request->filled($key)) {
                $original_servizio_lingua['nome'] = $request->get($key);
              }

              unset($original_servizio_lingua['id']);
              $nuovo_poi_lingua[] = $original_servizio_lingua;

              $poi_in_lingua->delete();
            }
            PoiLang::insert($nuovo_poi_lingua);
          }

        
        return SessionResponseMessages::redirect("admin/poi", $request);
      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

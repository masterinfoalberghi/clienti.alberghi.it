<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MappaRicercaPoiRequest;
use App\MappaRicercaPoi;
use App\MappaRicercaPoiLang;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SessionResponseMessages;

class MappaRicercaPoiController extends Controller
{   

   /* private static $colori = 
                        [
                          '#000', 
                          '#21a9e1', 
                          '#2B562A', 
                          '#702121', 
                        ];*/

      private static $colori = 
                        [
                          'black',
                          'red',
                          'green',
                          'blue',
                          'grey',
                          'orange',
                          'yellow',
                        ];


      private static $codici_colori = 
                      [
                        'black' => '#000',
                        'red' => '#d54e21',
                        'green' => '#1b7e5a',
                        'blue' => '#21a9e1',
                        'grey' => '#333',
                        'orange' => '#f70',
                        'yellow' => '#fc3',
                      ];

      /**
       * metodo da chiamare SEMPRE prima di salvare nel DB
       * rimette i tag html e LIMITA il numeto di
       *
       * @param string $testo
       */
       
      private function _scriviTesto(&$testo)
      {
        //$testo = strip_tags($testo, "<br>"); // tolgo tutto html
        $testo = nl2br($testo);
        $testo = str_replace("\n", '', $testo);
        $testo = str_replace("\r", '', $testo);
        $testo = str_replace("\t", '', $testo);
        $testo = str_replace('"', "'", $testo); // sostituisco le double quotes con single quotes
        $testo = str_replace("<br /> ", '<br />', $testo);
        
        /**
         * DOVE SONO PIU' DI 1 VOGLIO AL MASSIMO 1 br (a capo + NESSUNA riga vuota)
         */
         
        $pattern = '/(<br \/>){1,1000}/i';
        $replacement = '<br />';

        $new_testo = preg_replace($pattern, $replacement, $testo);

        if (!is_null($new_testo))
          $testo = $new_testo;

      }


      /**
       * metodo richiamato solo nello store
       * sostituisce ## con lo span no-translate per il testo da NON tradurre
       *
       * @param string $nome
       * @param string $info_titolo
       * @param string $info_desc
       */
      
      private function _processNoTranslateTag(&$nome, &$info_titolo, &$info_desc)
      {
        
        $content_processed = preg_replace_callback(
          '|#(.+?)#|s',
          function($matches){
            return "<span translate=\"no\">".$matches[1]."</span>";
          },
          $nome
        );
        $nome = $content_processed;

        $content_processed = preg_replace_callback(
          '|#(.+?)#|s',
          function($matches){
            return "<span translate=\"no\">".$matches[1]."</span>";
          },
          $info_titolo
        );
        $info_titolo = $content_processed;

        $content_processed = preg_replace_callback(
          '|#(.+?)#|s',
          function($matches){
            return "<span translate=\"no\">".$matches[1]."</span>";
          },
          $info_desc
        );
        $info_desc = $content_processed;
        
      }

    /**
     * metodo richiamato solo nello store
     * perché serve PRIMA di PASSARE il teso AL TRADUTTORE GT
     *
     * @param string $nome
     * @param string $info_titolo
     * @param string $info_desc
     */
    private function _leggiTesto(&$nome, &$info_titolo, &$info_desc)
      {

      $nome = nl2br($nome);
      $nome = str_replace("\n", '', $nome); // remove new lines
      $nome = str_replace("\r", '', $nome); // remove carriage returns
      
      $info_titolo = nl2br($info_titolo);
      $info_titolo = str_replace("\n", '', $info_titolo); // remove new lines
      $info_titolo = str_replace("\r", '', $info_titolo); // remove carriage returns

      $info_desc = nl2br($info_desc);
      $info_desc = str_replace("\n", '', $info_desc); // remove new lines
      $info_desc = str_replace("\r", '', $info_desc); // remove carriage returns
      
      }


    private function _preparaPerWeb(&$testo)
    {

      $testo = preg_replace('#<br\s*/?>#i', "\n", $testo); // rimetto gli a capo NON web

    }

    private function _listaPoiLingua($lang_id = null)
      {

        if(is_null($lang_id))
          {
          
          return MappaRicercaPoi::with('poi_lingua')->get();
          
          }
        else
          {
          
          return MappaRicercaPoi::with(['poi_lingua' => function($query) use ($lang_id)
            {
              $query->where('lang_id', '=', $lang_id);
            }])->get();
          
          }

      }

    private function _getPoiList()
      {
      return MappaRicercaPoi::with([
              'poi_lingua' => function($query) {
                $query->where('lang_id', '=', 'it')->orderBy('nome','asc');
              }
              ])
              ->get();
      }  


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
      {
      $poi_list = $this->_getPoiList();

      $colori = self::$colori;
      return view('admin.mappa-ricerca-poi_form', compact('poi_list','colori'));
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MappaRicercaPoiRequest $request)
      {

      /* TRANSACTION */

      DB::beginTransaction();
      $status = 'ok';


      try 
        {
      
        $poi = new MappaRicercaPoi;

         $colore = $request->get('colore');

        $poi->lat = $request->get('lat');
        $poi->long = $request->get('long');
        $poi->colore = self::$codici_colori[$colore];
        $poi->save();


        /**
         * Correggo titolo e testo
         */
         
        $nome = $request->get('nome');
        $info_titolo = $request->get('info_titolo');
        $info_desc = $request->get('info_desc');

        /**
         * inserisce i br e rimuove altri caratteri non html
         */
         
        $this->_leggiTesto($nome, $info_titolo, $info_desc);

        /**
         * sostituisce ## con lo span no-translate per il testo da NON tradurre
         */
         
        $this->_processNoTranslateTag($nome, $info_titolo, $info_desc);



        /**
         * Traduzioni con google translate
         */
        
        $nome_it = $nome;
        $info_titolo_it = $info_titolo;
        $info_desc_it = $info_desc;

        $da_tradurre = array('nome', 'info_titolo', 'info_desc');
        
        $traduzioni = [];
        $traduzioni['it']['nome'] = $nome_it;
        $traduzioni['it']['info_titolo'] = $info_titolo_it;
        $traduzioni['it']['info_desc'] = $info_desc_it;


        /**
         * Creo le traduzioni 
         */
         
        foreach (Utility::linguePossibili() as $lingua) {
          foreach ($da_tradurre as $nome) {
            
            $text = $nome.'_it';

            if ($lingua != 'it') {

              $traduzioni[$lingua][$nome] = Utility::translate($$text, $lingua);
              $testo = $traduzioni[$lingua][$nome];
              $this->_scriviTesto($testo);
              $traduzioni[$lingua][$nome] = $testo;

            } else {
              
              $testo = $traduzioni[$lingua][$nome];
              $this->_scriviTesto($testo);
              $traduzioni[$lingua][$nome] = $testo;
              
            }

          }
        }


        foreach ($traduzioni as $lang_id => $value) 
          {
          
          $poiLingua = new MappaRicercaPoiLang;
          $poiLingua->lang_id = $lang_id;
          $poiLingua->nome = $value['nome'];
          $poiLingua->info_titolo = $value['info_titolo'];
          $poiLingua->info_desc = $value['info_desc'];
          
          
          $poi->poi_lingua()->save($poiLingua);
          
          }

        } 
      catch (\Exception $e) 
        {
        config('app.debug_log') ? Log::emergency("\n".'---> Errore : '.get_class($this) .' --- '.$e->getMessage().' <---'."\n\n") : "";
        $status = 'ko';
        DB::rollback();
      }


      if ($status == 'ok') 
        {
        DB::commit();
        SessionResponseMessages::add("success", "Inserimento effettuato con successo.");
        }
      else
        {
        SessionResponseMessages::add("error", "Modifiche abortite. ATTENZIONE: Si è verificato un errore grave!!.");
        }

        
      return SessionResponseMessages::redirect("admin/mappa-ricerca-poi", $request);


    } /* end store()*/

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
      {
      $poi = MappaRicercaPoi::with([
              'poi_lingua'
              ])
            ->find($id);

      $poiLingua = [];
      
      foreach ($poi->poi_lingua as $poi_lingua) 
        {
        
        $arr = [];
        $nome = $poi_lingua->nome;
        $info_titolo = $poi_lingua->info_titolo;
        $info_desc = $poi_lingua->info_desc;

        $this->_preparaPerWeb($nome);
        $arr['nome'] = $nome;

        $this->_preparaPerWeb($info_titolo);
        $arr['info_titolo'] = $info_titolo;

        $this->_preparaPerWeb($info_desc);
        $arr['info_desc'] = $info_desc;

        $poiLingua[$poi_lingua->lang_id][] = $arr;
        
        }

    /*
    array:4 [▼
      "it" => array:1 [▼
        0 => array:3 [▼
          "nome" => "Fiera di Rimini"
          "info_titolo" => "Fiera di Rimini"
          "info_desc" => "Fiera di Rimini"
        ]
      ]
      "en" => array:1 [▼
        0 => array:3 [▼
          "nome" => "Rimini Fair"
          "info_titolo" => "Rimini Fair"
          "info_desc" => "Rimini Fair"
        ]
      ]
      "fr" => array:1 [▼
        0 => array:3 [▼
          "nome" => "Rimini Fair"
          "info_titolo" => "Rimini Fair"
          "info_desc" => "Rimini Fair"
        ]
      ]
      "de" => array:1 [▼
        0 => array:3 [▼
          "nome" => "Rimini Messe"
          "info_titolo" => "Rimini Messe"
          "info_desc" => "Rimini Messe"
        ]
      ]
    ]
     */

      $poi_list = $this->_getPoiList();

      $colori = self::$colori;
      $codici_colori = self::$codici_colori; 
      $inverted_codici_colori = array_flip(self::$codici_colori); 

      $showButtons = 1;
      return view('admin.mappa-ricerca-poi_form', compact('poi','poiLingua','poi_list', 'colori', 'codici_colori','inverted_codici_colori','showButtons'));
      }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MappaRicercaPoiRequest $request, $id)
      {

       /* TRANSACTION */
      DB::beginTransaction();
      $status = 'ok';

      try 
        {

        $poi = MappaRicercaPoi::with('poi_lingua')->find($id);

        $colore = $request->get('colore');

        $new_poi = [
          'lat' => $request->get('lat'),
          'long' => $request->get('long'),
          'colore' => self::$codici_colori[$colore]
        ];

        $poi->fill($new_poi);
        $poi->save();

        ($request->has('traduci') && $request->get('traduci') == 1) ? $traduzione = 1 : $traduzione = 0;


        if ($traduzione) 
          {
          $nome = $request->get('nomeit');
          $info_titolo = $request->get('info_titoloit');
          $info_desc = $request->get('info_descit');

          /**
           * inserisce i br e rimuove altri caratteri non html
           */
           
          $this->_leggiTesto($nome, $info_titolo, $info_desc);

          /**
           * sostituisce ## con lo span no-translate per il testo da NON tradurre
           */
           
          $this->_processNoTranslateTag($nome, $info_titolo, $info_desc);


          /**
           * Traduzioni con google translate
           */
          
          $nome_it = $nome;
          $info_titolo_it = $info_titolo;
          $info_desc_it = $info_desc;

          $da_tradurre = array('nome', 'info_titolo', 'info_desc');
          
          $traduzioni = [];
          $traduzioni['it']['nome'] = $nome_it;
          $traduzioni['it']['info_titolo'] = $info_titolo_it;
          $traduzioni['it']['info_desc'] = $info_desc_it;


          /**
           * Creo le traduzioni 
           */
           
          foreach (Utility::linguePossibili() as $lingua) {
            foreach ($da_tradurre as $nome) {
              
              $text = $nome.'_it';

              if ($lingua != 'it') {

                $traduzioni[$lingua][$nome] = Utility::translate($$text, $lingua);
                $testo = $traduzioni[$lingua][$nome];
                $this->_scriviTesto($testo);
                $traduzioni[$lingua][$nome] = $testo;

              } else {
                
                $testo = $traduzioni[$lingua][$nome];
                $this->_scriviTesto($testo);
                $traduzioni[$lingua][$nome] = $testo;
                
              }

            }
          }



          foreach ($traduzioni as $lang_id => $value) 
            {
            
            $poiLingua = new MappaRicercaPoiLang;
            $poiLingua->lang_id = $lang_id;
            $poiLingua->nome = $value['nome'];
            $poiLingua->info_titolo = $value['info_titolo'];
            $poiLingua->info_desc = $value['info_desc'];
            
            $poi->translate($lang_id)->first()->delete();
            $poi->poi_lingua()->save($poiLingua);
            
            }

          }
        else
          {

          $nuovo_poi_lingua = [];

          foreach ($poi->poi_lingua as $poi_lingua) 
            {
            $original_poi_lingua = $poi_lingua->toArray();


            $key = "nome{$poi_lingua->lang_id}";
            if ($request->has($key)) 
              {
              $nome = $request->get($key);
              $this->_scriviTesto($nome);
              $original_poi_lingua['nome'] = $nome;
              }


            $key = "info_titolo{$poi_lingua->lang_id}";
            if ($request->has($key)) 
              {
              $info_titolo = $request->get($key);
              $this->_scriviTesto($info_titolo);
              $original_poi_lingua['info_titolo'] = $info_titolo;
              }


            $key = "info_desc{$poi_lingua->lang_id}";
            if ($request->has($key)) 
              {
              $info_desc = $request->get($key);
              $this->_scriviTesto($info_desc);
              $original_poi_lingua['info_desc'] = $info_desc;
              }

            unset($original_poi_lingua['id']);

            $nuovo_poi_lingua[] = $original_poi_lingua;

            $poi_lingua->delete();

            }

            MappaRicercaPoiLang::insert($nuovo_poi_lingua);

          } // end if ($traduzione) 


          } 
        //catch (\Exception $e) 
        catch (Exception $e) 
          {
          config('app.debug_log') ? Log::emergency("\n".'---> Errore : '.get_class($this) .' --- '.$e->getMessage().' <---'."\n\n") : "";

          $status = 'ko';
          DB::rollback();
          }

        if ($status == 'ok') 
          {
          DB::commit();
          SessionResponseMessages::add("success", "Modifiche effettuate con successo.");
          }
        else
          {
          SessionResponseMessages::add("error", "Modifiche abortite. ATTENZIONE: Si è verificato un errore grave!!.");
          }

        return SessionResponseMessages::redirect("admin/mappa-ricerca-poi", $request);

      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
    MappaRicercaPoiLang::where('master_id', $id)->delete();
    MappaRicercaPoi::destroy($id);

    SessionResponseMessages::add("success", "POI eliminato con successo.");
    return SessionResponseMessages::redirect("admin/mappa-ricerca-poi", $request);
    }
}

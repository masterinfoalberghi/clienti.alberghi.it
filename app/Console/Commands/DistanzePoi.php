<?php

namespace App\Console\Commands;

use App\Hotel;
use App\Macrolocalita;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class DistanzePoi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poi:distanze {--hotel_id=} {--id=*} {--new}';

    /**
     * The console command description.
     *
     * @var string
     */
    //? Options, like arguments, are another form of user input. 
    //? Options are prefixed by two hyphens (--) when they are provided via the command line

    //? In this example, the user may pass a value for the option. 
    //? poi:distanze --hotel_id=17
    //? If the option is not specified when invoking the command, its value will be null:
    protected $description = 'Trova le distanze ed i tempi di percorrenza (mediante Google Place API) tra le strutture della macro scelta e POI creati a mano da backend. Accetta "--hotel_id=" come ID di un hotel (poi:distanze --hotel_id=17) OPPURE MOLTI ID "--id=" nella forma (poi:distanze --id=17 --id=234). Se presente "--new" vengono considerati SOLO i poi NUOVI, cioè quelli nella tabella tblHotelPoi che hanno i campi di google (g_) NULLI (il test viene fatto solo sul campo g_modo)';

    public $api_key;
    public $base_url_place;
    public $limit_walk_duration;
    public $only_new;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->api_key = Config::get("google.googlekey");
        $this->base_url_directions = "https://maps.googleapis.com/maps/api/directions/json";

        // limte di 25 minuiti, sopra il quale faccio una nuova richiesta per le direzioni in macchina
        $this->limit_walk_duration = 1500;


        parent::__construct();
    }


    /**
     * Effettua la chiamata cUrl in post all'url passato
     *
     * @param [type] $url
     * @return associative_array del json
     */
    private function _getCurlRequest($url)
    {
        //curl -L -X GET 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=-33.8670522%2C151.1957362&radius=1500&type=restaurant&keyword=cruise&key=YOUR_API_KEY';

        // The %2C means , comma in URL

        //open connection
        $ch = curl_init();


        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);


        return json_decode($result, true);
    }



    private function _chiamataDirectionsAPI($url, $p) {
        
        $mode = 'walking';

        $url_walking =  $url . '&destination='.$p-> lat . '%2C' . $p->long. '&language=it&mode='. $mode .'&key=' . $this->api_key;

        $result_arr =  $this->_getCurlRequest($url_walking);

        $this->info("1 Chiamata cURL per distanza " . $p->id . " mode ". $mode);

        if ($result_arr['status'] != "OK") {
            return $result_arr;
        }

        $routes = $result_arr['routes'];

        if ( $routes[0]['legs'][0]['duration']['value'] <= $this->limit_walk_duration ) {

            $result_arr['mode'] = $mode;
            $result_arr['url'] = $url_walking;

            return $result_arr;
        } else {
            //! Se "a piedi" supero i 20 minuti, allora faccio la richiesta con mode="driving"
            $mode = 'driving';

            $url_driving = $url . '&destination='.$p-> lat . '%2C' . $p->long. '&language=it&mode='. $mode .'&key=' . $this->api_key;

            $result_arr =  $this->_getCurlRequest($url_driving);

            $this->info("2 Chiamata cURL per distanza " . $p->id . " mode ". $mode);

            $result_arr['mode'] = $mode;
            $result_arr['url'] = $url_driving;

            return $result_arr;
            
        }

    }


    private function _trovaDistanzePoi($hotel = null)
    {
            
        $hotel = Hotel::find($hotel['hotel_id']);

        $this->info("Poi di " . $hotel->nome . " Solo nuovi ? ".$this->only_new);

        if ($hotel->poi_new($this->only_new)->count()) {
            $this->info("Poi di " . $hotel->nome . " Solo nuovi n. = " . $hotel->poi_new($this->only_new)->count());
            foreach ($hotel->poi_new($this->only_new)->get() as $p) {

                $url = $this->base_url_directions . '?origin=' . $hotel->mappa_latitudine . '%2C' . $hotel->mappa_longitudine;

                //dd($p->toArray());
                
                $data_pivot = $p->pivot->toArray();
                /**
                 * 
                 * array:7 [
                 * "hotel_id" => 17
                 * "poi_id" => 1
                 * "distanza" => "9.03"
                 * "g_distanza" => null
                 * "g_durata" => null
                 * "g_descrizione_rotta" => null
                 * "g_modo" => null
                 * ]
                 * 
                 */
                
           
                $result_arr =  $this->_chiamataDirectionsAPI($url, $p);
                      
                
                if ($result_arr['status'] == "OK") {

                    $routes = $result_arr['routes'];
                    
                    $data_pivot['g_distanza'] = $routes[0]['legs'][0]['distance']['text'];
                    $data_pivot['g_durata'] = $routes[0]['legs'][0]['duration']['text'];
                    $data_pivot['g_distanza_numeric'] = $routes[0]['legs'][0]['distance']['value'];
                    $data_pivot['g_durata_numeric'] = $routes[0]['legs'][0]['duration']['value'];
                    $data_pivot['g_descrizione_rotta'] = $routes[0]['summary'];
                    $data_pivot['g_modo'] = $result_arr['mode'];
                    $data_pivot['g_url'] = $result_arr['url'];


                    //? Aggiorno i dati di pivot 
                    $hotel->poi_new($this->only_new)->updateExistingPivot($p->id, $data_pivot);
                    
                    $this->info("Dati del Poi " . $p->id . " aggiornati!");

                } else {
                    $this->error("Errore risposta Google API per POI id " . $p->id);
                }
            }
        } else {
            if ($this->only_new) {
                $this->info($hotel->nome . " NON ha nessun NUOVO POI.");
            } else {
                $this->info($hotel->nome. " NON ha nessun POI. Prima devi assegnarli da admin in base alla località della struttura.");
                
            }
            
        }
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //! valorizzato se lo passo
        //! NULL altrimenti

        $options = $this->options();


        $hotel_id = $options['hotel_id'];

        $ids = $options['id'];

        $new = $options['new'];

        $this->only_new = $new;
        
        $fields_to_select = ['id as hotel_id', 'localita_id', 'mappa_latitudine as lat', 'mappa_longitudine as lng'];
        $hotels = null;


        if ( !is_null($hotel_id) || count($ids) ) {

            if (!is_null($hotel_id)) {

                $this->info("Hai passato il  parametro " . $hotel_id);

                $hotels = Hotel::select($fields_to_select)
                    ->where('id', $hotel_id)
                    ->get()
                    ->toArray();
            } else {
                $this->info("Hai passato un array di id: " . implode(',', $ids));

                $hotels = Hotel::select($fields_to_select)
                    ->whereIn('id', $ids)
                    ->get()
                    ->toArray();
            }

            // remove un-used attributes
            $h = new Hotel;
            //dd($h->appendedAttributes());
            foreach ($hotels as $key => $hotel) {
                foreach ($h->appendedAttributes() as $attr) {
                    unset($hotel[$attr]);
                }
                $hotels[$key] = $hotel;
            }

            foreach ($hotels as $hotel) {
                $this->_trovaDistanzePoi($hotel);
            }


            
        } else {



            //? //////////////////////////////////////////////
            //? SELEZIONE DEGLI HOTEL TRAMITE MACROLOCALITA //
            //? //////////////////////////////////////////////

            //$m = [];
            $m = Macrolocalita::noRR()->get()->pluck('nome', 'id')->toArray();
            // $macros = Macrolocalita::noRR()->get();

            // //? nome_ita: accessor per il nome in ita anche con il json
            // foreach ($macros as $macro) {
            //     $m[$macro->id] = $macro->nome_ita;
            // }
            

            $macro_nome = $this->choice('Quale macrolocalità ? (default tutte)', ['Tutte'] + $m, 0);

            $macro_nome != 'Tutte' ? $macrolocalita_id = Macrolocalita::where('nome', $macro_nome)->pluck('id')->first() : $macrolocalita_id = 0;

            if ($macrolocalita_id) {
                $hotels = Hotel::select($fields_to_select)
                    ->whereHas('localita.macrolocalita', function ($query)  use ($macrolocalita_id) {
                        $query->where('tblMacrolocalita.id', $macrolocalita_id);
                    })
                    ->where('attivo', 1)
                    //->where('categoria_id', 1)
                    //->where('id', 1232)
                    ->get()
                    ->toArray();
            } else {
                $hotels = Hotel::select($fields_to_select)
                    ->where('attivo', 1)
                    //->where('categoria_id', 1)
                    //->where('id', 1232)
                    ->get()
                    ->toArray();
            }


            if ($this->confirm('Verranno calcolati i tempi di raggiungimento di TUTTI I POI di ' . count($hotels) . ' hotel. Continuare ?', true)) {

                $bar = $this->output->createProgressBar(count($hotels));

                // remove un-used attributes
                $h = new Hotel;
                //dd($h->appendedAttributes());
                foreach ($hotels as $key => $hotel) {
                    foreach ($h->appendedAttributes() as $attr) {
                        unset($hotel[$attr]);
                    }
                    $hotels[$key] = $hotel;
                }

                //dd($hotels[0]);

                $bar->start();

                foreach ($hotels as $hotel) {

                    $this->_trovaDistanzePoi($hotel);

                    $bar->advance();
                }

                $bar->finish();
            }


        } // if/else passato hotel_id
        




       

    }// handle()
}

<?php

namespace App\Console\Commands;

use App\Hotel;
use App\Macrolocalita;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class GooglePlaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:place {--hotel_id=} {--id=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    //? Options, like arguments, are another form of user input. 
    //? Options are prefixed by two hyphens (--) when they are provided via the command line

    //? In this example, the user may pass a value for the option. 
    //? google:place --hotel_id=17
    //? If the option is not specified when invoking the command, its value will be null:
    protected $description = 'Ricerca nelle vicinanze tramite Goolgle Place Search. Accetta "--hotel_id=" come ID di un hotel (google:place --hotel_id=17) OPPURE MOLTI ID "--id=" nella forma (google:place --id=17 --id=234)';

    public $api_key;
    public $radius;
    public $base_url_place;
    public $lat;
    public $lng;
    public $hotel_id;

    public $types;
    public $filter_type;
    public $maxnumber_type;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->api_key = Config::get("google.googlekey");
        $this->radius = 1500;
        $this->base_url_place = "https://maps.googleapis.com/maps/api/place/nearbysearch/json";
        $this->base_url_directions = "https://maps.googleapis.com/maps/api/directions/json";

        //? Elenco dei tipi di luogo con cui interrogare le API di Google 
        $this->types = [
            'supermarket',
            'post_office',
            'cafe',
            'restaurant',
            'church',
            'park',
            'laundry',
        ];

        // $this->types = [
        //     'bus_station',
        // ];

        //? Ogni tipo può avere un insieme di type da filtrare: 
        //? ad esempio nei restaurant ESCLUDO tutti quelli che hanno come type (di google) lodging
        $this->filter_type = [
            'restaurant' => 'lodging',
            'park' => 'lodging',
        ];

        $this->maxnumber_type = [
            'supermarket' => 1,
            'post_office' => 1,
            'cafe' => 2,
            'restaurant' => 2,
            'church' => 1,
            'park' => 1,
            'laundry' => 1,
        ];


        
        //? coordinate H. Sabrina Rimini ID = 17
        // $this->lat = 44.0562309;
        // $this->lng = 12.5857937;
        // $this->hotel_id = 17;

        //? coordinate H. Armani Milano ID = 0
        // $this->lat = 45.47079637985694;
        // $this->lng = 9.192877169746758;
        // $this->hotel_id = 0;

        parent::__construct();
    }

    /**
     * Effettua la chiamata cUrl in post all'url passato
     *
     * @param [type] $url
     * @return associative_array del json
     */
    private function _getCurlRequest($url) {
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


    /**
     * Effettua le 2 chimate alle API "Place" e "Directions"
     * per trovare i luoghi e le distanze
     * 
     * $hotel = [
     *  "id" => 1875
     *  "localita_id" => 50
     *  "mappa_latitudine" => "43.915679"
     *  "mappa_longitudine" => "12.916036"
     *   ]
     *
     * @return void
     */
    private function _trovaGooglePoi($hotel = null) {

        if (!is_null($hotel)) {

            //? Fermate dei bus
            //$url = $this->base_url_place . '?location='. $this->lat .'%2C'. $this->lng . '&rankby=distance&type=transit_station&key='.$this->api_key;

            //? BANCOMAT
            //$url = $this->base_url_place . '?location='. $this->lat .'%2C'. $this->lng . '&rankby=distance&type=atm&key='.$this->api_key;

            //? RISTORANTI 
            //$url = $this->base_url_place . '?location=' . $this->lat . '%2C' . $this->lng . '&rankby=distance&type=restaurant&key=' . $this->api_key;

            //? PARCHI
            //$url = $this->base_url_place . '?location=' . $this->lat . '%2C' . $this->lng . '&rankby=distance&type=park&key=' . $this->api_key;

            //? BAR
            //$url = $this->base_url_place . '?location='. $this->lat .'%2C'. $this->lng . '&rankby=distance&type=bar&key='.$this->api_key;


            $base_url = $this->base_url_place . '?location=' . $hotel["lat"] . '%2C' . $hotel["lng"] . '&rankby=distance&key=' . $this->api_key;

            $db_data = [];

            //foreach (['pharmacy'] as $type) {
            foreach ($this->types as $type) {

                $this->info("Ricerca nearbysearch di " . $type);

                $exclude_type = null;
                $maxnumber_type = 0;

                if (isset($this->filter_type[$type])) {
                    $exclude_type = $this->filter_type[$type];
                }


                if (isset($this->maxnumber_type[$type])) {
                    $maxnumber_type = $this->maxnumber_type[$type];
                }



                $url = $base_url . "&type=$type";

                $result_arr = $this->_getCurlRequest($url);


                $n = 1;
                foreach ($result_arr['results'] as $res) {
                    //$this->info($res['name']);

                    //? Se non c'è niente da escludere per questo tipo
                    //? Oppure il tipo da escludere non è fra i types
                    if (is_null($exclude_type) || !in_array($exclude_type, $res['types'])) {

                        if ($maxnumber_type == 0 || $n <= $maxnumber_type) {

                            $db_data[] = [
                                'hotel_id' => $hotel["hotel_id"],
                                'type' => $type,
                                'name' => $res['name'],
                                'address' => addslashes($res['vicinity']),
                                'lat' => $res['geometry']['location']['lat'],
                                'lng' => $res['geometry']['location']['lng'],
                                'place_id' => $res['place_id'],
                                'google_types' => implode(",", $res['types']),
                                'google_results' => json_encode($res),
                            ];

                            $n++;
                        } else {

                            break;
                        }
                    }
                }
            } // endofor types   

            // https://maps.googleapis.com/maps/api/directions/json?origin=41.43206%2C-81.38992&destination=place_id:ChIJUzirzwjDLBMR3_EP2Z82JYE&language=it&mode=walking

            foreach ($db_data as $key => $elem) {

                $this->info("Distanza di " . $elem['name']);


                $url = $this->base_url_directions . '?origin=' . $hotel["lat"] . '%2C' . $hotel["lng"] . '&destination=place_id:' . $elem['place_id'] . '&language=it&mode=walking&key=' . $this->api_key;


                $result_arr = $this->_getCurlRequest($url);

                // dd($result_arr['status']);

                if ($result_arr['status'] == "OK") {

                    //? Risposta con le direzioni da "attaccare" a $elem
                    $distances = [];

                    $routes = $result_arr['routes'];
                    foreach ($routes as $route) {
                        //! Prendo solo la prima rotta a piedi
                        $distances[] = $route['legs'][0]['distance']['text'] . ': ' . $route['legs'][0]['duration']['text'] . ' a piedi';
                    }

                    $elem['distances'] = implode(",", $distances);
                    $elem['google_routes'] = json_encode($routes);

                    //! In alcuni type l'indirizzo dalla rotta è più preciso di quello del searchnearby
                    $elem['end_address'] = $route['legs'][0]['end_address'];

                    $db_data[$key] = $elem;
                }
            }

            //dd($db_data);

            DB::table('tblGooglePoi')->where('hotel_id', $hotel["hotel_id"])->delete();
            DB::table('tblGooglePoi')->insert($db_data);

            $this->info("Inserite " . count($db_data) . " righe per hotel " . $hotel["hotel_id"]);

            
        } else {
            $this->info("Hotel di cui trovare i POI è nullo !!???");
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

        //dd($options);

        $hotel_id = $options['hotel_id'];

        $ids = $options['id'];

        $hotels = null;
        $fields_to_select = ['id as hotel_id', 'localita_id', 'mappa_latitudine as lat', 'mappa_longitudine as lng'];

        if ( !is_null($hotel_id) || count($ids) ) {

            if (!is_null($hotel_id) ) {

                $this->info("Hai passato il  parametro " . $hotel_id);
                
                $hotels = Hotel::select($fields_to_select)
                    ->where('id', $hotel_id)
                    ->get()
                    ->toArray();
                
            } else {
                $this->info( "Hai passato un array di id: " . implode(',', $ids) );

                $hotels = Hotel::select($fields_to_select)
                    ->whereIn('id', $ids)
                    ->get()
                    ->toArray();
            }
            

            $h = new Hotel;
           
            foreach ($hotels as $key => $hotel) {
                foreach ($h->appendedAttributes() as $attr) {
                    unset($hotel[$attr]);
                }
                $hotels[$key] = $hotel;
            }

            foreach ($hotels as $hotel) {
                $this->_trovaGooglePoi($hotel);
            }
        
        } else {
            //? //////////////////////////////////////////////
            //? SELEZIONE DEGLI HOTEL TRAMITE MACROLOCALITA //
            //? //////////////////////////////////////////////

            // $m = [];
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
                    ->get()
                    ->toArray();
            } else {
                $hotels = Hotel::select($fields_to_select)
                    ->where('attivo', 1)
                    //->where('categoria_id', 1)
                    ->get()
                    ->toArray();
            }


            if ($this->confirm('Verranno cercati i Google POI per ' . count($hotels) . ' hotel. Continuare ?', true)) {

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
                    $this->_trovaGooglePoi($hotel);
                    $bar->advance();
                }

                $bar->finish();
            }
        } // if/else passato hotel_id

        

        

    } // end hanlde()

} // end class GooglePlaces

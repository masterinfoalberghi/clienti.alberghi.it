<?php

namespace App\Console\Commands;

use App\DescrizioneHotel;
use App\DescrizioneHotelLingua;
use App\Hotel;
use App\Macrolocalita;
use Google\Cloud\Translate\TranslateClient;
use Illuminate\Console\Command;

class TranslateSchedaHotel_INIZIALE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:scheda_hotel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Traduce il campo testo nella tabella descrizioneHotel_lingua';


    private $static_translation = [];
    
    private $glossary = [];



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        //////////////////////////////////////////////////////
        // ARRAY DELLE TRADUZIONI DEI TITOLOI DEI PARAGRAFI //
        //////////////////////////////////////////////////////
        $this->static_translation['DESCRIZIONE'] = 'DESCRIPTION';
        $this->static_translation['POSIZIONE'] = 'LOCATION';
        $this->static_translation['CAMERE'] = 'ROOMS';
        $this->static_translation['SERVIZI'] = 'SERVICES';
        $this->static_translation['CUCINA'] = 'FOOD';
        $this->static_translation['BAMBINI'] = 'KIDS';
        $this->static_translation['PISCINA'] = 'POOL';
        $this->static_translation['PISCINE'] = 'POOLS';
        $this->static_translation['BENESSERE'] = 'WELLNESS';
        $this->static_translation['SPIAGGIA'] = 'BEACH';
        $this->static_translation['CICLOTURISMO'] = 'CYCLE TOURISM';
        $this->static_translation['APPARTAMENTI'] = 'APARTMENTS';

        $this->glossary['dedicates much attention'] = 'pays special attention';
        $this->glossary['umbrellas'] = 'beach umbrella';
        $this->glossary['available upon request and upon request'] = 'available for a fee and on request';
        $this->glossary['bicycles for free use'] = 'free use of bicycles';
        $this->glossary['bicycles in use'] = 'free use of bicycles';
        $this->glossary['accompanied by a buffet of'] = 'followed by a buffet of';
        $this->glossary['typical Romagna dinner'] = 'typical local dinner';
        $this->glossary['play area'] = 'playground area';
        $this->glossary['pine forest'] = 'pine grove';
        $this->glossary['pinewood'] = 'pine grove';
        $this->glossary['Mirabilandia park'] = 'Mirabilandia amusement park';
        $this->glossary['free in all the structure'] = 'free throughout the entire structure';
        $this->glossary['water slide'] = 'waterslide';
        $this->glossary['Natural Park of the Po Delta'] = 'Po Delta Wildlife Park';
        $this->glossary['whirlpool'] = 'hydromassage';
        $this->glossary['equiped solarium'] = 'solarium facilities';
        $this->glossary['veranda'] = 'open-air porch';
        $this->glossary['Veranda'] = 'Open-air porch';
        $this->glossary['throughout the hotel'] = 'throughout the entire hotel';
        $this->glossary['Disabled Services'] = 'Services for people with disabilities';
        $this->glossary['DISABLED SERVICES'] = 'SERVICES FOR PEOPLE WITH DISABILITIES';
        $this->glossary['typical Romagna dinner'] = 'typical local dinner';
        $this->glossary['bike available'] = 'bikes available';
        $this->glossary['theme evenings'] = 'theme nights';
        $this->glossary['overlooking the pedestrian area'] = 'facing the traffic-free zone';
        $this->glossary['in addition to the services already listed'] = 'besides the services already listed';
        $this->glossary['nursery cabin'] = 'baby changing room';
        $this->glossary['bowling alleys'] = 'bocce courts';
        $this->glossary['bowling alley'] = 'bocce court';
        $this->glossary['bowling green'] = 'bocce courts';
        $this->glossary['while places last'] = '(subject to availability)';
        $this->glossary['according to the Romagna tradition'] = 'according to the local tradition';
        $this->glossary['Romagna cuisine'] = 'local cuisine';
        $this->glossary['in a side of the'] = 'on a cross street of the';
        $this->glossary['The Le Navi Aquarium'] = 'The Aquarium called Le Navi';
        $this->glossary['animation'] = 'live entertainment';
        $this->glossary['animation service'] = 'entertainment service';
        $this->glossary['mountain crystals bath'] = 'mountain crystals baths';
        $this->glossary['the square of dancing fountains'] = 'the dancing fountains of 1° May square';
        $this->glossary['partner beach'] = 'beah affiliated with the hotel';
        $this->glossary['(with linen)'] = '(with bedding and linen)';
        $this->glossary['North Sea Park'] = 'Mare Nord Park';
        $this->glossary['have all the services listed'] = 'have all the services listed above';
        $this->glossary['the services already listed add'] = 'besides the services already listed they have';
        $this->glossary['"LCD'] = '" LCD';
        $this->glossary['can be reached without road crossings'] = 'can be reached without crossing a road';
        $this->glossary['overlooking the sea or the sea'] = 'overlooking the sea';
        $this->glossary['annual 3-star hotel'] = '3-star hotel open all year long';
        $this->glossary['annual 2-star hotel'] = '2-star hotel open all year long';
        $this->glossary['annual 4-star hotel'] = '4-star hotel open all year long';
        $this->glossary['Among the many services offered:'] = 'It offers:';
        $this->glossary['located on the mountain side'] = 'facing the mountain side';
        $this->glossary['a paddle fan'] = 'a fan';
        $this->glossary['the Santamonica, Oltremare and Aquafan racetracks'] = 'the Misano World Circuit, Oltremare and Aquafan';
        $this->glossary['the Santamonica racetrack'] = 'Misano World Circuit';
        $this->glossary['double depth'] = 'double-depth';
        $this->glossary['water blades for cervical massage'] = 'cervical water-blade';
        $this->glossary['bathhouse'] = 'bathing establishment';
        $this->glossary['Leonardesco Canal Port'] = 'Leonardo da Vinci Canal Harbour';
        $this->glossary['bland Terraza'] = 'Terrazza Blanca';
        $this->glossary['Atlantica water park'] = 'Atlantica Water Park';
        $this->glossary['to the Green Booking and'] = 'to the Green Booking project and';
        $this->glossary['white cruise'] = 'white cruise party';
        $this->glossary['entry to Atlantica'] = 'entry to Atlantica Water Park';
        $this->glossary['discounts for Mirabilandia and'] = 'discounts for Mirabilandia Amusement Park and';
        $this->glossary[', clothesline,'] = ', indoor clothesline,';
        $this->glossary['Romagna cuisine'] = 'local cuisine';
        $this->glossary['at km 0'] = '"farm-to-table"';
        $this->glossary['Levante park'] = 'park of Levante';
        $this->glossary['donuts'] = 'ring-shaped cake';
        $this->glossary['arredamento moderno'] = 'furnished in modern way';
        $this->glossary['pool with shallow water pool'] = 'pool with shallow water';
        $this->glossary['cribs with beds'] = 'cribs with bed rails';
        $this->glossary['beach private'] = 'private beach';
        $this->glossary['for eating al fresco open'] = 'for eating outside';
        $this->glossary['Gelso Sport sports center'] = '"Gelso Sport" sports center';
        $this->glossary['international buffet with'] = 'international buffet breakfast with';
        $this->glossary['has a solrium terrace'] = 'has a solarium terrace';
        $this->glossary['with two basins'] = 'with two tubs';
        $this->glossary['shower fungus'] = 'mushroom shower';
        $this->glossary['foosball'] = 'table football'; 
        $this->glossary['a private driveway in the countryside'] = 'a private driveway surrounded by trees';
        $this->glossary['communicating rooms'] = 'connecting rooms';
        $this->glossary['in all the formulas'] = 'in every accommodation package';
        $this->glossary['prepared according to Romagna tradition'] = 'cooked according to the local tradition';
        $this->glossary['without having to cross streets'] = 'without having to cross the street';
        $this->glossary['is in the first line on the seafront of'] = 'overlooks the seafront of';
        $this->glossary['with a family management'] = 'family-run';
        $this->glossary['Adheres to AIC'] = 'Adheres to AIC (the Italian Coeliac Association)';
        $this->glossary['adheres to AIC'] = 'adheres to AIC (the Italian Coeliac Association)';
        $this->glossary['chip card locks'] = 'chip-card locks';
        $this->glossary['two paths full of shops'] = 'two main streets full of shops';
        $this->glossary['different types of living room'] = 'different types of accommodation packages';
        $this->glossary['blower tub'] = 'hot tub blower';
        $this->glossary['periodic outdoor dinners'] = 'recurring outdoor dinners';
        $this->glossary['hotel formula'] = 'hotel accommodation package';
        $this->glossary['Italy in Miniature'] = 'Italia in Miniatura (miniature park)';
        $this->glossary['(AIC certification)'] = '(certified by Italian Coeliac Association)';
        $this->glossary['Sport Center Gelso Sport'] = '"Gelso Sport" sports center';

    }



    private function _applicaGlossario(&$translation)
      {
      $arr_needle = array_keys($this->glossary);
      $arr_replace = array_values($this->glossary);

      $translation = str_replace($arr_needle, $arr_replace, $translation);
      }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
      {

      $target = $this->choice('in quale lingua vuoi traduure (en, fr, de| default en)?', ['en', 'fr', 'de'], 0);
      $m = Macrolocalita::noRR()->get()->pluck('nome','id')->toArray();

      $macro_nome = $this->choice('Quale macrolocalità ? (default tutte)', ['Tutte'] + $m, 0);

      $macro_nome != 'Tutte' ? $macrolocalita_id = Macrolocalita::where('nome',$macro_nome)->pluck('id')->first() : $macrolocalita_id = 0;    

      $credentialFile= base_path('GoogleTranslateClient.json');

      //dd($credentialFile);

      putenv("GOOGLE_APPLICATION_CREDENTIALS=$credentialFile");

      # Your Google Cloud Platform project ID
      $projectId = 'api-project-868696641806';

      # Instantiates a client
      $translate = new TranslateClient([
          'projectId' => $projectId
      ]);


      $hotel_ids = null;

      if($macrolocalita_id)
        {
          
        $hotel_ids = Hotel::whereHas('localita.macrolocalita', function ($query)  use ($macrolocalita_id) {
            $query->where('tblMacrolocalita.id', $macrolocalita_id);
        })->where('attivo',1)->pluck('id')->toArray();

        /*$hotel_ids = array('301','1377','113','284','1026','1105','723','98','1335','1376','278','746','559','842','802','1161','1135','807','279','115','1686','1314','1709','1720','694','1531','1272','479','1711','461','251','527','519','403','252','1250','1696','371','1052','1569','1212','1163','1673','840','130','1513','876','537', '185', '599', '635', '327', '1668', '876');*/

        }

      //dd(str_replace_array('?', $hotel_ids->getBindings(), $hotel_ids->toSql()));



      $descrizioni_hotel = DescrizioneHotel::with(
                                                  [
                                                  'descrizioneHotel_lingua'  => function ($query) {
                                                          $query->where('lang_id', 'it');
                                                      },
                                                  ]
                                              )
                                            ->whereHas('hotel', function ($query) {
                                                  $query->where('tblHotel.attivo', 1);
                                              });

      if (!is_null($hotel_ids)) 
        {
        $descrizioni_hotel 
        ->whereIn('tblDescrizioneHotel.hotel_id',$hotel_ids);
        }
        
        $descrizioni_hotel = $descrizioni_hotel
                              //->limit(2)
                              ->get();
      
        //dd(str_replace_array('?', $descrizioni_hotel->getBindings(), $descrizioni_hotel->toSql()));
        

      //dd($descrizioni_hotel->count());

    

      $this->info('Hotel totali '.$descrizioni_hotel->count());

      $traduzioni_hotel = [];

      $caratteri = 0;
      
      foreach ($descrizioni_hotel as $descrizioni) 
          {

          $descrizioni_json = json_decode($descrizioni->descrizioneHotel_lingua->first()->testo);

          $source='it';

          

          foreach ($descrizioni_json as $key => $desc) 
              {

              # The text to translate
              $testo = $desc->testo;


              $caratteri += strlen($testo);

              
              $translation = $translate->translate($testo, [
                  'source' => $source,
                  'target' => $target
              ]);

              $tradotto = $translation['text'];

              $this->_applicaGlossario($tradotto);

              $desc->testo = $tradotto;


              $desc->title = array_key_exists($desc->title, $this->static_translation) ? $this->static_translation[$desc->title] : $desc->title;
              $desc->subtitle = array_key_exists($desc->subtitle, $this->static_translation) ? $this->static_translation[$desc->subtitle] : $desc->subtitle;

              }

              $traduzioni_hotel[$descrizioni->id."|".$descrizioni->hotel_id] = $descrizioni_json;


              $this->info('Applicato glossario a hotel ID = '.$descrizioni->hotel_id);


      
          }


      $this->info('Caratteri da tradurre '.$caratteri);

      $bar = $this->output->createProgressBar(count($traduzioni_hotel));

      $bar->start();

      foreach ($traduzioni_hotel as $key => $traduzione) 
        {
        list($master_id, $hotel_id) = explode('|', $key);
        
        DescrizioneHotelLingua::where('master_id', $master_id)
                  ->where('lang_id', $target)
                  ->update(['testo' => json_encode($traduzione)]);

        $this->info('Traduzione hotel ID '.$hotel_id);

        $bar->advance();

        }

      $bar->finish();

      }
}

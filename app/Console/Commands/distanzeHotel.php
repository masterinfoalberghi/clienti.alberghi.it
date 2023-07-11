<?php

namespace App\Console\Commands;

use App\Poi;
use App\Hotel;
use App\Utility;
use Illuminate\Console\Command;

class distanzeHotel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotel:distanze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hotels = Hotel::with("localita")->where("id", ">", "20000")->get();
        foreach($hotels as $hotel) {

            $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $hotel->localita->centro_lat, $hotel->localita->centro_long);
            $hotel->distanza_centro = round($distanza_metri / 1000, 2);

            $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $hotel->localita->staz_lat, $hotel->localita->staz_long);
            $hotel->distanza_staz = round($distanza_metri / 1000, 2);

            $fiera = Poi::where("id", 56)->first();
            $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $fiera->lat, $fiera->long);
            $hotel->distanza_fiera = round($distanza_metri / 1000, 2);
          
            $caselli = [];

            $caselli_item = new \stdClass;
            $caselli_item->lat = 44.53063830511639;
            $caselli_item->long = 11.36053339804212;
            $caselli_item->name = "Bologna Arcoveggio";
            $caselli[] = $caselli_item;

            $caselli_item = new \stdClass;
            $caselli_item->lat = 44.59388513161618;
            $caselli_item->long = 11.403574156263794;
            $caselli_item->name = "Bologna Interporto";
            $caselli[] = $caselli_item;

            $caselli_item = new \stdClass;
            $caselli_item->lat = 44.48183932146115;
            $caselli_item->long = 11.426365913612292;
            $caselli_item->name = "Bologna S.Lazzaro";
            $caselli[] = $caselli_item;

            $caselli_item = new \stdClass;
            $caselli_item->lat = 44.52446160613582;
            $caselli_item->long = 11.24549954059821;
            $caselli_item->name = "Bologna Borgo Panigale";
            $caselli[] = $caselli_item;

            $caselli_item = new \stdClass;
            $caselli_item->lat = 44.51625563522576;
            $caselli_item->long = 11.371630740597997;
            $caselli_item->name = "Bologna Fiera";
            $caselli[] = $caselli_item;


            $distanza = 10000000;
            foreach ($caselli as $casello) :
                $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $casello->lat, $casello->long);
                if ($distanza_metri < $distanza) {
                    $hotel->distanza_casello =  round($distanza_metri / 1000, 2);
                    $hotel->distanza_casello_label = $casello->name;
                    $distanza = $distanza_metri;
                }
            endforeach;
            $hotel->save();

        }
    }
}

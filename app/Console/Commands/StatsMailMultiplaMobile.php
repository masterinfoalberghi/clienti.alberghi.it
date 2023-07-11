<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\StatsHotelMailMultipla;
use App\Hotel;
use Illuminate\Support\Facades\DB;

class StatsMailMultiplaMobile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:regenerate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rigenera tutte le statistiche sulle email multiple mobile';

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
     * @return mixed
     */
    public function handle()
    {
        
        /**
         * Cosa devo creare
         * 
         * hotel_id / n_mail  / giorno / mese / anno / tipologia
         */

        /**
         * Tronco la tabella delle statistiche
         */

        //StatsHotelMailMultipla::query()->truncate();

        DB::table("tblStatsHotelMailMultiple")->where('created_at', '>=', "2017-07-24")->delete();

        /**
         * Prendo tutti gli hotel ( anche i diasattivi ?  )
         */

        $hotels = Hotel::attivo()->get();

        /**
         * Prendo la data di partenza
         */

        $startTime = strtotime( '2017-07-24' );
        $endTime = strtotime( '2018-02-03' );

        foreach($hotels as $hotel):

            echo $hotel->id . PHP_EOL;

            // Loop between timestamps, 24 hours at a time
            for ( $i = $endTime; $i >= $startTime; $i = $i - 86400 ):

                $date0 =  date( 'Y-m-d 00:00:00', $i );
                $date24 =  date( 'Y-m-d 23:59:59', $i );

                $tipoMail = DB::table("email_multiple")
                    ->select("tipologia")
                    ->where("created_at",">=", $date0)
                    ->where("created_at","<=", $date24)
                    ->where("hotel_id", $hotel->id)
                    ->where(function($query){
                        $query
                            ->where('tipologia', 'wishlist')
                            ->orWhere('tipologia', 'normale')
                            ->orWhere('tipologia', 'mobile');
                    })
                    ->get();

                // echo $date0 . PHP_EOL;

                $tipologie = array();
                $tipologie["wishlist"] = 0;
                $tipologie["normale"] = 0;
                $tipologie["mobile"] = 0;

                foreach($tipoMail as $tipo) {
                    $tipologie[$tipo->tipologia] += 1;
                }

                foreach($tipologie as $key => $conteggio) {

                    if ($conteggio > 0) {

                        // echo $hotel->id . " " . 
                        //      $conteggio . " " . 
                        //      date( 'd', $i ) . " " . 
                        //      date( 'm', $i ) . " " . 
                        //      date( 'Y', $i ) . " " . 
                        //      $key  . " " . 
                        //      date("Y-m-d") . " " . 
                        //      date("Y-m-d") . PHP_EOL;
                   
                        DB::table("tblStatsHotelMailMultiple")->insert(
                            [
                                 "hotel_id" => $hotel->id, 
                                 "n_mail" => $conteggio, 
                                 "giorno" => date( 'd', $i ), 
                                 "mese" => date( 'm', $i ), 
                                 "anno" => date( 'Y', $i ), 
                                 "tipologia" => $key , 
                                 "created_at" => date("Y-m-d"), 
                                 "updated_at" => date("Y-m-d")
                            ]
                        );
                    } 

                }

            endfor;

        endforeach;

    }
}

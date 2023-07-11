<?php

namespace App\Console\Commands;

use App\Hotel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggiornaRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rating:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggiorna il rating hotel';

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
        $hotels = Hotel::with("rating")->get();

        $ratingsResults = [];
        $reviewsResults = [];

        /** Ciclo su gli hotel  */
        foreach($hotels as $hotel):

            /** predo tutti i rating importati partendo dall'ultimo */
            $ratingObject = $hotel->rating->toArray();
            $ratings = array_reverse($ratingObject);      

            $ratingsResult = [0,0,0,0,0];
            $reviewsResult = [0,0,0,0,0];

            /** Ciclo su tutti */
            foreach($ratings as $rating):

                /** Riporto i valori a dei tipo double */
                $valori = json_decode($rating["punteggio"]);
                $t = 0;
                
                foreach($valori as $valore):
                    $valori[$t] = (double) str_replace(",",".",$valore);
                    $t++;
                endforeach;

                /** 
                 * ciclo su tuti i rating  
                 * se sono uguale a 0 allora procedo con il rating precidente 
                 */

                $ratingsResult[0] == 0 && $valori[0] > 0 && $valori[1] > 0 ? $ratingsResult[0] = round(($valori[0] * $valori[1]),2) : NULL;
                $ratingsResult[1] == 0 && $valori[2] > 0 && $valori[3] > 0 ? $ratingsResult[1] = round(($valori[2] * $valori[3]),2) : NULL;
                $ratingsResult[2] == 0 && $valori[4] > 0 && $valori[5] > 0 ? $ratingsResult[2] = round(($valori[4] * $valori[5]),2) : NULL;
                $ratingsResult[3] == 0 && $valori[6] > 0 && $valori[7] > 0 ? $ratingsResult[3] = round(($valori[6] * $valori[7]),2) : NULL;
                $ratingsResult[4] == 0 && $valori[8] > 0 && $valori[9] > 0 ? $ratingsResult[4] = round(($valori[8] * $valori[9]),2) : NULL;

                $reviewsResult[0] == 0 && $valori[0] > 0 && $valori[1] > 0 ? $reviewsResult[0] = round($valori[1],2) : NULL;
                $reviewsResult[1] == 0 && $valori[2] > 0 && $valori[3] > 0 ? $reviewsResult[1] = round($valori[3],2) : NULL;
                $reviewsResult[2] == 0 && $valori[4] > 0 && $valori[5] > 0 ? $reviewsResult[2] = round($valori[5],2) : NULL;
                $reviewsResult[3] == 0 && $valori[6] > 0 && $valori[7] > 0 ? $reviewsResult[3] = round($valori[7],2) : NULL;
                $reviewsResult[4] == 0 && $valori[8] > 0 && $valori[9] > 0 ? $reviewsResult[4] = round($valori[9],2) : NULL;

            endforeach;
            
            $ratingsResults[$hotel->id] = $ratingsResult;
            $reviewsResults[$hotel->id] = $reviewsResult;

        endforeach;

        $data = [];

        foreach($hotels as $hotel):
            
            $media_votanti = (   
                $ratingsResults[$hotel->id][0] + 
                $ratingsResults[$hotel->id][1] + 
                $ratingsResults[$hotel->id][2] + 
                $ratingsResults[$hotel->id][3] + 
                $ratingsResults[$hotel->id][4]
            );

            $numero_votanti = (
                $reviewsResults[$hotel->id][0] + 
                $reviewsResults[$hotel->id][1] + 
                $reviewsResults[$hotel->id][2] +
                $reviewsResults[$hotel->id][3] + 
                $reviewsResults[$hotel->id][4]
            );

            if($media_votanti > 0 && $numero_votanti > 0)
                $media_rating = $media_votanti / $numero_votanti;
            else 
                $media_rating = 0;

            $fonti = 0;
            foreach($ratingsResults[$hotel->id] as $values) {
                if($values > 0) $fonti++;
            }

            DB::table("tblHotel")
                ->where("id", $hotel->id)
                ->update(["rating_ia" => $media_rating, "n_source_rating_ia" => $fonti, "n_rating_ia" => $numero_votanti ]);


        endforeach;

      
    }
}

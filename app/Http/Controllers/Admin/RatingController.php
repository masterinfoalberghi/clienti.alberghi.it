<?php

namespace App\Http\Controllers\Admin;

use DB;
use Log;
use Auth;
use Utility;
use App\Hotel;
use Validator;
use App\Rating;
use App\Localita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SessionResponseMessages;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class RatingController extends AdminBaseController
{   
    /**
     * Torna le localita
     * 
     * @access protected
     * @return Array
     */

    protected function getLocalitaForView()
    {
        
        $_localita = [];
        $localita = Localita::orderBy("nome", "asc")->get();
        foreach ($localita as $l) {
            $_localita[$l->id] = "{$l->nome} ({$l->prov}) - {$l->cap}";
        }

        return $_localita;

    }

    /**
     * Crea il file csv per il download
     *
     * @access private
     * @param Hotel $hotels
     * @return Array
     */

    private function _getFileCsv($hotels)
    {

        $csv = [];
        $hotels_array = [];
        $max_source = 4;

        /**
         * Costruisco il csv
         * Per prima cosa devo capire quanti campi dinamici di sorgente hanno gli hotel
         * Quindi ciclo per prendere il numero massimo
         **/

        foreach ($hotels as $hotel) {
            if (!is_null($hotel->source_rating_ia)) {
                $n_source = json_decode($hotel->source_rating_ia, true);
                if (!is_null($n_source)) {
                    if (is_array($n_source) && count($n_source) > $max_source) {
                        $max_source = count($n_source);
                    }
                }

            }
        }

        /**
         * ciclo per creare il csv
         */

        $columns = [];
        $columns[] = "hotel_id";

        for ($t = 0; $t < $max_source; $t++) {
            $columns[] = "url_" . ($t+1);
        } 

        foreach ($hotels as $hotel) {

            $csv_row = [];
            $csv_row[] = $hotel->id;

            if (is_null($hotel->source_rating_ia)) {

                for ($t = 0; $t < $max_source; $t++) {
                    $csv_row[] = "";
                }

            } else {

                $sources = json_decode($hotel->source_rating_ia, true);
                $n_sources = count($sources);
                $rest_sources = $max_source - $n_sources;
                $x = 0;
                
                foreach ($sources as $s) {
                    $csv_row[] = $s;
                    $x++;
                }

                if ($rest_sources > 0) {
                    for ($t = 0; $t < $rest_sources; $t++) {
                        $csv_row[] = "";
                        $x++;
                    }
                }

            }

            /** Rioridina per santiago */
            $csv_new_row = [];
            $csv_new_row[0] = $csv_row[0];
            $csv_new_row[1] = "";
            $csv_new_row[2] = "";
            $csv_new_row[3] = "";
            $csv_new_row[4] = "";
            $csv_new_row[5] = "";
            
            foreach($csv_row as $csv_item):
                if (strpos($csv_item, ".booking." ))
                    $csv_new_row[2] =  $csv_item;
                if (strpos($csv_item, ".tripadvisor." ))
                    $csv_new_row[1] =  $csv_item;
                if (strpos($csv_item, "goo.gl" ) || strpos($csv_item, "g.page") || strpos($csv_item, "g.page" ))
                    $csv_new_row[3] =  $csv_item;
                if (strpos($csv_item, "travel" ) || strpos($csv_item, "www.google.com") || strpos($csv_item, "search" ))
                    $csv_new_row[4] =  $csv_item;
            endforeach;

            /** Esporto solo quelli che almeno hanno una fonte */
            if ($csv_new_row[1] != "" || $csv_new_row[2] != "" || $csv_new_row[3] != "" || $csv_new_row[4] != "")
                $csv[] = $csv_new_row;

        }

        $filename = date("Ymdhis") . ".csv";
        $filepath = storage_path("app/rating/download/" . $filename);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $filename,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );

        $file = fopen($filepath, 'w');

        fputcsv($file, $columns);
        foreach ($csv as $csv_row) {
            fputcsv($file, $csv_row);
        }
        fclose($file);

        return [$filepath, $filename, $headers];

    }

    /**
     * Crea il csv degli hotel per Santiago
     *
     * @access public
     * @param  AcceptPolicyGalleryRequest  $request
     * @return Response
     */

    public function exportRating()
    {

        $hotels = Hotel::attivo()->get();
        $return = Self::_getFileCsv($hotels);
        return response()->download($return[0], $return[1], $return[2]);

    }

    public function exportNewRating()
    {
        $hotels = Hotel::whereNotNull("source_rating_ia")->where("rating_ia", 0)->get();
        $return = Self::_getFileCsv($hotels);
        return response()->download($return[0], $return[1], $return[2]);
    }

    public function importRating()
    {
        return view('admin.rating-import');
    }

    public function calcola(Request $request)
    {

        /** Prendo gli hotel con il rating */
        $hotels = Hotel::with("latestRating")->get();

        /** Ciclo sugli hotels */
        $trip = 0;
        foreach($hotels as $hotel):

            /**  */
            $media_rating = 0;
            $numero_votanti = 0;
            $fonti = 0;
            $rating = $hotel->latestRating;
            
            /** Calcolo dei voti */

            if (isset($rating->punteggio)) {

                $valori = json_decode($rating->punteggio);

                $valori[1] = str_replace(".", "", $valori[1]);
                $valori[3] = str_replace(".", "", $valori[3]);
                $valori[5] = str_replace(".", "", $valori[5]);
                $valori[7] = str_replace(".", "", $valori[7]);
                $valori[9] = str_replace(".", "", $valori[9]);

                (float)$valori[0] > 0 && (float)$valori[1] > 0 ? $valore1 = (float)$valori[0] * (float)$valori[1] : $valore1 = 0;
                (float)$valori[2] > 0 && (float)$valori[3] > 0 ? $valore2 = (float)$valori[2] * (float)$valori[3] : $valore2 = 0;
                (float)$valori[4] > 0 && (float)$valori[5] > 0 ? $valore3 = (float)$valori[4] * (float)$valori[5] : $valore3 = 0;
                (float)$valori[6] > 0 && (float)$valori[7] > 0 ? $valore4 = (float)$valori[6] * (float)$valori[7] : $valore4 = 0;
                (float)$valori[8] > 0 && (float)$valori[9] > 0 ? $valore5 = (float)$valori[8] * (float)$valori[9] : $valore5 = 0;

                /** Provo a prendere l'utimo valore valido */

                // $ratingsOld = Rating::where("hotel_id", $hotel->id)->orderBy("id", "DESC")->limit(10)->get();

                // foreach($ratingsOld as $ratingOld):
                //     $punteggio = json_decode($ratingOld->punteggio);
                //     if ($valore1 == 0) (float)$punteggio[0] > 0 && (float)$punteggio[1] > 0 ? $valore1 = (float)$punteggio[0] * (float)$punteggio[1] : $valore1 = 0;
                //     if ($valore2 == 0) (float)$punteggio[2] > 0 && (float)$punteggio[3] > 0 ? $valore2 = (float)$punteggio[2] * (float)$punteggio[3] : $valore2 = 0;
                //     if ($valore3 == 0) (float)$punteggio[4] > 0 && (float)$punteggio[5] > 0 ? $valore3 = (float)$punteggio[4] * (float)$punteggio[5] : $valore3 = 0;
                //     if ($valore4 == 0) (float)$punteggio[6] > 0 && (float)$punteggio[7] > 0 ? $valore4 = (float)$punteggio[6] * (float)$punteggio[7] : $valore4 = 0;
                //     if ($valore5 == 0) (float)$punteggio[8] > 0 && (float)$punteggio[9] > 0 ? $valore5 = (float)$punteggio[8] * (float)$punteggio[9] : $valore5 = 0;
                // endforeach;

                (float)$valori[0] > 0 ? $fonti++ : false;
                (float)$valori[2] > 0 ? $fonti++ : false;
                (float)$valori[4] > 0 ? $fonti++ : false;
                (float)$valori[6] > 0 ? $fonti++ : false;
                (float)$valori[8] > 0 ? $fonti++ : false;

                $numero_votanti = (float)$valori[1] + (float)$valori[3] + (float)$valori[5] + (float)$valori[7] + (float)$valori[9];
                $media_votanti = (float)$valore1 + (float)$valore2 + (float)$valore3 + (float)$valore4 + (float)$valore5;

                if ( $media_votanti > 0 && $numero_votanti > 0) 
                    $media_rating = round($media_votanti / $numero_votanti,1);
                else
                    $media_rating = 0;

            } 
            
            DB::table("tblHotel")
                ->where("id", $hotel->id)
                ->update(["rating_ia" => $media_rating, "n_source_rating_ia" => $fonti, "n_rating_ia" => $numero_votanti ]);

        endforeach;

        Utility::clearCacheHotel($hotel->id);
        SessionResponseMessages::add("success", "Rating aggiornato con con successo");
        return SessionResponseMessages::redirect("/admin/hotels", $request);

    }

    /**
     * Carico i file csv sul server
     *
     * @param Request $request
     * @return Response
     */

    public function uploadCsv(Request $request)
    {

        $rules = array('file' => 'file|mimes:csv,txt|max:3000');

        $messages = [
            'csv' => 'Il file selezionato deve essere un csv ',
            'max' => 'La dimensione massima del file deve essere :max KB',
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {

            $errors = $validation->errors()->all();
            $msg = "";

            foreach ($errors as $error) {
                $msg .= $error . "<br>";
            }

            config('app.debug_log') ? Log::emergency("\n" . '---> Errore UPLOAD CSV: ' . rtrim($msg, '<br>') . ' <---' . "\n\n") : "";
            return response()->json("Errore upload del file<br><br>" . rtrim($msg, '<br>'), 500);

        } else {

            $filename = Carbon::now()->timestamp . '.' . rand(1000, 9999) . '.csv';
            $request->file->storeAs('rating/uploads', $filename);

            $file = fopen(storage_path('app/rating/uploads/' . $filename), "r");
            $rows_csv = [];

            while (!feof($file)) {
                $rows_csv[] = fgetcsv($file);
            }

            fclose($file);

            $t = 0;
            foreach ($rows_csv as $row):
                if ($row) {
                    if ($t > 0) {

                        $hotel_id = (int)$row[0];
                        unset($row[0]);
                        $row = array_values($row);

                        foreach($row as $k => $c):
                            if ($k == 0 || $k == 4 || $k == 6 || $k == 8)
                                if ($c != "") {
                                    $row[$k] = (double) str_replace(",",".",$c) * 2; 
                                }
                            /** Se ho google travel azzero il valore di google mps per non far pesare doppio il valore */
                            if (($k == 4 || $k == 5) && $c != "" && $c != 0 && $c != -1) 
                                $row[$k] = 0;
                                
                        endforeach;

                        $punteggio = json_encode($row);

                        DB::table("tblRating")->insert(
                            [
                                "hotel_id" => $hotel_id,
                                "punteggio" => $punteggio,
                                "created_at" => Carbon::now(),
                                "updated_at" => Carbon::now(),
                            ]);
                    }
                    $t++;
                }
            endforeach;

            // Artisan::command('rating:update');
            return response()->json('OK', 200);;

        }

    }

    /**
     * Pagina Hotel per le recensioni
     */

     public function index() {

        $hotel = Hotel::find($this->getHotelId());

        return View::make(
            'admin.hotel_rating', ["hotel" => $hotel]
        );

     }

    function store (Request $request) 
    {   

        $data = json_decode($request->get("value"));
        $hotel_id = $this->getHotelId();
        Hotel::where("id", $hotel_id)->update(["enabled_rating_ia" => $data]);
        return response()->json(['success' => 'success'], 200);

    }
}

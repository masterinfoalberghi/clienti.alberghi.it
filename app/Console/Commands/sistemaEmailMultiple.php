<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;

class sistemaEmailMultiple extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailmultiple:sistema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sistema le email multiple da 15-02 al 16-03';

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
        try {
            $emails = DB::connection("archive")
                ->table("tblStatsMailMultipleArchive")
                ->select("id", "email_id", "created_at")
                ->where("id", ">", 13491413)
                    ->get();
        
            $change_data_archive = Carbon::now()->format("Ymd");
            
            foreach($emails as $email):

                $date_email_archive = Carbon::parse($email->created_at)->format("Ymd");
                
                /**
                 * Cambio archiviazione
                 * Sono in una archiviazione diversa quindi azzero tutti i parametri
                 */
                if ($date_email_archive != $change_data_archive) {

                    $this->info("Nuova archiviazione " . $date_email_archive);
                    $change_data_archive = $date_email_archive;

                    $email_id_archive = 0;
                    $camere = "";
                    $split_camere = 0;
                    $conteggio_camere = 0;
                    
                }

                 /**
                 * Cambio email
                 * Sono in una email diversa quindi aggiorno il paramentro di split
                 */
                if ($email->email_id != $email_id_archive) {

                    /* Cambio email */
                    $this->info("-->Nuova email " . $email->email_id);
                    $email_id_archive = $email->email_id;
                    $split_camere = $conteggio_camere;
                    
                } 
                
                /**
                 * prendo le camere
                 * faccio una query perche è troppo oneroso farlo tutto con una query sola
                 */

                $camere = DB::connection("archive")
                    ->table("tblStatsMailMultipleArchive")
                    ->select(["id", "camere"])
                    ->where("id", $email->id)
                        ->first();

                
                if (is_countable(json_decode($camere->camere))) {

                    $conteggio_camere = count(json_decode($camere->camere));

                    /**
                     * Se ho un paramentro split taglio via le camere in più
                     */

                    if ($split_camere > 0) {

                        $this->info("-->Split email " . $split_camere);
                        $new_camere = json_encode(array_slice(json_decode($camere->camere, true), $split_camere));   

                        DB::connection("archive")
                            ->table("tblStatsMailMultipleArchive")
                            ->where("id", $camere->id)
                                ->update(["camere" => $new_camere]);

                    }

                } else {

                    DB::connection("archive")
                            ->table("tblStatsMailMultipleArchive")
                            ->where("id", $camere->id)
                                ->update(["camere" => "email corrotta"]);

                }

            endforeach;

        } catch (Exception$err) {
            $this->info($err);
        }
  

    }
}

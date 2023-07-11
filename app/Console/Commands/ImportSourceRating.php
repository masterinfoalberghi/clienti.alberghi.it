<?php

namespace App\Console\Commands;

use DB;
use App;
use Illuminate\Console\Command;

class ImportSourceRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:source';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa un CSV con le fonti di ogni hotel per produrre il rating. Il CSV è situato in /storage/app/rating/source e viene cancellato alla fine della procedura di import.';

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
        $dir    = storage_path('app/rating/source/');
        $files  = scandir($dir);

        if (count($files) == 2)
            $this->info("Nessun file nella directory");			
        else {
            $this->info("Lettura cartella");	
            foreach($files as $file):	

                $file_info = pathinfo(storage_path('app/rating/source/' . $file));

                if (strtolower($file_info["extension"]) != "csv") {
                    if ($file_info["basename"] != "." && $file_info["basename"] != "..")
                        $this->info("Estensione " . $file_info["extension"] . " skip");	
                } else {

                    $cols = 0;
                    $this->info("Lettura file " . $file);	
                    $file_open = fopen(storage_path('app/rating/source/' . $file), "r");
                    $rows_csv = [];

                    while (!feof($file_open)) {
                        $row_string = fgetcsv($file_open);
                        $rows_csv[] = $row_string;
                        $cols = count($row_string);
                    }
                    fclose($file_open);

                    if (App::environment() != "local" && App::environment() != "develp") 
                        unlink(storage_path('app/rating/source/' . $file));

                    $this->info($cols . " colonne contate\n");	

                    $this->info("Scrittura fonti");	

                    $t = 0;
                    foreach ($rows_csv as $row):

                        if ($row && $t > 0) {
                            $hotel_id = (int)$row[0];
                            unset($row[0]);
                            $row = array_values($row);
                            // $this->info(print_r($row,1));	
                            $tt = 0;
                            foreach($row as $r):
                                if ($r == "" || is_null($r))
                                    unset($row[$tt]);
                                $tt++;
                            endforeach;
                            $row = array_values($row);
                            $source = json_encode($row);

                            // $this->info($source);	

                            DB::table("tblHotel")
                                ->where('id',$hotel_id)
                                ->update(["source_rating_ia" => $source ]);

                            $this->info("Scrittura righa " . $t . " - Hotel id ". $hotel_id . " Numero fonti " . (count($row)));	

                        }
                        $t++;

                    endforeach;

                }

            endforeach;
        }

        /** HASK PER SCRITTURA SBAGLIATA */
        DB::table("tblHotel")
            ->where('source_rating_ia', '[]')
            ->orWhere('source_rating_ia', '[""]')
            ->orWhere('source_rating_ia', '["",""]')
            ->orWhere('source_rating_ia', '["","",""]')
            ->orWhere('source_rating_ia', '["","","",""]')
            ->orWhere('source_rating_ia', '["","","","",""]')
            ->orWhere('source_rating_ia', '["","","","","",""]')
            ->orWhere('source_rating_ia', '["","","","","","",""]')
            ->orWhere('source_rating_ia', '["","","","","","","",""]')
            ->orWhere('source_rating_ia', '["","","","","","","","",""]')
            ->update(["source_rating_ia" => NULL, "n_source_rating_ia" => 0]);
    }
}

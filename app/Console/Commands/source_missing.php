<?php

namespace App\Console\Commands;

use DB;
use App\Hotel;
use Illuminate\Console\Command;

class source_missing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'source:missing';

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

        $txt_file = "";
        $missings = Hotel::attivo()->get();

        foreach($missings as $missing):

            if ($missing->source_rating_ia != "") {
                
                $source = json_decode($missing->source_rating_ia, true);

                if (count($source) < 3) {
                    
                    $txt = [];
                    $txt[] = $missing->id;
                    $txt[] = $missing->nome;
                    $txt[] = count($source);

                    if (!strpos($missing->source_rating_ia, "booking"))
                        $txt[] = "booking";

                    if (!strpos($missing->source_rating_ia, "tripadvisor"))
                        $txt[] = "tripadvisor";

                    if (!strpos($missing->source_rating_ia,"goo.gl") && !strpos($missing->source_rating_ia,"g.page"))
                        $txt[] = "google";

                    $txt_file .= implode(";" , $txt) . "\n";

                }

            }

        endforeach;
        echo $txt_file;
    
    //    $file = file_get_contents('./test.txt', FILE_USE_INCLUDE_PATH);
    }
}

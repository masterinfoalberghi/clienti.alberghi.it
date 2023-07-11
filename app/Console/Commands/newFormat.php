<?php

namespace App\Console\Commands;

use App\Hotel;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class newFormat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:newFormat';

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
        $start = 1;
        $folders = ["800x538", "875x496", "1600x1076"];
        $hotels = Hotel::with(["immaginiGallery" => function ($q) {
            $q->orderBy("hotel_id", "ASC")
              ->orderBy("position", "ASC");
        }])->where("attivo", 1)
            ->where("id", ">=", $start )
            ->get();

            foreach($hotels as $hotel):
                foreach($hotel->immaginiGallery as $immagine):
                    $from = "../static.info-alberghi.xxx/original_images/gallery/" . $immagine->foto;
                    try{
                        $contents = Storage::get($from );
                        echo "OK " . $immagine->hotel_id;
                    }catch(Exception $ex){
                        dd("MANCANTE: ". $immagine->foto . "\n");
                    }
                endforeach;
            endforeach;

    }
}

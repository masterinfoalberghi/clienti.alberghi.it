<?php

namespace App\Console\Commands;

use App\Hotel;
use Exception;
use App\ImmagineGallery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class S3image extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:s3';

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
        $starts = [
            237,
            1192,
            1996,
            1999
        ];
        $start = 0;
        $folders = ["65x57", "113x99", "220x148", "220x220", "360x200", "360x320", "720x400", "800x538", "875x496"];
        $hotels = Hotel::with(["immaginiGallery" => function ($q) {
            $q->orderBy("hotel_id", "ASC")
              ->orderBy("position", "ASC");
        }])->where("attivo", 1)
            //->whereIn("id", $starts )
            ->where("id", ">=", $starts[$start])
            ->get();

        $folder_id = 0;

        foreach($hotels as $hotel):
            foreach($hotel->immaginiGallery as $immagine):

                /** muovo tutte le immagini */
                foreach($folders as $folder):

                    $from = "../static.info-alberghi.xxx/public/images/gallery/". $folder . "/" . $immagine->foto;
                    $s3   = "images/gallery/". $folder . "/" . $immagine->hotel_id . "/" . $immagine->foto;
                    
                    /** La porto online */
                    if(!Storage::disk('s3')->exists($s3)) {
                        try{
                            $image = file_get_contents($from);
                        }catch(Exception $ex){
                            if ($folder == "875x496")
                                echo "----> MANCANTE (875x496): ". $from . "\n";
                            else
                                dd($ex);
                        }
                        Storage::disk('s3')->put($s3, $image, 'public');                       
                        echo "OK: " . $from . "\n";
                    } else
                        echo "SCARTATO: " . $from . "\n";

                endforeach;
            endforeach;
        endforeach;
        
    }
}

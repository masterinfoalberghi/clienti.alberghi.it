<?php

namespace App\Console\Commands;

use App\Hotel;
use Exception;
use Throwable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class imageWhiteRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:removewhite';

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
        
        $folders = ['800x538','65x57','113x99','220x148','220x200','220x220','360x200','360x320','720x400','1920x1080'];

        /** Faccio una query per trovare tutte le immagini di tutti gli hotel per localita */
        $hotels = Hotel::select("id", "localita_id")
            ->with(["immaginiGallery" => function ($q) {
                $q->orderBy("hotel_id", "ASC")
                ->orderBy("position", "ASC");
            }])->where("attivo", 1)
                // ->where("localita_id", 51) // Bologna
                ->where("localita_id", 52) // Firenze
                ->where("id", ">", 28684) 
                //->where("id", 28684) 
                    ->get();
        
        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, $hotels->count());
        $progressBar->start();

        /** Ciclo sugli hotel */
        foreach($hotels as $hotel) :

            $progressBar->advance();

            /** Ciclo sulle immagini */
            foreach($hotel->immaginiGallery as $immagine):
                
                try {
                    /** Imposto il nuovo nome */
                    $newname   = str_replace(["jpg", "jpeg", "png"],  uniqid() . ".webp", $immagine->foto);

                    /** Ciclo su tutti i folder che devo processare */
                    foreach($folders as $folder):

                        $d = explode("x", $folder);
                        $path   = storage_path("images/gallery/". $folder . "/" . $hotel->id . "/");   
                        $s3path = "images/gallery/". $folder . "/" . $hotel->id . "/";
                        
                        /** Creo la directory locale */
                        if(!File::isDirectory($path))
                            File::makeDirectory($path, 0777, true, true);

                        /** prendo l'immagine da S3 e creo una nuova immagine bucata in locale */
                        $lc         = $path . $newname;
                        $s3From     = $s3path . $immagine->foto;     
                        $s3To       = $s3path . $newname;

                        if (Storage::disk('s3')->exists($s3From)) {
                            
                            $images3    = Storage::disk("s3")->get($s3From);
                            $image      = Image::make($images3)->encode("webp");

                            $image->trim('top-left', ['left', 'right', 'bottom', 'top'], 10);
                            $image->trim('top-right', ['left', 'right', 'bottom', 'top'], 10);
                            
                            $canvas = Image::canvas($d[0], $d[1]);
                            $canvas->insert($image, "center");
                            $canvas->save( $lc, 100);
                            
                            /** Aggiorno il database ONLINW */
                            DB::connection("mysql_online")
                                ->table("tblImmaginiGallery")
                                ->where("id",$immagine->id)
                                ->update([
                                    "foto" => $newname
                                ]); 
                            
                            /** Porto la nuova immagine ONLINE */
                            $newimage = file_get_contents($lc);
                            Storage::disk('s3')->put($s3To, $newimage, "private"); 

                        }

                    endforeach;

                } catch(Throwable $e) {
                    dump($immagine->id);
                }

            endforeach;
            
        endforeach;

        $progressBar->finish();
        echo "\n\n";

    }
}

<?php

namespace App\Console\Commands;

use App\Hotel;
use App\ImmagineGallery;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Admin\ImmaginiGalleryController;

class CheckImageS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:checks3';

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

        $img_s3 = "";
        $hotels = Hotel::where("attivo", 1)->get();

        $dirs = [
            "65x57",
            "113x99",
            "220x148",
            "220x220",
            "360x200",
            "360x320",
            "720x400",
            "800x538",
            "1920x1080",
        ];

        foreach($hotels as $hotel):
            echo  "Structure " . $hotel->id ." - ";

            if ($hotel->listing_img != "" && $hotel->listing_img != NULL) {

                echo "Listing " . $hotel->listing_img ." - ";
                $img_s3 = $hotel->listing_img;

            } else {

                $image_name = ImmagineGallery::where("hotel_id", $hotel->id)->orderBy("position", "ASC")->first();
                echo "Listing " . $image_name->foto ." - ";
                $img_s3 = $image_name->foto;

            }

            $path_img_s3 = "images/gallery/360x200/" . $hotel->id . "/" . $img_s3;
            $exists = Storage::disk('s3')->exists($path_img_s3);

            if (!$exists) {
                
                $images_to_copy = ImmagineGallery::where("hotel_id", $hotel->id)->get();

                /** Prendo le immagini da static e le porto su S3 */
                foreach($dirs as $dir):
                    foreach($images_to_copy  as $image_to_copy ):

                        //$path_img_ec2 = "/Users/giovanni/Sites/info-alberghi/static.info-alberghi.xxx/public/images/gallery/" . $dir . "/" . $image_to_copy->foto;
                        $path_img_ec2 = "/var/www/static.info-alberghi.com/public/images/gallery/" . $dir . "/" . $image_to_copy->foto;
                        $path_img_s3  = "/images/gallery/" . $dir . "/" . $hotel->id . "/" .$image_to_copy->foto;

                        if (File::exists($path_img_ec2)) {
                            
                            $image = Image::make($path_img_ec2);

                            // if(Storage::disk("s3")->put($path_img_s3, $image->stream()))
                            //     echo  $path_img_ec2 . " -> ". $path_img_s3 . "\n";
                            // else 
                            //     dd("Errore scrittura " . $dir . "\n");

                        } else {

                            echo  "File mancante " . $dir . "\n";

                        }
                        

                    endforeach;
                endforeach;

            } else echo "√\n";

        endforeach;

        return 0;

    }
}

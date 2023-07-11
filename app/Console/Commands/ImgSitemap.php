<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Hotel;
use App\Utility;
use Illuminate\Filesystem\Filesystem;

class ImgSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera la sitemap in base a gli hotel attivi';

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
        ini_set('memory_limit', '512M');
        $fs = new Filesystem();

        $sm = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
        $sm .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;
        $langs = array("/","/ing/","/fr/","/ted/");
        $allHotel = Hotel::attivo()->get();

        foreach ($allHotel as $hotel):
            $images = $hotel->immaginiGallery->sortBy('position');
            foreach($langs as $lang):
                 $sm .=  '<url>' . PHP_EOL;
                    $sm .=  '<loc>https://www.info-alberghi.com'. $lang .'hotel.php?id=' . $hotel->id . '</loc>' . PHP_EOL;
                    $sm .=  '<lastmod>'. date("Y-m-d") . '</lastmod>'. PHP_EOL;
                    $sm .=  '<changefreq>daily</changefreq>'. PHP_EOL;
                    $sm .=  '<priority>0.7</priority>'. PHP_EOL;
                    $sm .=  '<image:image>' . PHP_EOL;
                    $sm .=  '<image:loc>https:' . Utility::asset($hotel->getListingImg("360x200")) . '</image:loc>' . PHP_EOL;
                    $sm .=  '<image:title>'. $hotel->nome . ' - ' . $hotel->localita->nome . '</image:title>' . PHP_EOL;
                    $sm .=  '</image:image>' . PHP_EOL;
                    $t = 1;
                    foreach($images as $img):
                        $sm .=  '<image:image>' . PHP_EOL;
                            $sm .=  '<image:loc><![CDATA[' . Utility::asset($img->getImg("800x538", false)) . ']]></image:loc>' . PHP_EOL;
                            $sm .=  '<image:title><![CDATA['. $hotel->nome . ' - ' . $hotel->localita->nome . ' (' . $t . '/'. count($images) .')]]></image:title>' . PHP_EOL;
                        $sm .=  '</image:image>' . PHP_EOL;
                        $t++;
                    endforeach;   
                 $sm .=  '</url>' . PHP_EOL;
             endforeach;

        endforeach;

        $sm .=  '</urlset>';

        $fs->put("public/sitemap_immagini_https_cdn.xml", $sm);

    }
}

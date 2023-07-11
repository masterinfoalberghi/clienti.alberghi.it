<?php

namespace App\Console\Commands;


use App\CmsPagina;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Hotel;
use App\Utility;
use Illuminate\Filesystem\Filesystem;

class UriSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:uri';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generale la sitemap per gli url/lingua';

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
        $today = Carbon::now()->format("Y-m-d");
        $lang = ""; 
        $langs = ["it", "en", "fr", "de"];

        $lang = $this->anticipate('Lingua da generare (it,en,fr,de) ?', $langs );

        if (!in_array($lang, $langs)) {
            $this->info('Lingua non trovata');
            dd();
        } 
        
        $sm = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
        $sm .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        $allUri = CmsPagina::where("lang_id", $lang)->attiva()->get();
        
        $allHotel = Hotel::attivo()->get();

        $sm .=  '<url>' . PHP_EOL .
                    '<loc>https://www.info-alberghi.com/</loc>' . PHP_EOL .
                    '<lastmod>'. $today .'</lastmod>' . PHP_EOL .
                    '<changefreq>weekly</changefreq>' . PHP_EOL .
                    '<priority>0.5</priority>' . PHP_EOL .
                '</url>'.  PHP_EOL;

        foreach ($allUri as $uri):
            
            switch($uri->template):
                case "statica":
                    $priority = "0.1";
                    $changefreq = "monthly";
                    break;
                case "localita":
                     $priority = "0.7";
                     $changefreq = "weekly";
                    break;
                default:
                    $priority = "0.9";
                    $changefreq = "daily";
                    break;
            endswitch;

            $sm .=  '<url>' . PHP_EOL .
                    '<loc>https://www.info-alberghi.com/' . $uri->uri . '</loc>' . PHP_EOL .
                    '<lastmod>'. date('Y-m-d', strtotime($uri->updated_at)) .'</lastmod>' . PHP_EOL .
                    '<changefreq>' . $changefreq . '</changefreq>' . PHP_EOL .
                    '<priority>' . $priority . '</priority>' . PHP_EOL .
                '</url>' . PHP_EOL;
        endforeach;

        $pageMobile = [
            ["", "0.9", "daily"], 
            ["&amp;offers", '0.9', 'daily'],
            ["&amp;prenotaprima", '0.9', 'daily'],
            ["&amp;children-offers", '0.9', 'daily'],
            ["&amp;lastminute", '0.9', 'daily'],
            ["&amp;price-list", '0.9', 'daily'],
            ["&amp;contact", '0.1', 'yearly'],
            ["&amp;gallery", '0.6', 'monthly'],
            ["&amp;map",'0.1', 'yearly'],
        ];

        foreach ($allHotel as $hotel):
            foreach($pageMobile as $pages):
                $sm .=  '<url>' . PHP_EOL .
                    '<loc>https://www.info-alberghi.com/' . 'hotel.php?id=' . $hotel->id . $pages[0] . '</loc>' . PHP_EOL .
                    '<lastmod>'. date('Y-m-d', strtotime($hotel->updated_at)) .'</lastmod>' . PHP_EOL .
                    '<changefreq>' . $pages[2] . '</changefreq>' . PHP_EOL .
                    '<priority>' . $pages[1] . '</priority>' . PHP_EOL .
                '</url>' . PHP_EOL;
            endforeach;
        endforeach;

        $sm .=  '</urlset>';

        $fs->put("public/sitemap_" . $lang . ".xml", $sm);

    }
}

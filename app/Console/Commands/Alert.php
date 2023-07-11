<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Utility;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class Alert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Controlla se i file necessari alla SEO esistono';

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
		
		$array_file = [];
		$send_email = false;
		
		$array_file["env"]                               = [File::exists('.env'), ".env"];
		$array_file["gitignore"]                         = [File::exists('.gitignore'), ".gitignore"];
		$array_file["public_htaccess"]                   = [File::exists('public/.htaccess'), "public/.htaccess"];
		$array_file["public_robots"]                     = [File::exists('public/robots.txt'), "public/robots.txt"];
		$array_file["public_sitemap_it"]                 = [File::exists('public/sitemap_it.xml'), "public/sitemap_it.xml"];
		$array_file["public_sitemap_en"]                 = [File::exists('public/sitemap_en.xml'), "public/sitemap_fr.xml"];
		$array_file["public_sitemap_de"]                 = [File::exists('public/sitemap_de.xml'), "public/sitemap_de.xml"];
		$array_file["public_sitemap_fr"]                 = [File::exists('public/sitemap_fr.xml'), "public/sitemap_fr.xml"];
		$array_file["public_image_sitemap"]              = [File::exists('public/sitemap_immagini_cdn.xml'), "public/sitemap_immagini_cdn.xml"];
		$array_file["public_google"]                     = [File::exists('public/google4556ae6f107fecf9.html'), "public/google4556ae6f107fecf9.html"];
		$array_file["storage_gitignore"]                 = [File::exists('storage/.gitignore'), "storage/.gitignore"];
		$array_file["storage_framework_view_gitignore"]  = [File::exists('storage/framework/views/.gitignore'), "storage/framework/views/.gitignore"];
		$array_file["storage_framework_cache_gitignore"] = [File::exists('storage/framework/cache/.gitignore'), "storage/framework/cache/.gitignore"];
		
		foreach ($array_file as $file_item):
			
			if ($file_item[0] == 0)
				$send_email = true;
				
		endforeach;
		
		if ($send_email == true) {
			
			Utility::swapToSendGrid();
			$nome_mittente = "Master IA";
			$email_mittente = "master@info-alberghi.com";
			$to = "giovanni@info-alberghi.com";
			$bcc = ["luigi@info-alberghi.com"];
			$oggetto = "Attenzione, file mancanti su Info aberghi";
			
			try {
		
				Mail::send('emails.alert', 
								compact(
									'array_file',
									'oggetto'
								), function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente) 
				{
					$message->from($email_mittente, $nome_mittente);
					$message->replyTo($email_mittente);
					$message->returnPath($email_mittente)->sender($email_mittente)->to($to);
			
					if ($bcc != "")
						$message->bcc($bcc);
			
					$message->subject($oggetto);
					
				});
			
			} catch (\Exception $ee) {	
				
				echo "Errore .. email non spedita " . $ee->getMessage();
							
			}
		}
    }
}

<?php

namespace App\Console\Commands;

use App\CmsPagina;
use Illuminate\Console\Command;

class CreatePagesPiscinaFuori extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:pagesPiscinaFuori';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clona le pagine piscina in pagine PiscinaFuori';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


  
    private function cloneIta()
      {
      // pagine piscina
      $pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'it')->get();
      
      
      /**
       * URI del tipo
       * 
       //$pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'it')->pluck('uri','id');
      * 177 => "hotel-piscina/rimini-hotel-piscina.php"
      * 331 => "hotel-piscina/riccione-hotel-piscina.php"
      * 377 => "hotel-piscina/bellaria-hotel-piscina.php"
      * 386 => "hotel-piscina/lidi-ravennati-hotel-piscina.php"
      * 438 => "hotel-piscina/cesenatico-hotel-piscina.php"
      * 439 => "hotel-piscina/milano-marittima-hotel-piscina.php"
      * 486 => "hotel-piscina/cervia-hotel-piscina.php"
      * 497 => "hotel-piscina/cattolica-hotel-piscina.php"
      * 511 => "hotel-piscina/gabicce-mare-hotel-piscina.php"
      * 515 => "hotel-piscina/misano-adriatico-hotel-piscina.php"
      * 557 => "hotel-piscina/riviera-romagnola-hotel-piscina.php"
      */

      foreach ($pagine_piscina as $pagina_piscina) 
        {
        $pagina = $pagina_piscina->replicate();
        $pagina->attiva = 0;
        $new_uri = str_replace('-.php','',str_replace('hotel-piscina','', $pagina->uri));
        $pagina->uri = 'hotel-piscina-fuori-struttura'.$new_uri.'.php';
        $pagina->listing_count = 0;
        $pagina->listing_gruppo_servizi_id = 35;
        $pagina->prezzo_minimo = "";
        $pagina->prezzo_massimo = "";
        $pagina->listing_preferiti = 0;
        $pagina->tipo_evidenza_crm = "";
        $pagina->ancora = "Hotel con Piscina fuori struttura";
        //dd($pagina);
        $pagina->save();

        $this->info("pagina ".$pagina->uri. " creata");
        }
      }

    private function cloneIng()
      {
      // pagine piscina
      
      $pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'en')->get();

      /**
       * 
        $pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'en')->pluck('uri','id');
       * 1602 => "ing/hotel-piscina/gabicce-mare-hotel-piscina.php" 
       * 1849 => "ing/hotel-piscina/cattolica-hotel-piscina.php"
       * 1916 => "ing/hotel-piscina/misano-adriatico-hotel-piscina.php"
       * 1967 => "ing/hotel-piscina/riccione-hotel-piscina.php"
       * 2035 => "ing/hotel-piscina/rimini-hotel-piscina.php"
       * 2147 => "ing/hotel-piscina/bellaria-hotel-piscina.php"
       * 2228 => "ing/hotel-piscina/cervia-hotel-piscina.php"
       * 2294 => "ing/hotel-piscina/milano-marittima-hotel-piscina.php"
       * 2358 => "ing/hotel-piscina/lidi-ravennati-hotel-piscina.php"
       * 3061 => "ing/hotel-piscina/cesenatico-hotel-piscina.php"
       * 3492 => "ing/hotel-piscina/riviera-romagnola-hotel-piscina.php"
       * 
       */
      
      foreach ($pagine_piscina as $pagina_piscina) 
        {
        $pagina = $pagina_piscina->replicate();
        $pagina->attiva = 0;
        $new_uri = str_replace(['hotel-piscina','ing//','-.php'],'', $pagina->uri);

        $pagina->uri = 'ing/outside-hotel-pool/'.$new_uri.'.php';
        $pagina->listing_count = 0;
        $pagina->listing_gruppo_servizi_id = 35;
        $pagina->prezzo_minimo = "";
        $pagina->prezzo_massimo = "";
        $pagina->listing_preferiti = 0;
        $pagina->tipo_evidenza_crm = "";
        $pagina->ancora = "Outside swimming pool hotels";
        
        $pagina->save();

        $this->info("pagina ".$pagina->uri. " creata");
        }

      }

    private function cloneTed()
      {
      // pagine piscina
      
      $pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'de')->get();

      /**
        $pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'de')->pluck('uri','id');
       * 1851 => "ted/hotel-piscina/cattolica-hotel-piscina.php"
       * 1918 => "ted/hotel-piscina/misano-adriatico-hotel-piscina.php"
       * 1969 => "ted/hotel-piscina/riccione-hotel-piscina.php"
       * 2037 => "ted/hotel-piscina/rimini-hotel-piscina.php"
       * 2149 => "ted/hotel-piscina/bellaria-hotel-piscina.php"
       * 2170 => "ted/hotel-piscina/gabicce-mare-hotel-piscina.php"
       * 2230 => "ted/hotel-piscina/cervia-hotel-piscina.php"
       * 2296 => "ted/hotel-piscina/milano-marittima-hotel-piscina.php"
       * 2360 => "ted/hotel-piscina/lidi-ravennati-hotel-piscina.php"
       * 3062 => "ted/hotel-piscina/cesenatico-hotel-piscina.php"
       * 3494 => "ted/hotel-piscina/riviera-romagnola-hotel-piscina.php"
       * 
       */
      
      foreach ($pagine_piscina as $pagina_piscina) 
        {
        $pagina = $pagina_piscina->replicate();
        $pagina->attiva = 0;
        $new_uri = str_replace(['hotel-piscina','ted//','-.php'],'', $pagina->uri);

        $pagina->uri = 'ted/hotel-mit-ausserhalb-schwimmbad/'.$new_uri.'.php';
        $pagina->listing_count = 0;
        $pagina->listing_gruppo_servizi_id = 35;
        $pagina->prezzo_minimo = "";
        $pagina->prezzo_massimo = "";
        $pagina->listing_preferiti = 0;
        $pagina->tipo_evidenza_crm = "";
        $pagina->ancora = "Hotel mit Ausserhalb Schwimmbad";
        
        $pagina->save();

        $this->info("pagina ".$pagina->uri. " creata");
        }

      }


    
    private function cloneFra()
      {
      // pagine piscina
      
      $pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'fr')->get();

      /**
        $pagine_piscina = CmsPagina::where('uri','like', '%hotel-piscina%')->where('lang_id', 'fr')->pluck('uri','id');
       * 1676 => "fr/hotel-piscina/gabicce-mare-hotel-piscina.php"
       * 1850 => "fr/hotel-piscina/cattolica-hotel-piscina.php"
       * 1917 => "fr/hotel-piscina/misano-adriatico-hotel-piscina.php"
       * 1968 => "fr/hotel-piscina/riccione-hotel-piscina.php"
       * 2036 => "fr/hotel-piscina/rimini-hotel-piscina.php"
       * 2148 => "fr/hotel-piscina/bellaria-hotel-piscina.php"
       * 2229 => "fr/hotel-piscina/cervia-hotel-piscina.php"
       * 2295 => "fr/hotel-piscina/milano-marittima-hotel-piscina.php"
       * 2359 => "fr/hotel-piscina/lidi-ravennati-hotel-piscina.php"
       * 3060 => "fr/hotel-piscina/cesenatico-hotel-piscina.php"
       * 3493 => "fr/hotel-piscina/riviera-romagnola-hotel-piscina.php"
       * 
       */
      
      foreach ($pagine_piscina as $pagina_piscina) 
        {
        $pagina = $pagina_piscina->replicate();
        $pagina->attiva = 0;
        $new_uri = str_replace(['hotel-piscina','fr//','-.php'],'', $pagina->uri);

        $pagina->uri = 'fr/hotel-avec-piscine-exterieur/'.$new_uri.'.php';
        $pagina->listing_count = 0;
        $pagina->listing_gruppo_servizi_id = 35;
        $pagina->prezzo_minimo = "";
        $pagina->prezzo_massimo = "";
        $pagina->listing_preferiti = 0;
        $pagina->tipo_evidenza_crm = "";
        $pagina->ancora = "Hôtels avec piscine exterieur";
        
        $pagina->save();

        $this->info("pagina ".$pagina->uri. " creata");
        }

      }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    
    $this->cloneIta();
    $this->cloneIng();
    $this->cloneTed();
    $this->cloneFra();
    
    }
}

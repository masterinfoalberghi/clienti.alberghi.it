<?php

namespace App\Console\Commands;

use App\Macrolocalita;
use App\Poi;
use App\Utility;
use Illuminate\Console\Command;

class RicalcolaDistanze extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'distanze:ricalcola {id_macro? : ID della macrolocalità per i quali hotel ricalcolare le distanze}';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Ricalcola le distanze da "centro", "stazione" e "fiera" di tutti gli hotel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }





    private function _distanzeMacro($macro, $fiera)
      {

      $this->info("Ricalcolo le distanze degli hotel di ". $macro->nome ."\n\t");

      foreach ($macro->localita as $localita) 
        {

        $this->info("Ricalcolo le distanze degli hotel di ".$localita->nome . "\n\t");

        foreach ($localita->clientiAttivi as $hotel) 
          {
          if (!$hotel->mappa_latitudine || !$hotel->mappa_longitudine)
            {
            $geolocation_valid = false;
            $this->error("hotel ".$hotel->nome. " - (". $hotel->id . ") " . " non ha le coordinate!!" ."\n\t");
            }
          else
            {
            $this->info("hotel ".$hotel->nome. " - (". $hotel->id . ") " . "\n\t");

            $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $localita->centro_lat, $localita->centro_long);
            $nuovo = round($distanza_metri / 1000, 2);
            if ($nuovo != $hotel->distanza_centro) 
              {
              $hotel->distanza_centro = $nuovo;
              $this->error("hotel ".$hotel->nome. " - (". $hotel->id . ") " . " centro: $hotel->distanza_centro km SALVATA" . "\n\t");
              } 
            
            
            $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $localita->staz_lat, $localita->staz_long);
            $nuovo = round($distanza_metri / 1000, 2);
            if($nuovo != $hotel->distanza_staz)
              {
              $hotel->distanza_staz = $nuovo;
              $this->error("hotel ".$hotel->nome. " - (". $hotel->id . ") " . " staz: $hotel->distanza_staz km SALVATA" . "\n\t");
              }

            $distanza_metri = Utility::getGeoDistance($hotel->mappa_latitudine, $hotel->mappa_longitudine, $fiera->lat, $fiera->long);
            $nuovo = round($distanza_metri / 1000, 2);
            if($nuovo != $hotel->distanza_fiera)
              {
              $hotel->distanza_fiera = $nuovo;
              $this->error("hotel ".$hotel->nome. " - (". $hotel->id . ") " . " fiera: $hotel->distanza_fiera km SALVATA" . "\n\t");
              }

            $hotel->save();

            $this->info("hotel ".$hotel->nome. " - (". $hotel->id . ") " . " centro: $hotel->distanza_centro km -  staz: $hotel->distanza_staz km - fiera: $hotel->distanza_fiera km" . "\n\t");


            }

          }/*foreach hotel localita */

        } /* foreach localita macro*/
      }



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
      {

      $fiera = Poi::where("id", 9)->first();
      $id_macro = $this->argument('id_macro');
       
       if (is_null($id_macro)) 
          {
          
          $this->info("Ricalcolo le distanze degli hotel di TUTTE le località !!");
          
          $macros = Macrolocalita::with([
                  'localita.clientiAttivi'
                  ])
                  ->real()
                  ->noRR()
                  ->get();          

          foreach ($macros as $macro) 
            {
            $this->_distanzeMacro($macro, $fiera);
            }


          } 
      else 
          {

          $macro = Macrolocalita::with([
                  'localita.clientiAttivi'
                  ])
                  ->findOrFail($id_macro);

          $this->_distanzeMacro($macro, $fiera);

          }
       


      }
}

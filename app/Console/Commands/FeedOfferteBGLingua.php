<?php

namespace App\Console\Commands;

use App\BambinoGratis;
use App\BambinoGratisLingua;
use App\Utility;
use Illuminate\Console\Command;

class FeedOfferteBGLingua extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bg_lingua:feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Legge dalla tabella tblBambiniGratis e alimenta la nuova tabella tblBambiniGratisLang traducendo automaticamente i testi';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }



    private function mapBgToBgLingua($bg)
      {

      $note_it = $bg->note;

      $traduzioni['it'] = $note_it;

      foreach (Utility::linguePossibili() as $lingua) 
        {
        if ($lingua != 'it') 
          {

          if ($note_it != '') 
            {
            $traduzioni[$lingua] = Utility::translate($note_it, $lingua);
            } 
          else 
            {
            $traduzioni[$lingua] = '';
            }
          
          
          }
        }

      foreach ($traduzioni as $lang_id => $value) 
        {
        
        $offertaLingua = new BambinoGratisLingua;
        $offertaLingua->lang_id = $lang_id;
        $offertaLingua->note = $value;
        $offertaLingua->approvata = $bg->approvata;
        $offertaLingua->data_approvazione = $bg->data_approvazione;
        
        $bg->offerte_lingua()->save($offertaLingua);
        
        }
      
      }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $all_bg = BambinoGratis::attivo()->get();
      //$all_bg = BambinoGratis::attivo()->where('hotel_id', 430)->get();


      $bar = $this->output->createProgressBar(count($all_bg));
      
      foreach ($all_bg as $key => $bg) 
        {

        if (!count($bg->offerte_lingua)) 
          {
          $bg->note == '' ? $str="SENZA note" : $str="CON note da tradurre";
          $this->info("\n\t");
          $this->info("Traduco BG #$key id=$bg->id $str");
          $this->mapBgToBgLingua($bg);
          $this->info("\n\t");
          } 
        else 
          {
          $this->info("\n\t");
          $this->info("GIA' TRADOTTO BG #$key id=$bg->id");
          $this->info("\n\t");
          }
        
        $bar->advance();
        
        }

      $bar->finish();

    }
}

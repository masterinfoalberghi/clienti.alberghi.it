<?php

namespace App\Console\Commands;

use Langs;
use App\Hotel;
use App\InfoPiscina;
use App\Macrolocalita;
use App\DescrizioneHotel;
use App\DescrizioneHotelLingua;
use App\InfoBenessere;
use Illuminate\Console\Command;
use Google\Cloud\Translate\TranslateClient;

class TranslateInfoHotelAllLang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:info_hotel_all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Traduce il campo "peculiarita" nelle tabelle tblInfoBenessere e tblInfoPiscina in tutte le lingue';

    private $translate = null;
    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        $credentialFile = base_path('GoogleTranslateClient.json');

        putenv("GOOGLE_APPLICATION_CREDENTIALS=$credentialFile");

        # Your Google Cloud Platform project ID
        $projectId = 'api-project-868696641806';

        # Instantiates a client
        $translate = new TranslateClient([
          'projectId' => $projectId
        ]);
        
        $this->translate = $translate;

        parent::__construct();

    }


    private function goTranslate($infoGenerico, $totali)
      {
        $bar = $this->output->createProgressBar($totali);

        $bar->start();

        $source = 'it';

        foreach ($infoGenerico as $info) {

          foreach (['en', 'fr', 'de'] as $target) {

            $translation = $this->translate->translate($info->peculiarita, [
              'source' => $source,
              'target' => $target
            ]);

            $tradotto = $translation['text'];



            $colonna = "peculiarita_" . $target;

            if ($info->$colonna == '') {

              $info->$colonna = $tradotto;
            }
          }

          $info->save();

          $this->info('Tradotto info hotel ID = ' . $info->hotel_id);

          $bar->advance();
        }

        $bar->finish();
      }



    private function translateInfoPiscina() {

      $infoPiscina = InfoPiscina::where('peculiarita', '!=', '')->get();

      $totali = $infoPiscina->count();

      $this->info('infoPiscina totali ' . $totali);

      $this->goTranslate($infoPiscina, $totali);

      $this->info('Tradotti tutti gli infoPiscina');
    
    }


  private function translateInfoBenessere()
  {

    $infoBenessere = InfoBenessere::where('peculiarita', '!=', '')->get();

    $totali = $infoBenessere->count();

    $this->info('InfoBenessere totali ' . $totali);

    $this->goTranslate($infoBenessere, $totali);

    $this->info('Tradotti tutti gli infoBenessere');
  }




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    $this->translateInfoPiscina();
    $this->translateInfoBenessere();
  
    
    }

  
}

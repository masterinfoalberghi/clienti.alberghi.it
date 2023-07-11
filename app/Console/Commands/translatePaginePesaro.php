<?php

namespace App\Console\Commands;

use App\Utility;
use App\CmsPagina;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Google\Cloud\Translate\TranslateClient;

class translatePaginePesaro extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:pagine_pesaro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prende le pagine che hannp nel\'uri pesaro e che sono in italiano e le traduce nelle altre lingue';

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

        $credentialFile = base_path('GoogleTranslateClient.json');

        //dd($credentialFile);

        putenv("GOOGLE_APPLICATION_CREDENTIALS=$credentialFile");

        # Your Google Cloud Platform project ID
        $projectId = 'api-project-868696641806';

        # Instantiates a client
        $translate = new TranslateClient([
            'projectId' => $projectId
        ]);

        CmsPagina::where('uri', 'like', '%pesaro%')->where('lang_id', '!=', 'it')->delete();
        CmsPagina::where('uri', 'like', '%pesaro%')->where('attiva', 1)->where('lang_id', 'it')->where('template', 'listing')->update(['descrizione_1' => '']);

        DB::table('tblMenuTematico')->where('macrolocalita_id',Utility::getIdMacroPesaro())->where('lang_id', '!=', 'it')->delete();


        //dd($pagine_pesaro_it->take(3));
        
        foreach (['en', 'fr', 'de'] as $target) {
            $pagina_translated = null;
            $pagine_pesaro_it = CmsPagina::where('uri', 'like', '%pesaro%')->where('attiva', 1)->where('lang_id', 'it')->get();
            
            foreach ($pagine_pesaro_it as $pagina_it) {

                $pagina_translated = $pagina_it;
                
                $pagina_translated->lang_id = $target;
                $pagina_translated->attiva = 1;
                $pagina_translated->uri = Utility::getUrlLocaleFromAppLocale($target).'/'. $pagina_it->uri;
                $pagina_translated->alternate_uri = $pagina_it->id;
                $pagina_translated->seo_title = $translate->translate($pagina_it->seo_title, ['source' => $pagina_it->lang_it, 'target' => $target])['text'];
                $pagina_translated->seo_description = $translate->translate($pagina_it->seo_description, ['source' => $pagina_it->lang_it, 'target' => $target])['text'];
                $pagina_translated->h1 = $translate->translate($pagina_it->h1, ['source' => $pagina_it->lang_it, 'target' => $target])['text'];
                $pagina_translated->h2 = $translate->translate($pagina_it->h2, ['source' => $pagina_it->lang_it, 'target' => $target])['text'];
                $pagina_translated->ancora = $translate->translate($pagina_it->ancora, ['source' => $pagina_it->lang_it, 'target' => $target])['text'];
                $pagina_translated = $pagina_translated->toArray();
                unset($pagina_translated['id']);
                

                $cms_pagina_id = DB::table('tblCmsPagine')->insertGetId($pagina_translated);
                
                // creo la voce di menu in lingua
                $menu_it = DB::table('tblMenuTematico')->where('macrolocalita_id',Utility::getIdMacroPesaro())->where('cms_pagine_id', $pagina_it->id)->first();

                if(!is_null($menu_it)) {

                    $menu_translated = $menu_it;

                    //dd($menu_translated);
                    $menu_translated->lang_id = $target;
                    $menu_translated->cms_pagine_id = $cms_pagina_id;
                    unset($menu_translated->id);

                    DB::table('tblMenuTematico')->insert((array) $menu_translated);

                }

            }
            
            $this->info('Inserito pagine lingua ' . $target);
            
        }

        $pagine_pesaro_it = CmsPagina::where('uri', 'like', '%pesaro%')->where('attiva', 1)->where('lang_id', 'it')->get();

        foreach ($pagine_pesaro_it as $pagina) {
            $pagina->alternate_uri = $pagina->id;
            $pagina->save();
        }


        $pagine_pesaro_en = CmsPagina::where('uri', 'like', '%pesaro%')->where('attiva', 1)->where('lang_id', 'en')->get();

        foreach ($pagine_pesaro_en as $pagina) {
            $pagina->h2 = str_replace(['Hotel 2 place', 'Hotel 3 place', 'Hotel 4 place', 'Hotel 5 place'],['2-Star Hotels', '3-Star Hotels', '4-Star Hotels', '5-Star Hotels'], $pagina->h2);
            $pagina->ancora = str_replace(['Hotel 2 place', 'Hotel 3 place', 'Hotel 4 place', 'Hotel 5 place'], ['2-Star Hotels', '3-Star Hotels', '4-Star Hotels', '5-Star Hotels'], $pagina->ancora);
            $pagina->save();
        }


        $pagine_pesaro_de = CmsPagina::where('uri', 'like', '%pesaro%')->where('attiva', 1)->where('lang_id', 'de')->get();

        foreach ($pagine_pesaro_de as $pagina) {
            $pagina->h2 = str_replace(['Hotel 2 Stelle', 'Hotel 3 Stelle', 'Hotel 4 Stelle', 'Hotel 5 Stelle'], ['2 Sterne Hotel', '3 Sterne Hotel', '4 Sterne Hotel', '5 Sterne Hotel'], $pagina->h2);
            $pagina->ancora = str_replace(['Hotel 2 Stelle', 'Hotel 3 Stelle', 'Hotel 4 Stelle', 'Hotel 5 Stelle'], ['2 Sterne Hotel', '3 Sterne Hotel', '4 Sterne Hotel', '5 Sterne Hotel'], $pagina->ancora);
            $pagina->save();
        }


        $pagine_pesaro_fr = CmsPagina::where('uri', 'like', '%pesaro%')->where('attiva', 1)->where('lang_id', 'fr')->get();

        foreach ($pagine_pesaro_fr as $pagina) {
            $pagina->h2 = str_replace(['Hôtel 2 place', 'Hôtel 3 place', 'Hôtel 4 place', 'Hôtel 5 place', 'lit et petit déjeuner' ], ['2 étoiles', '3 étoiles', '4 étoiles', '5 étoiles', 'Offres Bed and Breakfast'], $pagina->h2);
            $pagina->ancora = str_replace(['Hôtel 2 place', 'Hôtel 3 place', 'Hôtel 4 place','Hôtel 5 place', 'lit et petit déjeuner'], ['2 étoiles', '3 étoiles', '4 étoiles','5 étoiles', 'Offres Bed and Breakfast'], $pagina->ancora);
            $pagina->save();
        }

    }
}

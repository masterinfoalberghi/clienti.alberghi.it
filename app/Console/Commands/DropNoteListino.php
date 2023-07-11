<?php

namespace App\Console\Commands;

use App\NotaListinoLingua;
use Illuminate\Console\Command;

class DropNoteListino extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cut:dondizioni';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina dalle note listino la sezione CONDIZIONI';

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


        $search['it'] = '<p><strong>CONDIZIONI:</strong><br />';
        $search['en'] = '<p><strong>CONDITIONS:</strong><br />';
        $search['fr'] = '<p><strong>CONDITIONS:</strong><br />';
        $search['de'] = '<p><strong>BEDINGUNGEN:</strong><br />';

        foreach (['it','en','fr','de'] as $lang) 
          {
          
          $search_lang = $search[$lang];

          $note = NotaListinoLingua::where('lang_id',$lang)->where('testo','LIKE', '%'.$search_lang.'%')->get();

          $this->info('Note con condizioni in lingua '. $lang . ' '. $note->count());


          $bar = $this->output->createProgressBar($note->count());
          $bar->start();
          foreach ($note as $nota) 
            {
              $new_testo = substr($nota->testo, 0, strpos($nota->testo, $search_lang));
              $nota->testo = $new_testo;
              $nota->save();
            
              $bar->advance();
            }
          $bar->finish();
          
          }



    }
}

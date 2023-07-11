<?php

namespace App\Console\Commands;

use DB;
use App\CmsPagina;
use Illuminate\Console\Command;

class alternate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alternate';

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
     * @return mixed
     */
    public function handle()
    {
        
        $pagine = CmsPagina::where("lang_id" , "it")
        	->attiva()
			->get();
			
		foreach($pagine as $pagina):
		
			echo $pagina->uri . PHP_EOL;
			
			CmsPagina::where("uri", $pagina->uri)->update(["alternate_uri"=>$pagina->id]);
			CmsPagina::where("uri", "ing/" . $pagina->uri)->update(["alternate_uri"=>$pagina->id]);
			CmsPagina::where("uri", "fr/" . $pagina->uri)->update(["alternate_uri"=>$pagina->id]);
			CmsPagina::where("uri", "ted/" . $pagina->uri)->update(["alternate_uri"=>$pagina->id]);
					
		endforeach;
        
    }
}

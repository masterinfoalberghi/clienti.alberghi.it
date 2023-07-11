<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;

class importOldDataSahre extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'share:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data';

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
      	
      	$oldInsert = DB::table("tblStatsHotelShareOld")->get();
      	$codice = Carbon::now()->timestamp;
      	
      	foreach($oldInsert as $oi):
	      	
	      	$arrayInsert = array(
		      	
		      	'uri' => $oi->uri,
		      	'codice' => $codice,
		      	'roi' => 0,
		      	'useragent' => $oi->useragent,
		      	'IP' => $oi->IP,
		      	'os' => $oi->os,
		      	'created_at' => $oi->created_at,
		      	'updated_at' => $oi->updated_at
	      	);
	      	
	      	$this->info("url:" . $oi->uri);			  	
		  	DB::table("tblStatsHotelShare")->insert($arrayInsert);
	      	
      	endforeach;
      	
      	$oldInsert = DB::table("tblStatsHotelShareReadOld")->get();
      	
      	foreach($oldInsert as $oi):
	      	
	      	$arrayInsert = array(
		      	
		      	'anno' => $oi->anno,
		      	'mese' => $oi->mese,
		      	'giorno' => $oi->giorno,
		      	'uri' => $oi->uri,
		      	'count' => $oi->count,
		      	'roi' => 0
	      	);
	      	
	      	$this->info("url:" . $oi->uri);			  	
		  	DB::table("tblStatsHotelShareRead")->insert($arrayInsert);
	      	
      	endforeach;
      	
    }
}

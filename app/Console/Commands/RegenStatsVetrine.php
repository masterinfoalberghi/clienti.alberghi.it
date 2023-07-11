<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Config;
use Carbon\Carbon;

class RegenStatsVetrine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regen:stats-vetrine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rigenera le statistiche vetrine ripartendo dalla tabella archivio relativa';

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
        
    foreach (['tblStatsVetrine', 'tblStatsVaat', 'tblStatsVot', 'tblStatsVst', 'tblStatsVtt'] as $tbl) {

            if ($tbl == 'tblStatsVetrineArchive') {
              $columns = "distinct vetrina_id,slot_id,hotel_id,referer,useragent,created_at";
            } else {
              $columns = "distinct pagina_id,hotel_id,referer,useragent,created_at";
            }
        	
             DB::table($tbl . "Read")->truncate();

			$n_rows = DB::table($tbl . "Archive")->count();
			$rows_per_chunk = 10000;
			
			$t = 0;
			for($i = 0; $i < $n_rows; $i += $rows_per_chunk) {
				
				$data = DB::table($tbl . "Archive")
                  ->select(DB::raw($columns))
				  ->skip($i)
				  ->take($rows_per_chunk)
				  ->get();
				
  				$data_read = "";
				
				foreach($data as $r) {

          if (strpos($r->useragent, 'bot') === false && strpos($r->useragent, 'craw') === false) {
            
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $r->created_at);
            
            if ($tbl == 'tblStatsVetrine') {
              
              $vetrina_id_fld = 'vetrina_id';
              $vetrina_id_fld_value = $r->vetrina_id;
              
            } else {
              
              $vetrina_id_fld = 'pagina_id';
              $vetrina_id_fld_value = $r->pagina_id;
              
            }
  
            DB::statement("INSERT INTO {$tbl}Read (created_at, $vetrina_id_fld, hotel_id, visits)
            VALUES ('".$dt."', ". $vetrina_id_fld_value . ", " . $r->hotel_id .", 1)
            ON DUPLICATE KEY UPDATE visits = visits + 1");
            
            $t++;
          
          }  // endif not bot
					
				}
				
				$this->info("Processate $t records su $n_rows");
				
			}
			
			
			
		}
		
    }
}

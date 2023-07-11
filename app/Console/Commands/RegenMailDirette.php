<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Config;
use Carbon\Carbon;

class RegenMailDirette extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regen:stats-maildirette';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rigenera le statistiche delle email dirette partendo dalla tabella archivio';

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
		
		if (!$this->confirm('Attenzione questa procedura potrebbe durare alcune ore!! Continuare')) {
			dd();
		}
        
		if ($this->confirm('Troncare il DB tblMailSchedaRead?')) {
			DB::table("tblMailSchedaRead")->truncate();
		}
		
		$n_rows = DB::table("tblMailSchedaArchive")->count();
		$rows_per_chunk = 1000;
		
		$t = 0;
		for($i = 0; $i < $n_rows; $i += $rows_per_chunk) {
			
			$data = DB::table("tblMailSchedaArchive")
			  ->skip($i)
			  ->take($rows_per_chunk)
			  ->get();  						
	  			
  			foreach ($data as $r) {
  							  				
  				$anno = date("Y", strtotime($r->data_invio));
  				$mese = date("m", strtotime($r->data_invio));
  				$giorno = date("d", strtotime($r->data_invio));
  				
  				$created_at = $anno."-".$mese."-".$giorno;
  				$data_read = "('{$anno}','{$mese}', '{$giorno}', '{$r->tipologia}', {$r->hotel_id}, 1)";
  				
  				DB::statement("INSERT INTO tblMailSchedaRead (anno, mese, giorno, tipologia, hotel_id, conteggio )
  					VALUES ".$data_read."
  					ON DUPLICATE KEY UPDATE conteggio = conteggio + 1");
  					
  				$t++;
			
			}
			
			$this->info("Processate $t records su $n_rows");
			
		}
		
    }
}

<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use Config;
use Illuminate\Console\Command;

class RegenHotelStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regen:stats-schede';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rigenera le statistiche hotel dalla tabella archivio';

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
		if ($this->confirm('Troncare il DB Read?')) {
			DB::table("tblStatsHotelRead")->truncate();
		}
		
		$n_rows = DB::table("tblStatsHotelArchive")->count();
		$rows_per_chunk = 10000;
		
		$t = 0;
		for($i = 0; $i < $n_rows; $i += $rows_per_chunk) {
			
			$data = DB::table("tblStatsHotelArchive")
			  ->skip($i)
			  ->take($rows_per_chunk)
			  ->get();

			$data_archive = [];
			$data_read = "";

			foreach($data as $r) {
			
				$dt = Carbon::createFromFormat('Y-m-d H:i:s', $r->created_at);				
				$year  = $dt->format("Y");
				$month = $dt->format("m");
				$day   = $dt->format("d");
				
				DB::statement("INSERT INTO tblStatsHotelRead (anno, mese, giorno, hotel_id, visits)
					VALUES (".$year.", ".$month.", " . $day .", " . $r->hotel_id .", 1)
					ON DUPLICATE KEY UPDATE visits = visits + 1");
				
				$t++;
				
			}
			
			$this->info("Processate $t records su $n_rows");
			
			
		}

    }
}

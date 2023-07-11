<?php

namespace App\Console\Commands;

use Config;
use DB;
use App\StatsHotelOutboundLink;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportStatsOutbound extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:stats-outbound';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archivia i links al sito dell\'hotel';

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
		/**
         * Con questo script travaso i dati più vecchi di "oggi" da tblStatsHotel a tblStatsHotelArchive
         * nel fare questo aggiorno il dato aggregato in tblStatsHotelRead
         *
         * lavoro per chunks, in questo modo se ho un boom di accessi lo script non "patisce"
         */ 

		$term_storage_archived_data = Config::get("privacy.term_storage_archived_data");	

		/** Main function */
		$n_rows = StatsHotelOutboundLink::beforeToday()->count();
		$rows_per_chunk = 10000;

		$t = 0;
		for ($i = 0; $i < $n_rows; $i += $rows_per_chunk) {

            /** Query */
			$data = StatsHotelOutboundLink::beforeToday()
				->skip($i)
				->take($rows_per_chunk)
				->get();

            $data_read = "";
            $data_archivies = [];

            /** Ciclo */
			foreach ($data as $r) {

				$data_archivies[] = array(
					"created_at" => $r->created_at,
					"updated_at" => $r->updated_at,
					"hotel_id" => $r->hotel_id,
					"user_agent" => $r->user_agent
				);
                
				/** Tabella Read / Inserisci o aggiorna */
				$dt = Carbon::createFromFormat('Y-m-d H:i:s', $r->created_at);				
				DB::statement("INSERT INTO tblStatsHotelOutboundLinkRead (anno, mese,giorno, hotel_id, visits) VALUES (".  $dt->format("Y") .", ". $dt->format("n") .", " . $dt->format("d") .", " . $r->hotel_id .", 1) ON DUPLICATE KEY UPDATE visits = visits + 1");
				
				$t++;
				
			}  // end for chunk data

            /** Inserisci i dati nella tabella archivio */
			DB::connection("archive")
                ->table("tblStatsHotelOutboundLinkArchive")
                ->insert($data_archivies);
			
            $this->info("Record processati $t / $n_rows");

		}  // end for numrows
		
        /*
         * Cancello tutti i beforeToday
         * perchè non lo posso fare offset per offset con i chunk!
         * controllando le query nel general log le query in delete le fa senza offset!
         */
		
		StatsHotelOutboundLink::beforeToday()->delete();

        /** Cancello i dati più vecchi di xx anni */
        DB::connection("archive")
            ->table("tblStatsHotelOutboundLinkArchive")
            ->where("created_at", "<", Carbon::now()
            ->subMonths($term_storage_archived_data))
            ->delete();
		
    }
}
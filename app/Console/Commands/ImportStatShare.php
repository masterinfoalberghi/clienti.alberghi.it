<?php

namespace App\Console\Commands;

use DB;
use Config;
use Carbon\Carbon;
use App\StatsHotelShare;
use App\StatsHotelShareArchive;
use Illuminate\Console\Command;

class ImportStatShare extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'import:stats-share';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Processa le stats delle condivisioni del giorno precedente ad "oggi" importandole nella tabella d\'archivio e nella tabella statistica';

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
		
	    /*
		 * Con questo script travaso i dati più vecchi di "oggi" da tblStatsHotelCall a tblStatsHotelCallArchive
		 * mentre il dato aggragato in tblStatsHotelCallRead lo inserisco già all'atto del contaclick HotelCOntroller@contaClickCallMeAjax
		 */
        
        $term_storage_archived_data = Config::get("privacy.term_storage_archived_data");
		$data = StatsHotelShare::beforeToday()->get();
		$data_archivies = array();

        $t = 0;
		foreach ($data as $r) {
			$data_archivies[] = array(
				"created_at" => $r->created_at,
				"updated_at" => $r->updated_at,
				"uri" => $r->uri,
				"codice" => $r->codice,
				"roi" => $r->roi
			);

            /** Tabella Read / Inserisci o aggiorna */
            DB::statement("INSERT INTO tblStatsHotelShareRead (uri, count, roi)	VALUES ('".$r->uri."', '1', '0') ON DUPLICATE KEY UPDATE count = count + 1, roi = roi + " . $r->roi);

			$t++;

		}

        /** Inserisci i dati nella tabella archivio */
        DB::connection("archive")
            ->table("tblStatsHotelShareArchive")
            ->insert($data_archivies);

        $this->info("Record processati $t / " . $data->count());
		
        /*
         * Cancello tutti i beforeToday
         * perchè non lo posso fare offset per offset con i chunk!
         * controllando le query nel general log le query in delete le fa senza offset!
         */

        StatsHotelShare::beforeToday()->delete();

		/** Cancello i dati più vecchi di xx anni */	
		DB::connection("archive")
            ->table("tblStatsHotelShareArchive")
            ->where("created_at", "<", Carbon::now()->subMonths($term_storage_archived_data))
            ->delete();
		
	}
}

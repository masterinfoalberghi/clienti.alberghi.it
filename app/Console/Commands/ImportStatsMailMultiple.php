<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MailMultipla;
use Config;
use DB;
use Carbon\Carbon;

class ImportStatsMailMultiple extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:mailmultiple';

    /**
     * The console command description.
     *
     * @var string
     */
    
    protected $description = 'Importa le statistiche delle email multiple';

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

        /** Main function  */
		$n_rows = MailMultipla::beforeToday()->alreadySyncAPI()->count();
		$rows_per_chunk = 20;

		$t = 0;
		for ($i = 0; $i < $n_rows; $i += $rows_per_chunk) {
			
            /** Query */
			$data = MailMultipla::with(["camereAggiuntive", "clienti"])
				->beforeToday()
				->alreadySyncAPI()
				->skip($i)
				->take($rows_per_chunk)
				->get();
			
            $data_archivies = [];

            /** Ciclo */
			foreach ($data as $r) {

                $data_camera_archive = [];
                
                /** Preparo la camera */
                /** Prima camera */
				$cam = array(
					'flex_date' => $r->date_flessibili,
					'checkin' => $r->arrivo,
					'checkout' => $r->partenza,
					'adult' => $r->adulti,
				);

				if($r->eta_bambini != '') $cam['children'] = explode(',', $r->eta_bambini);
				else $cam['children'] = "";	

				$cam['meal_plan'] = $r->trattamento;
				$data_camera_archive[] = $cam;
				
                 /** Camere secondarie */
				$data_camere_aggiuntive_archive = $r->camereAggiuntive;
				if (count($data_camere_aggiuntive_archive)):
					foreach($data_camere_aggiuntive_archive as $item_camera):
						$cam = array(
							'flex_date' => $item_camera->date_flessibili,
							'checkin' => $item_camera->arrivo,
							'checkout' => $item_camera->partenza,
							'adult' => $item_camera->adulti,
						);

						if($item_camera->eta_bambini != '') $cam['children'] = explode(',', $item_camera->eta_bambini);
						else $cam['children'] = "";	

						$cam['meal_plan'] = $item_camera->trattamento;
						$data_camera_archive[] = $cam;
						
					endforeach;
				endif;
				
                /** Ciclo sul numero dei clienti della mail multipla */
				$data_clienti = $r->clienti;
				if (count($data_clienti)):
					foreach($data_clienti as $item_cliente):	
						
						$data_archivies[] = array(
							
							'hotel_id' => $item_cliente->id,
							'email_id' => $r->id,
							'lang_id' => $r->lang_id,
							'tipologia' => $r->tipologia,
							'nome' => $r->nome,
							'email' => $r->email,
							'camere' => json_encode($data_camera_archive),
							'richiesta' => $r->richiesta,
							'data_invio' => $r->data_invio,
							'referer' => $r->referer,
							'created_at' => Carbon::now(),
							'updated_at' => Carbon::now()
						);
				
						$anno = date("Y", strtotime($r->data_invio));
						$mese = date("m", strtotime($r->data_invio));
						$giorno = date("d", strtotime($r->data_invio));
						$created_at = $anno."-".$mese."-".$giorno;
						$data_read = "('{$anno}','{$mese}', '{$giorno}', '{$r->tipologia}', {$item_cliente->id}, 1)";
						DB::statement("INSERT INTO tblStatsMailMultipleRead (anno, mese, giorno, tipologia, hotel_id, conteggio ) VALUES ".$data_read." ON DUPLICATE KEY UPDATE conteggio = conteggio + 1");
                        
					endforeach;
                    $t++;

				endif;
				
			}

             /** Inserisci i dati nella tabella archivio */
            DB::connection("archive")
                ->table("tblStatsMailMultipleArchive")
                ->insert($data_archivies);
                
			$this->info("Processate $t di $n_rows");
			
    	}
	
        /*
         * Cancello tutti i beforeToday
         * perchè non lo posso fare offset per offset con i chunk!
         * controllando le query nel general log le query in delete le fa senza offset!
         */

		MailMultipla::beforeToday()->alreadySyncAPI()->delete();
		
        /** Cancello i dati più vecchi di xx anni */
        DB::table("tblCamereAggiuntive")
            ->where("created_at", "<", Carbon::now()
            ->subMonths($term_storage_archived_data))
            ->delete();
            
        DB::table("tblHotelMailMultiple")
            ->where("created_at", "<", Carbon::now()
            ->subMonths($term_storage_archived_data))
            ->delete();

        DB::table("tblMailMultiple")
            ->where("data_invio", "<", Carbon::now()
            ->subMonths($term_storage_archived_data))
            ->delete();

        DB::connection("archive")
            ->table("tblStatsMailMultipleArchive")
            ->where("data_invio", "<", Carbon::now()
            ->subMonths($term_storage_archived_data))
            ->delete();
	}
}







<?php

namespace App\Console\Commands;

use App\Hotel;
use App\StatsHotelCall;
use App\Utility;
use DB;
use Config;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class ImportStatsCalls extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'import:stats-calls';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Processa le stats delle chiamate del giorno precedente ad "oggi" importandole nella tabella d\'archivio e nella tabella statistica';

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
	 * Muove le chiamate su archivio e cancella quelle più vecchie di xx mesi 
	 */
	
	private function _move_to_archive() {

		/**
		 * Inserisco le nuove rows
		 */

		$n_rows = StatsHotelCall::beforeToday()->count();
		$rows_per_chunk = 10000;

        $t = 0;
		for ($i = 0; $i < $n_rows; $i += $rows_per_chunk) {

			$data = StatsHotelCall::beforeToday()
				->skip($i)
				->take($rows_per_chunk)
				->get();

            $data_read = "";
            $data_archivies = [];

			foreach ($data as $r) {

				$data_archivies[] = array(
					"created_at" => $r->created_at,
					"os" => $r->os,
					"hotel_id" => $r->hotel_id,
					"useragent" => $r->useragent,
				);

                $dt = Carbon::createFromFormat('Y-m-d H:i:s', $r->created_at);
                DB::statement("INSERT INTO tblStatsHotelCallRead (anno, mese, giorno, hotel_id, calls) VALUES (".  $dt->format("Y") .", ". $dt->format("n") .", " . $dt->format("d") .", "  . $r->hotel_id .", 1) ON DUPLICATE KEY UPDATE calls = calls + 1");
                
                $t++;

			} // end for chunk data

            /*
			 * Importo il chunk nelle tabelle di archivio e di consultazione statistica
			 */
			
			DB::connection("archive")
                ->table("tblStatsHotelCallArchive")
                ->insert($data_archivies);

            $this->info("Record processati $t / $n_rows");

		} // end for chunk data

       
        $term_storage_archived_data = Config::get("privacy.term_storage_archived_data");

        DB::connection("archive")
            ->table("tblStatsHotelCallArchive")
            ->where("created_at", "<", Carbon::now()
            ->subMonths($term_storage_archived_data))
            ->delete();

	}

	/**
	 * Notifica intetnto di chiamata
	 */

	private function _notifica($hotel_id = 0, $log_arr = [], $ieri = "") {

		if ($hotel_id != 0 && !empty($log_arr)) {

			// $hotel = Hotel::find($hotel_id);
			$hotel = Hotel::with(["localita","stelle"])->where("id", $hotel_id )->first();

			if (!is_null($hotel) && $hotel->attivo) {

				// DATI EMAIL
				$from = "info@info-alberghi.com";
				$nome = "Lo staff di Info Alberghi";
				$oggetto = "Riepilogo statistiche telefonate";
				$nome_cliente = "cliente";

				$hotel_id = $hotel->id;
				$hotel_name = $hotel->nome;
				$hotel_loc = $hotel->localita->nome;
				$hotel_rating = $hotel->stelle->nome;

				$bcc = "";
				$email_cliente = explode(',', $hotel->email);
				if ($hotel->email_secondaria != "")
					$email_cliente = explode(',', $hotel->email_secondaria);

				Utility::swapToSendGrid();
				
				try
				{
                    Mail::send('emails.riepilogo_intenti_di_chiamata',
                    compact(
                        'nome_cliente',
                        'oggetto',
                        'ieri',
                        'log_arr',
                        'hotel_id',
                        'hotel_name', 
                        'hotel_loc',
                        'hotel_rating' 
                    ), function ($message) use ($from, $oggetto, $nome, $email_cliente, $bcc) {
                        $message->from($from, $nome);
                        $message->replyTo($from);
                        if ($bcc != "") {
                            $message->bcc($bcc);
                        }
                        $message->to($email_cliente);
                        $message->subject($oggetto);
                    });
                } catch (Exception $e) {}

			} /* endif attivo e non nullo */

		} /* endif check parametri */

	} /* end fi function*/

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

	public function handle() {

	   /*
		* Con questo script travaso i dati più vecchi di "oggi" da tblStatsHotelCall a tblStatsHotelCallArchive
		* mentre il dato aggragato in tblStatsHotelCallRead lo inserisco già all'atto del contaclick HotelCOntroller@contaClickCallMeAjax
		*/

        /** Archiviazione dei dati */
        $this->_move_to_archive();

		/** Notifiche */
        $data = [];
		$all = StatsHotelCall::beforeToday()->get();
				
		foreach ($all as $item)
			$data[$item->hotel_id][] = '<td style="padding:5px;">' . explode(' ', $item->created_at)[1] . '</td><td style="padding:5px;">' . Utility::maskIP($item->IP) . '</td>';

		$primo = $all->first();

		if (!is_null($primo)) {
			$dateObj = date_create(explode(' ', $primo->created_at)[0]);
			$ieri = date_format($dateObj, 'd/m/Y');
		} else {
			$ieri_c = new \Carbon\Carbon('yesterday');
			$ieri = $ieri_c->format('d/m/Y');
		}

        $t = 0;
		foreach ($data as $hotel_id => $log_arr) {

			if (config('app.env') != "local" && config('app.env') != "develop")
                $this->_notifica($hotel_id, $log_arr, $ieri);
            else
                $this->info("($t) Notifica #$hotel_id");

            $t++;
        }

		/*
	     * Cancello tutti i beforeToday
	     * perchè non lo posso fare offset per offset con i chunk!
	     * controllando le query nel general log le query in delete le fa senza offset!
		 */

		StatsHotelCall::beforeToday()->delete();

	}
}

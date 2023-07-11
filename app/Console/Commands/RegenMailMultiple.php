<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RegenMailMultiple extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regen:stats-mailmultiple';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rigenera le statistiche delle email multiple partendo dalla tabella archivio';

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
        
		if ($this->confirm('Troncare il DB tblStatsMailMultipleRead?')) {
			DB::table("tblStatsMailMultipleRead")->truncate();
		}
        
		$n_rows = DB::table("tblStatsMailMultipleArchive")->count();
		$rows_per_chunk = 1000;
		
		$t = 0;
		for($i = 0; $i < $n_rows; $i += $rows_per_chunk) {
			
			$data = DB::table("tblStatsMailMultipleArchive")
			  ->skip($i)
			  ->take($rows_per_chunk)
			  ->get();  
			 
			  foreach ($data as $r) {
  				
  				$data_archive = [];
  				$data_camera_archive = [];
  				
  				$data_camera_archive[] = array(
  					'arrivo' => $r->arrivo,
  					'partenza' => $r->partenza,
  					'trattamento' => $r->trattamento,
  					'adulti' => $r->adulti,
  					'bambini' => $r->bambini,
  					'eta_bambini' => $r->eta_bambini,
  					'date_flessibili' => $r->date_flessibili
  				);
  				
  				$data_camere_aggiuntive_archive = DB::table("tblCamereAggiuntive")->where("mailMultipla_id", $r->id)->get();
  				
  				if (count($data_camere_aggiuntive_archive)):
  					foreach($data_camere_aggiuntive_archive as $item_camera):
  						
  						$data_camera_archive[] = array(
  							'arrivo' => $item_camera->arrivo,
  							'partenza' => $item_camera->partenza,
  							'trattamento' => $item_camera->trattamento,
  							'adulti' => $item_camera->adulti,
  							'bambini' => $item_camera->bambini,
  							'eta_bambini' => $item_camera->eta_bambini,
  							'date_flessibili' => $item_camera->date_flessibili
  						);
  						
  					endforeach;
  				endif;
  				
  				$data_clienti = $r->clienti()->get();
  				
  				if (count($data_clienti)):
  					foreach($data_clienti as $item_cliente):	
  						
  						$data_archive[] = array(
  							
  							'hotel_id' => $item_cliente->id,
  							'email_id' => $r->id,
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
  						
  						DB::statement("INSERT INTO tblStatsMailMultipleRead (anno, mese, giorno, tipologia, hotel_id, conteggio )
  							VALUES ".$data_read."
  							ON DUPLICATE KEY UPDATE conteggio = conteggio + 1");
  						
  						$t++;
  						
  					endforeach;
  					
  					DB::table("tblStatsMailMultipleArchive")->insert($data_archive);
  					
  				endif;
  				
  			}	  
		}
    }
}

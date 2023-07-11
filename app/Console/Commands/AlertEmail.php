<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\MailSchedaRead;
use App\MailMultipleRead;
use App\Utility;
use DB;
use Config;
use Illuminate\Support\Facades\Mail;

class AlertEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ho un avviso se le email scendono rispetto all\'anno precedente del 50%';
	
	private static function sendEmail ($emailIeri, $emailAnnoscorso, $quali) {
		
		Utility::swapToSendGrid();
		$to = "testing.infoalberghi@gmail.com";
		$bcc = array();
		if (Config::get("app.env") == "production") 
 			$bcc = array("luigi@info-alberghi.com");
			
		$oggetto = "Info-Alberghi.com - ALERT CALO EMAIL";
				
		Mail::send("emails.alertEmail" , compact("oggetto", "emailIeri", "emailAnnoscorso" , "quali"), function ($message) use ($to, $bcc, $oggetto)
		{
			$message->from("master@info-alberghi.com", "Info Alberghi");
			$message->replyTo("no-replay@info-alberghi.com");
			$message->returnPath("master@info-alberghi.com")->sender("master@info-alberghi.com")->to($to);
			$message->bcc($bcc);
			$message->subject($oggetto); 

		});
		
	}
	
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
		
		$unita = 4;
		$soglia = 3; 
		
        $ieri = Carbon::now()->subDay(1)->format("Y-m-d");
		$annoscorso = Carbon::now()->subDay(1)->subYear(1)->format("Y-m-d");

		$emailIeri = MailSchedaRead::select(DB::raw('sum(conteggio) as conteggio'))->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) =  "' . $ieri . '"')->first();
		$emailAnnoscorso = MailSchedaRead::select(DB::raw('sum(conteggio) as conteggio'))->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) =  "' . $annoscorso . '"')->first();
		$rapporto = floor($emailAnnoscorso->conteggio / $unita) * $soglia;
		
		// Mando un alert
		// Se le email dirette di Ieri sono meno del valore dell'anno predecente / $unita * soglia ( es 3/4 )
		if ($emailIeri->conteggio < $rapporto) {
			Self::sendEmail($emailIeri, $emailAnnoscorso, "dirette");
			//echo ($emailIeri->conteggio < $rapporto);
			echo "Spedisci email";
		}
		
		$emailIeri = MailMultipleRead::select(DB::raw('sum(conteggio) as conteggio'))->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) =  "' . $ieri . '"')->first();
		$emailAnnoscorso = MailMultipleRead::select(DB::raw('sum(conteggio) as conteggio'))->whereRaw('DATE(CONCAT(anno,"-",mese,"-",giorno)) =  "' . $annoscorso . '"')->first();
		$rapporto = floor($emailAnnoscorso->conteggio / $unita) * $soglia;
		
		// Mando un alert
		// Se le email dirette di Ieri sono meno del valore dell'anno predecente / $unita * soglia ( es 3/4 )
		if ($emailIeri->conteggio < $rapporto)
			Self::sendEmail($emailIeri, $emailAnnoscorso, "multiple");
		
    }
}

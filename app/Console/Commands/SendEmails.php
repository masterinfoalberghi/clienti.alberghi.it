<?php
	
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HotelController;
use App\Utility;
use Carbon\Carbon;
use App\Events\MailSchedaInviataHandler;
use App\Hotel;
use App\MailMultipla;
use App\CameraAggiuntiva;
use Illuminate\Cookie\CookiJar;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

// php artisan emal:Send 17 2017/01/01

class SendEmails extends Command 
{
	
	protected $signature = 		'email:Send {id} {data} {email}';
	protected $description = 	'Rispedisce le email ad un hotel partendo dal database.\nuso: php artisan email:Send *hotel_id* *date_start* *email_to_send*';
	const MS_RETURN_PATH = "richiesta@info-alberghi.com";
	
    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    private function _getOggettoMail ($camere = 1, $prefill=array(), $mobile = false) {

		$oggetto_data = "Info-Alberghi.com - ";
		
		if ($mobile)
			$oggetto_data = "Info-Alberghi.com mobile - ";
		
		$oggetto_data .= "(";
		
		$oggetto_data .= Carbon::createFromFormat('Y-m-d',$prefill["rooms"][0]["checkin"])->format('d/m') . " - ";
		$oggetto_data .= Carbon::createFromFormat('Y-m-d',$prefill["rooms"][0]["checkout"])->format('d/m') . " - ";
		
		if ($prefill["rooms"][0]["flex_date"]) 
		  $oggetto_data .= "flex - ";
	
		$oggetto_data .= $prefill["rooms"][0]["meal_plan"] . " - ";
		
		$adulti = $prefill["rooms"][0]["adult"];
		$oggetto_data .= "$adulti Pax - ";
		
		$bambini = $prefill["rooms"][0]["children"]; 

		if ($bambini ) {
			// $eta = $prefill["rooms"][0]["age_children"]; 
			$oggetto_data .= "$bambini bimbi - ";
		}
		
		if ($prefill["information"]) {
			
			$messaggio = substr($prefill["information"],0,20);
		
			if (strlen($prefill["information"]) > 20)
				$messaggio .= "...";
				
			$oggetto_data .= $messaggio;
			
		}
		
		$oggetto_data .= ")";
	  
		if ($camere>1) {
			$oggetto = $oggetto_data . " ... Richiesta per $camere preventivi ";

		} else {
			$oggetto = $oggetto_data . " - Richiesta Preventivo";
		}

      return $oggetto;
      
    }
	
	private function _ToEmail ($bcc) {
	  	
	  	if (is_array($bcc)):
	  		  	
			$bcc = join(",", $bcc);
			$bcc = str_replace(" ", "" , $bcc);
			$bcc = explode(",", $bcc);
			
			// Elimino le email doppie
			$bcc_new = array();
			foreach($bcc as $single) {
				
				if (!in_array($single, $bcc_new)) {
					array_push($bcc_new, $single);
				}
				
			}
		else:
			
			$bcc = str_replace(" ", "" , $bcc);
			$bcc = explode(",", $bcc);
			$bcc_new = $bcc;
			
		endif;
		return $bcc_new; 
	  
  	}
	
  	private function _makeEmail ($emails, $id, $tipoEmail) {

  		$emailsData = [];

  		foreach( $emails as $e ):
						
			$spam = Utility::checkSenderMailBlacklisted($e->email);
						
			if (!$spam) {
				
				$json = json_decode($e->camere);
				$numero_camere = count($json); 
				if ($e->tipologia == "normale") { $tipologia = "Computer"; } else { $tipologia = $e->tipologia; }

				$prefill = array(); 
				$prefill["ids_send_mail"] 	= $e->id;
				$prefill["customer"] 		= $e->nome;
				$prefill["email"] 			= $e->email;
				$prefill["phone"] 			= "";
				if (isset($e->telefono))
					$prefill["phone"] 			= $e->telefono;


				$prefill["information"] 	= $e->richiesta;
				if ($tipoEmail == "Dirette")
					$prefill["tag"] 			= "ED";
				else
					$prefill["tag"] 			= "EM";

				$prefill["sender"] 			= "info-alberghi.com";
				$prefill["language"] 		= "it_IT";
				$prefill['type'] 			= $tipologia;
				$prefill["rooms"] 			= array();

				$i=0;

				foreach($json as $j) {
			
					$prefill["rooms"][$i] = array();
					if (isset($j->checkin))
						$prefill["rooms"][$i]["checkin"] 		= $j->checkin;
					else
						$prefill["rooms"][$i]["checkin"] 		= $j->arrivo;

					if (isset($j->checkout))
						$prefill["rooms"][$i]["checkout"] 		= $j->checkout;
					else
						$prefill["rooms"][$i]["checkout"] 		= $j->partenza;

					if (isset($j->adult))
						$prefill["rooms"][$i]["adult"] 			= $j->adult;
					else
						$prefill["rooms"][$i]["adult"] 			= $j->adulti;

					if (isset($j->meal_plan))
						$prefill["rooms"][$i]["meal_plan"] 		= Utility::Trattamenti_json($j->meal_plan)[1];
					else
						$prefill["rooms"][$i]["meal_plan"] 		= Utility::Trattamenti_json($j->trattamento)[1];

					if (isset($j->flex_date))
						$prefill["rooms"][$i]["flex_date"] 		= $j->flex_date;
					else
						$prefill["rooms"][$i]["flex_date"] 		= $j->date_flessibili;

					if (isset($j->eta_bambini)) {

						$prefill["rooms"][$i]["children"] 		= count(explode(",", $j->eta_bambini));
						$prefill["rooms"][$i]["age_children"]	= $j->eta_bambini;

					} else if (isset($j->children) && is_array($j->children)) {

						$prefill["rooms"][$i]["children"] 		= count($j->children);
						$prefill["rooms"][$i]["age_children"]	= $j->children;

					}  else if (isset($j->children) && is_string($j->children)) {

						$prefill["rooms"][$i]["children"] 		= 0;
						$prefill["rooms"][$i]["age_children"]	= "";

					} else if(!isset($j->children)) {

						$prefill["rooms"][$i]["children"] 		= 0;
						$prefill["rooms"][$i]["age_children"]	= "";

					}

					$prefill["rooms"][$i]["age_children"] = Utility::purgeMenoUnoArray($prefill["rooms"][$i]["age_children"]);

					
					$i++;

				}

				$dati_json["customer"] = $e->nome;
				$dati_json["email"] = $e->email;
				$dati_json["phone"] = "";

				if (isset($e->telefono))
					$dati_json["phone"] = $e->telefono;

				$dati_json["information"] = $e->richiesta;

				if ($tipoEmail == "Dirette")
					$dati_json["tag"] = "ED";
				else
					$dati_json["tag"] = "EM";

				$dati_json["sender"] = "info-alberghi.com";
				$dati_json["type"] = $tipologia;
				$dati_json["language"] = "it_IT";
				$dati_json["rooms"] = array();
			
				/** 
				 * Ciclo sulle camere 
				 */
				
				$i=0;
				foreach($json as $j) {
										
					$dati_json["rooms"][$i] = array();

					if (isset($j->flex_date))
						$dati_json["rooms"][$i]["flex_date"] 		= $j->flex_date;
					else
						$dati_json["rooms"][$i]["flex_date"] = $j->date_flessibili;

					if (isset($j->checkin))
						$dati_json["rooms"][$i]["checkin"] 		= Carbon::createFromFormat('Y-m-d',$j->checkin)->format('d/m/Y');
					else
						$dati_json["rooms"][$i]["checkin"] 		= Carbon::createFromFormat('Y-m-d',$j->arrivo)->format('d/m/Y');

					if (isset($j->checkout)) {
						$dati_json["rooms"][$i]["checkout"] 		= Carbon::createFromFormat('Y-m-d',$j->checkout)->format('d/m/Y');
						$dati_json["rooms"][$i]["nights"] 			= Utility::night(Carbon::createFromFormat('Y-m-d',$j->checkin)->format('d/m/Y'), Carbon::createFromFormat('Y-m-d',$j->checkout)->format('d/m/Y'));
					} else {
						$dati_json["rooms"][$i]["checkout"] 		= Carbon::createFromFormat('Y-m-d',$j->partenza)->format('d/m/Y');
						$dati_json["rooms"][$i]["nights"] 			= Utility::night(Carbon::createFromFormat('Y-m-d',$j->arrivo)->format('d/m/Y'), Carbon::createFromFormat('Y-m-d',$j->partenza)->format('d/m/Y'));
					}

					if (isset($j->adult))
						$dati_json["rooms"][$i]["adult"] = $j->adult;
					else
						$dati_json["rooms"][$i]["adult"] = $j->adulti;

					
					if (isset($j->eta_bambini)) {
						$dati_json["rooms"][$i]["children"] = explode(",", $j->eta_bambini);	
					} else if (isset($j->children) && is_array($j->children)) {
						$dati_json["rooms"][$i]["children"] = $j->children;	
					}  else if (isset($j->children) && is_string($j->children)) {
						$dati_json["rooms"][$i]["children"] = "";
					} else if(!isset($j->children)) {
						$dati_json["rooms"][$i]["children"] = "";
					}

					$dati_json["rooms"][$i]["children"] = Utility::purgeMenoUnoArray($dati_json["rooms"][$i]["children"]);

					if (isset($j->meal_plan))
						$dati_json["rooms"][$i]["meal_plan"] = Utility::Trattamenti_json($j->meal_plan)[1];
					else
						$dati_json["rooms"][$i]["meal_plan"] = Utility::Trattamenti_json($j->trattamento)[1];

					$i++;

				}

				try {
					$hotel = Hotel::with(['localita', 'localita.macrolocalita','stelle'])->find($id);
				} catch (Exception $e) {
					throw new HotelNotExistsException;
				}
				

				$dati_mail['hotel_name'] 		= $hotel->nome;
				$dati_mail['hotel_id'] 			= $hotel->id;
				$dati_mail['hotel_rating'] 		= $hotel->stelle->nome;
				$dati_mail['hotel_loc'] 		= $hotel->localita->nome; //($hotel->localita->alias ? $hotel->localita->alias : $hotel->localita->nome);

				$dati_mail['referer'] 			= "";
				$dati_mail['actual_link'] 		= "";
				$dati_mail['ip'] 				= "";
				$dati_mail['device'] 			= $tipologia;

				$dati_mail['hotels_contact'] 	= "";

				$dati_mail['date_created_at'] 	= Carbon::createFromFormat('Y-m-d H:i:s',$e->created_at)->format('d/m/Y');
				$dati_mail['hour_created_at'] 	= Carbon::createFromFormat('Y-m-d H:i:s',$e->created_at)->format('H:i');
				$dati_mail['sign_email'] 		= base64_encode(json_encode($dati_json));	
				
			}
			
			$emailsData[] = [$prefill , $dati_json , $dati_mail, $numero_camere]; 

		endforeach;

		return $emailsData;
		
  	}

	public function send_email_dirette() {
		
		$id = 	$this->argument('id');
		$data = $this->argument('data');
		$email_to_send = $this->argument('email');
		
		echo "Dirette"	. PHP_EOL;
		echo "Hotel " 	. $id . PHP_EOL;
		echo "Dal " 	. $data . PHP_EOL;
					 
		$emails = DB::table('tblMailSchedaArchive')
					 ->where('hotel_id', $id)
					 ->where('data_invio', '>=', $data)
					 ->where('tipologia', "<>", "doppia")
					 ->orderBy('id', 'desc')
					 ->get();			 
		
		echo "Trovate " . count($emails) . PHP_EOL;
		$emailsData = Self::_makeEmail($emails, $id, "Dirette");

		foreach($emailsData as $data):

			$prefill = $data[0];
			$dati_json = $data[1];
			$dati_mail = $data[2];
			$numero_camere = $data[3];

			$oggetto = $this->_getOggettoMail($numero_camere,$prefill,false);
			$email_mittente = $prefill["email"];
			$nome_mittente  = $prefill["customer"];
			$telefono_mittente  = $prefill["phone"];
			$to = $email_to_send;
			$bcc = "";

			try {
						
				Mail::send(
				'emails.mail_scheda',
				compact(
					'dati_json',
					'dati_mail'
				), function ($message) use ($email_mittente, $to, &$bcc, $oggetto, $nome_mittente) {
				
					$message->from(Self::MS_RETURN_PATH, $nome_mittente);
					$message->replyTo($email_mittente);
					$message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($to);					
					$message->subject($oggetto);
					
				}
			);

				echo "OK .. email spedita " . $oggetto . "<br />";
				
			} catch (\Exception $ee) {	
				
				dd("Errore .. email non spedita " . $ee->getMessage());
							
			}
		
			echo " " . PHP_EOL;
				 
		endforeach;
		
	}
	
	public function send_email_multiple() {
		
		$id = 	$this->argument('id');
		$data = $this->argument('data');
		$email_to_send = $this->argument('email');
		
		echo "Multiple" . PHP_EOL;
		echo "Hotel " 	. $this->argument('id'). PHP_EOL;
		echo "Dal " 	. $this->argument('data'). PHP_EOL;
		
		$num_hotel = "Dato non ricostruibile";
		 
		$emails = DB::table('tblStatsMailMultipleArchive')
					 ->where('hotel_id', $id)
					 ->where('data_invio', '>=', $data)
					 ->where('tipologia', "<>", "doppia")
					 ->where('tipologia', "<>", "doppia")
					 ->orderBy('id', 'desc')
					 ->get();
						
		
		echo "Trovate " . count($emails) . PHP_EOL;
		$emailsData = Self::_makeEmail($emails, $id, "Multiple");	 

		foreach($emailsData as $data):

			$prefill = $data[0];
			$dati_json = $data[1];
			$dati_mail = $data[2];
			$numero_camere = $data[3];

			$oggetto = $this->_getOggettoMail($numero_camere,$prefill,false);
			$email_mittente = $prefill["email"];
			$nome_mittente  = $prefill["customer"];
			$telefono_mittente  = "";
			$to = $email_to_send;
			$email_type = "Multipla";
			$bcc = "";

			try {
						
				Mail::send(
					'emails.mail_multipla',
					compact(

						'email_type',
						'dati_mail',
						'dati_json'

					), function ($message) use ($email_mittente, $to, $bcc, $oggetto, $nome_mittente) {

						$message->from(Self::MS_RETURN_PATH, $nome_mittente);
						$message->replyTo($email_mittente);
						$message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($to);					
						$message->subject($oggetto);

					}
				);

				echo "OK .. email spedita " . $oggetto . "<br />";
				
			} catch (\Exception $ee) {	
				
				dd("Errore .. email non spedita " . $ee->getMessage());
							
			}
		
			echo " " . PHP_EOL;
				 
		endforeach;

	}
	
    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
       $this->send_email_dirette();
       $this->send_email_multiple();
    }
}

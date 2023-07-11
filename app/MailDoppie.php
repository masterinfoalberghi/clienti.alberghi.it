<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Utility;

class MailDoppie extends Model
{
	
	protected $table = 'tblMailDoppie';
	protected $guarded = ['id'];
	protected $fillable = ['id', 'ids_send_mail','codice_cookie','prefill'];

	public static function logEmailDoppie($codice_cookie, $ids, $prefill = null, $debug_email = false) {
		
		if ($debug_email)
			echo "<br /><br /><b>Risultato scrittura</b><br />";
		
		foreach ($ids as $id):
		
			$logMailDoppia = MailDoppie::where('ids_send_mail', $id)
								->where("codice_cookie", $codice_cookie)
								->where('created_at', '>' , Carbon::now()->subDay(3))
								->orderBy('created_at', 'desc')
								->first();
			
			if (!$logMailDoppia):
				
				if ($debug_email)
					echo Utility::echoDebug("creato", $id);
				
				$logMailDoppia = new MailDoppie;
				$logMailDoppia->ids_send_mail = $id;
				$logMailDoppia->codice_cookie = $codice_cookie;
				$logMailDoppia->prefill = $prefill;
				$logMailDoppia->save();
			
			else:	
				
				if ($debug_email)
					echo Utility::echoDebug("aggiornato", $id);
					
				$logMailDoppia->updated_at = Carbon::now();
				$logMailDoppia->update(['prefill'=>$prefill]);
				
			endif;
			
		endforeach;
			
	}
	
	/**
	 * Controlla se esistono email doppie.
	 * 
	 * @access public
	 * @static
	 * @param String $codice_cookie
	 * @param String $id
	 * @return Query
	 */
	 
	public static function controlloMailDoppie($codice_cookie, $idsArray, $prefill) 
	{
		/* lo trasformo in un array */
			
		//dd($codice_cookie, $idsArray, $prefill);
				
		$clienti_ids_arr = [];
		$clienti_ids_arr_not_sent = [];
			
		$emails_confronto = MailDoppie::whereIn('ids_send_mail', $idsArray)
								->where("codice_cookie", $codice_cookie)
								->where('created_at', '>' , Carbon::now()->subDay(3))
								->orderBy('created_at', 'desc')
								->get();	
		
		foreach($emails_confronto as $email_confronto):
			
			if ($prefill == $email_confronto["prefill"])
				array_push( $clienti_ids_arr_not_sent, $email_confronto->ids_send_mail);
									
		endforeach;
		
		$clienti_ids_arr_not_sent = array_unique($clienti_ids_arr_not_sent);
		$clienti_ids_arr = array_diff($idsArray, $clienti_ids_arr_not_sent);
	
		return ["clienti_ids_arr" => $clienti_ids_arr , "clienti_ids_arr_not_sent" => $clienti_ids_arr_not_sent];
		
	}
}

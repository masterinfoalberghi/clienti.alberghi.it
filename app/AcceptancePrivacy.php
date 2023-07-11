<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Config;

class AcceptancePrivacy extends Model
{
	// tabella in cui vengono salvati i record 
	protected $table = 'tblAcceptancePrivacy';
	
	// attributi NON mass-assignable
	protected $guarded = ['id','created_at'];
	
	public function scopeBeforeToday($query)
	{
		return $query->where("created_at", "<", date("Y-m-d 00:00:00"));
	}
	
	/**
	 * Scrive o aggiorna la data di accettazione della PP 
	 * @param String $email
	 * @param String $codice_cookie
	 * @param String $ip
	 * @param String $device
	 */
		
	public static function addPrivacyRow ( $email, $ip, $device ) {
		
		$privacyRow = AcceptancePrivacy::where("email", $email)->first();
		
		if (!$privacyRow):
		
			$privacyRow = new AcceptancePrivacy;
			$privacyRow->email =$email;
			$privacyRow->IP = $ip;
			$privacyRow->device = $device;
			$privacyRow->save();
			
		else:
			
			$privacyRow->IP = $ip;
			$privacyRow->device = $device;
			$privacyRow->updated_at = Carbon::now();
			$privacyRow->update();
			
		endif;
		
	}
	
	/**
	 * Controlla se ho la PP attiva
	 * @param  String $email
	 * @return Boolean
	 */
	
	public static function readPrivacyRow ( $email ) {
		
		$privacyRow = AcceptancePrivacy::where("email", $email)->first();
		
		if (!$privacyRow)
			return false;
			
		return true;
		
	}
	
	/**
	 * Legge il check sulla PP
	 * @param  String $email
	 * @return Boolean
	 */
	
	public static function 	getCheckForm ( $email ) {

		$privacy = false;
		if (($email  != "" && AcceptancePrivacy::readPrivacyRow($email)) || Config::get("privacy.privacy"))
			$privacy = true;
			
		return $privacy;
			
	}
		
}

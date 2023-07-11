<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CookieDB extends Model
{
		// tabella in cui vengono salvati i record
		protected $table = 'tblCookies';
		
		// attributi NON mass-assignable
		protected $guarded = ['id'];



		// gg dopo i quali vengono cancellati i cookie (dall'ultima modifica) 
		private $delay_days_last_moddify;


	public function __construct(array $attributes = [])
  {
      $this->delay_days_last_moddify = 30;
      parent::__construct($attributes);
  }



	/* =============================================
	  =            Query scope            =
	  ============================================= */

	public function scopeFindByCodice($query, $codice_cookie) 
	  {
	  return $query->where('codice_cookie', '=', $codice_cookie);
	  }



	public function scopeModifyWithDelay($query)
	  {
	  $now_time  = Carbon::now()->toDateTimeString(); 
	  return $query->whereRaw("DATEDIFF('".$now_time."', updated_at ) > ". $this->delay_days_last_moddify);
	
	  }  



}

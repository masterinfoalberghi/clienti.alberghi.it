<?php

/**
 * MailMultipla
 *
 * @author Info Alberghi Srl
 * 
 */

namespace App;

use App\CameraAggiuntiva;
use App\Hotel;
use Lang;
use Illuminate\Database\Eloquent\Model;

class MailMultipla extends Model
{

	protected $table = 'tblMailMultiple';
	protected $guarded = ['id'];
	protected $fillable = ['tipologia', 'nome', 'arrivo', 'partenza', 'trattamento', 'adulti', 'bambini', 'eta_bambini', 'email', 'richiesta', 'IP', 'referer', 'camere', 'date_flessibili', 'api_sync','lang_id'];

	const TIPOLOGIA_NORMALE = 'normale';
	const TIPOLOGIA_WISHLIST = 'wishlist';
	const TIPOLOGIA_MOBILE = 'mobile';

	public static $adulti_select = array(
		'1' => '1 Ao',
		'2' => '2 As',
		'3' => '3 As',
		'4' => '4 As',
		'5' => '5 As',
		'6' => '6 As'
	);
	
	public static $adulti_over_select = array(
		'0' => '0 AOo',
		'1' => '1 AOs',
		'2' => '2 AOs',
		'3' => '3 AOs',
		'4' => '4 AOs',
		'5' => '5 AOs',
		'6' => '6 AOs',
	);

	public static $bambini_select = array(
		'0' => '0 Bs',
		'1' => '1 Bo',
		'2' => '2 Bs',
		'3' => '3 Bs',
		'4' => '4 Bs',
		'5' => '5 Bs',
		'6' => '6 Bs'
	);

	public static $camere_select = array(
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
	);
	
	
	/**
	 * Mette le email in relazione con i clienti
	 * 
	 * @access public
	 * @return $query
	 */
	 	
	public function clienti()
	{
		return $this->belongsToMany('App\Hotel', 'tblHotelMailMultiple', 'mailMultipla_id', 'hotel_id');
	}


	/**
	 * Mette le email in relazione con le camere aggiuntive
	 * 
	 * @access public
	 * @return $query
	 */
	 
	public function camereAggiuntive()
	{
		return $this->hasMany('App\CameraAggiuntiva', 'mailMultipla_id', 'id');
	}

	
  
	/* ------------------------------------------------------------------------------------
	 * SCOPE
	 * ------------------------------------------------------------------------------------ */

	 
	 	public function scopeBeforeToday($query)
	 	{
	 		return $query->where("created_at", "<", date("Y-m-d 00:00:00"));
	 	}
	 
	
	/**
	 * Filtro per tipologia
	 * 
	 * @access public
	 * @param object $query
	 * @param array $tipologie (default: array)
	 * @return object
	 */
	 
	public function scopeTipologia($query, $tipologie = [])
	{
		if (!$tipologie)
			return $query;

		/**
		  * Interseziono l'array passato con le tipologie selezionabili, così
		  * vengono eliminate tipologie inserite a mano
		  */
		
		$tipologie_selezionate = array_intersect(self::getTipologieSelezionabili(), $tipologie);
		return $query->whereIn('tipologia', $tipologie_selezionate);
		
	}

	public function scopeToSyncAPI($query)
	{
		return $query->where("api_sync", 0)->where('tipologia','!=','doppia')->where('tipologia','!=','doppia parziale');
	}


	public function scopeAlreadySyncAPI($query)
	{
		return $query->where("api_sync", 1);
	}
	
	
	/**
	 * Ritorna le tipologia delle email multiple
	 * 
	 * @access public
	 * @static
	 * @return array
	 */
	 
	public static function getTipologieSelezionabili()
	{
		return [
			self::TIPOLOGIA_NORMALE,
			self::TIPOLOGIA_WISHLIST,
			self::TIPOLOGIA_MOBILE
		];
	}


}

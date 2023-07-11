<?php

/**
 * MailScheda
 *
 * @author Info Alberghi Srl
 * 
 */

namespace App;

use App\Hotel;
use App\CameraAggiuntiva;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MailScheda extends Model
{

	protected $table = 'tblMailScheda';
	protected $guarded = ['id'];
	protected $fillable = ['nome', 'arrivo', 'partenza', 'telefono', 'trattamento', 'adulti', 'bambini', 'camere', 'eta_bambini', 'email', 'richiesta', 'IP', 'referer', 'tipologia', 'date_flessibili','api_sync','lang_id', "whatsapp"];

	const TIPOLOGIA_DESKTOP = 'normale';
	const TIPOLOGIA_MOBILE  = 'mobile';
	
	public static $adulti_select = array(
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
	);


	/**
	 * Salva mese e anno in autimatico
	 * 
	 * @access protected
	 * @static
	 * @return void
	 */
	 
	protected static function boot()
	{
		parent::boot();

		parent::saving(function(MailScheda $self)
			{
				$data_creazione = Carbon::parse($self->created_at);
				$self->mese = $data_creazione->month;
				$self->anno = $data_creazione->year;
			});
	}



	/* ------------------------------------------------------------------------------------
	 * RELETIONS
	 * ------------------------------------------------------------------------------------ */



	 
	/**
	 * Mette le email in relazione con i clienti
	 * 
	 * @access public
	 * @return $query
	 */
	 
	public function cliente()
	{
		return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
	}


	/**
	 * Mette le email in relazione con le camere aggiuntive
	 * 
	 * @access public
	 * @return $query
	 */
	 
	public function camereAggiuntive()
	{
		return $this->hasMany('App\CameraAggiuntiva', 'mailScheda_id', 'id');
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
	 
	public function scopeTipologia($query, $tipologie = array())
	{
		if (!$tipologie || $tipologie == [-1])
			return $query;

		/**
		  * Interseziono l'array passato con le tipologie selezionabili, così
		  * vengono eliminate tipologie inserite a mano
		  */
		  
		$tipologie_selezionate = array_intersect(self::getTipologieSelezionabili(), $tipologie);

		return $query->whereIn('tipologia', $tipologie_selezionate);
	}

	
	/**
	 * Filtro per anno
	 * 
	 * @access public
	 * @param object $query
	 * @param string $anno
	 * @return object 
	 */
	 
	public function scopeAnno($query, $anno)
	{
		if (!$anno)
			return $query;

		return $query
		->where('anno', '=', $anno);
		
	}



	
	public function scopeToSyncAPI($query)
	{
		return $query->where("api_sync", 0)->where('tipologia','!=','doppia');
	}

	public function scopeAlreadySyncAPI($query)
	{
		return $query->where("api_sync", 1);
	}


	/**
	 * Ritorna le tipologia di email dirette
	 * 
	 * @access public
	 * @static
	 * @return array
	 */
	 
	public static function getTipologieSelezionabili()
	{
		
		return [
		self::TIPOLOGIA_DESKTOP,
		self::TIPOLOGIA_MOBILE
		];
		
	}


}

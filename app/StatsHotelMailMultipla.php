<?php
	
/**
 * StatsHotelMailMultipla
 *
 * @author Info Alberghi Srl
 * 
 */
 
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StatsHotelMailMultipla extends Model
{
	
	protected $table = 'tblStatsHotelMailMultiple';
	const TIPOLOGIA_NORMALE  	= 'normale';
	const TIPOLOGIA_WISHLIST 	= 'wishlist';
	const TIPOLOGIA_MOBILE  	= 'mobile';

	/**
	 * Mette le email in relazione con i clienti
	 * 
	 * @access public
	 * @return $query
	 */

	public function clienti()
	{
		return $this->belongsTo('App\Hotel', 'hotel_id');
	}


	
  
	/* ------------------------------------------------------------------------------------
	 * SCOPE
	 * ------------------------------------------------------------------------------------ */


	 
	
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
	 * Ritorna le tipologia di email dirette
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

	
	/**
	 * Questo metodo aggiunge i dati dello storico delle mail nelle Statistiche delle MailMultiple
	 * ATTENZIONE: Se ci sono inserimenti in tblHotelMailMultiple/tblMailMultiple usare questo metodo per aggiornare le statistiche
	 * 
	 * @access public
	 * @static
	 * @param array $hotel_ids (default: [])
	 * @param string $tipologia (default: 'normale')
	 * @return void
	 */
	 
	public static function addStatsHotelMailMultiple($hotel_ids = [], $tipologia = 'normale')
	{

		$current_data = new Carbon();

		foreach ($hotel_ids as $hotel_id)
			$data_stats[] = [$hotel_id, "'$tipologia'", $current_data->year, $current_data->month, $current_data->day, 1, 'NOW()', 'NOW()'];

		foreach ($data_stats as $data)
			DB::statement("INSERT INTO tblStatsHotelMailMultiple (hotel_id, tipologia, anno, mese, giorno, n_mail, updated_at, created_at)
	        VALUES (".implode(",", $data).")
	        ON DUPLICATE KEY UPDATE n_mail = n_mail +1, updated_at = VALUES(updated_at)");


	}


}

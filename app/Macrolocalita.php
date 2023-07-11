<?php

/**
 * Macrolocalita
 *
 * @author Info Alberghi Srl
 *
 */


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Macrolocalita extends Model
{

	
	protected $table = 'tblMacrolocalita';
	protected $guarded = ['id'];
	protected $fillable = ['nome', 'latitudine', 'longitudine', 'zoom'];


	/* ------------------------------------------------------------------------------------
	 * RELAZIONI
	 * ------------------------------------------------------------------------------------ */


	/**
	 * Aggancia le micro localita
	 * 
	 * @access public
	 * @return void
	 */
	 
	public function localita()
	{
		return $this->hasMany('App\Localita', 'macrolocalita_id', 'id');
	}


	/**
	 * Aggancia i numeri listing_count, n_offerte, prezzo_minimo.
	 * 
	 * @access public
	 * @return void
	 */
	 
	public function conNumeri()
	{
		return $this->hasMany('App\CmsPagina', 'menu_macrolocalita_id', 'id');
	}


	/* ------------------------------------------------------------------------------------
	 * SCOPE
	 * ------------------------------------------------------------------------------------ */
	

	public function scopeReal($query)
	{
		return $query->where('id', '>', 0);
	}


	public function scopeNoRR($query)
	{
		return $query->where('id', '!=', 11);
	}


	public function scopeNoPesaro($query)
	{
		return $query->where('id', '!=', 12);
	}




	public static function searchById($macrolocalita_id = array())
	{
		$all_macrolocalita = Macrolocalita::pluck('nome', 'id'  )->all();
		$return = array();

		foreach ($macrolocalita_id as $id) {
			array_push($return, $all_macrolocalita[$id]);
		}

		return $return;

	}

	// $m->nome_ita
	public function getNomeItaAttribute()
	{
		if (is_null(json_decode($this->nome))) {
			return $this->nome;
		} else {
			return json_decode($this->nome)->it;
		}
		
	}




}

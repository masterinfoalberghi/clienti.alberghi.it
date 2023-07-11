<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MappaRicercaPoiLang extends Model
{
	// tabella in cui vengono salvati i record 
	protected $table = 'tblMappaRicercaPoiLang';
	// attributi NON mass-assignable
	protected $guarded = ['id'];


	public function poi()
	  {
	  return $this->belongsTo('App\MappaRicercaPoi', 'master_id', 'id');
	  }
	
}

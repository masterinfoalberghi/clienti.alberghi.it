<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotPagine extends Model
{
	// tabella in cui vengono salvati i record
	protected $table = 'tblSpotPagine';

	// attributi NON mass-assignable
	protected $guarded = ['id'];


	public function scopeAttivo($query)
	{
		return $query->where("spot_attivo", 1);
	}


}

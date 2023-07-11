<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntoForzaChiaveEspansa extends Model
{
	// tabella in cui vengono salvati i record 
	protected $table = 'tblPuntiForzaChiaveEspansa';
	// attributi NON mass-assignable
	protected $guarded = ['id'];

	public function chiave()
    {
    return $this->belongsTo('App\PuntoForzaChiave','puntoForzaChiave_id','id');
    }
}

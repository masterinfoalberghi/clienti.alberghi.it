<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntoForzaChiave extends Model
{
	// tabella in cui vengono salvati i record 
	protected $table = 'tblPuntiForzaChiave';
	// attributi NON mass-assignable
	protected $guarded = ['id'];


	public function alias()
	  {
	  return $this->hasMany('App\PuntoForzaChiaveEspansa','puntoForzaChiave_id','id');
	  }

	protected static function boot()
	  {
	  parent::boot();

	  // Se elimino una parola chiave, elimino anche le parole chiave espanse
	  static::deleting(function($punto)
	    {
	    $punto->alias()->delete();
	    });
	  }

}

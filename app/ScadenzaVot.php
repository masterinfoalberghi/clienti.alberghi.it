<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScadenzaVot extends Model
  {

    protected $dates = [
        'created_at',
        'updated_at',
        'scadenza_al'
    ];

  // tabella in cui vengono salvati i record
  protected $table = 'tblScadenzeVot';
  
  // attributi NON mass-assignable
  protected $guarded = [];


  public function offertaTop()
    {
      return $this->belongsTo('App\VetrinaOffertaTop','vot_id','id');
    }

  public function scopeDiOggi($query)
    {
    return $query->where('scadenza_al',Carbon::now()->toDateString());
    }

  public function scopeNonInviata($query)
    {
    return $query->whereInviata(0);
    }

  }

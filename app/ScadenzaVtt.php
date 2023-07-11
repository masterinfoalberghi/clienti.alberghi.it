<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScadenzaVtt extends Model
  {

    protected $dates = [
        'created_at',
        'updated_at',
        'scadenza_al'
    ];

  // tabella in cui vengono salvati i record
  protected $table = 'tblScadenzeVtt';
  
  // attributi NON mass-assignable
  protected $guarded = [];


  public function offertaTop()
    {
      return $this->belongsTo('App\VetrinaBambiniGratisTop','vtt_id','id');
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

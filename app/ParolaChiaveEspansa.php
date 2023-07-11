<?php

namespace App;

use App\ParolaChiave;
use Illuminate\Database\Eloquent\Model;

class ParolaChiaveEspansa extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblParoleChiaveEspanse';
  // attributi NON mass-assignable
  //protected $guarded = ['id'];

  protected $fillable = ['parolaChiave_id','chiave'];

  public function chiave()
    {
    return $this->belongsTo('App\ParolaChiave','parolaChiave_id','id');
    }

  }

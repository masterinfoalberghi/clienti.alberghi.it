<?php

namespace App;

use App\Servizio;
use Illuminate\Database\Eloquent\Model;

class ServizioLingua extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblServiziLang';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function servizio()
    {
    return $this->belongsTo('App\Servizio', 'master_id', 'id');
    }

  }

<?php

namespace App;

use App\ServizioPrivato;
use Illuminate\Database\Eloquent\Model;

class ServizioPrivatoLingua extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblServiziPrivatiLingua';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function servizio_privato()
    {
    return $this->belongsTo('App\ServizioPrivato', 'master_id', 'id');
    }

  }

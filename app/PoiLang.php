<?php

namespace App;

use App\Poi;
use App\ServizioLingua;
use Illuminate\Database\Eloquent\Model;

class PoiLang extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblPoiLang';
  // attributi NON mass-assignable
  protected $guarded = ['id'];


  public function poi()
    {
    return $this->belongsTo('Poi', 'master_id', 'id');
    }


  }

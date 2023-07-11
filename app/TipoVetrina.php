<?php

namespace App;


use App\TipoVetrina;
use App\Vetrina;
use Illuminate\Database\Eloquent\Model;

class TipoVetrina extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblTipoVetrine';
  // attributi NON mass-assignable
  protected $guarded = ['id'];


  public function vetrine()
    {
    return $this->hasMany('App\Vetrina', 'tipoVetrina_id', 'id');
    }


  }

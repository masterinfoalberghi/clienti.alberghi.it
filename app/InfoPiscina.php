<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoPiscina extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblInfoPiscina';
  
  // attributi NON mass-assignable
  protected $guarded = ['hotel_id'];

  public function hotel()
    {
      return $this->hasOne('App\Hotel','hotel_id','id');
    }

  }

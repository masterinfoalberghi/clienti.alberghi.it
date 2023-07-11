<?php

namespace App;

use App\Hotel;
use Illuminate\Database\Eloquent\Model;

class Tipologia extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblTipologie';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function hotels()
    {
    return $this->hasMany('App\Hotel', 'tipologia_id', 'id');
    }
  }

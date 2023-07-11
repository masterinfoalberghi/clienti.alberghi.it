<?php

namespace App;

use App\NotaListinoLingua;
use App\ServizioLingua;
use Illuminate\Database\Eloquent\Model;

class NotaListino extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblNoteListino';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function noteListino_lingua()
    {
    return $this->hasMany('App\NotaListinoLingua', 'master_id', 'id');
    }

  public function cliente()
    {
    return $this->hasOne('App\Hotel', 'hotel_id', 'id');
    }


  public function scopeAttivo($query)
    {
    return $query->where('attivo', '=', '1');
    }
  }

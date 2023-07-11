<?php

namespace App;

use App\NotaListino;
use App\Servizio;
use Illuminate\Database\Eloquent\Model;

class NotaListinoLingua extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblNoteListinoLang';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function notaListino()
    {
    return $this->belongsTo('NotaListino', 'master_id', 'id');
    }

  }

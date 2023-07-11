<?php

namespace App;

use App\Hotel;
use Illuminate\Database\Eloquent\Model;

class AccettazioneCaption extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblAccettazioneCaptionGallery';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function contraenti()
    {
    return $this->hasMany('App\Hotel', 'accettazioneCaption_id', 'id');
    }

  }


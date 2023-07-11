<?php

namespace App;

use App\DescrizioneHotel;
use App\Servizio;
use Illuminate\Database\Eloquent\Model;

class DescrizioneHotelLingua extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblDescrizioneHotelLang';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function descrizioneHotel()
    {
    return $this->belongsTo('App\DescrizioneHotel', 'master_id', 'id');
    }





  public function scopeInLingua($query,$locale)
    {
    return $query->where('lang_id',$locale);
    }



  }

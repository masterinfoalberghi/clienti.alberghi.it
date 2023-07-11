<?php

namespace App;

use App\DescrizioneHotelLingua;
use App\Hotel;
use App\ServizioLingua;
use Illuminate\Database\Eloquent\Model;

class DescrizioneHotel extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblDescrizioneHotel';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function descrizioneHotel_lingua()
    {
    return $this->hasMany('App\DescrizioneHotelLingua', 'master_id', 'id');
    }

  public function hotel()
    {
    return $this->hasOne('App\Hotel', 'id', 'hotel_id');
    }


  public function descrizioneHotelInlingua($locale = 'it')
    {
    return $this->hasMany('App\DescrizioneHotelLingua', 'master_id', 'id')->inLingua($locale)->first();
    }

  }

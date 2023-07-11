<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelPreferito extends Model
  {

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  
  // tabella in cui vengono salvati i record
  protected $table = 'tblHotelPreferiti';
  
  // attributi NON mass-assignable
  protected $guarded = ['id'];


  public function hotel()
    {
      return $this->hasOne('App\Hotel','hotel_id','id');
    }

  }

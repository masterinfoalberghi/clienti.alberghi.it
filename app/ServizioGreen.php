<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServizioGreen extends Model
{
    use HasFactory;


    // tabella in cui vengono salvati i record 
    protected $table = 'tblServiziGreen';

    // attributi NON mass-assignable
    protected $guarded = ['id'];


    public function clienti()
    {
        return $this->belongsToMany('App\Hotel', 'tblHotelServiziGreen', 'servizio_id', 'hotel_id')->withPivot('altro');
    }

    public function gruppo()
    {
        return $this->belongsTo('App\GruppoServiziCovid', 'gruppo_id', 'id');
    }
}

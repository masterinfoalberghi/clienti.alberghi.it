<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServizioCovid extends Model
{
    use HasFactory;


    // tabella in cui vengono salvati i record 
    protected $table = 'tblServiziCovid';

    // attributi NON mass-assignable
    protected $guarded = ['id'];


    public function clienti()
    {
    return $this->belongsToMany('App\Hotel', 'tblHotelServiziCovid', 'servizio_id', 'hotel_id')->withPivot('distanza');
    }

    public function gruppo()
    {
        return $this->belongsTo('App\GruppoServiziCovid', 'gruppo_id', 'id');
    }
}

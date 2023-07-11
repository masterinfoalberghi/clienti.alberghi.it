<?php

namespace App;

use App\ServizioCovid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServizioCovidHotel extends Model
{
    protected $table = 'tblHotelServiziCovid';

    // attributi NON mass-assignable
    protected $guarded = ['id'];

    public function servizi()
    {
        return $this->belongsTo('App\ServizioCovid', 'servizio_id', 'id');
    }
}

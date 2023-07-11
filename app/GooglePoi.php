<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GooglePoi extends Model
{
    use HasFactory;

    // tabella in cui vengono salvati i record 
    protected $table = 'tblGooglePoi';

    // attributi NON mass-assignable
    protected $guarded = ['id'];



    function cliente()
    {
        return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

}

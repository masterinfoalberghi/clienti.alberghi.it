<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServizioInOut extends Model
{
    use HasFactory;

    // tabella in cui vengono salvati i record 
    protected $table = 'tblServiziInOut';

    // attributi NON mass-assignable
    protected $guarded = ['id'];


    public function clienti()
    {
        return $this->belongsToMany('App\Hotel', 'tblHoltelServiziInOut', 'servizio_id', 'hotel_id')->withPivot('valore_1', 'valore_2', 'opzione');
    }

    public function gruppo()
    {
        return $this->belongsTo('App\GruppoServiziInOut', 'gruppo_id', 'id');
    }
}

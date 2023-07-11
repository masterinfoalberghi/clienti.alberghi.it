<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruppoServiziGreen extends Model
{
    use HasFactory;


    // tabella in cui vengono salvati i record 
    protected $table = 'tblGruppiServiziGreen';

    // attributi NON mass-assignable
    protected $guarded = ['id'];


    public function servizi()
    {
        return $this->hasMany('App\ServizioGreen', 'gruppo_id', 'id');
    }
}

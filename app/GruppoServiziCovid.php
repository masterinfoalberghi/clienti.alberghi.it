<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruppoServiziCovid extends Model
{
    use HasFactory;


    // tabella in cui vengono salvati i record 
    protected $table = 'tblGruppiServiziCovid';

    // attributi NON mass-assignable
    protected $guarded = ['id'];


    public function servizi()
    {
        return $this->hasMany('App\ServizioCovid', 'gruppo_id', 'id');
    }
    
}

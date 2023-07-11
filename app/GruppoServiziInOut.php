<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruppoServiziInOut extends Model
{
    use HasFactory;

    // tabella in cui vengono salvati i record 
    protected $table = 'tblGruppiServiziInOut';

    // attributi NON mass-assignable
    protected $guarded = ['id'];


    public function servizi()
    {
        return $this->hasMany('App\ServizioInOut', 'gruppo_id', 'id');
    }
}

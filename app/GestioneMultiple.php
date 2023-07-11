<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GestioneMultiple extends Model
{
    protected $table = 'tblGestioneMultiple';
    protected $guarded = ['id'];
    public $timestamps = false;
}

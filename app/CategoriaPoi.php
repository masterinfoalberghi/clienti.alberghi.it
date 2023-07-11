<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaPoi extends Model
{
    use HasFactory;

    // tabella in cui vengono salvati i record 
    protected $table = 'tblCategoriePoi';
    // attributi NON mass-assignable
    protected $guarded = ['id'];

    public $timestamps = false;

    public function poi()
    {
        return $this->hasMany('App\Poi', 'categoria_id', 'id');
    }


}

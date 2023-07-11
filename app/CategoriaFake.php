<?php

namespace App;

use App\Hotel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;
use App;

class CategoriaFake extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblCategorieFake';
  // attributi NON mass-assignable
  protected $guarded = ['id'];


  public function clienti()
    {
    return $this->hasMany('App\Hotel', 'categoria_id', 'id');
    }

   public function descrizione()
    {
    $locale = App::getLocale();

    return Lang::get('labels.cat'.$this->id);

    
    }

  public function scopeReal($query)
    {
    return $query->where('ordinamento', '<', 7);
    }
	
  
  }

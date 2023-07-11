<?php

namespace App;

use App\ServizioPrivatoLingua;
use Illuminate\Database\Eloquent\Model;

class ServizioPrivato extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblServiziPrivati';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  protected $fillable = ['nome', 'categoria_id', 'hotel_id'];

  
  public function servizi_privati_lingua()
    {
    return $this->hasMany('App\ServizioPrivatoLingua', 'master_id', 'id');
    }

  public function translate($locale = 'it') 
    {
    return $this->hasMany('App\ServizioPrivatoLingua', 'master_id', 'id')->where('lang_id', '=', $locale);
    }

  function categoria()
    {
    return $this->belongsTo('App\CategoriaServizi', 'categoria_id', 'id');
    }

  public function clienti()
    {
    return $this->belongsTo('App\Hotel','hotel_id', 'id');
    }


  /* =============================================
    =            Query scope            =
    ============================================= */

  /*
  ATTENZIONE!!!!! - DIPENDE DA categoria_id
   */
  public function scopeGratuito($query)
    {
    return $query->where('categoria_id', 1);
    }

  }

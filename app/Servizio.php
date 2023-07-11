<?php

namespace App;

use App\ServizioLingua;
use Illuminate\Database\Eloquent\Model;

class Servizio extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblServizi';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function servizi_lingua()
    {
    return $this->hasMany('App\ServizioLingua', 'master_id', 'id');
    }

  public function translate($locale = 'it') 
    {
    return $this->hasMany('App\ServizioLingua', 'master_id', 'id')->where('lang_id', '=', $locale)->orderBy('nome','asc');
    }

  public function translate_it() 
    {
    return $this->hasOne('App\ServizioLingua', 'master_id', 'id')->where('lang_id', '=', 'it')->orderBy('nome','asc');
    }

  function gruppo()
    {
    return $this->belongsTo('App\GruppoServizi', 'gruppo_id', 'id');
    }

  function categoria()
    {
    return $this->belongsTo('App\CategoriaServizi', 'categoria_id', 'id');
    }

  public function clienti()
    {
    return $this->belongsToMany('App\Hotel', 'tblHotelServizi', 'servizio_id', 'hotel_id')->withPivot('position', 'note', 'note_en', 'note_fr', 'note_de');
    }

  public function clienti_attivi()
    {
    return $this->belongsToMany('App\Hotel', 'tblHotelServizi', 'servizio_id', 'hotel_id')->where('attivo', 1);
    }

  protected static function boot() 
    {

  

    parent::boot();

    // called BEFORE delete()
    static::deleting
      (      
      function(Servizio $self) 
        { 
        $self->servizi_lingua()->delete();
        }
      );
    }


  /* =============================================
    =            Query scope            =
    ============================================= */

  public function scopeNuovo($query)
    {
    return $query->where('categoria_id', '>', 0);
    }


  public function scopeInRicercaMappa($query)
    {
    return $query->where('ricerca_mappa', 1);
    }

  /*
  ATTENZIONE!!!!! - DIPENDE DA categoria_id
   */
  public function scopeGratuito($query)
    {
    return $query->where('categoria_id', 1);
    }
  /*
  ATTENZIONE!!!!! - DIPENDE DA categoria_id
   */
   public function scopePerBambini($query)
    {
    return $query->where('categoria_id', 3);
    }


  public function getNomeLocale($locale = 'it') 
    {    
      $col = 'nome_'.$locale;
      return $this->$col;
    }

  }

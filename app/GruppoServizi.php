<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GruppoServizi extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblGruppoServizi';

  // attributi NON mass-assignable
  //protected $guarded = ['id'];
  protected $fillable = ['id','nome'];


  public function servizi()
    {
    return $this->hasMany('App\Servizio', 'gruppo_id', 'id');
    }


  public function scopeTipoPiscina($query)
    {
    return $query->where('nome', 'Piscina');
    }

  public function scopeTipoPiscinaFuori($query)
    {
    return $query->where('nome', 'Piscina fuori struttura');
    }

  public function scopeTipoBenessere($query)
    {
    return $query->where('nome', 'Benessere');
    }

  public function scopeRicercaMappa($query)
    {
    return $query->where('ricerca_mappa', 1);
    }


  public function getNomeLocale($locale = 'it') 
    {    
      $col = 'nome_'.$locale;
      return $this->$col;
    }

  }

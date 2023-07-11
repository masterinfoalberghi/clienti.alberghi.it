<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriaServizi extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblCategoriaServizi';

  // attributi NON mass-assignable
  protected $guarded = ['id'];
  //protected $fillable = ['id','nome','position'];


  public function servizi()
    {
    return $this->hasMany('App\Servizio', 'categoria_id', 'id')->orderBy('admin_position');
    }


  public function serviziPrivati()
    {
    return $this->hasMany('App\ServizioPrivato', 'categoria_id', 'id');
    }


   public function getNomeFrontEnd()
    {
    if($this->alias == '')
      {
      return $this->nome;
      }
    else
      {
      return $this->alias; 
      }
    }

  /* =============================================
    =            Query scope            =
    ============================================= */


  public function scopeNotListing($query) 
    {
    return $query->where('listing',0);
    }


  public function scopePerBambini($query) 
    {
    return $query->where('id',3);
    }





  }

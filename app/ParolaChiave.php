<?php

namespace App;

use App\ParolaChiaveEspansa;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ParolaChiave extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblParoleChiave';
  // attributi NON mass-assignable

  //protected $guarded = ['id'];

  protected $fillable = ['id','chiave'];


   /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates()
    {
    return ['created_at', 'updated_at', 'mappa_dal', 'mappa_al'];
    }

  public function alias()
    {
    return $this->hasMany('App\ParolaChiaveEspansa','parolaChiave_id','id');
    }

  protected static function boot()
    {
    parent::boot();

    // Se elimino una parola chiave, elimino anche le parole chiave espanse
    static::deleting(function($user)
      {
      $user->alias()->delete();
      });
    }

  public function getNomeLocale($locale = 'it') 
    {    
      $col = 'nome_'.$locale;
      return $this->$col;
    }


  public function isValidInMappa()
    {
    if (is_null($this->mappa_dal) || is_null($this->mappa_al)) 
      {
      return true;
      }
    else
      {
      return Carbon::now()->between($this->mappa_dal, $this->mappa_al);
      }
    }


  // è già passata
  public function isOverInMappa()
    {
    return Carbon::now()->gte($this->mappa_al);
    }


  public function isComingInMappa()
    {    
    return Carbon::now()->lte($this->mappa_dal);
    }



  }

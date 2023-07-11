<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Hotel;
use App\ListinoCustomLingua;

class ListinoCustom extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblListiniCustom';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function listiniCustomLingua()
    {
    return $this->hasMany('App\ListinoCustomLingua', 'master_id', 'id');
    }

  public function translate($locale = 'it') 
    {
    return $this->hasMany('App\ListinoCustomLingua', 'master_id', 'id')->where('lang_id', '=', $locale);
    }

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  protected static function boot()
    {

    /**
     * PASSAGGIO DELICATO
     * voglio che chiamando Model::delete() vengano cancellati anche i Related Model (cioè i record relativi, in questo caso le lingue)
     *
     * Ci sono vari modi per farlo:
     * http://laravel.io/forum/03-26-2014-delete-relationschild-relations-without-cascade
     * http://stackoverflow.com/questions/14174070/automatically-deleting-related-rows-in-laravel-eloquent-orm
     *
     * Abbiamo scelto la via più ordinata e precisa, ma che non è la più performante
     * LEGGERE I LINK SOPRA PER CAPIRE!!!
     */


    parent::boot();

    // called BEFORE delete()
    static::deleting
      (
      function(ListinoCustom $self)
        {

        /*
         * Questo NON chiama la delete di ListinoCustomLingua, ma è la delete del Query Builder!
         * con tutto quello che ne consegue...
         * La posso chiamare così perchè ListinoCustomLingua è la foglia della relazione (non ha ulteriori sotto-relazioni)
         */
        $self->listiniCustomLingua()->delete();
        }
      );
    }


  /* =============================================
    =            Query scope            =
    ============================================= */

  public function scopeAttivo($query)
    {
    return $query->whereAttivo(1);
    }

  }

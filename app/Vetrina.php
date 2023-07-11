<?php

namespace App;



use App\TipoVetrina;
use App\Vetrina;
use App\Zona;
use Illuminate\Database\Eloquent\Model;

class Vetrina extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblVetrine';
  // attributi NON mass-assignable
  protected $guarded = ['id'];
  protected $fillable = ['nome','descrizione','attiva','tipoVetrina_id'];


  public function tipo()
    {
    return $this->belongsTo('App\TipoVetrina', 'tipoVetrina_id', 'id');
    }

  public function localita() 
    {
    return $this->belongsToMany('App\Localita', 'tblLocalitaVetrine', 'vetrina_id' , 'localita_id');
    }

  public function slots()
    {
    return $this->hasMany('App\SlotVetrina', 'vetrina_id', 'id');
    }

  public function slotDisponibili()
    {
      return $this->tipo->n_righe * $this->tipo->n_colonne;
    }
    
  public function getSlots($listing_categorie = 0)
    {
      if ($listing_categorie == 0) {
        return $this->slots()->attivo();
      } else {
        return $this->slots()->attivo()->categoria($listing_categorie);
      }
      
    }


  private function _aggiornaPagina()
    {
      if ($this->pagina_corrente - $this->pagine == 0)
        {
          $this->pagina_corrente = 1;
        }
      else 
        {
          $this->pagina_corrente +=1;
        }

      $this->save();
      
    }


  /* =============================================
  =            Query scope                =
  ============================================= */
  
  public function scopeAttiva($query) 
    {
    return $query->whereAttiva(1);
    }


  public function scopePrincipale($query) 
    {
    return $query->where('tipoVetrina_id',1);
    }

  public function scopeLimitrofe($query) 
    {
    return $query->where('tipoVetrina_id',2);
    }


  }

<?php

namespace App;

use App\Hotel;
use App\Motivazione;
use App\OffertaPrenotaPrimaLingua;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OffertaPrenotaPrima extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblOffertePrenotaPrima';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function last_modifica()
    {
      return ($this->created_at > $this->updated_at) ? $this->created_at : $this->updated_at;
    }

  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates()
    {
    return ['valido_dal', 'valido_al', 'prenota_entro'];
    }

  public function offerte_lingua()
    {
    return $this->hasMany('App\OffertaPrenotaPrimaLingua', 'master_id', 'id');
    }

  public function translate($locale = 'it') 
    {
    return $this->hasMany('App\OffertaPrenotaPrimaLingua', 'master_id', 'id')->where('lang_id', '=', $locale);
    }

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

   /**
   * Tutte le motivazioni per l'offerta
   */
  public function motivazioni()
    {
      return $this->morphToMany(Motivazione::class, 'motivazionabile', 'tblMotivazionabili');
    }


  public function getMotivazioni()
    {
    $motivazioni_arr = [];
    foreach ($this->motivazioni as $motivazione) 
      {
      $motivazioni_arr[] = strtolower($motivazione->motivazione);
      }
    return implode(', ', $motivazioni_arr);
    }



  /*
  definisco un Accessor per un attributo tipologia che NON ESISTE 
  lo utilizzo perhcé in alcune view (es: draw_item_cliente_offerta_listing) 
  utilizzo $offerteLast->tipologia
   */
  public function getTipologiaAttribute($value)
    {
      return 'offerta_prenota_prima';
    }


  /* =============================================
    =            Query scope            =
    ============================================= */

  public function scopeAttiva($query)
    {
    return $query->whereAttivo(1)->where('prenota_entro', '>=', date('Y-m-d'))->where('valido_al', '>=', date('Y-m-d'));
    }

  public function scopeAttivaScaduta($query)
    {
    return $query
            ->whereAttivo(1)
            ->where(function ($q) {
              $q
                ->where('valido_al', '<', date('Y-m-d'))
                ->orWhere('prenota_entro', '<', date('Y-m-d'));
            });
    }

  public function scopeNonArchiviata($query)
    {
    return $query->whereAttivo(1);
    }

  public function scopeArchiviata($query)
    {
    return $query->whereAttivo(0);
    }


  public function scopeOrdinaPer($query, $order)
    {

    if(is_null($order))
      {
      return $query->orderBy("prenota_entro");
      }
    elseif ($order == 'prezzo_min') 
      {
      return $query->orderBy("perc_sconto", "asc");
      }
    elseif ($order == 'prezzo_max') 
      {
      return $query->orderBy("perc_sconto", "desc");
      }
    else
      {
      return $query->orderBy("prenota_entro");
      }
    
    }  

  // Mi interessa sapere l'ultima data di modifica, cioè la data più recente in cui è avvenuta una modica,
  // ma può essere la data della tabella di questa model oppure di uno dei suoi figli in lingua !!!
  // DEVO PREDNERE LA DATA MAGGIORE DI TUTTE 
  
  public function getUltimaModifica()
    {
      $data = $this->last_modifica();
      
      foreach ($this->offerte_lingua() as $offertaLingua) 
        {
        if($offertaLingua->last_modifica() > $data)
          $data = $offertaLingua->last_modifica;
        }

      return date_format(date_create($data),'d/m/Y H:i:s');

    }


  public function scopeOlderThan($query, $years=1)
    {
    return $query->where('valido_dal', '<=', Carbon::today()->subYears($years)->toDateString());
    } 

    
  }

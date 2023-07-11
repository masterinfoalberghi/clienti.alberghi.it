<?php

namespace App;

use App\Hotel;
use App\Localita;
use App\Macrolocalita;
use App\Zona;
use DB;
use Illuminate\Database\Eloquent\Model;

class Localita extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblLocalita';
  // attributi NON mass-assignable
  protected $guarded = ['id'];
  protected $fillable = ['macrolocalita_id'];

  public function clienti()
    {
    return $this->hasMany('App\Hotel', 'localita_id', 'id');
    }

  public function poi()
    {
    return $this->belongsToMany('App\Poi', 'tblLocalitaPoi', 'localita_id', 'poi_id');
    }

  public function clientiAttivi()
    {
    return $this->hasMany('App\Hotel', 'localita_id', 'id')->attivo();
    }  


  /**
   * [scopeNotMontagna escludo Campiglio, Polsa, Pinzolo]
   * @param  [type] $query [description]
   * @return [type]        [description]
   */
  public function scopeNotMontagna($query)
    {
        return $query->whereNotIn('id',[36,45,48]);
    }

  public function numero_clienti_attivi()
    {
    return $this->clienti()->attivo()
          ->selectRaw('localita_id, count(*) as aggregate')
          ->groupBy('localita_id');
    }
    

    /* Notice that you add either the primary key or the
      foreign key to the field list, or else Laravel
      won't be able to link the models together!*/
  public function prezzo_min_clienti_attivi()
  {
      return $this->clienti()->attivo()
         ->selectRaw('min(prezzo_min) as min_prezzo')
         ->where('prezzo_min','>',0);
  }
   
  public function prezzo_max_clienti_attivi()
  {
      return $this->clienti()->attivo()
         ->selectRaw('max(prezzo_max) as max_prezzo')
         ->where('prezzo_max','>',0);
  }

  public function macrolocalita()
    {
    return $this->belongsTo('App\Macrolocalita', 'macrolocalita_id', 'id');
    }


  public function vetrine() 
    {
    return $this->belongsToMany('App\Vetrina', 'tblLocalitaVetrine', 'localita_id', 'vetrina_id');
    }

  public function vetrineDiZona() 
    {
    return $this->belongsToMany('App\Vetrina', 'tblLocalitaVetrine', 'localita_id', 'vetrina_id')->where('tipoVetrina_id','1');
    }

  public function vetrineLaterali() 
    {
    return $this->belongsToMany('App\Vetrina', 'tblLocalitaVetrine', 'localita_id', 'vetrina_id')->where('tipoVetrina_id','2');
    }


  public static function searchById($localita_id = array())
  {
    $all_localita = Localita::pluck('nome','id'  )->all();
    $return = array();
	
    foreach ($localita_id as $id) {
		array_push($return,$all_localita[$id]);
    }
	
    return $return;
    
  }
  /**
   * [searchByName ricerca una località in base al nome ed utilizzando il metodo confrontaStringa (rimuove in entrambe gli spazi interni e fa l'UPPERCASE)]
   * @param  [string] $localita_nome [description]
   * @return [Localita|false]  [ritorna localita object in caso di successo, false altrimenti]
   */
  public function searchByName($localita_nome)
  {
    $all_localita = Localita::pluck('nome', 'id')->all();

    foreach ($all_localita as $id => $to_search) {
        if (Utility::confrontaStringa($to_search, $localita_nome)) {
          return Localita::find($id);
        }
    }

    return false;
    
  }



  public function hotel_bonus_vacanze () {

    return $this->hasMany('App\Hotel', 'localita_id', 'id')->attivo()->where("bonus_vacanze_2020", 1);
  
  }

  
  
  


  }

<?php

namespace App;

use App\PuntoForza;
use Illuminate\Database\Eloquent\Model;

class PuntoForzaLingua extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblPuntiForzaLang';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function puntoDiForza()
    {
    return $this->belongsTo('App\PuntoForza', 'master_id', 'id');
    }


  public function scopeMatch($query, $chiave = array())
    {

    if (!count($chiave))
      return $query;

    if (count($chiave) == 1)  
      {
      $alias = $chiave[0];
      return $query->whereRaw("(UCASE(nome) = ?)",[strtolower($alias)]);
      } 
    else 
      {
        $count = 0;
        $elements = count($chiave)-1;
        
        foreach ($chiave as $alias) 
          {
            if ($count == 0) 
              {
               $query->whereRaw("(UCASE(nome) = ?)",[strtolower($alias)]);
              }
            else
              { 
                
                if ($elements == $count) 
                  {
                  return $query->orWhereRaw("(UCASE(nome) = ?)",[strtolower($alias)]);
                  } 
                else 
                  {
                  $query->orWhereRaw("(UCASE(nome) = ?)",[strtolower($alias)]);
                  }

              }
            $count++;
          }
        
      }

    } /* end scopeMatch*/


  } /* end class PuntoForzaLingua*/

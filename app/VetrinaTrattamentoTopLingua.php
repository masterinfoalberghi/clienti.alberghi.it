<?php

namespace App;

use App\VetrinaTrattamentoTop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VetrinaTrattamentoTopLingua extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblVetrineTrattamentoTopLang';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function vetrina()
    {
    return $this->belongsTo('App\VetrinaTrattamentoTop', 'master_id', 'id');
    }


  public function pagina()
    {
    return $this->belongsTo('App\CmsPagina', 'pagina_id', 'id');
    }


  /* =============================================
    =            Query scope            =
    ============================================= */

  public function scopeInLingua($query, $lang)
    {
    return $query->whereLang_id($lang);
    }


  public function scopeAssociataPagina($query, $pagina_id)
    {
      return $query->where('pagina_id', $pagina_id);
    }


   public function scopeLimitIds($query, $ids = '')
    {
    if(empty($ids))
      return $query;

    if(strpos($ids, ",") !== false)
      return $query->whereIn("id", explode(",", $ids));

    else
      return $query->where("id", $ids);
    }


  }

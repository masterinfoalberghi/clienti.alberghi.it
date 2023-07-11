<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Extensions\LangModel;

class ListinoCustomLingua extends LangModel
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblListiniCustomLang';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  public function listinoCustom()
    {
    return $this->belongsTo('App\ListinoCustom', 'master_id', 'id');
    }

  public static function getFirstCellString($lang_id)
    {
    if($lang_id === "de")
      return "Datum";

    return "Date";
    }


  public function scopeDaVisualizzare($query, $locale = 'it')
    {
      return  $query->where('lang_id',$locale)
                ->where(function($q) {
                  $q
                  ->where('titolo', '!=', '')
                  ->orWhere('sottotitolo', '!=', '');      
                });
              
    }

  }

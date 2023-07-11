<?php

namespace App;

use App\BambinoGratis;
use Illuminate\Database\Eloquent\Model;

class BambinoGratisLingua extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblBambiniGratisLang';
  // attributi NON mass-assignable
  protected $guarded = ['id'];


  /**
   * The relationships that should always be loaded.
   *
   * @var array
   */
  protected $with = ['BambinoGratis'];


  public function getDates() 
    {
    return ['data_approvazione'];
    }
 
  public function BambinoGratis()
    {
    return $this->belongsTo('App\BambinoGratis', 'master_id', 'id');
    }

  /* =============================================
    =            Query scope            =
    ============================================= */


  public function scopeInLingua($query, $lang)
    {
    return $query->whereLang_id($lang);
    }


    /////////////
    //MUTATORS //
    /////////////
    
    public function getNoteAttribute($value)
      {
      if($this->BambinoGratis->solo_2_adulti && strpos($_SERVER["REQUEST_URI"], "/admin") === false) 
        {
        return $value .= '<br/>' . __('labels.solo_2_adulti');
        }
      else
        {
        return $value;
        }
      }




  }

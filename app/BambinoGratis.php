<?php 
namespace App;

use App\Hotel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class BambinoGratis extends Model
  {
  
  // tabella in cui vengono salvati i record
  protected $table = 'tblBambiniGratis';
  
  // attributi NON mass-assignable
  protected $guarded = ['id'];



  public function last_modifica()
    {
      return ($this->created_at > $this->updated_at) ? $this->created_at : $this->updated_at;
    }


  public function _fino_a_anni($locale = 'it') 
    {
    if ($this->fino_a_anni > 1) 
      {
      $fino_a_anni = $this->fino_a_anni . ' '.Lang::get('labels.anni');
      } 
    elseif ($this->fino_a_anni == 1) 
      {
      $fino_a_anni = $this->fino_a_anni . ' '.Lang::get('labels.anno');
      } 
    elseif ($this->fino_a_anni == 0) 
      {
      //$fino_a_anni = "";
      $fino_a_anni = 1 . ' '.Lang::get('labels.anno');
      }
    
    return $fino_a_anni;
    }
  
  public function _fino_a_mesi($locale = 'it') 
    {
    
    if ($this->fino_a_mesi > 1) 
      {
      $fino_a_mesi = $this->fino_a_mesi . ' '.Lang::get('labels.mesi');
      } 
    elseif ($this->fino_a_mesi == 1) 
      {
      $fino_a_mesi = $this->fino_a_mesi . ' '.Lang::get('labels.mese');
      } 
    elseif ($this->fino_a_mesi == 0) 
      {
      $fino_a_mesi = "";
      }

    if ($fino_a_mesi != "") {
      if ($this->_fino_a_anni() != '')  
        {
          $fino_a_mesi = ' ' . Lang::get('labels.e') . ' '. $fino_a_mesi;
        }
      }
    
    return $fino_a_mesi;
    }

  public function _anni_compiuti($locale = 'it')
    {
    if ($this->anni_compiuti == '') 
      {
      return ' ';
      } 
    else 
      {
      return ' ' . Lang::get('labels.'.$this->anni_compiuti);
      }
    }
  
  public function getAnniCompiutiAttribute($value)
    {
        if ($value == 'compiuti') {
          return '';
        } else {
          return $value;
        }
    }
  
  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates() 
    {
    return ['valido_dal', 'valido_al', 'data_approvazione'];
    }
  
  public function cliente() 
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  public function offerte_lingua()
    {
    return $this->hasMany('App\BambinoGratisLingua', 'master_id', 'id');
    }
    
  public function translate($locale = 'it') 
    {
    return $this->hasMany('App\BambinoGratisLingua', 'master_id', 'id')->where('lang_id', '=', $locale);
    }

  public function translate_it() 
    {
    return $this->hasOne('App\BambinoGratisLingua', 'master_id', 'id')->where('lang_id', '=', 'it');
    }
  public function translate_en() 
    {
    return $this->hasOne('App\BambinoGratisLingua', 'master_id', 'id')->where('lang_id', '=', 'en');
    }
  public function translate_fr() 
    {
    return $this->hasOne('App\BambinoGratisLingua', 'master_id', 'id')->where('lang_id', '=', 'fr');
    }
  public function translate_de() 
    {
    return $this->hasOne('App\BambinoGratisLingua', 'master_id', 'id')->where('lang_id', '=', 'de');
    }
  
  /* =============================================
    =            Query scope            =
    ============================================= */
  
  public function scopeAttivo($query) 
    {
    return $query->where('valido_al', '>=', date('Y-m-d'));
    }


  public function scopeArchiviato($query)
    {
    return $query->whereAttivo(0);
    }

  public function scopeNotArchiviato($query)
    {
    return $query->whereAttivo(1);
    }

  // valido al ha come time 00:00:00
  // quindi aggiungo un giorno
  // in modo che se è valido fino ad oggi, oggi sia compreso
  public function isAttivo()
    {
    return $this->valido_al->addDay()->timestamp >= time();
    }

  public function getUltimaModifica()
    {
      $data = $this->last_modifica();
      

      // non ho figli in cui cercare

      return date_format(date_create($data),'d/m/Y H:i:s'); 

    }


  public function scopeOlderThan($query, $years=1)
    {
    return $query->where('valido_dal', '<=', Carbon::today()->subYears($years)->toDateString());
    } 

  }

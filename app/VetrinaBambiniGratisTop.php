<?php

namespace App;

use App\Hotel;
use App\Http\Controllers\Admin\VetrinaOfferteTopController;
use App\VetrinaBambiniGratisTop;
use App\VetrinaBambiniGratisTopLingua;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class VetrinaBambiniGratisTop extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblVetrineBambiniGratisTop';
  // attributi NON mass-assignable
  protected $guarded = ['id'];



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
      $fino_a_anni = "";
      }
    
    return $fino_a_anni;
    }
  
  public function _fino_a_mesi() 
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
    return ['valido_dal', 'valido_al', 'prenota_entro'];
    }


  public function offerte_lingua()
    {
    return $this->hasMany('App\VetrinaBambiniGratisTopLingua', 'master_id', 'id');
    }

  public function translate($locale = 'it') 
    {
    return $this->hasMany('App\VetrinaBambiniGratisTopLingua', 'master_id', 'id')->where('lang_id', '=', $locale);
    }

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }



  public function scadenza()
    {
      return $this->hasOne('App\ScadenzaVtt','vtt_id','id');
    }


  public function scopeWithClienteLazyEagerLoaded($query, $locale = 'it')
    {
      return $query->with([
        'cliente' => function($query) {
          $query->where('attivo', '1');
          $query->withFirstImage();
          $query->withCount(['caparreAttive', 'immaginiGallery', 'serviziCovid']);
        },
        'cliente.stelle',
        'cliente.localita.macrolocalita',
        'cliente.numero_offerte_attive',
        'cliente.numero_last_attivi',
        'cliente.numero_bambini_gratis_attivi',
        'cliente.numero_immagini_gallery', 
        'cliente.bambiniGratisAttivi'   
      ]);
    }

  public function scopeWithClienteEagerLoaded($query, $locale = 'it')
    {
      $terms = [];

      return $query->with([
          'cliente' => function($query) {
            $query->where('attivo', '1');
          },
          'cliente.stelle',
          'cliente.localita.macrolocalita',
          'cliente.offerte'  => function($query) use ($locale){
            $query
              ->attiva()
              ->orderByRaw("RAND()");
          },
          'cliente.offerte.offerte_lingua' => function($query) use ($locale, $terms){
            $query
              ->where('lang_id', '=', $locale)
              ->multiTestoOrTitoloLike($terms);
          },
          'cliente.last'  => function($query) use ($locale){
            $query
              ->attiva()
              ->orderByRaw("RAND()");
          },
          'cliente.last.offerte_lingua' => function($query) use ($locale, $terms){
            $query
              ->where('lang_id', '=', $locale)
              ->multiTestoOrTitoloLike($terms);
          },

          'cliente.offertePrenotaPrima'  => function($query) use ($locale){
            $query
              ->attiva()
              ->orderByRaw("RAND()");
          },
          'cliente.offertePrenotaPrima.offerte_lingua' => function($query) use ($locale, $terms){
            $query
              ->where('lang_id', '=', $locale)
              ->multiTestoOrTitoloLike($terms);
          },

          'cliente.offerteLast'  => function($query) use ($locale){
            $query
              ->attiva()
              ->orderByRaw("RAND()");
          },
          'cliente.offerteLast.offerte_lingua' => function($query) use ($locale, $terms){
            $query
              ->where('lang_id', '=', $locale)
              ->multiTestoOrTitoloLike($terms);
          }, 
          'cliente.bambiniGratisAttivi' => function($query){
            $query
              ->orderBy('valido_dal','asc');
          },
          'cliente.coupon'  => function($query){
             $query
              ->attivo()
              ->disponibile()
              ->fruibile();
          },
          'cliente.immaginiGallery'
          ]);
    }




  /**
   * [getMesiValiditaAsStr ritorna il nome di un mese passato come numero prendendo i valori dalla VetrinaOfferteTopController]
   * @return [type] [description]
   */
  public function getMesiValiditaAsStr()
    {
      $mesi_valdita = "";

      $vc = new VetrinaOfferteTopController;

      foreach (explode(',', $this->mese) as $value) 
        {
        $mesi_valdita .= $vc->getMeseStr($value) . ", ";
        }
      
      return rtrim($mesi_valdita, ', ');
    } 



  /* =============================================
    =            Query scope            =
    ============================================= */

  
  ///////////////////////////////////////////////////////////////////////////////////////////////////////
  // http://stackoverflow.com/questions/5033047/mysql-query-finding-values-in-a-comma-separated-string //
  ///////////////////////////////////////////////////////////////////////////////////////////////////////
  public function scopeAttiva($query)
    {
    return $query->whereAttivo(1)->whereRaw("FIND_IN_SET('".date('n')."-".date('Y')."',mese) > 0");
    }

  public function scopeNonArchiviata($query)
    {
    return $query->whereAttivo(1);
    }

  public function scopeArchiviata($query)
    {
    return $query->whereAttivo(0);
    }

  }

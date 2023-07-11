<?php

namespace App;

use App\CmsPagina;
use App\Hotel;
use App\Http\Controllers\Admin\VetrinaTrattamentoTopController;
use Illuminate\Database\Eloquent\Model;

class VetrinaTrattamentoTop extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblVetrineTrattamentoTop';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates()
    {
    return ['valido_dal', 'valido_al', 'prenota_entro'];
    }


  public function vetrine_lingua()
    {
    return $this->hasMany('App\VetrinaTrattamentoTopLingua', 'master_id', 'id');
    }

  public function translate($locale = 'it') 
    {
    return $this->hasMany('App\VetrinaTrattamentoTopLingua', 'master_id', 'id')->where('lang_id', '=', $locale);
    }

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }


  public function scopeWithClienteLazyEagerLoaded($query, CmsPagina $cms_pagina) 
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
   * [getMesiValiditaAsStr ritorna il nome di un mese passato come numero prendendo i valori dalla VetrinaTrattamentoTopController]
   * @return [type] [description]
   */
  public function getMesiValiditaAsStr()
    {
      $mesi_valdita = "";

      $vc = new VetrinaTrattamentoTopController;

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

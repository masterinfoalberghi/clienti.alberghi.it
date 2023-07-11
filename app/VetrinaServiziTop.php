<?php

namespace App;

use App\CmsPagina;
use App\Hotel;
use App\Http\Controllers\Admin\VetrinaServiziTopController;
use Illuminate\Database\Eloquent\Model;

class VetrinaServiziTop extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblVetrineServiziTop';
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
    return $this->hasMany('App\VetrinaServiziTopLingua', 'master_id', 'id');
    }

  public function translate($locale = 'it') 
    {
    return $this->hasMany('App\VetrinaServiziTopLingua', 'master_id', 'id')->where('lang_id', '=', $locale);
    }

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }


  public function scopeWithClienteLazyEagerLoaded($query,  $locale = 'it', CmsPagina $cms_pagina) 
    {

      /////////////////////////////////////////////////////////////////////////
      // dal 18 dicembre al 31 dicembre cerco anche le offerte per capodanno //
      /////////////////////////////////////////////////////////////////////////
      
      if( $listing_parolaChiave_id = Utility::checkOfferteInEvidenza($cms_pagina->lang_id) )
        {
          
        $parola_chiave = ParolaChiave::with("alias")->find($listing_parolaChiave_id);

        if (isset($parola_chiave->alias))
          foreach ($parola_chiave->alias as $term)
            $terms[] = $term->chiave;


        return $query->with([
          'cliente' => function($query)  use ($cms_pagina) {
            $query->where('attivo', '1');
            $query->withCount(['caparreAttive', 'immaginiGallery', 'serviziCovid'])->withFirstImage()->withFirstImageGroup($cms_pagina->listing_gruppo_servizi_id);
          },
          'cliente.stelle',
          'cliente.localita.macrolocalita',
          'cliente.numero_offerte_attive',
          'cliente.numero_last_attivi',
          'cliente.numero_bambini_gratis_attivi',
          /*'cliente.immaginiListing' =>  function ($query) use ($cms_pagina) {
              $query
               ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id);
          },*/
          'cliente.servizi' => function ($query) use ($cms_pagina) {
              $query
               ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id);
          },

          'cliente.servizi.servizi_lingua' => function ($query) use ($locale) {
              $query
               ->where('lang_id', '=', $locale);
          },      

          'cliente.servizi.categoria' => function ($query) {
              $query
               ->where('listing', 1);
          },

          'cliente.offerteLast'  => function($query) 
          {
            $query
            ->attiva()
            ->ordinaPer(null);
          },
          
          'cliente.offerteLast.offerte_lingua' => function($query) use ($cms_pagina, $terms)
          {
            $query
            ->where('lang_id', '=', $cms_pagina->lang_id)
            ->multiTestoOrTitoloLike($terms);
          },
          
        ]);

        }
      else
        {

        return $query->with([
          'cliente' => function($query)  use ($cms_pagina) {
            $query->where('attivo', '1');
            $query->withCount(['caparreAttive','immaginiGallery', 'serviziCovid'])->withFirstImage()->withFirstImageGroup($cms_pagina->listing_gruppo_servizi_id);
          },
          'cliente.stelle',
          'cliente.localita.macrolocalita',
          'cliente.numero_offerte_attive',
          'cliente.numero_last_attivi',
        //   'cliente.numero_coupon_attivi',
          'cliente.numero_bambini_gratis_attivi',
          
          /*'cliente.immaginiListing' =>  function ($query) use ($cms_pagina) {
              $query
               ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id);
          },*/
          'cliente.servizi' => function ($query) use ($cms_pagina) {
              $query
               ->where('gruppo_id', '=', $cms_pagina->listing_gruppo_servizi_id);
          },

          'cliente.servizi.servizi_lingua' => function ($query) use ($locale) {
              $query
               ->where('lang_id', '=', $locale);
          },      

          'cliente.servizi.categoria' => function ($query) {
              $query
               ->where('listing', 1);
          },
        ]);
          
        }

    }


  public function scopeWithClienteEagerLoaded($query, $locale = 'it',$listing_gruppo_servizi_id = null)
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
          'cliente.immaginiGallery',

          'cliente.immaginiListing' =>  function ($query) use ($listing_gruppo_servizi_id) {
              $query
               ->where('gruppo_id', '=', $listing_gruppo_servizi_id);
          },
          'cliente.servizi' => function ($query) use ($listing_gruppo_servizi_id) {
              $query
               ->where('gruppo_id', '=', $listing_gruppo_servizi_id);
          },

          'cliente.servizi.servizi_lingua' => function ($query) use ($locale) {
              $query
               ->where('lang_id', '=', $locale);
          },      

          'cliente.servizi.categoria' => function ($query) {
              $query
               ->where('listing', 1);
          },
          
          ]);
    }




  /**
   * [getMesiValiditaAsStr ritorna il nome di un mese passato come numero prendendo i valori dalla VetrinaServiziTopController]
   * @return [type] [description]
   */
  public function getMesiValiditaAsStr()
    {
      $mesi_valdita = "";

      $vc = new VetrinaServiziTopController;

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

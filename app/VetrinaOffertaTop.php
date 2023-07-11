<?php

namespace App;

use App\Hotel;
use App\Http\Controllers\Admin\VetrinaOfferteTopController;
use App\Utility;
use App\VetrinaOffertaTop;
use App\VetrinaOffertaTopLingua;
use Illuminate\Database\Eloquent\Model;

class VetrinaOffertaTop extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblVetrineOfferteTop';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates()
    {
    return ['valido_dal', 'valido_al', 'prenota_entro'];
    }


  public function offerte_lingua()
    {
    return $this->hasMany('App\VetrinaOffertaTopLingua', 'master_id', 'id');
    }

  public function translate($locale = 'it') 
    {
    return $this->hasMany('App\VetrinaOffertaTopLingua', 'master_id', 'id')->where('lang_id', '=', $locale);
    }

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }



  public function scadenza()
    {
      return $this->hasOne('App\ScadenzaVot','vot_id','id');
    }


  public function scopeWithClienteLazyEagerLoaded($query, $locale = 'it', $cms_pagina = null)
    {
      if (is_null($cms_pagina)) 
        {
        return $query->with([
          'cliente' => function($query) {
            $query->where('attivo', '1');
          },
          'cliente.stelle',
          'cliente.localita.macrolocalita',
          'cliente.numero_offerte_attive',
          'cliente.numero_last_attivi',
          'cliente.numero_bambini_gratis_attivi',
          'cliente.numero_immagini_gallery',    
        ]);
        } 
      else 
        {
        $terms = [];

        if ($cms_pagina->listing_parolaChiave_id)
          {
            // ATTENZIONE VOGLIO MOSTRARE SOLO LE OFFERTE CHE HANNO QUELLA PAROLA CHIAVE
            // QUINDI PRECARICO SOLO QUELLE OFFERTE NEGLI HOTEL NELL' EAGER LOADING!!!
            
            /*
             * Dalla parola chiave ottengo le parole chiave espanse
             */
            $parola_chiave = ParolaChiave::with("alias")->find($cms_pagina->listing_parolaChiave_id);

            foreach($parola_chiave->alias as $term)
              $terms[] = $term->chiave;
        
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
                      'cliente.offerteLast'  => function($query) {
                        $query
                          ->attiva()
                          ->orderBy("valido_dal");
                      },
                      'cliente.offerteLast.offerte_lingua' => function($query) use ($cms_pagina, $terms){
                        $query
                          ->where('lang_id', '=', $cms_pagina->lang_id)
                          ->multiTestoOrTitoloLike($terms);
                      },    
                    ]);
          } 
        elseif (!empty($cms_pagina->listing_offerta_prenota_prima)) 
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
                    
                    'cliente.offertePrenotaPrima'  => function($query) {
                           $query
                             ->attiva()
                              ->orderBy("prenota_entro");
                    },
                    'cliente.offertePrenotaPrima.offerte_lingua' => function($query) use ($cms_pagina){
                           $query
                             ->where('lang_id', '=', $cms_pagina->lang_id);
                    },

          ]);
          
          }
        elseif ($cms_pagina->listing_offerta == 'offerta') 
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
                    
                    'cliente.offerte'  => function($query) {
                           $query
                             ->attiva()
                              ->orderBy("valido_dal");
                    },
                    'cliente.offerte.offerte_lingua' => function($query) use ($cms_pagina){
                           $query
                             ->where('lang_id', '=', $cms_pagina->lang_id);
                    },

          ]);
          
          }
        elseif ($cms_pagina->listing_offerta == 'lastminute') 
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
                    
                    'cliente.last'  => function($query) {
                           $query
                             ->attiva()
                              ->orderBy("valido_dal");
                    },
                    'cliente.last.offerte_lingua' => function($query) use ($cms_pagina){
                           $query
                             ->where('lang_id', '=', $cms_pagina->lang_id);
                    },

          ]);
          
          }
        else 
          {
          //////////////////////////////////////////////////////////////////////////////////////////////////
          // se noin sono un listing di tipo parolaChiave_id è inutile che eagerloado TUTTE LE OFFERTE !! //
          //////////////////////////////////////////////////////////////////////////////////////////////////
          
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
              'cliente' => function($query) use ($terms) {
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

          }
        
        }
      

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

  public function scopeVisibileInScheda($query)
    {
    return $query->where('nascondi_in_scheda',0);
    } 

  public function scopeTipo($query, $tipo)
    {
    return $query->whereTipo($tipo);
    }

  public function scopeOrdinaPer($query, $order)
    {

    if(is_null($order))
      {
      return $query->orderBy("prenota_entro");
      }
    elseif ($order == 'prezzo_min') 
      {
      if ($this->tipo == 'prenotaprima') 
        {
        return $query->orderBy("perc_sconto", "asc");
        } 
      else 
        {
        return $query->orderBy("prezzo_a_persona", "asc");        
        }
      }
    elseif ($order == 'prezzo_max') 
      {
      if ($this->tipo == 'prenotaprima') 
        {
        return $query->orderBy("perc_sconto", "desc");
        }
      else
        {
        return $query->orderBy("prezzo_a_persona", "desc");        
        }
      }
    else
      {
      return $query->orderBy("prenota_entro");
      }
    
    }  

  }

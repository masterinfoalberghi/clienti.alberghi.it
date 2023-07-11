<?php

namespace App;

use App\Utility;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CmsPagina extends Model
  {

  // tabella in cui vengono salvati i record
  protected $table = 'tblCmsPagine';

  // attributi NON mass-assignable
  protected $guarded = ['id'];


  public function menuMacroLocalita()
  {
    return $this->belongsTo('App\Macrolocalita', 'menu_macrolocalita_id', 'id');
  }

   public function menuLocalita()
  {
    return $this->belongsTo('App\Localita', 'menu_localita_id', 'id');
  }


   public function listingMacroLocalita()
  {
    return $this->belongsTo('App\Macrolocalita', 'listing_macrolocalita_id', 'id');
  }

   public function listingLocalita()
  {
    return $this->belongsTo('App\Localita', 'listing_localita_id', 'id');
  }


  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates()
    {
    return ['menu_dal', 'menu_al', 'created_at', 'updated_at'];
    }


  public function vetrine_offerte_top_lingua()
    {
    return $this->hasMany('App\VetrinaOffertaTopLingua', 'pagina_id', 'id');
    }

  public function vetrine_offerte_bg_lingua()
    {
    return $this->hasMany('App\VetrinaBambiniGratisTopLingua', 'pagina_id', 'id');
    }

  public function vetrine_trattamenti_top_lingua()
    {
    return $this->hasMany('App\VetrinaTrattamentoTopLingua', 'pagina_id', 'id');
    }

  public function vetrine_servizi_top_lingua()
    {
    return $this->hasMany('App\VetrinaServiziTopLingua', 'pagina_id', 'id');
    }

  /**
   * Per praticità, alcuni elenchi sono espressi sul db come comma separated string<br>
   * Es: listing_categorie, listing_tipologie, ecc...<br>
   * Questo è un helper per ritornarli come array
   * @param  string $field il campo da esplodere in un array
   * @return array
   */
  public function splitCommaSeparatedString($field)
    {
    $retval = [];

    if(!empty($this->$field) && strpos($this->$field, ',') !== false)
      $retval = explode(',', $this->$field);
    else
      $retval = [$this->$field];

    return $retval;
    }

  public function scopeAttiva($query)
    {
    return $query->where("attiva", 1);
    }

  public function scopeLingua($query, $lang_id)
    {
    return $query->where("lang_id", $lang_id);
    }


  public function scopeListingMacroLocalita($query, $macrolocalita_id)
    {
    return $query->where("listing_macrolocalita_id", $macrolocalita_id);
    }

  /**
   *
   * select `uri`, `id` from `tblCmsPagine` 
   * where `attiva` = 1 
   * and `lang_id` = 'it' 
   * and ( `listing_macrolocalita_id` = 1 or (`listing_macrolocalita_id` = 0 and `listing_localita_id` = 0) or (`listing_macrolocalita_id` = 11 and `listing_localita_id` = 49))
   * and `vetrine_top_enabled` = 1
   *
   */
  

  public function scopeListingMacroLocalitaOrTrasversali($query, $macrolocalita_id)
    {
    return $query
            ->where(function ($query1) use ($macrolocalita_id) {
                $query1
                ->where("listing_macrolocalita_id", $macrolocalita_id)
                ->orWhere(function ($query2) {
                    $query2
                          ->where('listing_macrolocalita_id', 0)
                          ->where('listing_localita_id', 0);
                })
                ->orWhere(function ($query2) {
                    $query2
                          ->where('listing_macrolocalita_id', Utility::getMacroRR())
                          ->where('listing_localita_id', Utility::getMicroRR());
                });
              });
    }    

  public function scopeVetrinaTopEnabled($query)
    {
    return $query->where("vetrine_top_enabled", 1);
    }

  public function scopeLikeUri($query,$uri)
    {
    return $query->where("uri", 'like', '%'.$uri.'%');
    }


  /**
   * [getAlternatePages Trovo le pagine associata a questa TRANNE QUESTA STESSA PAGINA]
   * @param  [type] $id [id della pagina di cui trovare le associate]
   * @return [type]     [description]
   */
  public static function getAlternatePages($id)
    {
    //$p = new CmsPagina; 
    return (new static)->where("alternate_uri", $id)->where("id","!=",$id);
    }



  //////////////////////////////////////////////////////////////////////////////////////////
  // 09/01/18 Voglio aggiungere il link ai trattamenti b&b nella sezione categoria @lucio //
  //////////////////////////////////////////////////////////////////////////////////////////
  public static function getCategorieWithBB($id_macrolocalita = 0, $id_localita = 0, $locale = 'it')
    {
    $q =  self::attiva()
        ->where("lang_id", $locale)
        ->where("template", "listing")
        ->where("listing_annuali", 0)
        ->where("listing_whatsapp", 0)
        ->where("listing_bambini_gratis", 0)
        ->where("listing_gruppo_servizi_id", 0)
        ->where("listing_parolaChiave_id", 0)
        
        ->where(function($query) use ($id_macrolocalita)
          {
            /*
             * se voglio costruire il menu per la MACROLOCALITA RR (id = 11)
             * devo prendere tutte le pagine listing RR cioè quelle che hanno listing_id_macro e listing_id_localita uguale a RR
             */
             
            if ($id_macrolocalita != Utility::getMacroRR()) {
              $query->where("listing_macrolocalita_id", $id_macrolocalita);
            }
            else {
              $query->where("listing_macrolocalita_id", Utility::getMacroRR());
            }
          })

        ->where("ancora", "!=", "");

        if ($id_macrolocalita != Utility::getMacroRR()) {

          /////////////////////////////////////////////////////////////
          // se è Bellaria / IgeaMarina                              //
          // filtro anche per località perché hanno la stessa macro  //
          /////////////////////////////////////////////////////////////
          if ($id_macrolocalita == Utility::getMacroBellaria()) 
            {

            $q->whereRaw("( 
               (listing_categorie != '' AND listing_categorie REGEXP '^-?[0-9]+$' AND listing_trattamento = '' AND listing_localita_id = $id_localita) 
               OR 
               (listing_tipologie != '' AND listing_tipologie REGEXP '^-?[0-9]+$' AND listing_trattamento = '' AND listing_localita_id = $id_localita) 
               OR (listing_categorie = '' AND listing_trattamento = 'bb') 
             )");
           
            } 
          else 
            {

            $q->whereRaw("( 
              (listing_categorie != '' AND listing_categorie REGEXP '^-?[0-9]+$' AND listing_trattamento = '' AND listing_localita_id = $id_localita) 
              OR 
              (listing_tipologie != '' AND listing_tipologie REGEXP '^-?[0-9]+$' AND listing_trattamento = '') 
              OR (listing_categorie = '' AND listing_trattamento = 'bb') 
            )");
            
            }
          
        
        }
        else {
          $id_localita_rr = Utility::getMicroRR();
          $q->whereRaw("( 
            (listing_categorie != '' AND listing_categorie REGEXP '^-?[0-9]+$' AND listing_trattamento = '' AND listing_localita_id = $id_localita_rr) 
            OR 
            (listing_tipologie != '' AND listing_tipologie REGEXP '^-?[0-9]+$' AND listing_trattamento = '') 
            OR (listing_categorie = '' AND listing_trattamento = 'bb') 
          )");
        }
        
        return $q->get();
        /*echo(str_replace_array('?', $q->getBindings(), $q->toSql()));
        die();*/
    }


  //////////////////////////////////////////////////////////////////////////////////////////
  // 09/01/18 Voglio aggiungere il link ai trattamenti b&b nella sezione categoria @lucio //
  // PRIMA ERA COSI'                                                                      //
  //////////////////////////////////////////////////////////////////////////////////////////
  public static function getCategorie($id_macrolocalita = 0, $id_localita = 0, $locale = 'it')
    {
    return self::attiva()
        ->where("lang_id", $locale)
        ->where("template", "listing")
        ->where("listing_annuali", 0)
        ->where("listing_whatsapp", 0)
        ->where("listing_bambini_gratis", 0)
        ->where("listing_trattamento", '')
        ->where("listing_gruppo_servizi_id", 0)
        ->where("listing_parolaChiave_id", 0)
        ->where(function($query) use ($id_macrolocalita, $id_localita)
          {
            /*
             * se voglio costruire il menu per la MACROLOCALITA RR (id = 11)
             * devo prendere tutte le pagine listing RR cioè quelle che hanno listing_id_macro e listing_id_localita uguale a RR
             */
             
            if ($id_macrolocalita != Utility::getMacroRR()) {
              $query->where("listing_macrolocalita_id", $id_macrolocalita);
              $query->where("listing_localita_id", $id_localita);
            }
            else {
              $query->where("listing_macrolocalita_id", Utility::getMacroRR());
              $query->where("listing_localita_id", Utility::getMicroRR());
            }
          })
        ->where("ancora", "!=", "")
        ->whereRaw("( (listing_categorie != '' AND listing_categorie REGEXP '^-?[0-9]+$') OR (listing_tipologie != '' AND listing_tipologie REGEXP '^-?[0-9]+$') )")
        ->get();
    }


  }

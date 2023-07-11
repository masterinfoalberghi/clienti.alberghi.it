<?php

namespace App;

use App\Hotel;
use Lang;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ListinoMinMax extends Model
  {

  // tabella in cui vengono salvati i record 
  protected $table = 'tblListiniMinMax';
  // attributi NON mass-assignable
  protected $guarded = ['id'];

  /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates()
    {
    return ['periodo_dal', 'periodo_al'];
    }

  public function cliente()
    {
    return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
    }

  /*
   *
   * Grafica il prezzo per il listino
   *
   * @prezzo (array|integer)		- Il prezzo da graficare
   *
   */
   
  public function getPrezzo($prezzo = 0) 
    {
	    if (is_array($prezzo)) {
	    	
	    	$prezzo_return = "";
	    	
	    	if ($prezzo["min"] != "/")
	    		$prezzo_return .= '<small>min</small> <b>' . $prezzo["min"] . "</b> &euro;<br />";	
	    		
	    	if ($prezzo["max"] != "/")
	    		$prezzo_return .= '<small>max</small> <b>' . $prezzo["max"] . "</b> &euro;";
		    
		    return $prezzo_return;
		
		}
		
		return $prezzo > 0 ? $prezzo . " &euro;" : '';
		
    }


 
  /**
    * Grafica il match offerte nel listino
    * 
    * @access public
    * @param int $offerte (default: 0)
    * @param array $offerte (default: array())
    * @return void
    */
   public function getPrezzoeNumeroOfferte($listino = array()) 
   {
	    
	    $nooffCalcolate = 0; 
	    $nooffCalcolateBg = 0;
	    $nooffCalcolatePp = 0;
	    
	    $prezzo = $this->getPrezzo($listino["prezzo"]);
			
	    if ($listino["nofferte"] != 0 && $prezzo != "") {
			
			$title = "";
			$titleBg = "";
			
			foreach($listino["evidenze"] as $key => $off) {
							
				if ($off > 0) {
					
					if ($key == "offerta") {
						
						$title .= $off > 1 ? $off . " " . Lang::get("hotel.n_off")  . ' <i class="icon-heart"></i><br />'   : Lang::get("hotel.1_spec") . '  <i class="icon-heart"></i><br />' ;
						$nooffCalcolate += $off;
						
					} elseif ( $key == "prenotaprima") {
						
						$title .= $off > 1 ? $off . " " . Lang::get("hotel.n_off") . " <b>" . Lang::get("labels.prenota") . '</b>  <i class="icon-heart"></i><br />'   : Lang::get("hotel.1_off") . " <b>" .  Lang::get("labels.prenota")  . '</b> <i class="icon-heart"></i><br />' ;
						$nooffCalcolate += $off;
						
					} elseif ($key == "lastminute") {
						
						$title .= $off > 1 ? $off . " " . Lang::get("hotel.n_off") . " <b>" . Lang::get("listing." . $key) . '</b> <i class="icon-heart"></i><br />' : Lang::get("hotel.1_off") . " <b>" . Lang::get("listing." . $key)  . '</b> <i class="icon-heart"></i><br />' ;
						$nooffCalcolate += $off;
						
					}  elseif ($key == "bambinigratis") {
						
						$title .= $off > 1 ? $off . " " .  Lang::get("hotel.n_off") . " <b>" . Lang::get("labels.bg") . '</b> <i class="icon-heart"></i><br />' : Lang::get("hotel.1_off") . " <b>" .  Lang::get("labels.bg") . '</b> <i class="icon-heart"></i><br />' ;
						$nooffCalcolate += $off;
					
					}
						
				}
				
			}
			
			foreach($listino["offerte"] as $key => $off) {
							
				if ($off > 0) {
					
					if ($key == "offerta") {
						
						$title .= $off > 1 ? $off . " " . Lang::get("hotel.n_off") . '<br />'   : Lang::get("hotel.1_spec") . '<br />' ;
						$nooffCalcolate += $off;
						
					} elseif ( $key == "prenotaprima") {
						
						$title .= $off > 1 ? $off . " " . Lang::get("hotel.n_off") . " <b>" . Lang::get("labels.prenota") . '</b><br />'   : Lang::get("hotel.1_off") . " <b>" .  Lang::get("labels.prenota")  . '</b><br />' ;
						$nooffCalcolate += $off;
						
					} elseif ($key == "lastminute") {
						
						$title .= $off > 1 ? $off . " " . Lang::get("hotel.n_off") . " <b>" . Lang::get("listing." . $key) . '</b><br />' : Lang::get("hotel.1_off") . " <b>" . Lang::get("listing." . $key)  . '</b><br />' ;
						$nooffCalcolate += $off;
						
					}  elseif ($key == "bambinigratis") {
						
						$title .= $off > 1 ? $off . " " .  Lang::get("hotel.n_off") . " <b>" . Lang::get("labels.bg") . '</b><br />' : Lang::get("hotel.1_off") . " <b>" .  Lang::get("labels.bg") . '</b><br />' ;
						$nooffCalcolate += $off;
					
					}
						
				}
				
			}
			
			$id = Utility::generateRandomString(6);
			
			if ($nooffCalcolate > 0) {
				$prezzo .= '<span class="badge reverse tipped-html" data-tooltip-id="tooltip-listino-'.$id.'">' . $nooffCalcolate . '</span>';
				$prezzo .= '<span id="tooltip-listino-'.$id.'" style="display:none;" >' . $title . '</span>';
			}
							
			   
		}
			
		if ($prezzo == "")
			return '<td width="16%" class="empty"></td>';
			
		return '<td width="16%">' . $prezzo . '</td>';
		
	}
		

  public function getPrezzoMin($prezzo = 0)
  {
    return $prezzo > 0 ? '<small class="min">Min</small> '.$prezzo . " &euro;": '';
  }


  public function getPrezzoMax($prezzo = 0)
  {
    return $prezzo > 0 ? '<small class="min">Max</small>  '.$prezzo . " &euro;" : '';
  }


  //////////////
  // ACCESSOR //
  //////////////
  public function getPrezzoSdMinAttribute($value)
    {
      if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }
  public function getPrezzoBbMinAttribute($value)
    {
     if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }
  public function getPrezzoMpMinAttribute($value)
    {
     if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }
  public function getPrezzoPcMinAttribute($value)
    {
     if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }
  public function getPrezzoAiMinAttribute($value)
    {
     if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }

  public function getPrezzoSdMaxAttribute($value)
    {
      if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }
  public function getPrezzoBbMaxAttribute($value)
    {
     if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }
  public function getPrezzoMpMaxAttribute($value)
    {
     if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }
  public function getPrezzoPcMaxAttribute($value)
    {
     if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }
  public function getPrezzoAiMaxAttribute($value)
    {
     if ($value == 0) 
      {
         return '/';
       } 
       else 
       {
         return str_replace('.',',',$value);
       }
    }


  /////////////
  // MUTATOR //
  /////////////
  public function setPrezzoSdMinAttribute($prezzo) 
    { 
        $this->attributes['prezzo_sd_min'] = str_replace(',','.',$prezzo);
    }
  public function setPrezzoBbMinAttribute($prezzo) 
    { 
        $this->attributes['prezzo_bb_min'] = str_replace(',','.',$prezzo);
    }
  public function setPrezzoMpMinAttribute($prezzo) 
    { 
        $this->attributes['prezzo_mp_min'] = str_replace(',','.',$prezzo);
    }
  public function setPrezzoPcMinAttribute($prezzo) 
    { 
        $this->attributes['prezzo_pc_min'] = str_replace(',','.',$prezzo);
    }
  public function setPrezzoAiMinAttribute($prezzo) 
    { 
        $this->attributes['prezzo_ai_min'] = str_replace(',','.',$prezzo);
    }

  public function setPrezzoSdMaxAttribute($prezzo) 
    { 
        $this->attributes['prezzo_sd_max'] = str_replace(',','.',$prezzo);
    }
  public function setPrezzoBbMaxAttribute($prezzo) 
    { 
        $this->attributes['prezzo_bb_max'] = str_replace(',','.',$prezzo);
    }
  public function setPrezzoMpMaxAttribute($prezzo) 
    { 
        $this->attributes['prezzo_mp_max'] = str_replace(',','.',$prezzo);
    }
  public function setPrezzoPcMaxAttribute($prezzo) 
    { 
        $this->attributes['prezzo_pc_max'] = str_replace(',','.',$prezzo);
    }
  public function setPrezzoAiMaxAttribute($prezzo) 
    { 
        $this->attributes['prezzo_ai_max'] = str_replace(',','.',$prezzo);
    }



  /* =============================================
    =            Query scope            =
    ============================================= */

  public function scopeAttivo($query)
    {
    return $query->whereAttivo(1);
    }


  public function scopeNonNullo($query) 
    {
    return $query->where(function ($query1) 
      {
      $query1->where('prezzo_sd_min', '>', '0')->orWhere('prezzo_bb_min', '>', '0')->orWhere('prezzo_mp_min', '>', '0')->orWhere('prezzo_pc_min', '>', '0')->orWhere('prezzo_ai_min', '>', '0')->orWhere('prezzo_sd_max', '>', '0')->orWhere('prezzo_bb_max', '>', '0')->orWhere('prezzo_mp_max', '>', '0')->orWhere('prezzo_pc_max', '>', '0')->orWhere('prezzo_ai_max', '>', '0');
      });
    }


  public function scopePrezzoAlMassimo($query,$prezzo,$trattamenti) 
    {
    if(count($trattamenti) == 0) 
      return $query;

    /* se il prezzo è 0 ritorno solo quelli che hanno il trattamento valorizzato */
    if($prezzo == 0)
      {
        return $query->where(function ($query1) use ($trattamenti, $prezzo)
          {
            foreach(["ai", "pc", "mp", "bb", "sd"] as $t)
              {
              if(in_array($t, $trattamenti))
                $clause[] = "prezzo_{$t}_min > 0";
              }
            $query1->whereRaw("(".implode(" OR ", $clause).")");
          });        
      }

    return $query->where(function ($query1) use ($trattamenti, $prezzo)
      {
        foreach(["ai", "pc", "mp", "bb", "sd"] as $t)
          {
          if(in_array($t, $trattamenti))
            $clause[] = "(prezzo_{$t}_max <= ".$prezzo." AND prezzo_{$t}_max > 0)";
          }
        $query1->whereRaw("(".implode(" OR ", $clause).")");
      });
    }

  /*
   * Serve per i listing
   */  
  public function scopeAttivoPerTrattamento($query, array $trattamenti)
    {
    $clause = [];
    foreach(["ai", "pc", "mp", "bb", "sd"] as $t)
      {
      if(in_array($t, $trattamenti))
        $clause[] = "prezzo_{$t}_min > 0";
      }

    return $query
      ->attivo()
      ->whereRaw("(".implode(" OR ", $clause).")");
    }  



    public function scopeAPartireDa($query, $a_partire_da = "")
      {
        if($a_partire_da == "") 
          return $query;

        return $query
          ->where("periodo_dal", ">=", Carbon::createFromFormat( 'd/m/Y', $a_partire_da )->toDateString());
      }     



     /*
    * Serve per la ricerca avanzata
    * che può aricercare anche per data_dal e per prezzo 
    */  
    public function scopeAttivoAPartireDaPerTrattamento($query, array $trattamenti,  $prezzo = 0, $a_partire_da = "")
      {
        
        return $query
          ->attivo()
          ->aPartireDa($a_partire_da)
          ->prezzoAlMassimo($prezzo,$trattamenti);

      }


    /*
    Serve in admin per aggiungere i periodi nella gestione del listino standard
     */

    /**
     * [scopePrimoPeriodo ritorna la riga del listino dell'hotel con il periodo minore]
     * @param  [type]  $query    [description]
     * @param  integer $hotel_id [description]
     * @return [Listino]          [description]
     */
    public function scopePrimoPeriodo($query, $hotel_id = 0)
      {
        return $query
          ->where('hotel_id',$hotel_id)->orderByRaw("-periodo_dal desc")->first();
      }

    /**
     * [scopePrimoPeriodo ritorna la riga del listino dell'hotel con il periodo maggiore]
     * @param  [type]  $query    [description]
     * @param  integer $hotel_id [description]
     * @return [Listino]          [description]
     */
    public function scopeUltimoPeriodo($query, $hotel_id = 0)
      {
        return $query
          ->where('hotel_id',$hotel_id)->orderByRaw("periodo_dal desc")->first();
      }


  }

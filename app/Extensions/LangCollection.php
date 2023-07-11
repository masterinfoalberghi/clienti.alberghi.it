<?php

/**
 * Molte delle relazioni che abbiamo sono di lingua<br>
 * tblListinoCustom => tblListinoCustomLang<br>
 * In amministrazione, negli elenchi, devo mostrare sempre il testo nella versione in italiano<br>
 * (perchè gli albergatori sono italiani)<br>
 * da qui ho l'esigenza di poter chiamare questo tipo di collections selezionando direttamente una lingua.<br>
 *
 * @see App\LangModel
 * @link http://heera.it/extend-laravel-eloquent-collection-object
 * @author: Luca Battarra
 */

namespace App\Extensions;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

class LangCollection extends Collection
  {

  /**
   * Cerca un model nella collection per lingua.
   *
   * @param  string  $lang_id
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function findByLang($lang_id)
    {
	  /*dd($this->items);
    return Arr::first($this->items, function ($itemKey, $model) use ($lang_id) {
        return $model->lang_id === $lang_id;
    }, null);*/
    
    	foreach($this->items as $e) {
	    	
	    	if ($e->lang_id == $lang_id)
	    	return $e;
	    	
    	}
    	return null;
    
    }




  }
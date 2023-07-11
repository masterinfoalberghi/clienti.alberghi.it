<?php

/**
 * Molte delle relazioni che abbiamo sono di lingua<br>
 * tblListinoCustom => tblListinoCustomLang<br>
 * In amministrazione, negli elenchi, devo mostrare sempre il testo nella versione in italiano<br>
 * (perchè gli albergatori sono italiani)<br>
 * da qui ho l'esigenza di poter chiamare questo tipo di collections selezionando direttamente una lingua.<br>
 * Una relazione di lingua (es tblListinoCustomLang) per essere tornata in una collection LangCollection
 * deve estendere LangModel invece di Model
 *
 * @see Helpers\LangCollection
 * @link http://heera.it/extend-laravel-eloquent-collection-object
 * @author: Luca Battarra
 */

namespace App\Extensions;

use App\Extensions\LangCollection;
use Illuminate\Database\Eloquent\Model;

class LangModel extends Model
  {

  /**
   * Override del metodo interno di Illuminate\Database\Eloquent\Model
   *
   * @param  array  $models
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function newCollection(array $models = [])
    {
    return new LangCollection($models);
    }

  }

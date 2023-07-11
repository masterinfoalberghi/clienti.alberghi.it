<?php
namespace App\Http\Requests\Admin;

use App\Http\Controllers\Admin\SUPERVetrinaTopController;
use App\Http\Controllers\Admin\VetrinaOfferteTopController;
use App\Http\Requests\Request;
use App\Utility;
use Illuminate\Support\Facades\Redirect;

class VetrineOfferteTopRequest extends Request
  {

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() 
    {
    return true;
    }
  
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() 
    {

      /** ATTENZIONE UTILIZZO  Illuminate\Validation\Validator\CustomValidator::validateMaxCharacter Per validate le offerte ed i last (che hanno anche un conta caratteri lato client) 
      non posso usare la validazione predefinita max: perché PHP conta per ogni riga in cui si va a capo i caratteri invisibili \n\r...
      quindi prima strippo questi caratteri, poi faccio il strlen
      ] */

    
    if ($this->request->has('offerta_id')) 
      {
        
        //////////////////////////
        // VALIDAZIONE MODIFICA //
        //////////////////////////
        
        $offerta_id = $this->request->get('offerta_id');

        if ($this->request->get('tipo') == 'prenotaprima') 
          {
           $rules[ "prenota_entro"] = ["required", "date_format:d/m/Y"];
           $rules[ "perc_sconto"] = ["required","integer","between:1,100"];
           // valido_dal > prenota_entro (se offerta comincia il 10/7 il prenota prima DEVE COMINCIARE PRIMA DEL 10/07)
           $rules["valido_dal"] = "data_maggiore_di:prenota_entro";
         } 
        else 
          {
          $rules[ "prezzo_a_persona"] = ["required","integer","between:1,3000"];
          }

        foreach (Utility::linguePossibili() as $lang) 
          {
            $key = $lang.$offerta_id;
            $const_titolo = 'LIMIT_TITOLO_'.$lang;
            $const_testo = 'LIMIT_TESTO_'.$lang;

            $rules['titolo'.$key] = ["required","max_character:".constant("App\Http\Controllers\Admin\VetrinaOfferteTopController::$const_titolo")];
            $rules['testo'.$key] = ["required","max_character:".constant("App\Http\Controllers\Admin\VetrinaOfferteTopController::$const_testo")];
            
          }

        
      } 
    else 
      {
        
        /////////////////////////////
        // validazione INSERIMENTO //
        /////////////////////////////
        $rules =  [
                "titolo" => ["required","max_character:".VetrinaOfferteTopController::LIMIT_TITOLO],
                "testo" => ["required","max_character:".VetrinaOfferteTopController::LIMIT_TESTO],
                //"mese" => ["required"],
                ]; 

        if ($this->request->get('tipo') == 'prenotaprima') 
          {
           $rules[ "prenota_entro"] = ["required", "date_format:d/m/Y"];
           $rules[ "perc_sconto"] = ["required","integer","between:1,100"];
           // valido_dal > prenota_entro (se offerta comincia il 10/7 il prenota prima DEVE COMINCIARE PRIMA DEL 10/07)
           $rules["valido_dal"] = "data_maggiore_di:prenota_entro";
         } 
        else 
          {
          $rules[ "prezzo_a_persona"] = ["required","integer","between:1,3000"];
          }
          

      }


    $anni = Utility::_getYears();

    /**
     * array:4 [▼
          0 => 2017
          1 => 2018
          2 => 2019
          3 => 2020
        ]
     */
    
    if( is_null($this->get('mese'.$anni[0])) && is_null($this->get('mese'.$anni[1])) && is_null($this->get('mese'.$anni[2])) && is_null($this->get('mese'.$anni[3])) ) 
      {
      $rules['mese'.$anni[0]] = ["present"];
      }

    return $rules;
  
    
    }

    public function messages() 
      {

      if ($this->request->has('offerta_id')) 
        {
          $offerta_id = $this->request->get('offerta_id');

          ///////////////////////////////////
          // MESSAGGI VALIDAZIONE MODIFICA //
          ///////////////////////////////////
          $messages = [
                  "prezzo_a_persona.integer" => "Il prezzo deve essere un numero intero",
                  "prezzo_a_persona.between" => "Il prezzo deve essere compreso tra :min e :max",
                  ];

          // valido_dal > prenota_entro
          $messages["valido_dal.data_maggiore_di"] = "La data entro cui prenotare deve essere precedente all'inizio del soggiorno" ;
          
          foreach (Utility::linguePossibili() as $lang) 
            {
              $key = $lang.$offerta_id;
              
              $const_titolo = 'LIMIT_TITOLO_'.$lang;
              $const_testo = 'LIMIT_TESTO_'.$lang;
              
              $messages['titolo'.$key.'.required'] = "Il titolo in" .Utility::getLanguage($lang)[0]." è obbligatorio";

              $messages['titolo'.$key.'.max_character'] = "Il titolo in " .Utility::getLanguage($lang)[0]." può contenere al massimo " .constant("App\Http\Controllers\Admin\VetrinaOfferteTopController::$const_titolo"). " caratteri";

              $messages['testo'.$key.'.required'] = "Il testo in " .Utility::getLanguage($lang)[0]." è obbligatorio";
              
              $messages['testo'.$key.'.max_character'] = "Il testo in " .Utility::getLanguage($lang)[0]." può contenere al massimo ". constant("App\Http\Controllers\Admin\VetrinaOfferteTopController::$const_testo") ." caratteri";
              
            }

        }
      else
        {
          //////////////////////////////////////
          // MESSAGGI VALIDAZIONE INSERIMENTO //
          //////////////////////////////////////
          $messages =  [
                  "titolo.required" => "Il titolo è obbligatorio",
                  "titolo.max_character" => "Il titolo può contenere al massimo ".VetrinaOfferteTopController::LIMIT_TITOLO." caratteri",
                  "testo.required" => "Il testo è obbligatorio",
                  "testo.max_character" => "Il testo può contenere al massimo ".VetrinaOfferteTopController::LIMIT_TESTO." caratteri",
                  "prezzo_a_persona.integer" => "Il prezzo deve essere un numero intero",
                  "prezzo_a_persona.between" => "Il prezzo deve essere compreso tra :min e :max",
                  ];

          // valido_dal > prenota_entro
          $messages["valido_dal.data_maggiore_di"] = "La data entro cui prenotare deve essere precedente all'inizio del soggiorno" ;

        }

      $anni = Utility::_getYears();

      $messages['mese'. $anni[0] .'.present'] = "Selezionare almeno un mese di validità";

      return $messages;

      }
  }

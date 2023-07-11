<?php
namespace App\Http\Requests\Admin;

use App\Http\Controllers\Admin\OffertePrenotaPrimaController;
use App\Http\Requests\Request;
use App\Utility;
use Illuminate\Support\Facades\Auth;

class OffertePrenotaPrimaRequest extends Request
  {

    var $offset = 7;
  /*
  Utilizzo dei custom validation definiti in /app/Providers/CustomValidator.php
  ==============================================================================
   */
  
  private function _check_dates(&$rules)
    {
      // valido_al > valido_dal
      $rules["valido_al"][] = "data_maggiore_di:valido_dal";

      if (Auth::user()->hasRole(['commerciale','hotel'])) 
        {
        // valido_dal deve essere >= oggi
        $rules["valido_dal"][] = "data_maggiore_ieri";
        // prenota_entro deve essere >= oggi
        $rules["prenota_entro"][] = "data_maggiore_ieri";
        }


      // valido_dal > prenota_entro (se offerta comincia il 10/7 il prenota prima DEVE COMINCIARE PRIMA DEL 10/07)
      $rules["valido_dal"][] = "data_maggiore_di:prenota_entro,".$this->offset;

      // valido_al entro 12 mesi da adesso 
      $rules["valido_al"][] = "entro_mesi_da_adesso:".OffertePrenotaPrimaController::MAX_AL_NUMBER;
    }


  private function _add_messages(&$messages)
    {
      // valido_al > valido_dal
      $messages["valido_al.data_maggiore_di"] = "La data di inizio soggiorno deve essere precedente a quella di fine soggiorno";

       if (Auth::user()->hasRole(['commerciale','hotel'])) 
        {
        // valido_dal deve essere >= oggi
        $messages["valido_dal.data_maggiore_ieri"] = "La data di inizio soggiorno deve partire almeno da oggi";
        // prenota_entro deve essere >= oggi
        $messages["prenota_entro.data_maggiore_ieri"] = "La data entro cui è possibile prenotare deve partire almeno da oggi";
        }


      // valido_dal > prenota_entro
      $messages["valido_dal.data_maggiore_di"] = "La data entro cui prenotare deve essere precedente all'inizio del soggiorno di almeno ".$this->offset. " giorni";
      
      // valido_al - valido_dal <= 12 MESI 
      $messages["valido_al.entro_mesi_da_adesso"] = "La data di fine soggiorno non può superare ".OffertePrenotaPrimaController::MAX_AL_NUMBER. " mesi da oggi";

      // testo offerta NO SPAM
      $messages["testo.offer_message_spam"] = "Il testo dell'offferta NON deve contenere INDIRIZZI EMAIL, INDIRIZZI INTERNET o NUMERI DI TELEFONO";
    }


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
        //dd($this->request->all()); 
        
        //////////////////////////
        // VALIDAZIONE MODIFICA //
        //////////////////////////
        
        $offerta_id = $this->request->get('offerta_id');

        $rules = [
                "valido_dal" => ["required", "date_format:d/m/Y"], 
                "valido_al" => ["required", "date_format:d/m/Y"], 
                "prenota_entro" => ["required", "date_format:d/m/Y"],
                "perc_sconto" => ["required","integer","between:5,90"],
               ];

        foreach (Utility::linguePossibili() as $lang) 
          {
            $key = $lang.$offerta_id;
            $const_titolo = 'LIMIT_TITOLO_'.$lang;
            $const_testo = 'LIMIT_TESTO_'.$lang;

            $rules['titolo'.$key] = ["required","max_character:".constant("App\Http\Controllers\Admin\OffertePrenotaPrimaController::$const_titolo")];
            $rules['testo'.$key] = ["required","offer_message_spam","max_character:".constant("App\Http\Controllers\Admin\OffertePrenotaPrimaController::$const_testo")];
            
            
            
          }


        /* 
        SE IN MODIFICA NON HO TOCCATO LE DATE
        NON FACCIO ALCUNA VERIFICA SUI RANGE
        La logica è: quando l'hai creata andavano bene i range, se adesso la modifichi MA NON TOCCHI LE DATE
        allora come range continua ad andare tutto bene.
        Se CAMBI LE DATE, allora ricalcolo TUTTE LE VALIDAZIONI COME SE FOSSE UN NUOVO INSERIMENTO
        - dal non può essere minore di oggi 
        - la fine non può essere dopo 12 mesi 
         */
        
        /*
        "old_valido_dal" => "24/10/2015"
         "old_valido_al" => "07/11/2015"
         "valido_dal" => " 24/10/2015 "
         "valido_al" => " 07/11/2015"
          
          list($d, $m, $y) = explode('/', $periodo_al);
          $periodo_al_carbon = Carbon::createFromDate($y,$m,$d);
         */
        $valido_dal = $this->request->get('valido_dal');
        $valido_al = $this->request->get('valido_al');
        $prenota_entro = $this->request->get('prenota_entro');

        $old_valido_dal = $this->request->get('old_valido_dal');
        $old_valido_al = $this->request->get('old_valido_al');
        $old_prenota_entro = $this->request->get('old_prenota_entro');

        /*if ( Utility::getCarbonDate($valido_dal)->ne(Utility::getCarbonDate($old_valido_dal)) || 
             Utility::getCarbonDate($valido_al)->ne(Utility::getCarbonDate($old_valido_al)) ||
             Utility::getCarbonDate($prenota_entro)->ne(Utility::getCarbonDate($old_prenota_entro))
            ) 
          {*/

          // HO MODIFICATO LE DATE
          
          if ($this->request->get("archiviazione") != 1) {
            $this->_check_dates($rules);
            }

          /*}*/
        
      } 
    else 
      {
        
        /////////////////////////////
        // validazione INSERIMENTO //
        /////////////////////////////
        $rules =  [
                "titolo" => ["required","max_character:".OffertePrenotaPrimaController::LIMIT_TITOLO],
                "testo" => ["required","offer_message_spam","max_character:".OffertePrenotaPrimaController::LIMIT_TESTO],
                "valido_dal" => ["required", "date_format:d/m/Y"], 
                "valido_al" => ["required", "date_format:d/m/Y"],
                "prenota_entro" => ["required", "date_format:d/m/Y"],
                "perc_sconto" => ["required","integer","between:5,90"],
                ];  


        $this->_check_dates($rules);
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
                  "prenota_entro.data_minore_di_con_offset" => "La data entro cui prenotare deve essere precedente all'inizio del soggiorno di almeno ".OffertePrenotaPrimaController::OFFSET
                  ];


          foreach (Utility::linguePossibili() as $lang) 
            {
              $key = $lang.$offerta_id;
              
              $const_titolo = 'LIMIT_TITOLO_'.$lang;
              $const_testo = 'LIMIT_TESTO_'.$lang;
              
              $messages['titolo'.$key.'.required'] = "Il titolo in" .Utility::getLanguage($lang)[2]." è obbligatorio";
              $messages['titolo'.$key.'.max_character'] = "Il titolo in " .Utility::getLanguage($lang)[2]." può contenere al massimo " .constant("App\Http\Controllers\Admin\OffertePrenotaPrimaController::$const_titolo"). " caratteri";
              $messages['testo'.$key.'.required'] = "Il testo in " .Utility::getLanguage($lang)[2]." è obbligatorio";
              $messages['testo'.$key.'.max_character'] = "Il testo in " .Utility::getLanguage($lang)[2]." può contenere al massimo ". constant("App\Http\Controllers\Admin\OffertePrenotaPrimaController::$const_testo") ." caratteri";
              $messages['testo'.$key.'.offer_message_spam'] = "Il testo dell'offferta in " .Utility::getLanguage($lang)[2]." NON deve contenere INDIRIZZI EMAIL, INDIRIZZI INTERNET o NUMERI DI TELEFONO";
              $messages['testo'.$key.'.not_regex'] = "Il testo dell'offferta non deve contenere parole MAIUSCOLE (max 1 lettera consentita)";
              
            }


          $valido_dal = $this->request->get('valido_dal');
          $valido_al = $this->request->get('valido_al');
          $old_valido_dal = $this->request->get('old_valido_dal');
          $old_valido_al = $this->request->get('old_valido_al');

         /* if (Utility::getCarbonDate($valido_dal)->ne(Utility::getCarbonDate($old_valido_dal)) || Utility::getCarbonDate($valido_al)->ne(Utility::getCarbonDate($old_valido_al))) 
            {*/
            // HO MODIFICATO LE DATE
            
            $this->_add_messages($messages);
            
            /*}*/

        }
      else
        {
          //////////////////////////////////////
          // MESSAGGI VALIDAZIONE INSERIMENTO //
          //////////////////////////////////////
          $messages =  [
                  "titolo.required" => "Il titolo è obbligatorio",
                  "titolo.max_character" => "Il titolo può contenere al massimo ".OffertePrenotaPrimaController::LIMIT_TITOLO." caratteri",
                  "testo.required" => "Il testo è obbligatorio",
                  "testo.max_character" => "Il testo può contenere al massimo ".OffertePrenotaPrimaController::LIMIT_TESTO." caratteri",
                  "testo.not_regex" => "Il testo dell'offferta non deve contenere parole MAIUSCOLE (max 1 lettera consentita)",
                  "prezzo_a_persona.integer" => "Il prezzo deve essere un numero intero",
                  "prezzo_a_persona.between" => "Il prezzo deve essere compreso tra :min e :max",
                  "prenota_entro.data_minore_di_con_offset" => "La data entro cui prenotare deve essere precedente all'inizio del soggiorno di almeno ".OffertePrenotaPrimaController::OFFSET
                  ];


          $this->_add_messages($messages);
        }


      return $messages;

      }
  }

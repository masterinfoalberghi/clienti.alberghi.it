<?php

namespace App\Http\Requests\Admin;

use App\Http\Controllers\Admin\BambiniGratisController;
use App\Http\Requests\Request;
use App\Utility;
use Illuminate\Support\Facades\Auth;

class BambiniGratisRequest extends Request
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

        if (!$this->request->has('id') || $this->request->get('id') == 0 ) 
            {
            // inserimento
            $rules =  [
                "note" => ["offer_message_spam","max_character:".BambiniGratisController::LIMIT_TESTO],
                ];
            
            if(!$this->request->get('solo_2_adulti'))
              {
              $rules["note"][] = "required";
              }
             
            }
        else
            {
              // modifica
            $rules = [];
            
            if( $this->request->has('traduci') && $this->request->get('traduci') == 1)
              {
              $campo = 'noteit'.$this->request->get('id');
              
              if(!$this->request->get('solo_2_adulti'))
                {
                $rules[$campo][] = "required";
                }

              }
            else
              {
              foreach (Utility::linguePossibili() as $lang) 
                {
                $campo = 'note'.$lang.$this->request->get('id');
                
                if(!$this->request->get('solo_2_adulti'))
                  {
                  $rules[$campo][] = "required";
                  }
                
                $rules[$campo][] = "offer_message_spam";
                $rules[$campo][] = "max_character:".BambiniGratisController::LIMIT_TESTO;
                }
              }

            } 

        if (Auth::user()->hasRole(['commerciale','hotel'])) 
          {
          // valido_dal deve essere >= oggi
          $rules["valido_dal"][] = "data_maggiore_ieri";

          // al massimo un'offerta può partire da 1 anno da oggi
          $rules["valido_dal"][] = "entro_giorni_da_adesso:".BambiniGratisController::MAX_DAL_NUMBER; 
          }

        if ($this->get('fino_a_anni') == 0 && $this->get('fino_a_mesi') == 0) 
          {
          $rules['fino_a_anni'][] ="different:eta_minima";
          }

        return $rules;

        }

    public function messages() 
        {
        $messages =  [
                  "note.max_character" => "Il campo note può contenere al massimo ".BambiniGratisController::LIMIT_TESTO." caratteri",
                  "note.offer_message_spam" => "Il testo dell'offferta NON deve contenere INDIRIZZI EMAIL, INDIRIZZI INTERNET o NUMERI DI TELEFONO",
                  "note.required" => "Inserire il testo dell'offerta",
                  ];

        if (!$this->request->has('id')) 
          {
          $messages =  [
                  "note.required" => "Inserire il testo dell'offerta",
                  ];  
          }
        else
          {

          foreach (Utility::linguePossibili() as $lang) 
             {
             $campo = 'note'.$lang.$this->request->get('id').'.required';
             $messages[$campo] = "Inserire il testo dell'offerta ($lang)";
             $campo = "note".$lang.$this->request->get('id').".offer_message_spam";
             $messages[$campo] = "Il testo dell'offferta NON deve contenere INDIRIZZI EMAIL, INDIRIZZI INTERNET o NUMERI DI TELEFONO";
             $campo = "note".$lang.$this->request->get('id').".max_character";
             $messages[$campo] = "Il campo note può contenere al massimo ".BambiniGratisController::LIMIT_TESTO." caratteri";
             }
          }


        if (Auth::user()->hasRole(['commerciale','hotel'])) 
          {
          // valido_dal deve essere >= oggi
          $messages["valido_dal.data_maggiore_ieri"] = "La data di inizio offerta deve partire almeno da oggi";
          $messages["valido_dal.entro_giorni_da_adesso"] = "La data di inizio offerta può essere al massimo 1 anno da oggi";
          }
        
        $messages["fino_a_anni.different"] = "Specificare una età dei bambini";

         return $messages;
        }
}

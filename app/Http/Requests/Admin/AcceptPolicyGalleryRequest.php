<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AcceptPolicyGalleryRequest extends Request
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
    $rules = [
            "accettazione" => ["accepted"],
           ];
    
    return $rules;
    }


  public function messages() 
    {
    /*
    ATTENZIONE: sovrascrivo solo alcuni messaggi di validazione, quindi non ricreo l'array $message da capo
     */
    $messages["accettazione.accepted"] = "Devi accettare la policy sulla gestione dei titoli delle foto per proseguire";
   
    return $messages;
    }

  }

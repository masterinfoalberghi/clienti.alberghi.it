<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CouponScontoRequest extends Request
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
            "valore" => ["required","integer","between:1,500"],
            "periodo_dal" => ["required", "date_format:d/m/Y"], 
            "periodo_al" => ["required", "date_format:d/m/Y","data_maggiore_di:periodo_dal", "data_maggiore_di_con_offset:periodo_dal,durata_min"],
            "durata_min" => ["different:seleziona"],
            "numero" => ["required","integer","between:1,100"],
            "accettazione" => ["accepted"],
            "referente" => ["required", "regex:/^[a-zA-Z'\s]+$/"]
           ];
    
    return $rules;
    }
  
  public function messages() 
    {
    /*
    ATTENZIONE: sovrascrivo solo alcuni messaggi di validazione, quindi non ricreo l'array $message da capo
     */
    $messages["valore.required"] = "Il valore del coupon è obbligatorio";
    $messages["periodo_al.data_maggiore_di"] = "La data di inizio validità deve essere precedente a quella di fine validità";
    $messages["periodo_al.data_maggiore_di_con_offset"] = "Il periodo di validità deve essere almeno uguale alla durata minima richiesta";
    $messages["durata_min.different"] = "selezionare le notti minime di permanenza per la validità del coupon";

    return $messages;
    }
  }

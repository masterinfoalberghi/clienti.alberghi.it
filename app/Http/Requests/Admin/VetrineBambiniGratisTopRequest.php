<?php
namespace App\Http\Requests\Admin;
use App\Http\Requests\Request;
use App\Utility;


class VetrineBambiniGratisTopRequest extends Request
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
    $rules = [];
    
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

    if ($this->get('fino_a_anni') == 0 && $this->get('fino_a_mesi') == 0) 
      {
      $rules['fino_a_anni'][] ="different:eta_minima";
      }

     return $rules;    
    }

  public function messages() 
      {
      $messages = [];
      
      $anni = Utility::_getYears();

      $messages['mese'. $anni[0] .'.present'] = "Selezionare almeno un mese di validità";

      $messages["fino_a_anni.different"] = "Specificare una età dei bambini";

      return $messages;
      }

   
  }

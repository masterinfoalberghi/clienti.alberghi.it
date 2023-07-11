<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class NewsLetterRequest extends Request
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
    return [
            "titolo" => ["required"],
            "url" => ["required","url"],
            "data_invio" => ["required", "date_format:d/m/Y"],
            ];
    }

   public function messages() 
    {
     return [
            "titolo.required" => "Il campo Titolo è obbligatorio",
            "url.required" => "Il campo URL è obbligatorio",
            "url.url" => "Il campo URL non ha un formato valido",
            "data_invio.required" => "La data di invio della newsletter è obbligatoria",
            "data_invio.date_format" => "La data  non ha un formato valido",
            ]; 
    }

  }

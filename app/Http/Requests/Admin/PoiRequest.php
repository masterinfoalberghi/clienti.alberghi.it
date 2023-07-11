<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PoiRequest extends Request
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
            "nome" => ["required"],
            "lat" => ["required","numeric"],
            "long" => ["required","numeric"],
            ];
    }

   public function messages() 
    {
     return [
            "nome.required" => "Il campo Nome è obbligatorio",
            "lat.required" => "Il campo Latitudine è obbligatorio",
            "long.required" => "Il campo Longitudine è obbligatorio",
            "lat.numeric" => "Latitudine deve essere un numero (. come separatore per i decimali)",
            "long.numeric" => "Longitudine deve essere un numero (. come separatore per i decimali)",
            ]; 
    }

  }

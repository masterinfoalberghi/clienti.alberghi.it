<?php

namespace App\Http\Requests\Admin;

use App\Utility;
use Illuminate\Foundation\Http\FormRequest;

class MappaRicercaPoiRequest extends FormRequest
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
        if ($this->request->has('poi_id')) 
          {
          //////////////////////////
          // VALIDAZIONE MODIFICA //
          //////////////////////////
          $rules = [
              "lat" => ["required","numeric"],
              "long" => ["required","numeric"],
          ];

          foreach (Utility::linguePossibili() as $lang) 
            {
              $key = $lang;
              
              $rules['nome'.$lang] = ["required"];
              $rules['info_titolo'.$key] = ["required"];
            }

           return $rules;

          }
        else
          {

          return [
              "nome" => ["required"],
              "lat" => ["required","numeric"],
              "long" => ["required","numeric"],
              "info_titolo" => ["required"],
          ];

          }

    }

    public function messages() 
     {

      if ($this->request->has('poi_id')) 
        {
        $messages = [
            "lat.required" => "Il campo Latitudine è obbligatorio",
            "long.required" => "Il campo Longitudine è obbligatorio",
            "lat.numeric" => "Latitudine deve essere un numero (. come separatore per i decimali)",
            "long.numeric" => "Longitudine deve essere un numero (. come separatore per i decimali)"
          ];

         foreach (Utility::linguePossibili() as $lang) 
            {
            $messages['nome'.$lang.'.required'] = "Il campo Nome in " .Utility::getLanguage($lang)[2]." è obbligatorio";
            $messages['info_titolo'.$lang.'.required'] = "Il campo Titolo Info window in " .Utility::getLanguage($lang)[2]." è obbligatorio";
            }

          return $messages;

        }
      else
        {

        return [
               "nome.required" => "Il campo Nome è obbligatorio",
               "info_titolo.required" => "Il campo Titolo Info window è obbligatorio",
               "lat.required" => "Il campo Latitudine è obbligatorio",
               "long.required" => "Il campo Longitudine è obbligatorio",
               "lat.numeric" => "Latitudine deve essere un numero (. come separatore per i decimali)",
               "long.numeric" => "Longitudine deve essere un numero (. come separatore per i decimali)",
               ]; 

        }
     }


}

<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class InfoPiscinaRequest extends Request
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
            "sup" => ["required","integer","between:1,5000"],
            "h" => ["integer","between:1,2000"],
            "h_min" => ["integer","between:1,2000"],
            "h_max" => ["integer","between:1,2000"],
            "espo_sole" => ["integer","between:1,23"],
            "vasca_idro_posti_dispo" => ["integer","between:1,50"],
            "vasca_idro_n_dispo" => ["integer","between:1,50"],
            'vasca_bimbi_sup' => ["integer","between:1,200"],
            //'vasca_bimbi_h' => ["integer","between:5,200"],
            'lettini_dispo' => ["integer","between:1,300"],
            'posizione' => ["required"],
        ];

        if (!$this->request->get('h_min') && !$this->request->get('h_max'))
          {
            $rules["h"][]="required"; 
          }        

        if ($this->request->get('aperto_dal')) 
          {
          // la data di apertura deve essere <= a quella di chiusura (posso anche avere solo 1 mese e quindi coincidono)
          $rules["aperto_dal"][] = "apertura_non_maggiore_di:aperto_al";

          }
          

   
        return $rules;
    }

    public function messages() 
    {
        return   [
                  "sup.required" => "La superficie è obbligatoria",
                  "sup.integer" => "La superficie deve avere un valore intero",
                  "sup.between" => "La superficie deve essere compresa tra :min e :max metri quadrati",

                  "h.required" => "L'altezza è obbligatoria",
                  "h.integer" => "L'altezza deve avere un valore intero",
                  "h.between" => "L'altezza deve essere compresa tra :min e :max centimetri",

                  "h_min.integer" => "L'altezza (minima) deve avere un valore intero",
                  "h_min.between" => "L'altezza (minima) deve essere compresa tra :min e :max centimetri",

                  "h_max.integer" => "L'altezza (massima) deve avere un valore intero",
                  "h_max.between" => "L'altezza (massima) deve essere compresa tra :min e :max centimetri",

                  "espo_sole.integer" => "Il numero di ore di esposizione al sole deve essere un valore intero",
                  "espo_sole.between" => "Il numero di ore di esposizione al sole deve essere compreso tra :min e :max",

                  "vasca_idro_posti_dispo.integer" => "Il numero di posti della vasca idromassaggio (a parte) deve essere un valore intero",
                  "vasca_idro_posti_dispo.between" => "Il numero di posti della vasca idromassaggio (a parte) deve essere compreso tra :min e :max",

                  "vasca_bimbi_sup.integer" => "La superficie (della vasca bambini) deve avere un valore intero",
                  "vasca_bimbi_sup.between" => "La superficie (della vasca bambini) deve essere compresa tra :min e :max metri quadrati",

                  "vasca_bimbi_h.integer" => "L'altezza (della vasca bambini) deve avere un valore intero",
                  "vasca_bimbi_h.between" => "L'altezza (della vasca bambini) deve essere compresa tra :min e :max centimetri",

                  "lettini_dispo.integer" => "Il numero di lettini prendisole disponibili deve essere un valore intero",
                  "lettini_dispo.between" => "Il numero di lettini prendisole disponibili deve essere compreso tra :min e :max",

                  "aperto_dal.apertura_non_maggiore_di" => "La data di chiusura della piscina non può essere antecedente a quella di apertura",
                  ];
    }
}

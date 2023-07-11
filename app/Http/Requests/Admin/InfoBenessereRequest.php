<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class InfoBenessereRequest extends Request
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
            "sup" => ["required","integer"],
            "area_fitness" => ["required"],
            "a_pagamento" => ["required"],
            "obbligo_prenotazione" =>  ["required"],
            "uso_in_esclusiva" =>  ["required"],
        ];


        if($this->request->has('area_fitness') && $this->request->get('area_fitness') == 1)
          {
          $rules['sup_area_fitness'] = ["required","integer","between:1,5000"];    
          }

        if ($this->request->get('aperto_dal')) 
          {
          // la data di apertura deve essere <= a quella di chiusura (posso anche avere solo 1 mese e quindi coincidono)
          $rules["aperto_dal"][] = "apertura_non_maggiore_di:aperto_al";
          }

        if(!$this->request->has('in_hotel'))
          {
          $rules['distanza_hotel'] = ["required","integer","between:1,10000"];    
          }


   
        return $rules;
    }

    public function messages() 
    {
        return   [
                  "aperto_dal.apertura_non_maggiore_di" => "La data di chiusura non può essere antecedente a quella di apertura",
                  ];
    }
}

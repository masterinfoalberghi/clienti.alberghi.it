<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

class HotelRequest extends Request
{

    public function __construct() {
        Validator::extend("multiple_email", function($attribute, $value, $parameters) {
            $rules = [
                'email' => 'email',
            ];
            $mail_arr = explode(',', $value);
            foreach ($mail_arr as $email) {
                $data = [
                    'email' => $email
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }
            return true;
        });
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
        /* ATTENZIONE: non posso solo controllare la validità del campo email perché potrebbe contenere n indirizzi separati da ',' */
        /* You need to write custom Validator, which will take the array and validate each ofthe emails in array manually */
        $rules = [
            "email" => ["required","multiple_email"],
        ];






        /*
        ATTENZIONE: si crea un'eccezione per l'ID 409 perché ha il sito in costruzione !!!
        DA TOGLIERE ASAP ( && $this->request->input("id") != 409) 
        @Sacco 23/05/2017
         */
        if ($this->request->has('attivo') && $this->request->get('id') != 409) 
            {
            $rules["link"] = ["required","url"];
            }
        

        return $rules;
    }


    public function messages() 
      {
        $messages = [
                "link.url" => "Il campo link deve avere un formato valido.",
                "email.multiple_email" => "Almeno una delle mail inserite non è valida",
                ]; 

        return $messages; 
      }
}

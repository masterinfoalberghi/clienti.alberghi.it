<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;

class richiestaMailMultiplaMobileRequest extends Request
{

    private $spam_words = ['http', 'www', '://', 'lavor', 'camerier'];

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

        $ids_send_mail = $this->get('ids_send_mail');
        Session::put('ids_send_mail', $ids_send_mail);

        $cms_pagina_id = $this->get('cms_pagina_id');
        Session::put('cms_pagina_id', $cms_pagina_id);

        $rules = [
            "nome" => ["required"],
            "email" => ["required", "email", "email_validation"], // email_validation: custom validation che utilizza emailhippo.com
            "richiesta" => ["mail_message_spam"], // il testo della mail non deve contenere alcune parole considerate SPAM App\Providers\AppServiceProvider
            "accettazione" => ["accepted"],
            "numero_camere" => ["required", "integer", "between:1,100"],
        ];

        return $rules;

    }
}

<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;

class RichiestaMailSchedaMobileRequest extends Request
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

        $ids_send_mail = $this->get('ids_send_mail');
        Session::put('ids_send_mail', $ids_send_mail);

        $rules = [
            "nome" => ["required"],
            "email" => ["required", "email", "email_validation"], // email_validation: custom validation che utilizza emailhippo.com
            "richiesta" => ["mail_message_spam"],
            "accettazione" => ["accepted"],
            "numero_camere" => ["required", "integer", "between:1,100"],
        ];

        return $rules;

    }

}

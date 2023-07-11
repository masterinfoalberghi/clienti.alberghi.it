<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;

class RichiediPreventivoRequest extends Request
{

    /*
    Utilizzo dei custom validation definiti in /app/Providers/CustomValidator.php
    ==============================================================================
     */

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
     * Get the validation rules that apply to the request.åååå
     *
     * @return array
     */
    public function rules()
    {

        Session::flash('validazione', 'preventivo');

        $rules = [
            "nome" => ["required"],
            "email" => ["required", "email", "email_validation"], // email_validation: custom validation che utilizza emailhippo.com
            "richiesta" => ["mail_message_spam"],
            "accettazione" => ["accepted"],
            "numero_camere" => ["required", "integer", "between:1,100"],
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            "arrivo.arrivo_maggiore_di_oggi" => "Tutte le date di arrivo devono partite da oggi",
        ];

        return $messages;

    }

}

<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;

class RichiestaMailMultiplaRequest extends Request
{

    /*
    Utilizzo dei custom validation definiti in /app/Providers/CustomValidator.php
    ==============================================================================
     */

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

        $multiple_loc = $this->get('multiple_loc');
        if(!is_null($multiple_loc))
            Session::flash('id_loc', $multiple_loc[0]);

        $rules = [
            "multiple_loc_single" => ["required"],
            "nome" => ["required"],
            "email" => ["required", "email", "email_validation"], // email_validation: custom validation che utilizza emailhippo.com
            "richiesta" => ["mail_message_spam"],
            "numero_camere" => ["required", "integer", "between:1,100"],
        ];

        return $rules;

    }
}

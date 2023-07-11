<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class RichiestaWishlistRequest extends Request
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
        // se la validazione fallisce vengo rispedito
        // alla route di provenienza MailMultiplaController@wishlist
        // e devo mettere in session quello che devo mantenere ù
        // oltre ai campi del form che vengono già messi nello scope FLASH
        // (SESSION per 1 richiesta) da Laravel
        Session::put('ids_send_mail', $this->get('ids_send_mail'));

        $rules = [
            "nome" => ["required"],
            "email" => ["required", "email", "email_validation"], // email_validation: custom validation che utilizza emailhippo.com
            "richiesta" => ["mail_message_spam"],
            "numero_camere" => ["required", "integer", "between:1,100"],
        ];

        return $rules;

    }
}

<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Lang;

class RichiestaRicercaAvanzataRequest extends Request
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
            "multiple_loc" => ["required"],
        ];

        $data_default = Lang::get('labels.data_default', array(), Request::get("locale"));

        if (Request::get("a_partire_da") != $data_default) {
            $rules["a_partire_da"] = ["date_format:d/m/Y"];
        }

        return $rules;

    }
}

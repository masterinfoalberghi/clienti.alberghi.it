<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SelezionaHotelRequest extends Request
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
    return [
            "ui_editing_hotel" => ["required", "regex:/^[0-9]+(\s[a-zA-Z0-9\-\._'\s&è®éêìù+àòü\/]+)?$/i"]
            ];
    }



  public function messages()
     {
      return [
                "ui_editing_hotel.required" => 'Selezionare un hotel'
              ];
     }



  }



  
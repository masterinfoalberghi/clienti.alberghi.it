<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Utility;

class NoteListinoRequest extends Request
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
      $rules = [];

      foreach (Utility::linguePossibili() as $lang) 
        {
          $const_note = 'LIMIT_NOTE_'.$lang;
          $rules["testo." .$lang] = ["max_character:".constant("App\Http\Controllers\Admin\NoteListinoController::$const_note")];            
          
        }

      return $rules;
      }

    public function messages() 
      {
      $messages = [];

      foreach (Utility::linguePossibili() as $lang) 
        {
        $const_note = 'LIMIT_NOTE_'.$lang;

        $messages['testo.' .$lang. '.max_character'] = "Le note in " .Utility::getLanguage($lang)." possono contenere al massimo " .constant("App\Http\Controllers\Admin\NoteListinoController::$const_note"). " caratteri";
          
        }

      return $messages;
      }
}

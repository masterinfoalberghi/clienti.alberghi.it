<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Session;

class RichiediCouponRequest extends Request
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
    Session::flash('validazione', 'coupon');
    return ["accetto" => ["accepted"], "email_coupon" => ["required", "email"]];
    }


  /**
   * OVERRIDE del metddo di risposta (class FormRequest extends Request implements ValidatesWhenResolved)
   * per ritornare con la ancora
   * Get the proper failed validation response for the request.
   *
   * @param  array  $errors
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function response(array $errors)
    {
        if ($this->ajax() || $this->wantsJson()) {
            return new JsonResponse($errors, 422);
        }

        return $this->redirector->to($this->getRedirectUrl().'#coupon')
                                        ->withInput($this->except($this->dontFlash))
                                        ->withErrors($errors, $this->errorBag);
    }


  }

<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.password');
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request,  Closure $callback = null)
    {

        $this->validate($request, ['username' => 'required|string']);

        $user = User::where("username", $request->only('username'))->first();

        if (is_null($user)) {
            return back()->withErrors(
            ['username' => trans(Password::INVALID_USER)]
            );
        }

        $credential_arr = [];
        $credential_arr['username'] = $user->username;
       
       // We will send the password reset link to this user. Once we have attempted
       // to send the link, we will examine the response then see the message we
       // need to show to the user. Finally, we'll send out a proper response.
       $response = $this->broker()->sendResetLink(
        $credential_arr
       );

       
       return $response == Password::RESET_LINK_SENT
                   ? $this->sendResetLinkResponse($request, $response)
                   : $this->sendResetLinkFailedResponse($request, $response);

    }


    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()->withErrors(
            ['username' => trans($response)]
        );
    }

}
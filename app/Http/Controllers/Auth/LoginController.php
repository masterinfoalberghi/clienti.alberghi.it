<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';
    protected $loginPath = 'admin/auth/login';
    protected $redirectAfterLogout = 'admin/auth/login';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    // --==============================--
    // --@file: LoginController.php
    // --@desc: quando faccio login setto un cookie in modo da non utilizzare la cache e vedere tutto "fresco"
    // --@time: 2021-5-21 16:54:18
    // --@autor: @Luigi
    // --@return 
    // --==============================--

    protected function setLoginCookie()
    {

        
        //? 09/05/22 @Luigi
        //? Il dominio dei cookie dell'applicazione è clienti.info-alberghi.com
        //? in modo che i cookie tipo xsrf non sia "confuso" con quello creato da un'altra app .info-alberghi.com (editor.info-...)
        //? questo deve essere letto da www.info-alberghi quindi deve essere sul dominio .info-alberghi.com
        
        // lifetime = session
        return Cookie::make('login_cookie', '1', 525600, '/', env("PARENT_COOKIE_DOMAIN", '.info-alberghi.com'));
    }

    protected function setAdminAccessDate()
    {
        $logged_user = $this->guard()->user();
        $logged_user->access_admin_at = Carbon::now();
        $logged_user->save();
    }


    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        /*
      ADESSO Laravel fa in modo prebuilt il flush della session !!!
       */

      
      $this->guard()->logout();
      $request->session()->flush();
      $request->session()->regenerate();
      
      //$login_cookie =   Cookie::forget('login_cookie');
      Cookie::queue(Cookie::forget('login_cookie', '/', env("PARENT_COOKIE_DOMAIN", '.info-alberghi.com')));
      
        //return redirect($this->redirectPath())->withCookie($login_cookie);
        return redirect($this->redirectPath());
    }


    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        $this->setAdminAccessDate();

        Cookie::queue('login_cookie', '1', 525600, '/', env("PARENT_COOKIE_DOMAIN", '.info-alberghi.com'));

        // $login_cookie = $this->setLoginCookie();

        if (Auth::user()->role == "hotel" && (Auth::user()->hotel_id >= 20000 || Auth::user()->hotel_id == 470)) {
           
            $hotel_id = Auth::user()->hotel_id;
		    $from = "assistenza@info-alberghi.com";
		    $nome = "Lo staff di Info Alberghi";
		    $oggetto = "Login Bologna";
            try {

                Mail::send("emails.login",
                compact(
                    "hotel_id",
                    "oggetto"
                ), function ($message) use ($from, $nome, $oggetto ) {
                    $message->from($from, $nome);
                    $message->replyTo($from);
                    $message->to("testing.infoalberghi@gmail.com");
                    $message->subject($oggetto);
                    
                });

            } catch (\Exception $e) {
                echo "Error " . $e->getMessage();
            }

        }

        // if ($this->guard()->user()->role == "hotel")
        //     return redirect("/admin/politiche-cancellazione");

        return redirect($this->redirectPath());

        /*return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());*/
    }


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $this->validateLogin($request);

    

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
           
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {

        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return 'username';
    }




    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() =>  'Nessun account trovato con queste credenziali.',
            ]);
    }

    /**
     * Manutenzione amministrazione
     */
    public function manutenzione()
    {

        return view('auth.login_manutenzione');
    }
}

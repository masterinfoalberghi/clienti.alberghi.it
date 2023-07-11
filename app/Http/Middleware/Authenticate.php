<?php

/**
 * Authenticate
 *
 * @author Info Alberghi Srl
 * 
 */	

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate {

	protected $auth;

	/**
	 * Crea una nuova istanza filtro
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Esegue il controllo sull'autenticazione
	 *
	 * @param  Request  $request
	 * @param  Closure  $next
	 * @return Closure $next
	 */
	 
	public function handle($request, Closure $next)
	{

		//return abort(404);
		if ($this->auth->guest())
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect()->guest('admin/auth/login');
			}
		}

		return $next($request);

	}

}

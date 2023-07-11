<?php

/**
 * CheckRole
 *
 * @author Info Alberghi Srl
 * 
 */
 
namespace App\Http\Middleware;
use Closure;

class CheckRole
{

	/**
	 * Prendo i privilegi richiesti
	 * 
	 * @access private
	 * @param object $route
	 * @return array
	 */
	 
	private function getRequiredRoleForRoute($route)
	{
		$actions = $route->getAction();
		return isset($actions['roles']) ? $actions['roles'] : null;
	}

	/**
	 * Controllo se ho i privilegi utente
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	 
	public function handle($request, Closure $next)
	{
		/**
		 * Chiedo il ruolo richiesto
		 */
		$roles = $this->getRequiredRoleForRoute($request->route());

		/**
		 * Se ho questo privilegio allora passo 
		 */
 		if ($request->user()->hasRole($roles) || !$roles)
			return $next($request);
		
		/**
		 * Se non ho i permessi allora do un'errore
		 */
		
		return response([
				'error' => [
				'code' => 'INSUFFICIENT_ROLE',
				'description' => 'You are not authorized to access this resource.'
				]
		], 401);
		
	}

}

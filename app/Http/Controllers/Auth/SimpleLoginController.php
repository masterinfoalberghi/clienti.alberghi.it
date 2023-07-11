<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpleLoginController extends Controller
{

	// SOLO CHI E' GIA' AUTENTICATO PUO' ACCEDERE A QUESTO CONTROLLER (serve al multiutente)
	public function __construct()
	{
	    $this->middleware('auth');
	}


	public function login(Request $request, $from_caparra = 0)
		{
		
		//dd($request->username);
		
		// validate the request
		//  By default, Laravel's base controller class uses a ValidatesRequests trait which provides a convenient method to validate incoming HTTP request 
		//  with a variety of powerful validation rules.
		$this->validate($request, ['username' => 'required|exists:users']);


		$user = User::byUsername($request->username);

		Auth::login($user);

		if ($from_caparra) 
			{
			return redirect("/admin/politiche-cancellazione");
			} 
		else 
			{
			return redirect('/admin');	
			}
	
		}



}

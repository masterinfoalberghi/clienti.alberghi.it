
php artisan make:auth 

crea lo scaffolding per il sistema di login e registrazione con username e password.







il sistema di autenticazione di default di Laravel utilizza guard: in config/auth.php ho la guaard di default che è web

Quando faccio Auth::check($credentials) dove $credentials è un array con email e password , per verificare se sono autenticato utilizzo la web guard

'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],


e la web guard usa il provider users.



Siccome la sicurezza è già stata "testata" con il primo login, posso creare un meotodo che 
SOLO SE SONO GIA' LOGGATO COME HOTEL CHE APPARTIENE AD UN GRUPPO, mi fa fare autologin con uno degli atri hotel del gruppo senza pwd !!!!

quando mi loggo dal link non voglio utilizzare la route di default che va in  App\Http\Controllers\Auth\LoginController@login,
ma voglio creare un'altra route che va in un altro controller SimpleLoginController che fa loggare utilizzando una guard differente che non controlla la pwd ???



php artisan make:controller Auth/SimpleLoginController



in questo metodo riscriverò la funzione "login"



https://jamesmcfadden.co.uk/custom-authentication-in-laravel-with-guards-and-user-service-providers
https://gistlog.co/corbosman/d0951f9c6cdba20f644e



Authentication in Laravel is centered around guards which allow us to specify varying ways that a user can be authenticated. 
The first thing we’ll do is add our own guard for authenticating interchangeable users. We can do this in /config/auth.php:


creo una nuova guard e la chiamo simple: 


 'simple' => [
            'driver' => 'session',
            'provider' => 'simple',
        ],

questa guard ha un custom provider chiamato "simple" che utilizza sempre la tabella degli utenti ed eloquent


'simple' => [
    'driver' => 'eloquent.simple',
    'model' => App\User::class,
],

the guard provider allows the retrieval of users from your storage engine, and follows the Illuminate\Contracts\Auth\UserProvider contract

creo app/Providers/EloquentSimpleUserProvider.php in cui sovrascrivo il metodo per trovare l'utente tramite le credenziali

<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;

class EloquentSimpleUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
       if (empty($credentials)) {
                 return;
             }

        return User::where('username',$credentials['username'])->first();
    }
}




In our auth configuration earlier, we referenced our new guard provider as eloquent.simple. We’ll need to bind that reference to our implementation, which we can accomplish in the AppServiceProvider:



lo scopo finale è quello di avere un form così:

<form action="simple.login" method="post" accept-charset="utf-8">
	<input type="hidden" name="username" value="">
	<input type="submit" name="submit" value="submit">	
</form>


facendo un post a quella route verifico lo username e faccio il login con quell'utente 



Route::group(['middleware' => ['auth:simple']], function() {
    Route::post('admin/simpleLogin', ['as' => 'simple.login', 'uses' => 'Auth\SimpleLoginController@login']);
});
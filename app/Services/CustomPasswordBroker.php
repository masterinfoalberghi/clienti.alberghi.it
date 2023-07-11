<?php
namespace App\Services;

use Illuminate\Auth\Passwords\PasswordBroker as BasePasswordBroker;
use Closure;
use Illuminate\Support\Arr;
use UnexpectedValueException;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class CustomPasswordBroker extends BasePasswordBroker    
{    
// override the functions that you need here    

	/**
	 * Send a password reset link to a user.
	 *
	 * @param  array  $credentials
	 * @return string
	 */
	public function sendResetLink(array $credentials, Closure $callback = null)
	{
	    // First we will check to see if we found a user at the given credentials and
	    // if we did not we will redirect back to this current URI with a piece of
	    // "flash" data in the session to indicate to the developers the errors.
	    $user = $this->getUser($credentials);

	    if (is_null($user)) {
	        return static::INVALID_USER;
	    }

	    // Once we have the reset token, we are ready to send the message out to this
	    // user with a link to reset their password. We will then redirect back to
	    // the current URI having nothing set in the session to indicate errors.
	    $user->sendPasswordResetNotification(
	        $this->tokens->create($user)
	    );

	    return static::RESET_LINK_SENT;
	}



	/**
	 * Reset the password for the given token.
	 *
	 * @param  array  $credentials
	 * @param  \Closure  $callback
	 * @return mixed
	 */
	public function reset(array $credentials, Closure $callback)
	{
	    // If the responses from the validate method is not a user instance, we will
	    // assume that it is a redirect and simply return it from this method and
	    // the user is properly redirected having an error message on the post.
	    $user = $this->validateReset($credentials);

	    if (! $user instanceof CanResetPasswordContract) {
	        return $user;
	    }

	    $password = $credentials['password'];

	    // Once the reset has been validated, we'll call the given callback with the
	    // new password. This gives the user an opportunity to store the password
	    // in their persistent storage. Then we'll delete the token and return.
	    $callback($user, $password);

	    $this->tokens->delete($user);

	    return static::PASSWORD_RESET;
	}



}   
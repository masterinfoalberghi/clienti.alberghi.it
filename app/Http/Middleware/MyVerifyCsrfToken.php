<?php

/**
 * MyVerifyCsrfToken
 *
 * @author Info Alberghi Srl
 *
 */

namespace App\Http\Middleware;

use App\Http\Middleware\VerifyCsrfToken;
use Closure;
use Request;
use App\Utility;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Session;

class MyVerifyCsrfToken extends VerifyCsrfToken {

	protected $routes = [
							'mail_scheda',
							'form_wishlist',
							'richiesta_wishlist',
							'mail-multipla',
							'richiesta_mailmultipla',
							'ricerca_avanzata.php',
							'richiesta_ricerca_avanzata.php',
							'trova_hotel.php',
							'mail_multipla_mobile',
							'richiesta_mail_multipla_mobile',
							'mail_scheda_mobile',
							'richiesta_mail_scheda_mobile',
							'mail_listing',
							'richiesta_mail_listing',
							'delete_selected',
						];

	/**
	 * Non verifica il CSFR token per alcuni URI (action del form, che va in errore se la sessione è scaduta)
	 *
	 * @param  Request  $request
	 * @param  Closure $next
	 * @return mixed
	 *
	 * @throws \Illuminate\Session\TokenMismatchException
	 */

	public function handle($request, Closure $next)
	{


		if ($this->isReading($request)
			|| in_array($request->segment(1), $this->routes)
			|| in_array($request->segment(2), $this->routes)
			|| in_array($request->segment(3), $this->routes)
			|| $this->tokensMatch($request))
		{
			return $this->addCookieToResponse($request, $next($request));
		}

		/**
		 * Se la sessione scade allora lo mando alla pagina giusta
		 */

		if ($request->segment(1) == "admin")
			return redirect('/admin/auth/login', 301);

		else {

			/**
			 * Se succede mi mando una email
			 * Problema rilevato con la versione 2.0 desktop in fase di sviluppo da tenere sotto controllo
			 */
			$ip = $request->server('REMOTE_ADDR');
			$server = Request::server('HTTP_HOST');
			$subject = "Error at ".$server;
			$trace  = "CODE: INSUFFICIENT_ROLE" . "<br />";
			$trace .= "DESCRIPTION: You are not authorized to access this resource." . "<br />";
			$trace .= "reading: " . $this->isReading($request) . "<br />";
			$trace .= "segment1: " . in_array($request->segment(1), $this->routes) . " (" . $request->segment(1) . ")" . "<br />";
			$trace .= "segment2: " . in_array($request->segment(2), $this->routes) . " (" . $request->segment(2) . ")" . "<br />";
			$trace .= "segment3: " . in_array($request->segment(3), $this->routes) . " (" . $request->segment(3) . ")" . "<br />";
			$trace .= "tokensMatch: " . $this->tokensMatch($request) . "<br />";
			$trace .= "token: " . csrf_token() . "<br />";
			$trace .= "<br />";

			if (Request::all()):
				$trace .= '<span style="padding:30px 0; text-align: left; font-size:20px; color: #ff0000; display:block; white-space: wrap">Server data</span>';
				$trace .= "<table style='width:100%; max-width:1000px'>";
				foreach (Request::all() as $k => $s) {
					$trace .= "<tr><td style='width:150px;'>";
					if (is_array($s)) {
						foreach ($s as $kk=> $ss) {
							if (is_array($ss))
								$trace .=  $kk ."</td><td>". implode(",", $ss) . PHP_EOL;
							else
								$trace .=  $kk ."</td><td>". $ss . PHP_EOL;
						}
					} else {
						$trace .=  $k ."</td><td>". $s . PHP_EOL;
					}
					$trace .= "</td></tr>";
				}
				$trace .= "</table>";
			endif;

			if (Request::server()):
				$trace .= '<span style="padding:30px 0; text-align: left; font-size:20px; color: #ff0000; display:block; white-space: wrap">Post data</span>';
				$trace .= "<table style='width:100%; max-width:1000px'>";
				foreach (Request::server() as $k => $s) {
					$trace .= "<tr><td style='width:150px;'>";
					if (is_array($s)) {
						foreach ($s as $kk=> $ss) {
							$trace .=  $kk ."</td><td>". $ss . PHP_EOL;
						}
					} else {
						$trace .=  $k ."</td><td>". $s . PHP_EOL;
					}
					$trace .= "</td></tr>";
				}
				$trace .= "</table>";
			endif;

			for($t=1;$t<20;$t++):
				$cc = \Cookie::get("prefill_v" . $t);
				if ($cc):
					$trace .= '<span style="padding:30px 0; text-align: left; font-size:20px; color: #ff0000; display:block; white-space: wrap">Prefill ' . $t .'</span>';
					$trace .= "<table style='width:100%; max-width:1000px'>";
					$trace .= "<tr><td>";
						if ($cc):
							$trace .= \Cookie::get("prefill_v".$t);
						endif;
						$trace .= "</td></tr>";
					$trace .= "</table>";
				endif;
			endfor;

			//Utility::sendMeEmailError($subject , $trace, $server);
			Session::flash('flash_message', 'La tua richiesta è scaduta!');
			Session::flash('flash_message_important', true);
			return redirect('/', 301);

		}

	}


}

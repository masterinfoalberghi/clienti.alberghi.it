<?php
namespace App\Exceptions;


use App;
use App\Http\Controllers\HomeController;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Utility;

class Handler extends ExceptionHandler
{

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		\Illuminate\Auth\AuthenticationException::class,
		\Illuminate\Auth\Access\AuthorizationException::class,
		\Symfony\Component\HttpKernel\Exception\HttpException::class,
		\Illuminate\Database\Eloquent\ModelNotFoundException::class,
		\Illuminate\Session\TokenMismatchException::class,
		\Illuminate\Validation\ValidationException::class,
	];
	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Throwable $e)
	{

		$token = 0;


		// se sono il local ed ho il debug attivo comportamento default
		if (App::environment() == 'local' && config('app.debug')) 
			{
			return parent::report($e);
			}


		///////////////////////////////////////////////////////////////////////////////////////
		// Laravel 5.3: 
		// If a form request's validation fails, Laravel will now throw an instance of 
		// Illuminate\Validation\ValidationException instead of an instance of HttpException //
		///////////////////////////////////////////////////////////////////////////////////////
		if ($e instanceof ValidationException) 
			{
			//die('Laravel ha validato qualcosa !!!');
			return parent::report($e);
			}

		if ($e instanceof NotFoundHttpException)
			$statusCode = $e->getStatusCode();
		elseif ($e instanceof TokenMismatchException)
			$token = 1;

		if (!isset($statusCode)) {

			$file = $e->getFile();
			$line = $e->getLine();
			$exception = $e->getMessage();
			$ip = Request::server('REMOTE_ADDR');
			$server = Request::server('HTTP_HOST');
			$host = gethostbyaddr(Request::server('REMOTE_ADDR'));
			$url = $host.Request::server('REQUEST_URI');

			$separator = '</td></tr><tr><td>';
			$one_line = str_replace("\n", $separator, $e->getTraceAsString());

			$trace  = '<span style="padding:30px 0px; text-align: left; font-size:20px; color: #ff0000; display:block;">Whoops, look like something went wrong</span>';
			$trace .= "<table style='width:100%; max-width:1000px'>";
			$trace .= '<tr><td style="width:150px; color: #666; font-size:12px;">IP</td><td>' . $ip . '</td></tr>';
			$trace .= '<tr><td style="width:150px; color: #666; font-size:12px;">URL</td><td>' . $url . '</td></tr>';
			$trace .= '<tr><td style="width:150px; color: #666; font-size:12px;">FILE</td><td>' . $file . '</td></tr>';
			$trace .= '<tr><td style="width:150px; color: #666; font-size:12px;">LINE</td><td>' . $line . '</td></tr>';
			$trace .= '<tr><td style="color: #666; font-size:12px;">ERRORE</td><td>' . $exception . '</td></tr>';
			$trace .= "</table>";

			$trace .= '<span style="padding:30px 0; text-align: left; font-size:20px; color: #ff0000; display:block; white-space: wrap">Log error</span>';
			$trace .= "<table style='width:100%; max-width:1000px'>";
			$trace .= "<tr><td style='width:150px;'>". $one_line . "</td></tr>";
			$trace .= "</table>";

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
				$trace .= '<span style="padding:30px 0; text-align: left; font-size:20px; color: #ff0000; display:block; white-space: wrap">Prefill ' . $t .'</span>';
				$trace .= "<table style='width:100%; max-width:1000px'>";
				$trace .= "<tr><td>";
					$cc = \Cookie::get("prefill_v" . $t);
					if ($cc):
						$trace .= \Cookie::get("prefill_v".$t);
					endif;
					$trace .= "</td></tr>";
				$trace .= "</table>";
			endfor;

			if (!$token && !$e instanceof \Illuminate\Validation\ValidationException) {

				$subject = "Error at ".$server." (".$file ." at line ". $line.")";
				$body = $trace;
				
				Utility::sendMeEmailError( $subject , $body, $server);

			}
		}

		return parent::report($e);
	}



	protected function handleValidationException($request, $e)
	{
		$errors = @$e->validator->errors()->toArray();
		$message = null;
		if (count($errors)) {
			$firstKey = array_keys($errors)[0];
			$message = @$e->validator->errors()->get($firstKey)[0];
			if (strlen($message) == 0) {
				$message = "An error has occurred when trying to register";
			}
		}

		if ($message == null) {
			$message = "An unknown error has occured";
		}

		//\Flash::error($message);

		return \Illuminate\Support\Facades\Redirect::back()->withErrors($e->validator)->withInput();
	}


	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Throwable $e)
	{


		if ($e instanceof \Illuminate\Validation\ValidationException)
			return $this->handleValidationException($request, $e);

		$detect = new \Detection\MobileDetect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'views.' : 'phone_views.') : 'views.');

		$locale = Utility::getLocaleByUrl();

		$desc1   = Lang::get('labels.desc1404', array(), $locale);
		$desc2   = Lang::get('labels.desc2404', array(), $locale);
		$desc3   = Lang::get('labels.desc3404', array(), $locale);

		$diritti  = Lang::get('hotel.diritti', array(), $locale);
		$privacy  = Lang::get('hotel.privacy', array(), $locale);
		$link   = Utility::getUrlWithLang($locale, '/privacy-policy.php');

		$homepage = HomeController::_404($locale);

		$valoriHomepage = $homepage[1];
		$template_homepage = $homepage[2];
		$offers_homepage = $homepage[3];
		$news_homepage = $homepage[4];
		$menu_localita = $homepage[5];

		// 404 page when a model is not found
		if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {

			$titolo = Lang::get('labels.page404', array(), $locale);

			array($locale, $valoriHomepage, $template_homepage, $offers_homepage, $news_homepage, $menu_localita);

			return response()
			->view(
				$deviceType .'errors.404',
				compact(
					'titolo',
					'desc1',
					'desc2',
					'desc3',
					'locale',
					'diritti',
					'privacy',
					'link',
					'valoriHomepage',
					'template_homepage',
					'offers_homepage',
					'news_homepage',
					'menu_localita'
				),
				404
			);

		}

		if ($this->isHttpException($e)) {

			return $this->renderHttpException($e);

		} else {

			// se sono il local ed ho il debug attivo comportamento default
			if (App::environment() == 'local' && config('app.debug')) 
				{
				return parent::render($request,$e);
				}

			$titolo = Lang::get('labels.page500', array(), $locale);
			return response()
					->view(
						$deviceType .'errors.500',
							compact(
								'e',
								'titolo',
								'desc1',
								'desc2',
								'desc3',
								'locale',
								'diritti',
								'privacy',
								'link',
								'valoriHomepage',
								'template_homepage',
								'offers_homepage',
								'news_homepage',
								'menu_localita'
							),
						500
					);

		}

	}

}

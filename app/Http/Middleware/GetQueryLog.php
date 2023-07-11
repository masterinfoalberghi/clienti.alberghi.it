<?php

/**
 * CheckDispositivo
 *
 * @author Info Alberghi Srl
 *
 */
	
namespace App\Http\Middleware;

use App\CookieIA;
use App\Utility;
use Carbon\Carbon;
use Closure;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetQueryLog
  {
  
	
  /**
	 * Prende gli altri valori Cookie.
	 * 
	 * @access private
	 * @return void
	 */
	 
	private function _cookieOther () {
		
		$hotelPreferiti = CookieIA::getFavourite();		
		$localita = CookieIA::getCookieLocalita();
		
		$other = "";
		
		$other .= '<span style="color:#888; font-size:12px; width:120px; display:inline-block; padding:2px 5px; margin-bottom:1px; ">Hotel preferiti</span>&nbsp;';
		$other .= '<span style="display:inline-block; ">'. $hotelPreferiti . "</span><br/>";	
		
		$other .= '<span style="color:#888; font-size:12px; width:120px; display:inline-block; padding:2px 5px; margin-bottom:1px; ">Località visitate</span>&nbsp;';
		$other .= '<span style="display:inline-block; ">'. $localita . "</span><br/>";	
		
		return $other;
		
	}
  
  	/**
	 * Crea l'html per il cookie
	 * 
	 * @access private
	 * @param mixed $prefill
	 * @return string $new_prefill
	 */
	 
	private function _cookiePrefill ($prefill) {


		if (empty($prefill)) 
			{
			return "Nessun cookie o nessun cookie con una corrispondenza sul DB";
			}
		
		$new_prefill = "";
				
		foreach ($prefill as $key => $p):
			
			if (!is_array($p)) {
				
				$new_prefill .= '<span style="color:#888; font-size:12px; width:120px; display:inline-block; padding:2px 5px; margin-bottom:1px; ">' . $key . '</span>&nbsp;';
				$new_prefill .= '<span style="display:inline-block; ">'. $p . "</span><br/>";
				
			} else {
				
				if ($key == "rooms") {
				
					$c = 1; 
					foreach ($prefill[$key] as $nc):
						
						$new_prefill .= '<span style="color:#888; font-size:10px; width:100px; display:inline-block; "></span>&nbsp;';
						$new_prefill .= "<br />Camera ". $c ."<br /><br />"; $c++;
						
						foreach ($nc as $key2 => $p2):
						
							$new_prefill .= '<span style="color:#888; font-size:12px; width:120px; display:inline-block; padding:2px 5px; margin-bottom:1px; ">' . $key2 . '</span>&nbsp;';
							$new_prefill .= $p2 . "<br />";
						
						endforeach;
						
					endforeach;
					
				}
				
				if ($key == "servizi") {
					
					$new_prefill .= "<br />Ricerca<br /><br />";
					
					$new_prefill .= '<span style="color:#888; font-size:12px; width:120px; display:inline-block; padding:2px 5px; margin-bottom:1px; ">' . $key . '</span>&nbsp;';
					$new_prefill .= implode(", ", $p) . "<br />";

				}
				
				if ($key == "localita_multi") {
					
					$new_prefill .= '<span style="color:#888; font-size:12px; width:120px; display:inline-block; padding:2px 5px; margin-bottom:1px; ">' . $key . '</span>&nbsp;';			
					$new_prefill .= implode(", ", $p) . "<br />";
					
					$new_prefill .= "<br />Altro<br /><br />"; $c++;

				}
				
			}
			
		endforeach;
		
		return $new_prefill;
		
	}
  
  	/**
	 * Costruisce l'html delle query
	 * 
	 * @access private
	 * @static
	 * @param object $queryLog
	 * @return string $query
	 */
	 
	private static function _queryLog ($queries) {
		
		$query = "";
		$count = 1;

		/**
		 * Ciclo su tutto l'oggetto 
		 */
		foreach ($queries as $q):

			if ($count < 10)
				$query .= '<span style="color:#888; font-size:12px;display:inline-block; padding:2px 5px; margin-bottom:1px; ">0' . $count . '</span>&nbsp;';
			else
				$query .= '<span style="color:#888; font-size:12px; display:inline-block; padding:2px 5px; margin-bottom:1px; ">' . $count . '</span>&nbsp;';

			$qu = explode("?", $q["query"]);
			$newquery = "";
			$cc = 0;
			
			/**
			 * Aggiungo i parametri
			 */
			 
			if (is_array($qu)) {
				foreach ($qu as $newq):

					$newquery .= $newq;

				if (isset($q["bindings"][$cc]))
					$newquery .= "'" . $q["bindings"][$cc] . "'";

				$cc++;

				endforeach;
				
			} else {
				
				$newquery =  $q["query"];
				
			}
			
			
			$query .= $newquery . '<br/>';
			$count++;
			endforeach;

		return 	$query;
				
	}
	
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next) 
    {
		DB::enableQueryLog();
    	return $next($request);
    }

/**
	 * Mostra il pannello del debug
	 *
 * @param  [type] $request  [description]
 * @param  [type] $response [description]
 * @return [type]           [description]
 */
	 
  public function terminate($request, $response) 
    {
    
		$segment_exclude = array("attiva_preferito", "disattiva_preferito","serviziHotelCrm", "serviziAggiuntiviHotelCrm", "infoPiscinaHotelCrm", "infoBenessereHotelCrm", "admin");
	   
		    
		/**
		 * Se sono un Virtual Host valido (NON ABBIAMO più IP pubblico !!!!) e non sono l'amministrazione
		 */
		
		/*if ( Config::get('app.env') == 'local' && !in_array($request->segment(1),$segment_exclude)  || ( !is_null(Auth::user()) && Auth::user()->hasRole('admin') ) ) {*/
		if ( Config::get('app.env') == 'local' && !in_array($request->segment(1),$segment_exclude) && false  ) {

			$prefillcookie 	= CookieIA::checkForCookie();
			$data 			= Carbon::now()->toDateTimeString();
			$queries 		= DB::getQueryLog();
			
			$memory 		= '<strong>' . round(memory_get_usage()/1000000,2) . '</strong> mb usati&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$hostname 		= 'Hostname:&nbsp;<strong>' . $request->getHttpHost() . '</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$queries_cont	= '<a href="#" onclick="changeTab(\'query_log\')" style="color:#1fb0e1"><strong>' . count($queries)  . '</strong> query eseguite</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$cache 			= '<a href="#" onclick="changeTab(\'cache_log\')" style="color:#1fb0e1"><strong>Cache</strong></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$prefill		= '<a href="#" onclick="changeTab(\'prefill_log\')" style="color:#1fb0e1">Oggetto <strong>prefill</strong></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$other			= '<a href="#" onclick="changeTab(\'other_cookie_log\')" style="color:#1fb0e1">Altri <strong>oggetti cookie</strong></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			
			$queries_html 	= '<span id="query_log" style="height:500px; padding:15px; display:none; overflow:scroll; ">' . $this->_queryLog($queries) . '</span>';
			$prefill_html	= '<span id="prefill_log" style="height:500px;  padding:15px; display:none; overflow:scroll; ">' . $this->_cookiePrefill($prefillcookie) . '</span>';
			$cache_html		= '<span id="cache_log" style="height:500px;  padding:15px; display:none; overflow:scroll; ">' .  Utility::$debugTagString  . '</span>';
			$other_html		= '<span id="other_cookie_log" style="height:500px;  padding:15px; display:none; overflow:scroll; ">' .  $this->_cookieOther()  . '</span>';
			
			
			echo '<script>'.
					'function hideTab () {' .
						'document.getElementById("cache_log").style.display = "none";' .
						'document.getElementById("prefill_log").style.display = "none";' .
						'document.getElementById("query_log").style.display = "none";' .
						'document.getElementById("other_cookie_log").style.display = "none";' .
					'}' .
					'function changeTab ( chi = "" ) {' .
						'hideTab();' .
						'if(chi !="") {' .
							'document.getElementById(chi).style.display = "block";' .
						'}' .
					'}' .
				 '</script>';
			
			echo '<div id="report_debug" style="position:fixed; line-height:1.5em; font-size:16px; z-index:100000; border-top:1px solid #FFF; background:#444;  color:#fff; bottom:0; left:0; right:0;  display:block;">' .
					 '<span style="display:block; padding:15px;  background:#333;">' .
						$memory . 
						$hostname .
						$queries_cont .
						$cache . 
						$prefill .
						$other .
						'<span onclick="hideTab()" style="float:right; width:30px; height:30px; display:block; color:#fff; text-align:center; cursor:pointer">x</span>' .
					 '</span>' . 
					 '<span style="display:block; ">' .
						 $queries_html .
						 $prefill_html .
						 $cache_html . 
						 $other_html .
					 '</span>' .
				'</div>';
		    	
		}
			    	}
		    		
		    		
  }

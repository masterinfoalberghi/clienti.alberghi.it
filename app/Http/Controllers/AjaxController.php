<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\StatsHotelShare;
use App\User;
use App\Utility;
use Browser;
use Carbon\Carbon;
use DB;
use Request;

class AjaxController extends Controller
{
   
   	/**
	 * Conteggio degli share
	 * @param  string $url
	 * @return mixed
	 */
	 
	public function contaClickShare($url, $codice)
	{
		
		// IP salvato come intero per risparmiare spazio db
		$ip = Utility::get_client_ip();
		$ua = Request::header('User-Agent');
		$ts = Carbon::now();

		
		$os = 		Browser::platformName(); 

		if ($ua == NULL || $ua == "")
			$ua = Request::server('HTTP_USER_AGENT');

		if ($ua == NULL || $ua == "")
			$ua = "no_user_agent ( both? )";

		/*
		 * Salvo il dato singolo
		 */
		 
		DB::statement("INSERT INTO tblStatsHotelShare (uri, codice, roi, useragent, IP, os, created_at, updated_at)
							VALUES ('".$url."', '".$codice."', 0, '".$ua."', '".$ip."', '".$os."', '".$ts->toDateTimeString()."', '".$ts->toDateTimeString()."')
							ON DUPLICATE KEY UPDATE codice = '" . $codice . "'");
		
		
		echo "ok";
		
	}

    
}

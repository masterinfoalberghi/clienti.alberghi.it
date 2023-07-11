<?php

/*
Server side mobile detection
*/

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$pathDeviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet_views' : 'phone_views') : 'views');


///////////////////////////////
//"Forzo" la modalità mobile //
///////////////////////////////
//$pathDeviceType = 'phone_views';


return [

	'pathDeviceType' => $pathDeviceType,

	/*
	|--------------------------------------------------------------------------
	| View Storage Paths
	|--------------------------------------------------------------------------
	|
	| Most templating systems load templates from disk. Here you may specify
	| an array of paths that should be checked for your views. Of course
	| the usual Laravel view path has already been registered for you.
	|
	*/
	
	/*
	ATTENZIONE
	=============
	Laravel will return whichever view that matches first, so be sure to sort the paths accordingly.
	------------------------------------------------------------------------------------------------
	
	Quindi siccome l'amministrazione NON VINE RIPETUTA in "tablet_views" e "phone_views" DOVREBBE CONTINUARE A CERCARLA in "views/admin" anche nei mobile
	Mentre per le pagine per cui esistono i template in "tablet_views" e "phone_views" (TUTTO IL RESTO DEL SITO fuori dalla admin) dovrebbe caricare questi ultimi

	 */
	'paths' => [
		realpath(base_path('resources/'.$pathDeviceType)),
		realpath(base_path('resources/views')),
		realpath(base_path('resources')),
		realpath(base_path('static')),
		realpath(base_path('public')),
	],

	/*
	|--------------------------------------------------------------------------
	| Compiled View Path
	|--------------------------------------------------------------------------
	|
	| This option determines where all the compiled Blade templates will be
	| stored for your application. Typically, this is within the storage
	| directory. However, as usual, you are free to change this value.
	|
	*/

	'compiled' => realpath(storage_path().'/framework/views'),

];

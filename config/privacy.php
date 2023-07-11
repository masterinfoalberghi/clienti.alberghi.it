<?php

return [
	
   /*
	|--------------------------------------------------------------------------
	| Privacy cookie email
	|--------------------------------------------------------------------------
	|
	| settato a true non cecca di default l'accettamento della privacy nel form di contatto
	|
	*/

	'privacy' => env('PRIVACY', false),

	/*
 	|--------------------------------------------------------------------------
 	| Privacy cookie law
 	|--------------------------------------------------------------------------
 	|
 	| settato a true blocca i cookie fino all'accettazione della privacy
 	|
 	*/
 
	'blocco_cookie' => env('BLOCCO_COOKIE', false),
	
	/*
 	|--------------------------------------------------------------------------
 	| Privacy termine conservazione dei dati archiviati
 	|--------------------------------------------------------------------------
 	|
 	| va settato in mesi ( 36 di defualt )
 	|
 	*/
	
	'term_storage_archived_data' => env("TERM_STORAGE_ARCHIVED_DATA", 36),
	
	
];
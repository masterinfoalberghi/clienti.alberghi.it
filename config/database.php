<?php

return [

	/*
	|--------------------------------------------------------------------------
	| PDO Fetch Style
	|--------------------------------------------------------------------------
	|
	| By default, database results will be returned as instances of the PHP
	| stdClass object; however, you may desire to retrieve records in an
	| array format for simplicity. Here you can tweak the fetch style.
	|
	*/

	'fetch' => PDO::FETCH_CLASS,

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify which of the database connections below you wish
	| to use as your default connection for all database work. Of course
	| you may use many connections at once using the Database library.
	|
	*/

	'default' => env('DB_CONNECTION', 'mysql'),

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

	'connections' => [

		'sqlite' => [
			'driver'   => 'sqlite',
			'database' => storage_path().'/database.sqlite',
			'prefix'   => '',
		],

		'mysql' => [
			'driver'    => 'mysql',
			'host'      => env('DB_HOST', 'infoaws.cdc0cwmavv0p.eu-south-1.rds.amazonaws.com'),
			'database'  => env('DB_DATABASE', 'infoalberghi'),
			'username'  => env('DB_USERNAME', 'admin_infoaws'),
			'password'  => env('DB_PASSWORD', 'zy$vj2g-Qij4uz-ti!w,i4'),
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix'    => '',
			'strict'    => false,
		],

        'mysql_online' => [
			'driver'    => 'mysql',
			'host'      => env('DB_HOST_ONLINE', 'infoaws.cdc0cwmavv0p.eu-south-1.rds.amazonaws.com'),
			'database'  => env('DB_DATABASE_ONLINE', 'infoalberghi'),
			'username'  => env('DB_USERNAME_ONLINE', 'admin_infoaws'),
			'password'  => env('DB_PASSWORD_ONLINE', 'zy$vj2g-Qij4uz-ti!w,i4'),
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix'    => '',
			'strict'    => false,
		],

		'api' => [
		    'driver' => 'mysql',
		    'host' => env('DB_HOST_API', 'ia-api.studio99.sm'),
		    'port' => env('DB_PORT_API', '3306'),
		    'database' => env('DB_DATABASE_API', 'ia_api_user'),
		    'username' => env('DB_USERNAME_API', 'ia-api-user'),
		    'password' => env('DB_PASSWORD_API', 'CJem1EOPp9BeLtkV'),
		   	'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
				'strict'    => false,
				'engine' => 'InnoDB'
        ],

        'archive' => [
		    'driver' => 'mysql',
		    'host' => env('DB_HOST_ARCHIVE', '168.119.89.178'),
		    'port' => env('DB_PORT_ARCHIVE', '3306'),
		    'database' => env('DB_DATABASE_ARCHIVE', 'ia_infoalberghi_archive'),
		    'username' => env('DB_USERNAME_ARCHIVE', 'infoalberghi'),
		    'password' => env('DB_PASSWORD_ARCHIVE', 's2dh9A#4'),
		   	'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
				'strict'    => false,
				'engine' => 'InnoDB'
        ],
    

		/*
		 * Attenzione all'encoding.
		 * Il problema può sembrare sciocco o trascurabile, invece è VITALE
		 * se si comincia ad incasinarsi con il charset uscirne costa tanto
		 * quindi è importante curare questo aspetto.
		 *
		 * Quando si estraggono dei dati dai database vecchi e si stampano a video, le stringhe non devono contenere errori di encoding
		 *
		 * Questi sono degli errori di encoding:
		 * Ponte 1Â° Maggio
		 * ristorante di qualitÃ
		 *
		 * Questi NON sono errori di encoding:
		 * la zona &egrave; servita
		 *
		 * Se guardando i dati della tabella da phpmyadmin, vedi degli errori di encoding, alla devi usare la connessione in latin1
		 * se non vedi errori di encoding, devi usare la connessione utf8
		 *
		 * È per questo che ognuno dei due database vecchi (infoalberghi / prova_info_alberghi)
		 * ho configurato due connessioni con due encoding diversi
		 * (infoalberghi_utf8, infoalberghi_latin1 / prova_info_alberghi_utf8, prova_info_alberghi_latin1)
		 *
		 * @author Luca Battarra
		 */

		'infoalberghi_utf8' => [
			'driver'    => 'mysql',
			'host'      => env('OLD_STATS_DB_HOST', 'localhost'),
			'database'  => env('OLD_STATS_DB_DATABASE', 'forge'),
			'username'  => env('OLD_STATS_DB_USERNAME', 'forge'),
			'password'  => env('OLD_STATS_DB_PASSWORD', ''),
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
			'strict'    => false,
		],

		'infoalberghi_latin1' => [
			'driver'    => 'mysql',
			'host'      => env('OLD_STATS_DB_HOST', 'localhost'),
			'database'  => env('OLD_STATS_DB_DATABASE', 'forge'),
			'username'  => env('OLD_STATS_DB_USERNAME', 'forge'),
			'password'  => env('OLD_STATS_DB_PASSWORD', ''),
			'charset'   => 'latin1',
			'collation' => 'latin1_swedish_ci',
			'prefix'    => '',
			'strict'    => false,
		],
		
		'prova_info_alberghi_utf8' => [
			'driver'    => 'mysql',
			'host'      => env('OLD_DB_HOST', 'localhost'),
			'database'  => env('OLD_DB_DATABASE', 'forge'),
			'username'  => env('OLD_DB_USERNAME', 'forge'),
			'password'  => env('OLD_DB_PASSWORD', ''),
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
			'strict'    => false,
		],

		'prova_info_alberghi_latin1' => [
			'driver'    => 'mysql',
			'host'      => env('OLD_DB_HOST', 'localhost'),
			'database'  => env('OLD_DB_DATABASE', 'forge'),
			'username'  => env('OLD_DB_USERNAME', 'forge'),
			'password'  => env('OLD_DB_PASSWORD', ''),
			'charset'   => 'latin1',
			'collation' => 'latin1_swedish_ci',
			'prefix'    => '',
			'strict'    => false,
		],

		'pgsql' => [
			'driver'   => 'pgsql',
			'host'     => env('DB_HOST', 'localhost'),
			'database' => env('DB_DATABASE', 'forge'),
			'username' => env('DB_USERNAME', 'forge'),
			'password' => env('DB_PASSWORD', ''),
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		],

		'sqlsrv' => [
			'driver'   => 'sqlsrv',
			'host'     => env('DB_HOST', 'localhost'),
			'database' => env('DB_DATABASE', 'forge'),
			'username' => env('DB_USERNAME', 'forge'),
			'password' => env('DB_PASSWORD', ''),
			'prefix'   => '',
		],

	],

	/*
	|--------------------------------------------------------------------------
	| Migration Repository Table
	|--------------------------------------------------------------------------
	|
	| This table keeps track of all the migrations that have already run for
	| your application. Using this information, we can determine which of
	| the migrations on disk haven't actually been run in the database.
	|
	*/

	'migrations' => 'migrations',

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store that also
	| provides a richer set of commands than a typical key-value systems
	| such as APC or Memcached. Laravel makes it easy to dig right in.
	|
	*/

	'redis' => [

		'cluster' => false,

		'default' => [
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 0,
		],

	],

];

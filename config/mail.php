<?php

return [
	
	/* Aggiunge IA in copa in tutte le email */
	'add_copy_email' => env('ADD_COPY_EMAIL', false), // Mette in copia IA
	
	/* Bypassa il controllo email doppie */
	'spedisci_email_duplicate' => env("SPEDISCI_DUPLICATI", false), // Spedisce le email doppie  
	
	/* Spedisce sempre le email dirette anche se sono doppie */
	'spedisci_sempre_email_dirette' => env("SPEDISCI_DIRETTE", false), // Spedisce le email dirette  
	
	/* Spedisce le email di upselling */
	'upselling' => env("UPSELLING", true),
	
	/* tempo per quale tenere le email doppie per il confronto */
	'temp_storage_email_duplicate' => env("TEMP_STORAGE_EMAIL_DUPLICATE", 3),
	
	/**
	 * ATTENZIONE SERVE PER IL DEBUG DELLE EMAIL 
	 * NON ATTIVARE ONLINE 
	 */
	
	'debug_email' => env("DEBUG_EMAIL", false), // Spedisce le email dirette  
	
	/* Email fake per il locale */	
	'fake_mail' => env('FAKE_MAIL','testing.infoalberghi@gmail.com'),
	
	'use_mailtrap' => env('USE_MAILTRAP',false),

	'spedisci_al_mittente' => env('SPEDISCI_AL_MITTENTE', true),

	/* Non rimove le email doppie dallalista di invio. Se impostato a false lascia tutte le email all'interno della lista di spedizione */
	'filter_list_email' => env('FILTER_LIST_EMAIL', true),

	/* Spedisce le email anche al database API */
	'send_to_api_db' => env('SEND_TO_API_DB', true),

	/* inserisce il Json nel footer delle mail per i risponditori */
	'footer_json_mail' => env('FOOTER_JSON_MAIL', false),
	
	/*
	|--------------------------------------------------------------------------
	| Mail Driver
	|--------------------------------------------------------------------------
	|
	| Laravel supports both SMTP and PHP's "mail" function as drivers for the
	| sending of e-mail. You may specify which one you're using throughout
	| your application here. By default, Laravel is setup for SMTP mail.
	|
	| Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "log"
	|
	*/

	'driver' => env('MAIL_DRIVER', 'smtp'),

	/*
	|--------------------------------------------------------------------------
	| SMTP Host Address
	|--------------------------------------------------------------------------
	|
	| Here you may provide the host address of the SMTP server used by your
	| applications. A default option is provided that is compatible with
	| the Mailgun mail service which will provide reliable deliveries.
	|
	*/

	'host' => env('MAIL_HOST', 'smtp.mailgun.org'),

	/*
	|--------------------------------------------------------------------------
	| SMTP Host Port
	|--------------------------------------------------------------------------
	|
	| This is the SMTP port used by your application to deliver e-mails to
	| users of the application. Like the host we have set this value to
	| stay compatible with the Mailgun e-mail application by default.
	|
	*/

	'port' => env('MAIL_PORT', 587),


	/*
	|--------------------------------------------------------------------------
	| Global "From" Address
	|--------------------------------------------------------------------------
	|
	| You may wish for all e-mails sent by your application to be sent from
	| the same address. Here, you may specify a name and address that is
	| used globally for all e-mails that are sent by your application.
	|
	*/


	/* 
	 * Di default è valorizzato a NULL ma deve essere impostato per il funzionamento del 
	 * password recovery nativo di Laravel
	 * @author Luca Battarra
	 */
	

	'from' => ['address' => "no-reply@info-alberghi.com", 'name' => "no-reply@info-alberghi.com"],

	/*
	|--------------------------------------------------------------------------
	| E-Mail Encryption Protocol
	|--------------------------------------------------------------------------
	|
	| Here you may specify the encryption protocol that should be used when
	| the application send e-mail messages. A sensible default using the
	| transport layer security protocol should provide great security.
	|
	*/

//	'encryption' => 'tls',
	'encryption' =>  env('MAIL_ENCRYPTION'),

	/*
	|--------------------------------------------------------------------------
	| SMTP Server Username
	|--------------------------------------------------------------------------
	|
	| If your SMTP server requires a username for authentication, you should
	| set it here. This will get used to authenticate with your server on
	| connection. You may also set the "password" value below this one.
	|
	*/

	'username' => env('MAIL_USERNAME'),

	/*
	|--------------------------------------------------------------------------
	| SMTP Server Password
	|--------------------------------------------------------------------------
	|
	| Here you may set the password required by your SMTP server to send out
	| messages from your application. This will be given to the server on
	| connection so that the application will be able to send messages.
	|
	*/

	'password' => env('MAIL_PASSWORD'),

	/*
	|--------------------------------------------------------------------------
	| Sendmail System Path
	|--------------------------------------------------------------------------
	|
	| When using the "sendmail" driver to send e-mails, we will need to know
	| the path to where Sendmail lives on this server. A default path has
	| been provided here, which will work well on most of your systems.
	|
	*/

	'sendmail' => '/usr/sbin/sendmail -bs',

	/*
	|--------------------------------------------------------------------------
	| Mail "Pretend"
	|--------------------------------------------------------------------------
	|
	| When this option is enabled, e-mail will not actually be sent over the
	| web and will instead be written to your application's logs files so
	| you may inspect the message. This is great for local development.
	|
	*/

	'pretend' => false,
	
	'markdown' => [
	    'theme' => 'default',

	    'paths' => [
	        resource_path('views/vendor/mail'),
	    ],
	],

	

];

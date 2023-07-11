<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "The :attribute must be accepted.",
	"active_url"           => "The :attribute is not a valid URL.",
	"after"                => "The :attribute must be a date after :date.",
	"alpha"                => "The :attribute may only contain letters.",
	"alpha_dash"           => "The :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"            => "The :attribute may only contain letters and numbers.",
	"array"                => "The :attribute must be an array.",
	"before"               => "The :attribute must be a date before :date.",
	"between"              => [
		"numeric" => "The :attribute must be between :min and :max.",
		"file"    => "The :attribute must be between :min and :max kilobytes.",
		"string"  => "The :attribute must be between :min and :max characters.",
		"array"   => "The :attribute must have between :min and :max items.",
	],
	"boolean"              => "The :attribute field must be true or false.",
	"confirmed"            => "The :attribute confirmation does not match.",
	"date"                 => "The :attribute is not a valid date.",
	"date_format"          => "The :attribute does not match the format :format.",
	"different"            => "The :attribute and :other must be different.",
	"digits"               => "The :attribute must be :digits digits.",
	"digits_between"       => "The :attribute must be between :min and :max digits.",
	"email"                => "The :attribute must be a valid email address.",
	"filled"               => "The :attribute field is required.",
	"exists"               => "The selected :attribute is invalid.",
	"image"                => "The :attribute must be an image.",
	"in"                   => "The selected :attribute is invalid.",
	"integer"              => "The :attribute must be an integer.",
	"ip"                   => "The :attribute must be a valid IP address.",
	"max"                  => [
		"numeric" => "The :attribute may not be greater than :max.",
		"file"    => "The :attribute may not be greater than :max kilobytes.",
		"string"  => "The :attribute may not be greater than :max characters.",
		"array"   => "The :attribute may not have more than :max items.",
	],
	"mimes"                => "The :attribute must be a file of type: :values.",
	"min"                  => [
		"numeric" => "The :attribute must be at least :min.",
		"file"    => "The :attribute must be at least :min kilobytes.",
		"string"  => "The :attribute must be at least :min characters.",
		"array"   => "The :attribute must have at least :min items.",
	],
	"not_in"               => "The selected :attribute is invalid.",
	"numeric"              => "The :attribute must be a number.",
	"regex"                => "The :attribute format is invalid.",
	"required"             => "The :attribute field is required.",
	"required_if"          => "The :attribute field is required when :other is :value.",
	"required_with"        => "The :attribute field is required when :values is present.",
	"required_with_all"    => "The :attribute field is required when :values is present.",
	"required_without"     => "The :attribute field is required when :values is not present.",
	"required_without_all" => "The :attribute field is required when none of :values are present.",
	"same"                 => "The :attribute and :other must match.",
	"size"                 => [
		"numeric" => "The :attribute must be :size.",
		"file"    => "The :attribute must be :size kilobytes.",
		"string"  => "The :attribute must be :size characters.",
		"array"   => "The :attribute must contain :size items.",
	],
	"unique"               => "The :attribute has already been taken.",
	"url"                  => "The :attribute format is invalid.",
	"timezone"             => "The :attribute must be a valid zone.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => [
						'richiesta' => ['mail_message_spam' => 'Attention! Votre e-mail est considéré comme du spam parce que vous avez entré lien ou texte non acceptés. Changez-le, pour envoyer correctement.'],

								"nome" => [
															'required' => 'Insérer un nom',
															'regex' => 'Le nom peut contenir que des lettres'
													],

							'arrivo' => [
															'required' => 'Insérer une date d\'arrivée',
															'date_format' => 'Date d\'arrivée: format non valide',
															'after' => 'Date d\'arrivée doit commencer à partir d\'aujourd\'hui'
															],

						'a_partire_da' => [
															'date_format' => 'Date de départ: format non valide'
															],

						'partenza' => [
															'required' => 'Insérer une date de départ',
															'date_format' => 'Date de départ: format non valide',
															],

							'n1_arrivo' => [
															'required' => 'Insérer une date d\'arrivée',
															'date_format' => 'Date d\'arrivée: format non valide',
															'after' => 'Date d\'arrivée doit commencer à partir d\'aujourd\'hui'
															],

						'n1_partenza' => [
															'required' => 'Insérer une date de départ',
															'date_format' => 'Date de départ: format non valide',
															],

							'n2_arrivo' => [
															'required' => 'Insérer une date d\'arrivée',
															'date_format' => 'Date d\'arrivée: format non valide',
															'after' => 'Date d\'arrivée doit commencer à partir d\'aujourd\'hui'
															],

						'n2_partenza' => [
															'required' => 'Insérer une date d\'arrivée',
															'date_format' => 'Date de départ: format non valide',
															],

						

									'email' => [
															'required' => 'Insérer une adresse email',
															'email' => 'Adresse email invalide',
															'email_validation' => "L' adresse e-mail ne peut pas être contacté par notre serveur .\nAssurez-vous qu'il est correct et re- insérer !"
															],

						'accettazione' => [
														'accepted' => 'Acceptez le traitement des données personnelles'
															],

						'trattamento' => ['different' => 'Sélectionnez un traitement'],
						
						'multiple_loc' => ['required' => 'Sélectionnez un lieu'],


						"accetto" => [
														'accepted' => 'Acceptez le traitement des données personnelles'
														], 

						 "email_coupon" =>  [
																'required' => 'Insérer une adresse email',
																'email' => 'Adresse email invalide'
																],


						"nome_hotel" => [
														'min' => 'nom d\'hôtel doit être d\'au moins :min caractères'
													],

						"partenza" => ['data_maggiore_di' => 'Le départ doit être après l\'arrivée'],

],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [
			'multiple_loc' => 'Lieu',
			'nome' => 'Nom',
			'n1_arrivo' => 'Arrivée',
			'n1_partenza' => 'Départ',
			'n1_eta_1' => 'Âge du 1er enfant',
			'n1_eta_2' => 'Âge du 2ème enfant',
			'n1_eta_3' => 'Âge de 3ème enfant',
			'n1_eta_4' => 'Âge de 4ème enfant',
			'n1_eta_5' => 'Âge de 5ème enfant',
			'n2_arrivo' => 'Arrivée',
			'n2_partenza' => 'Départ',
			'n2_eta_1' => 'Âge du 1er enfant',
			'n2_eta_2' => 'Âge du 2ème enfant',
			'n2_eta_3' => 'Âge de 3ème enfant',
			'n2_eta_4' => 'Âge de 4ème enfant',
			'n2_eta_5' => 'Âge de 5ème enfant',
			'arrivo' => 'Arrivée',
			'partenza' => 'Départ',
			'eta_1' => 'Âge du 1er enfant',
			'eta_2' => 'Âge du 2ème enfant',
			'eta_3' => 'Âge de 3ème enfant',
			'eta_4' => 'Âge de 4ème enfant',
			'eta_5' => 'Âge de 5ème enfant',
			],

];
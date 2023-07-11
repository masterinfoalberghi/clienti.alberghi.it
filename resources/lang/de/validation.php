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
						'richiesta' => ['mail_message_spam' => 'Warnung! Ihre E-Mail wird als Spam betrachtet, weil Sie den Link oder Text nicht erlaubt eingetragen. Ändern Sie es, um es richtig zu senden.'],

								"nome" => [
															'required' => 'Legen Sie einen Namen',
															'regex' => 'Der Name kann nur Buchstaben'
													],

							'arrivo' => [
															'required' => 'Geben Sie Anreisedatum',
															'date_format' => 'Anreisedatum hat ein ungültiges Format',
															'after' => 'Anreisedatum muss von heute beginnen'
															],

						'a_partire_da' => [
															'date_format' => 'Abreisedatum hat ein ungültiges Format'
															],

						'partenza' => [
															'required' => 'Geben Abreisedatum',
															'date_format' => 'Abreisedatum hat ein ungültiges Format',
															],

							'n1_arrivo' => [
															'required' => 'Geben Sie Anreisedatum',
															'date_format' => 'Anreisedatum hat ein ungültiges Format',
															'after' => 'Anreisedatum muss von heute beginnen'
															],

						'n1_partenza' => [
															'required' => 'Geben Abreisedatum',
															'date_format' => 'Abreisedatum hat ein ungültiges Format',
															],

							'n2_arrivo' => [
															'required' => 'Geben Sie Anreisedatum',
															'date_format' => 'Anreisedatum hat ein ungültiges Format',
															'after' => 'Anreisedatum muss von heute beginnen'
															],

						'n2_partenza' => [
															'required' => 'Geben Abreisedatum',
															'date_format' => 'Abreisedatum hat ein ungültiges Format',
															],

						

									'email' => [
															'required' => 'Geben Sie eine E-Mail-Adresse',
															'email' => 'E-Mail-Adresse ist ungültig',
														'email_validation' => "Die E-Mail -Adresse kann nicht von unserem Server kontaktiert werden . Stellen Sie sicher, es ist richtig, und setzen Sie es !"
															],

						'accettazione' => [
														'accepted' => 'Akzeptieren Sie die Verarbeitung persönlicher Daten'
															],

						'trattamento' => ['different' => 'Wählen Sie eine Behandlung'],
						
						'multiple_loc' => ['required' => 'Wählen Sie einen Ort'],


						"accetto" => [
														'accepted' => 'Akzeptieren Sie die Verarbeitung persönlicher Daten'
														], 

						 "email_coupon" =>  [
																'required' => 'Geben Sie eine E-Mail -Adresse',
																'email' => 'E-Mail-Adresse ist ungültig'
																],


						"nome_hotel" => [
														'min' => 'Hotelname muss mindestens :min Zeichen lang sein.'
													],

						"partenza" => ['data_maggiore_di' => 'Die Abreise muss nach der Ankunft sein'],

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
			'multiple_loc' => 'Ort',
			'nome' => 'Name',
			'n1_arrivo' => 'Ankunft',
			'n1_partenza' => 'Abreise',
			'n1_eta_1' => '1. Kind Alter',
			'n1_eta_2' => '2. Kind Alter',
			'n1_eta_3' => '3. Kind Alter',
			'n1_eta_4' => '4. Kind Alter',
			'n1_eta_5' => '5. Kind Alter',
			'n2_arrivo' => 'Ankunft',
			'n2_partenza' => 'Abreise',
			'n2_eta_1' => '1. Kind Alter',
			'n2_eta_2' => '2. Kind Alter',
			'n2_eta_3' => '3. Kind Alter',
			'n2_eta_4' => '4. Kind Alter',
			'n2_eta_5' => '5. Kind Alter',
			'arrivo' => 'Ankunft',
			'partenza' => 'Abreise',
			'eta_1' => '1. Kind Alter',
			'eta_2' => '2. Kind Alter',
			'eta_3' => '3. Kind Alter',
			'eta_4' => '4. Kind Alter',
			'eta_5' => '5. Kind Alter',
			],

];
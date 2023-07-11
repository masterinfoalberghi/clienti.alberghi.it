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
						'richiesta' => ['mail_message_spam' => 'Warning! Your email is considered spam because you entered link or text not allowed. Change it to send it correctly.'],

								"nome" => [
															'required' => 'Insert a name',
															'regex' => 'The name can contain only letters'
													],

							'arrivo' => [
															'required' => 'Insert arrival date',
															'date_format' => 'Arrival date has invalid format',
															'after' => 'Arrival date must start from today'
															],

						'a_partire_da' => [
															'date_format' => 'Departure date has invalid format'
															],

						'partenza' => [
															'required' => 'Insert departure date',
															'date_format' => 'Departure date has invalid format',
															],

							'n1_arrivo' => [
															'required' => 'Insert arrival date',
															'date_format' => 'Arrival date has invalid format',
															'after' => 'Arrival date must start from today'
															],

						'n1_partenza' => [
															'required' => 'Insert departure date',
															'date_format' => 'Departure date has invalid format',
															],

							'n2_arrivo' => [
															'required' => 'Insert arrival date',
															'date_format' => 'Arrival date has invalid format',
															'after' => 'Arrival date must start from today'
															],

						'n2_partenza' => [
															'required' => 'Insert departure date',
															'date_format' => 'Departure date has invalid format',
															],

						

									'email' => [
															'required' => 'Insert an email address',
															'email' => 'Email address is invalid',
														'email_validation' => "The email address can not be contacted by our server.\nMake sure it is correct and re-insert it !"
															],

						'accettazione' => [
														'accepted' => 'Accept the processing of personal data'
															],

						'trattamento' => ['different' => 'Select a treatment'],
						
						'multiple_loc' => ['required' => 'Select a location'],


						"accetto" => [
														'accepted' => 'Accept the processing of personal data'
														], 

						 "email_coupon" =>  [
																'required' => 'Insert an email address',
																'email' => 'Email address is invalid'
																],


						"nome_hotel" => [
														'min' => 'hotel name must be at least :min characters'
													],

						"partenza" => ['data_maggiore_di' => 'The departure must be after arrival'],

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
			'multiple_loc' => 'location',
			'nome' => 'Name',
			'n1_arrivo' => 'Arrival',
			'n1_partenza' => 'Departure',
			'n1_eta_1' => '1st child age',
			'n1_eta_2' => '2nd child age',
			'n1_eta_3' => '3rd child age',
			'n1_eta_4' => '4th child age',
			'n1_eta_5' => '5th child age',
			'n2_arrivo' => 'Arrival',
			'n2_partenza' => 'Departure',
			'n2_eta_1' => '1st child age',
			'n2_eta_2' => '2nd child age',
			'n2_eta_3' => '3rd child age',
			'n2_eta_4' => '4th child age',
			'n2_eta_5' => '5th child age',
			'arrivo' => 'Arrival',
			'partenza' => 'Departure',
			'eta_1' => '1st child age',
			'eta_2' => '2nd child age',
			'eta_3' => '3rd child age',
			'eta_4' => '4th child age',
			'eta_5' => '5th child age',
			],

];
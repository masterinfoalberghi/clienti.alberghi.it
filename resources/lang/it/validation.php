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

"accepted"             => "Il campo :attribute deve essere accettato.",
"active_url"           => "Il campo :attribute non è una URL valida.",
"after"                => "Il campo :attribute deve essere una data successiva a :date.",
"alpha"                => "Il campo :attribute può contenere solo lettere.",
"alpha_dash"           => "Il campo :attribute può contenere solo lettere, numeri e trattini.",
"alpha_num"            => "Il campo :attribute può contenere solo lettere e numeri.",
"array"                => "Il campo :attribute deve essere un array.",
"before"               => "Il campo :attribute deve essere una data precedente a :date.",
"between"              => [
	"numeric" => "Il campo :attribute deve essere compreso tra :min e :max.",
	"file"    => "Il campo :attribute must be between :min and :max kilobytes.",
	"string"  => "Il campo :attribute must be between :min and :max characters.",
	"array"   => "Il campo :attribute must have between :min and :max items.",
],
"boolean"              => "Il campo :attribute field must be true or false.",
"confirmed"            => "Il campo :attribute e il suo campo di verifica devono combaciare.",
"date"                 => "Il campo :attribute is not a valid date.",
"date_format"          => "Il campo :attribute non è nel formato :format.",
"different"            => "Il campo :attribute and :other must be different.",
"digits"               => "Il campo :attribute must be :digits digits.",
"digits_between"       => "Il campo :attribute must be between :min and :max digits.",
"email"                => "Il campo :attribute deve essere un indirizzo mail valido.",
"filled"               => "Il campo :attribute field is required.",
"exists"               => "The selected :attribute is invalid.",
"image"                => "Il campo :attribute must be an image.",
"in"                   => "The selected :attribute is invalid.",
"integer"              => "Il campo :attribute deve essere un intero.",
"ip"                   => "Il campo :attribute must be a valid IP address.",
"max"                  => [
	"numeric" => "Il campo :attribute may not be greater than :max.",
	"file"    => "Il campo :attribute may not be greater than :max kilobytes.",
	"string"  => "Il campo :attribute may not be greater than :max characters.",
	"array"   => "Il campo :attribute may not have more than :max items.",
],
"mimes"                => "Il campo :attribute must be a file of type: :values.",
"min"                  => [
	"numeric" => "Il campo :attribute must be at least :min.",
	"file"    => "Il campo :attribute must be at least :min kilobytes.",
	"string"  => "Il campo :attribute must be at least :min characters.",
	"array"   => "Il campo :attribute must have at least :min items.",
],
"not_in"               => "The selected :attribute is invalid.",
"numeric"              => "Il campo :attribute deve essere un numero.",
"regex"                => "Il campo :attribute è in un formato invalido.",
"required"             => "Il campo :attribute è obbligatorio.",
"required_if"          => "Il campo :attribute è obbligatorio quando :other vale :value.",
"required_with"        => "Il campo :attribute è obbligatorio quando :values è valorizzato.",
"required_with_all"    => "Il campo :attribute è obbligatorio quando :values è valorizzato.",
"required_without"     => "Il campo :attribute è obbligatorio quando :values non è valirozzato.",
"required_without_all" => "Il campo :attribute è obbligatorio quando nessuno di :values sono valorizzati.",
"same"                 => "Il campo :attribute e :other devono combaciare.",
"size"                 => [
	"numeric" => "Il campo :attribute must be :size.",
	"file"    => "Il campo :attribute must be :size kilobytes.",
	"string"  => "Il campo :attribute must be :size characters.",
	"array"   => "Il campo :attribute must contain :size items.",
],
"unique"               => ":attribute è già stato usato.",
"url"                  => "Il campo :attribute format is invalid.",
"timezone"             => "Il campo :attribute must be a valid zone.",

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
'richiesta' => ['mail_message_spam' => 'Attenzione! La tua email viene considerata spam perché hai inserito link o testo non consentito. Modificala per inviarla correttamente.'],

"nome" => [
'required' => 'Inserisci un nome',
'regex' => 'Il nome può contenere solo lettere'
],

'arrivo' => [
'required' => 'Inserisci la data di arrivo',
'date_format' => 'La data di arrivo non è stata selezionata correttamente',
'after' => 'La data di arrivo deve partire almeno da oggi'
],

'a_partire_da' => [
'date_format' => 'La data di partenza non è stata selezionata correttamente'
],

'partenza' => [
'required' => 'Inserisci la data di partenza',
'date_format' => 'La data di partenza non è stata selezionata correttamente',
],

'n1_arrivo' => [
'required' => 'Inserisci la data di arrivo',
'date_format' => 'La data di arrivo non ha un formato valido',
'after' => 'La data di arrivo deve partire da oggi'
],

'n1_partenza' => [
'required' => 'Inserisci la data di partenza',
'date_format' => 'La data di partenza non ha un formato valido',
],

'n2_arrivo' => [
'required' => 'Inserisci la data di arrivo',
'date_format' => 'La data di arrivo non ha un formato valido',
'after' => 'La data di arrivo deve partire da oggi'
],

'n2_partenza' => [
'required' => 'Inserisci la data di partenza',
'date_format' => 'La data di partenza non ha un formato valido',
],

'email' => [
'required' => 'Inserisci un indirizzo email',
'email' => 'L\'indirizzo email non è valido',
'email_validation' => "L'indirizzo email inserito non può essere contattato dai nostri server.\nVeirifcare che sia corretto e reinserirlo !"
],

'accettazione' => [
'accepted' => 'Accetto il trattamento dei dati personali'
],


'trattamento' => ['different' => 'Selezionare un trattamento'],

'multiple_loc' => ['required' => 'Selezionare una località'],


"accetto" => [
'accepted' => 'Accetta il trattamento dei dati personali'
],

"email_coupon" =>  [
'required' => 'Inserisci un indirizzo email',
'email' => 'L\'indirizzo email non è valido'
],
"nome_hotel" => [
'min' => 'il nome hotel deve essere almeno :min caratteri'
],
"partenza" => ['data_maggiore_di' => 'La partenza deve essere successiva all\'arrivo'],
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
'multiple_loc' => 'località',
'nome' => 'Nome',
'n1_arrivo' => 'Arrivo',
'n1_partenza' => 'Partenza',
'n1_eta_1' => 'Età 1° bambino',
'n1_eta_2' => 'Età 2° bambino',
'n1_eta_3' => 'Età 3° bambino',
'n1_eta_4' => 'Età 4° bambino',
'n1_eta_5' => 'Età 5° bambino',
'n2_arrivo' => 'Arrivo',
'n2_partenza' => 'Partenza',
'n2_eta_1' => 'Età 1° bambino',
'n2_eta_2' => 'Età 2° bambino',
'n2_eta_3' => 'Età 3° bambino',
'n2_eta_4' => 'Età 4° bambino',
'n2_eta_5' => 'Età 5° bambino',
'arrivo' => 'Arrivo',
'partenza' => 'Partenza',
'eta_1' => 'Età 1° bambino',
'eta_2' => 'Età 2° bambino',
'eta_3' => 'Età 3° bambino',
'eta_4' => 'Età 4° bambino',
'eta_5' => 'Età 5° bambino',
],

];

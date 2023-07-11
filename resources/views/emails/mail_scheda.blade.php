@include("emails.includes.header", ["dati_json" => $dati_json])

@php
	$to_include_head_contact = [
		"hotel_id" => $dati_mail["hotel_id"], 
		"hotel_name"=> $dati_mail["hotel_name"], 
		"hotel_rating" => $dati_mail["hotel_rating"], 
		"hotel_loc" => $dati_mail["hotel_loc"],
		"email_type" => "Diretta",
		"caparre" => null
		];

	if (isset($email_type)) 
		{
		$to_include_head_contact['email_type'] = $email_type;
		}

	if( isset($dati_mail["caparre"]) )
		{
		$to_include_head_contact["caparre"] = $dati_mail['caparre'];
		}
@endphp

@include("emails.includes.head-contact", $to_include_head_contact)

@include("emails.includes.email" , [
	"dati_json" => $dati_json, 
])
@include("emails.includes.foot-contact", [
	"hotels_contact" => $dati_mail["hotels_contact"], 
	"ip" => $dati_mail["ip"], 
	"referer" => $dati_mail["referer"], 
	"actual_link" => $dati_mail["actual_link"], 
	"device" => $dati_mail["device"], 
	"date_created_at" => $dati_mail["date_created_at"], 
	"hour_created_at" => $dati_mail["hour_created_at"], 
	"sign_email" => $dati_mail["sign_email"]
])
@include("emails.includes.footer")

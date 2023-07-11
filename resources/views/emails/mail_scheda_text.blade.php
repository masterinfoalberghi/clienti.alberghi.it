@include("emails.includes.head-contact-text", ["hotel_id" => $dati_mail["hotel_id"], "hotel_name"=> $dati_mail["hotel_name"], "hotel_rating" => $dati_mail["hotel_rating"], "hotel_loc" => $dati_mail["hotel_loc"],"email_type" => "Diretta"])
@include("emails.includes.email-text" , ["dati_json" => $dati_json])
@include("emails.includes.foot-contact-text", ["hotels_contact" => $dati_mail["hotels_contact"], "ip" => $dati_mail["ip"], "referer" => $dati_mail["referer"], "actual_link" => $dati_mail["actual_link"], "device" => $dati_mail["device"], "date_created_at" => $dati_mail["date_created_at"], "hour_created_at" => $dati_mail["hour_created_at"], "sign_email" => $dati_mail["sign_email"] ])
@include("emails.includes.footer-text")

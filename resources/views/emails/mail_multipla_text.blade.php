@include("emails.includes.head-contact-text", ["email_type" => $email_type])
@include("emails.includes.email-text" , ["dati_json" => $dati_json])
@include("emails.includes.foot-contact-text", ["hotels_contact" => $dati_mail["hotels_contact"], "ip" => $dati_mail["ip"], "referer" => $dati_mail["referer"], "actual_link" => $dati_mail["actual_link"], "device" => $dati_mail["device"], "date_created_at" => $dati_mail["date_created_at"], "hour_created_at" => $dati_mail["hour_created_at"], "sign_email" => $dati_mail["sign_email"] ])
@include("emails.includes.footer-text")
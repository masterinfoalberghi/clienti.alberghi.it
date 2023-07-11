@php $count = 1; @endphp
@foreach ($dati_json["rooms"] as $room)

@if(count($dati_json["rooms"]) > 1)Camera {{$count}} -@endif Richiesta per {{$room["nights"]}} notti
------------------------------------------------------------------------------------

@php \Carbon\Carbon::setLocale("it"); @endphp
Check-in: {{\Carbon\Carbon::createFromFormat('d/m/Y',$room["checkin"])->formatLocalized("%d %b %Y (%a)")}} - @if($room["flex_date"]) {{trans("labels.date_flessibili")}} @endif

Check-out: {{\Carbon\Carbon::createFromFormat('d/m/Y',$room["checkout"])->formatLocalized("%d %b %Y (%a)")}} @if($room["flex_date"]) {{trans("labels.date_flessibili")}} @endif

Pax: {{$room["adult"]}} Ad @if (!empty($room["children"])) + {{count($room["children"])}} Ba ({{implode(",", $room["children"])}} anni) @endif

Tratt: {{strip_tags(\Utility::getTrattamentoOfferte(strtolower($room["meal_plan"])))}}
@php $count++; @endphp
@endforeach

Note: {{$dati_json["information"]}}

@include("emails.includes.firma_cliente_text", ["email" => $dati_json["email"], "customer" => $dati_json["customer"], "phone" => $dati_json["phone"] , "language" => $dati_json["language"]])

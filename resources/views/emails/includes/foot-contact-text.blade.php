@php
$link = "";
@endphp
@if ($actual_link != $referer && $referer != "")
@php
if ( strpos($referer , ", " ) === false) {
$uri = \Utility::urlToPage($referer);
if($uri[1] != "#"):
$link = $uri[0];
endif;
} else {
$link = $referer;
}
@endphp
@endif

@if ($link != "")
Pagina di provenienza: {!!$link!!}
@endif
@if (isset($hotels_contact) && $hotels_contact != "")
Hotel contattati: {{$hotels_contact}}
@endif
Email spedita in data {{$date_created_at}} @if($hour_created_at) alle ore {{$hour_created_at}} @endif da un dispositivo {{$device}} @if($ip!="") con IP {{Utility::maskIP($ip)}}@endif.
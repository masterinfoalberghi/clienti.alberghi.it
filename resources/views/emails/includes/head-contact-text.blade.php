INFOALBERGHI

@if($email_type == "Diretta")		
Richiesta preventivo email diretta
{{$hotel_name}} {{$hotel_rating}} - {{$hotel_loc}}
https://www.info-alberghi.com/hotel.php?id={{$hotel_id}}
@elseif( $email_type == "Multipla")
Richiesta preventivo email multipla
@else
Richiesta preventivo email multipla
@endif
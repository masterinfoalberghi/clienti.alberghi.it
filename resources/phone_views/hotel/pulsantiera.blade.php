@php 
$class="col-xs-3";
$noff 	= $cliente->offerteTop()->attiva()->visibileInScheda()->count();
$noff  += $cliente->offerte()->attiva()->count();
$nopp	= $cliente->offertePrenotaPrima()->attiva()->count();
$nlast 	= $cliente->last()->attiva()->count();
$nbg 	= $cliente->bambiniGratisAttivi->count();
$nbg 	= $cliente->offerteBambiniGratisTop()->attiva()->count() + $nbg;

foreach($cliente->offerteTop as $ot) {
    if ($ot->tipo == "lastminute") {
        $nlast++;
        $noff--;
    } 
    if ($ot->tipo == "prenotaprima") {
        $nopp++;
        $noff--;
    } 
}
@endphp

@if ($nlast || $noff || $nbg || $nopp)

    <div class="row pulsantiera pulsantiera2 @if ($margin == "no") no-margin-top-pulsantiera @endif">
        @if ($nlast)
            <div class="{{$class}} pulsante  lastminute">
                @if ($cliente->attivo == -1)
                    <a href="{{Utility::getUrlWithLang($locale,'/hotel.demo?id=demo&lastminute')}}"  class="link_scroll " data-url="lastminute">
                @else
                    <a href="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id.'&lastminute')}}"  class="link_scroll " data-url="lastminute">
                @endif
                    <span class="img"><img src="{{Utility::asset("images/icons/red/Clock.png")}}" /></span><span class="testo">{{ trans('hotel.last') }}</span>
                    <span class="numero white-fe-pulsante">{{$nlast}}</span>
                </a>
            </div>
        @else
            <div class="{{$class}} pulsante lastminute disabled"  >
                <span class="img"><img src="{{Utility::asset("images/icons/black/Clock.png")}}" /></span><span class="testo">{{ trans('hotel.last') }}</span>
            </div>
        @endif

        @if ($nopp) 
            <div class="{{$class}} pulsante prenotaprima">
                @if ($cliente->attivo == -1)
                    <a href="{{Utility::getUrlWithLang($locale,'/hotel.demo?id=demo&prenotaprima')}}"  class="link_scroll " data-url="prenotaprima">
                @else
                    <a href="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id.'&prenotaprima')}}"  class="link_scroll " data-url="prenotaprima">
                @endif
                    <span class="img"><img src="{{Utility::asset("images/icons/red/Clock.png")}}" /></span><span class="testo">{{ trans('hotel.offerte_prenota_prima_box') }}</span>
                    <span class="numero white-fe-pulsante">{{$nopp}}</span>
                </a>
            </div>
        @else
            <div class="{{$class}} pulsante prenotaprima disabled"  >
                <span class="img"><img src="{{Utility::asset("images/icons/black/Clock.png")}}" /></span><span class="testo">{{ trans('hotel.offerte_prenota_prima_box') }}</span>
            </div>
        @endif

        @if ($noff)
            <div class="{{$class}} pulsante   offerte">
                @if ($cliente->attivo == -1)
                    <a href="{{Utility::getUrlWithLang($locale , '/hotel.demo?id=demo&offers')}}"  class="link_scroll " data-url="offers">
                @else
                    <a href="{{Utility::getUrlWithLang($locale , '/hotel.php?id='.$cliente->id.'&offers')}}"  class="link_scroll " data-url="offers">
                @endif
                    <span class="img"><img src="{{Utility::asset("images/icons/red/GiftFilled.png")}}" /></span><span class="testo">{{ trans('hotel.offerte_generiche') }}</span>
                    <span class="numero white-fe-pulsante">{{$noff}}</span>
                </a>
            </div>
        @else
            <div class="{{$class}} pulsante offerte disabled"  >
                <span class="img"><img src="{{Utility::asset("images/icons/black/GiftFilled.png")}}" /></span><span class="testo">{{ trans('hotel.offerte_generiche') }}</span>
            </div>
        @endif

        @if ($nbg)
            <div class="{{$class}} pulsante  bg">
                @if ($cliente->attivo == -1)
                    <a href="{{Utility::getUrlWithLang($locale,'/hotel.demo?id=demo&children-offers')}}"  class="link_scroll " data-url="bg">
                @else
                    <a href="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id.'&children-offers')}}"  class="link_scroll " data-url="bg">
                @endif
                    <span class="img"><img src="{{Utility::asset("images/icons/red/Children.png")}}" /></span><span class="testo">{{ strtoupper(trans('hotel.offerta_bg')) }}</span>
                    <span class="numero white-fe-pulsante">{{$nbg}}</span>
                </a>
            </div>
        @else
            <div class="{{$class}} pulsante bg disabled"  >
                <span class="img"><img src="{{Utility::asset("images/icons/black/Children.png")}}" /></span><span class="testo">{{ strtoupper(trans('hotel.offerta_bg')) }}</span>
            </div>
        @endif

    </div>
@endif
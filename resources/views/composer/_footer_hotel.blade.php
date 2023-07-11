<div>
  <b>{{{$cliente->nome}}}</b>  <span class="rating">{{$cliente->stelle->nome}}</span><br />
  <div>
  {{ trans('hotel.da') }}
    <strong>{{{$cliente->prezzo_min}}}</strong> {{ trans('hotel.a') }} 
    <strong>{{{$cliente->prezzo_max}}}</strong> <strong>&euro;</strong>
  </div>
</div>

<div>
  <b class="hidden">{{{$cliente->nome}}}</b>
  <div class="address">
    {{-- se "Rimini Mare" id=39 => scrivo 'Rimini' --}}
    {{{ $cliente->indirizzo}}} - {{{ $cliente->cap }}} - {{{ $cliente->localita->id == 39 ? 'Rimini' : $cliente->localita->nome.' ('. $cliente->localita->prov . ')' }}}<br/>
    {!! $cliente->telefono != '' ? 'Tel: '.$cliente->telefono : '' !!}
    @if ($cliente->link != '')
      <br /><a href="{{ url('/away/'.$cliente->id) }}" target="_blank" rel="nofollow" class="reverse"><strong>{{$cliente->testo_link != '' ? $cliente->testo_link : $cliente->link}}</strong></a><br />
    @endif<br />
  
    @if ($cliente->rating_ia > 6 && $cliente->enabled_rating_ia == true)
        <span>
            {{ trans('hotel.recensione') }}: <span>info-alberghi.com</span><br />
        </span>
        <span>
            <span> {{ trans('hotel.hotel_di') }} <b>{{$cliente->localita->nome}}</b></span> &rarr;
        </span>
        <span>
            {{-- TODO: correggere semantica markup html possibilimente elementi block non solo <span> 
            PS: questo <meta> vuoto serve a qualcosa? --}}
            <meta> 
            <strong><span>{{$cliente->rating_ia}}</span> / <span>10</span> {{ trans('hotel.stelle') }}</strong>
        </span>
    @endif

  </div>
</div>
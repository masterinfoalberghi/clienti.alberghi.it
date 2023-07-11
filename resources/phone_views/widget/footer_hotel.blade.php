<div class="footer-hotel">
	<div>
		<b>{{{$cliente->nome}}}</b>  <span class="rating">{{$cliente->stelle->nome}}</span><br />
		<div>
		{{ trans('hotel.da') }}
			<strong> {{{$cliente->prezzo_min}}}</strong> {{ trans('hotel.a') }} 
			<strong> {{{$cliente->prezzo_max}}} </strong> <strong>&euro;</strong>
		</div>
	</div>
	<div >
		<div class="address">
			{{-- se "Rimini Mare" id=39 => scrivo 'Rimini' --}}
			{{{ $cliente->indirizzo}}} - {{{ $cliente->cap }}} - {{{ $cliente->localita->id == 39 ? 'Rimini' : $cliente->localita->nome.' ('. $cliente->localita->prov . ')' }}}<br/>
			{!! $cliente->telefono != '' ? 'Tel: '.$cliente->telefono : '' !!}
			@if ($cliente->link != '')
				<br /><a href="{{ url('/away/'.$cliente->id) }}" target="_blank" rel="nofollow" class="reverse"><strong>{{$cliente->testo_link != '' ? $cliente->testo_link : $cliente->link}}</strong></a><br />
			@endif<br />

            @if ($cliente->rating_ia > 6 && $cliente->enabled_rating_ia == true)
                <span>
                    <span> {{ trans('hotel.hotel_di') }} <b>{{$cliente->localita->nome}}</b></span> &rarr;
                </span>
                <span >
                    <meta content="1">
                    <strong><span>{{$cliente->rating_ia}}</span> / <span>10</span> {{ trans('hotel.stelle') }}</strong>
                </span>
                @endif
			
		</div>
	</div>
</div>

{{-- TODO JSON SCHEDA MOBILE --}}
@if (isset($cliente))
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "Hotel",
	"name": "{{$cliente->nome}}",
	"url": "https://www.info-alberghi.com/hotel.php?id={{$cliente->id}}",
	"description": "{{$cliente->tmp_punti_di_forza_it}}",
	"image": [ 
		"https://static.info-alberghi.com/images/gallery/800x538/{{$cliente->immaginiGallery->toArray()[0]['foto']}}"
	],
	"telephone": [ 
		"{{$cliente->telefono}}" @if ($cliente->whatsapp), "{{$cliente->whatsapp}}" @endif 
	],
	"priceRange": "€{{$cliente->prezzo_min}} - €{{$cliente->prezzo_max}} ",
	{{-- "petsAllowed": "True",					TODO  $cliente->servizi->toArray() --}}
	"starRating": {
		"@type": "Rating",
		"ratingValue": "{{{$cliente->stelle->ordinamento}}}" {{-- TODO TEST CON QUESTA VARIABLE --}}
	},
	"paymentAccepted":"{{$cliente->contanti ? 'Contanti' : null}}{{$cliente->carta_credito ? ', Carta di credito' : null}}{{$cliente->paypal ? ', paypal' : null}}.",
	"numberOfRooms" : "{{$cliente->n_camere}}",
	"checkinTime" : "{{$cliente->checkin_it}}",
	"checkoutTime": "{{$cliente->checkout_it}}",
	"hasMap": "https://www.info-alberghi.com/mappa-hotel/{{$cliente->id}}",
	"address": {
		"@type": "PostalAddress",
		"addressCountry": "IT",
		"addressLocality": "{{$cliente->localita->nome}}",
		"addressRegion": "Emilia Romagna",
		"postalCode": "{{$cliente->cap}}",
		"streetAddress": "{{$cliente->indirizzo}}"
	},
	"availableLanguage": [
		{
			"@type": "Language",
			"name": "Italian",
			"alternateName": "it"
		}
	@if ($cliente->inglese),
		{
			"@type": "Language",
			"name": "English",
			"alternateName": "en"
		}
	@endif 
	@if ($cliente->francese),
		{
			"@type": "Language",
			"name": "French",
			"alternateName": "fr"
		}
	@endif 
	@if ($cliente->tedesco),
		{
			"@type": "Language",
			"name": "German",
			"alternateName": "de"
		}
	@endif 
	@if ($cliente->russo),
		{
			"@type": "Language",
			"name": "Russian",
			"alternateName": "ru"
		}@endif 
	], 
  "openingHoursSpecification": {
		"@type": "OpeningHoursSpecification",
		"opens": "00:00",
		"closes": "23:59" @if($cliente->annuale === 0),
		"validFrom": "{{$cliente->aperto_dal}}", 
		"validThrough": "{{$cliente->aperto_al}}"
		@endif
	},
	@if ($cliente->rating_ia > 6 && $cliente->enabled_rating_ia == true)
	"aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{$cliente->rating_ia}}",
        "bestRating": "10",
        "reviewCount": "{{ number_format( $cliente->n_rating_ia , 0 , null , "" ) }}"
	},
	@endif
{{-- ALTERNATIVA:
	"review": {
		"@type": "Review",
		"author": { 
			"@type": "Organization", 
			"name": "info-alberghi.com"
		},
		"reviewRating": {
				"@type": "Rating",
				"bestRating": "10",
				"ratingValue": "{{$cliente->rating_ia}}",
				"worstRating": "1"
		}
	}, --}}
	"geo": {
		"@type": "GeoCoordinates",
		"latitude": "{{$cliente->mappa_latitudine}}",
		"longitude": "{{$cliente->mappa_longitudine}}"
	
	} @if (isset($cliente->infoPiscina->sup) && $cliente->infoPiscina->sup > 0) @php $pool = $cliente->infoPiscina; @endphp,
	"amenityFeature": {
		"@type": "LocationFeatureSpecification",
		"name": "Piscina",
		"description": "@if($pool->riscaldata) Riscaldata @endif @if($pool->idro), idromassaggio @endif @if($pool->idro_cervicale), idromassaggio cervicale @endif @if($pool->aperitivi), aperitivi in piscina @endif @if($pool->getto_bolle), getto di bolle @endif @if($pool->wi_fi), zona wi-fi @endif @if($pool->lettini_dispo), {{ $pool->lettini_dispo }} posti prendisole @endif.",
		"value": "true"@if (isset($fotoPiscina) && $fotoPiscina->count()),
		"image": "https://static.info-alberghi.com/images/gallery/360x200/{{$fotoPiscina[0]->foto}}"@endif
	}
	@endif
}
</script>
@endif
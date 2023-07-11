
<footer id="footer">

	<div class="container">
		<div class="row">
			@if (!isset($cliente))
					<div class="col-sm-2"></div>
					<div class="col-sm-8">	
						@include('composer._footer_ia')
					</div>
			@else 
					<div class="col-sm-6">	
							@include('composer._footer_ia')
					</div>
					<div class="col-sm-6">
						@include('composer._footer_hotel')
					</div>
			@endif
			
			<div class="clearfix"></div>
				
		</div>
	</div>


@if (isset($cliente))

<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "Hotel",
	"name": "{{$cliente->nome}}",
	"url": "https://www.info-alberghi.com/hotel.php?id={{$cliente->id}}",
	"description": "{{$cliente->tmp_punti_di_forza_it}}",
	"image": [ 
		@php $ultima_immagine = count($cliente->immaginiGallery->toArray()); @endphp
		@foreach ($cliente->immaginiGallery->toArray() as $img)
		"https://static.info-alberghi.com/images/gallery/800x538/{{$img['foto']}}"{{0 === --$ultima_immagine ? '' : ','}}
		@endforeach
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
		}
	@endif 
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
</footer>
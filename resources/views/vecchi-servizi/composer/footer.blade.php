<aside id="footeraside">
	<div class="container">
		<div class="row">
					
			<header class="hide">
				<h2>{{trans("title.link_utili")}}</h4>
			</header>
			
			@php $t=1; @endphp
			@foreach($titoliFooter as $titoli)
				
				@if (isset($footerLink[$t]) && count($footerLink[$t]) > 0) 
				
				<nav class="col-sm-3">
					
					<header>
						<h4>{{$titoli}}</h4>
					</header>
					
					<ul>
						@foreach($footerLink[$t] as $link)
							<li>
								@if ( strpos($link["uri"], "https://") !== false )
									<a href="{{$link["uri"]}}" target="{{$link["target"]}}">{{$link["titolo"]}}</a>
								@else
									<a href="{{url('')}}/{{$link["uri"]}}" target="{{$link["target"]}}">{{$link["titolo"]}}</a>
								@endif
							</li>
						@endforeach
					</ul>
				</nav>
							
				@php $t++; @endphp
				
				@endif
				
			@endforeach
						
			<nav class="col-sm-3">
				
				<header>
					<h4>Social</h4>
				</header>
				<ul>
					<li><a target="_blank" rel="noopener" href="https://www.facebook.com/infoalberghi"><span class="social fb"><i class="icon-facebook"></i></span>facebook</a></li>
					<li><a target="_blank" rel="noopener" href="https://twitter.com/infoalberghi"><span class="social tw"><i class="icon-twitter"></i></span>twitter</a></li>
					<li><a target="_blank" rel="noopener" href="{{url('')}}/contattaci.php"><span class="social dn"><i class="icon-newspaper"></i></span>contatti e rassegna stampa</a></li>
				</ul>
				
			</nav>
			
			<div class="clearfix"></div>
		
	    
	    </div>
	</div>
</aside>

<div class="clearfix"></div>


<footer id="footer">

	<div class="container">
		<div class="row">
			
			<div class="col-sm-8">
			
				<img src="{{Utility::asset('images/logo-small.png')}}" alt="Info Alberghi S.r.l" />
			
				<p>
					Via Gambalunga, 81/A 47921 - Rimini&nbsp;&nbsp;&nbsp;P.IVA 03479440400<br />
					{{ trans('hotel.diritti') }}&nbsp;&nbsp;&nbsp;
					@if ($locale == 'it')
						<a href="{{Utility::getUrlWithLang($locale, "/informativa-privacy-gdpr.php")}}" class="reverse" target="_blank">Privacy policy</a>&nbsp;&nbsp;&nbsp;
						<a href="{{Utility::getUrlWithLang($locale, "/cookie-policy-gdpr.php")}}" class="reverse" target="_blank">Cookie policy</a>
					@elseif ($locale == 'de')
						<a href="{{Utility::getUrlWithLang($locale, "/datenschutzerklarung-gdpr.php")}}" class="reverse" target="_blank">Datenschutzerklärung</a>&nbsp;&nbsp;&nbsp;
						<a href="/ing/cookie-policy-gdpr.php" class="reverse" target="_blank">Cookie policy</a>
					@elseif ($locale == 'fr')
						<a href="{{Utility::getUrlWithLang($locale, "/politique-de-confidentialite-gdpr.php")}}" class="reverse" target="_blank">Politique de confidentialité</a>&nbsp;&nbsp;&nbsp;
						<a href="/ing/cookie-policy-gdpr.php" class="reverse" target="_blank">Cookie policy</a>
					@else
						<a href="/ing/privacy-policy-gdpr.php" class="reverse" target="_blank">Privacy policy</a>&nbsp;&nbsp;&nbsp;
						<a href="/ing/cookie-policy-gdpr.php" class="reverse" target="_blank">Cookie policy</a>
					@endif
				</p>
				
				<a href="{{url('')}}/admin" target="_blank" class="btn btn-cyano">Area riservata</a>

			</div>
			
			@if (isset($cliente))
			
				<div class="col-sm-4">
					
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
                                    <meta>
                                    <strong><span>{{$cliente->rating_ia}}</span> / <span>10</span> {{ trans('hotel.stelle') }}</strong>
                                </span>
							@endif

						</div>
					</div>
				</div>
		
			@endif
			<div class="clearfix"></div>
				
		</div>
	</div>


@if (isset($cliente))
@php

@endphp
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
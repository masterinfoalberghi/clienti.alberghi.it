<?php 
/**
 *
 * visualizzo dati orari dell'hotel:
 * @parameters: $immagini_gallery, $pathDeviceType (mi dice se sono nella vista del mobile oppure no), $hotel_id, $hotel_nome
 *
 *
 *
 */ 
?>


{{-- GALLERY MOBILE PHONE --}}



@if ($gallery == "scheda")
	@if (!is_null($gallery_mobile) && count($gallery_mobile))
		<div class="gallery-hotel">
			 
			<?php $count=0; $t=1;?>
			@foreach ($gallery_mobile as $img)
				@if ($count < 5)
					<?php 
						
						$last = $img; 
						$featured = $img[3];
						if ($img[3] == "" || $img[3] == "http://www.info-alberghi.com/" || $img[3] == "//static.info-alberghi.com/")
							$featured = $img[0];
							
					?>
				    <figure @if($count > 0) class="small-gallery-image" @endif>
						<img width="360" height="320" itemprop="image" src="{{$featured}}" alt="{{$cliente->nome}} {{$cliente->localita->nome}}"/> 
						
                        @if($count > 0)
                            <a href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id. "&gallery")}}" class="button-scheda dot"> </a>
                        @else
                            @if ($cliente->attivo == -1) <a href="{{Utility::getUrlWithLang($locale, "/hotel.demo?id=demo&gallery")}}" class="button-scheda dot">
                            @else 						 <a href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id. "&gallery")}}" class="button-scheda dot"> @endif 
                            
                                    <img src="{{Utility::asset("/mobile/img/photogallery.svg")}}"  width="20" height="20" /><br />
                                    {{trans("labels.galleria")}}<br />
                                    <small>{{count($gallery_mobile)}} {{trans("labels.foto")}}</small>
                                </a>
                        @endif
                                
					</figure>
				@endif
				<?php $count++; $t++;?>
		    @endforeach	
			@if($cliente->certificazione_aci)
				<style>
					.cert_aci { position: absolute; top:5px; left:5px; z-index: 10000; display: block; }
				</style>
			<div class="cert_aci"><img src="{{Utility::asset('/images/aic_logo_mobile.png')}}" /></div>
			@endif
		</div>
	@endif
@else
	@if ( !is_null($cliente->descrizioneHotel) && $cliente->descrizioneHotel->video_url != '' )
	<div class="videoWrapper">
		<iframe width="290" height="163" src="{{ str_ireplace('http://', 'https://', $cliente->descrizioneHotel->video_url) }}" frameborder="0" allowfullscreen></iframe><br />
	</div>
	@endif
	<div class="gallery-hotel">
		<?php $t=1;?>
		@foreach ($gallery_mobile as $img)	
		    <figure>
			    <a href="{{$img[1]}}" data-size="720x400">
				    <img src="{{$img[0]}}" data-srcset="{{$img[1]}} 2x" alt="{{$img[2]}} - {{$cliente->nome}} {{$cliente->localita->nome}} ({{$t+1}}/{{count($immagini_gallery)}})"  />  
					@if ($img[2])
						<figcaption>
							<span>{{ucfirst(strtolower($img[2]))}}</span>
						</figcaption>
					@endif
			    </a>
		    </figure>
		    <?php $t++;?>
	    @endforeach	
	</div>
{{-- SCHEMA.ORG JSON - Gallery Mobile --}}
<script type="application/ld+json">
{
"@context": "http://schema.org",
	"@type": "Hotel",
	"name": "{{{$cliente->nome}}}",
	"starRating": {
		"@type": "Rating",
		"ratingValue": "{{{$cliente->stelle->ordinamento}}}"
	},
	"address": {
		"@type": "PostalAddress",
		"addressCountry": "IT",
		"addressLocality": "{{$cliente->localita->nome}}",
		"addressRegion": "Emilia Romagna",
		"postalCode": "{{$cliente->cap}}",
		"streetAddress": "{{$cliente->indirizzo}}"
	},
	"priceRange": "€{{{$cliente->prezzo_min}}} - €{{{$cliente->prezzo_max}}}",
	"image": [  
	@php $ultima_immagine_mobile = count($gallery_mobile); @endphp
	@foreach ($gallery_mobile as $img)
			{
			"@type": "ImageObject",
			"url": "{{$img[1]}}",
			"thumbnailUrl": "{{$img[0]}}" @if ($img[2]),
			"caption": "{{ucfirst(strtolower($img[2]))}}"@endif
			}{{0 === --$ultima_immagine_mobile ? '' : ','}}
	@endforeach	
	]
}
</script>

@endif


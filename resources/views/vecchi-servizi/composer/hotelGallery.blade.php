<?php 
/**
 *
 * visualizzo dati orari dell'hotel:
 * @parameters: $immagini_gallery, $hotel_id, $hotel_nome
 *
 *
 *
 */
 ?>

@if (count($immagini_gallery))
		
	<section id="gallery" class="margin-bottom">
		
		<header class="hidden">
			<h2 class="content-section">{{trans("title.gallery")}}</h2>
		</header>

		<div class="slider-for">
			
			@php $t = 0; @endphp
			@foreach ($immagini_gallery as $thumb => $img)
				
				<figure>

					@if ($t == 0)
						<img class="image_gallery" data-index="{{$t}}" src="{{$img[1]}}" alt="{{$img[3]}} - {{$cliente->nome}} {{$cliente->localita->nome}} ({{$t+1}}/{{count($immagini_gallery)}})"/>
					@else
						<img class="image_gallery" data-index="{{$t}}" data-lazy="{{$img[1]}}" alt="{{$img[3]}} - {{$cliente->nome}} {{$cliente->localita->nome}} ({{$t+1}}/{{count($immagini_gallery)}})"/>
					@endif

					@if($img[3])
						<figcaption><span>{{ucfirst(strtolower($img[3]))}}</span></figcaption>
					@endif

				</figure>

				@php $t++; @endphp
				
			@endforeach	
			
			@if ( !is_null($cliente->descrizioneHotel) && $cliente->descrizioneHotel->video_url != '' )

				<figure>
					<iframe width="780" height="519" src="https://www.youtube.com/embed/{{$codice}}?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
				</figure>

			@endif

		</div>
		

		<nav class="slider-nav">
			
			@php 
				$t = 0;
				$current = 0; 
			@endphp
			

			@foreach ($immagini_gallery as $thumb => $img)

				<div class="thumbs @if($t == $current) current @endif">
					<img class="image_gallery-thumb"  src="{{$img[0]}}" data-index-thumb="{{$t}}" alt="thumbs @if($img[3]) - {{$img[3]}}@endif - {{$cliente->nome}} {{$cliente->localita->nome}} ({{$t+1}}/{{count($immagini_gallery)}})"/>
				</div>

				@php $t++; @endphp

			@endforeach	

			@if (!is_null($cliente->descrizioneHotel) && $cliente->descrizioneHotel->video_url != '' )
				
				<div class="thumbs t-video">
					
					<img src="{{Utility::asset('images/play.png')}}" alt="Video {{$cliente->nome}} {{$cliente->localita->nome}}"/>
					
				</div>
				
			@endif

			<div class="clearfix"></div>
			
		</nav>
		
		<footer>

			@if($cliente->certificazione_aci)
				<div class="cert_aci"><img src="{{Utility::asset('images/aic_logo.png')}}" /></div>
			@endif

		</footer>
		


	</section>
	
	
@endif

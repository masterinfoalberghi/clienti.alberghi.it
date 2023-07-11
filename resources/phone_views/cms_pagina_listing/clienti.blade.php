@if(!isset($listing_bambini_gratis))
	@php $listing_bambini_gratis = 0; @endphp
@endif

@if (isset($filter_text) && !empty($filter_text))
	<div id="filtroattivo">{{ trans('listing.order') }}<br /><strong>{{$filter_text}}</strong></div>
@endif

@php
$listing_gruppo_piscina = Utility::getGruppoPiscina();
$listing_gruppo_benessere = Utility::getGruppoBenessere();

@endphp

@foreach ($clienti as $cliente)
			
		@if (!is_null($cliente->vetrina_id))

			@if ($evidenza_vetrina)<div class="inVetrina">@endif
			@include('draw_item_cliente.slotVetrina',['slot' => $cliente, 'locale' => $locale])
			@if ($evidenza_vetrina)</div>@endif

			@elseif ($cliente instanceof App\VetrinaBambiniGratisTopLingua) 

			@if ($evidenza_vetrina)<div class="inVetrina">@endif
			@include('draw_item_cliente.vaat',['vaat' => $cliente, 'locale' => $locale])
			@if ($evidenza_vetrina)</div>@endif
			
			@elseif ($cliente->getTop() != '')
			
			@php
				list($url,$vetrina_top_id) = $cliente->getTop(); 
				$url_scheda = Utility::getUrlWithLang($locale,'/'.$url.'/'.$cliente->id.'/'.$vetrina_top_id); 
			@endphp

			@if (isset($cms_pagina) && $cms_pagina->listing_gruppo_servizi_id && !is_null($cliente->first_image_group))
				@php $passing_image = $cliente->first_image_group; $image_listing = $cliente->getListingImg('360x200', true, $passing_image); @endphp
			@else
				@php $image_listing = $cliente->getListingImg("360x200", true, $cliente->listing_img); @endphp
			@endif
						
			@include('draw_item_cliente.draw_item_cliente_vetrina_listing',

				array(
					'url' => $url_scheda, 
					'class_item' => 'clienti', 
					'image_listing' => $image_listing
				)
				
			)
					
		@endif
	
@endforeach


@foreach ($clienti as $cliente)
			
	@if (is_null($cliente->vetrina_id) && !$cliente instanceof App\VetrinaBambiniGratisTopLingua && $cliente->getTop() == '')

		@if ($listing_bambini_gratis)

			
				{{-- DISEGNO IL CLIENTE COME CLIENTE BG NORMALE --}}
				@component('components.log_view',['log_view' => $log_view])
					CLIENTE BG NORMALE
				@endcomponent
				{{-- adesso i VAAT sono disegnati QUI --}}

				
				@php /* url per visualizzare/contare la scheda hotel */ $url_scheda = url(Utility::getLocaleUrl($locale)).'/hotel.php?id='.$cliente->id; @endphp
				@include('draw_item_cliente.draw_item_cliente_bg_listing',array( 'url' => $url_scheda, 'class_item' => 'item_wrapper_cliente',	'image_listing' => $cliente->getListingImg('360x200', true),	'image_listing_retina' => $cliente->getListingImg('720x400', true)))

			
			
		@else
			
			{{-- è una vetrina TOP --}}
			
			@if ($cliente->getTop() != '')
				@php list($url,$vetrina_top_id) = $cliente->getTop(); /* url per visualizzare la scheda hotel con il contaclick */
				$url_scheda = Utility::getUrlWithLang($locale , '/'.$url.'/'.$cliente->id.'/'.$vetrina_top_id); @endphp
			@else
				
				@php /* url per visualizzare la scheda hotel */
				$url_scheda = Utility::getUrlWithLang($locale, '/hotel.php?id='.$cliente->id); @endphp
				
			@endif

			{{-- verifico se esiste un'immagine per il listing nell'eager loading --}}
			
			@if (isset($cms_pagina) && $cms_pagina->listing_gruppo_servizi_id && !is_null($cliente->first_image_group))
				@php
				$passing_image = $cliente->first_image_group;
				$image_listing = $cliente->getListingImg('360x200', true, $passing_image);
				@endphp

			@else
				
				@php $image_listing = $cliente->getListingImg('360x200', true);	@endphp
				
			@endif
			
			@include('draw_item_cliente.draw_item_cliente_vetrina_listing',
		
			array(
					 
					'url' => $url_scheda, 
					'class_item' => 'item_wrapper_cliente', 
					'image_listing' => $image_listing, 
					'image_listing_retina' => $cliente->getListingImg('720x400', true)
				)
			)
	
			
		@endif

	@endif

	
@endforeach

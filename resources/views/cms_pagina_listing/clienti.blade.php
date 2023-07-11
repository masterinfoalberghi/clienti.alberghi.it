@php
		
	if(!isset($listing_bambini_gratis)) 
		$listing_bambini_gratis = 0;
	
	if (!isset($wishlist))
		$wishlist = 0;
		
	$open_vetrina = 0;
	$c=0;


	if(!isset($log_view))
		$log_view = Config::get("logging.log_view");
	
@endphp


@component('components.log_view',['log_view' => $log_view])
cms_pagina_listing/clienti.blade.php
<br/>
CLIENTI NELLA VIEW IN PARTENZA {{$clienti->count()}}
@endcomponent


<section id="vetrina" class="evidenziati">
	
	@foreach ($clienti as $cliente)

		@if (!is_null($cliente->vetrina_id))
		   
		  {{-- DISEGNO LA VETRINA --}}
		    @component('components.log_view',['log_view' => $log_view])
				VETRINA draw_item_cliente.slotVetrina
			@endcomponent
			@include('draw_item_cliente.slotVetrina',['slot' => $cliente, 'locale' => $locale, 'class_item' => 'clienti slotVetrina'])
						
		@elseif ($cliente instanceof App\VetrinaBambiniGratisTopLingua) 
		  
		  {{-- DISEGNO IL VAAT --}}
			@component('components.log_view',['log_view' => $log_view])
				VATT draw_item_cliente.vaat
			@endcomponent
			@include('draw_item_cliente.vaat',['vaat' => $cliente, 'locale' => $locale, 'class_item' => 'clienti vaat'])
		
		@elseif ($cliente->getTop() != '')
				
			@php  
				
				list($url,$vetrina_top_id) = $cliente->getTop(); 
				$url_scheda = Utility::getUrlWithLang($locale,'/'.$url.'/'.$cliente->id.'/'.$vetrina_top_id); 
				
			@endphp
			
			@if (isset($cms_pagina) && $cms_pagina->listing_gruppo_servizi_id && !is_null($cliente->first_image_group))

				@php $passing_image = $cliente->first_image_group; $image_listing = $cliente->getListingImg('220x220', true, $passing_image);@endphp
				
			@else
			  
				@php $image_listing = $cliente->getListingImg("220x220", true, $cliente->listing_img); @endphp
				
			@endif
			
			{{-- DISEGNO IL CLIENTE COME VST/VTT --}}
			@component('components.log_view',['log_view' => $log_view])
				CLIENTE COME VST/VTT
			@endcomponent

			@include('draw_item_cliente.draw_item_cliente_vetrina_listing',
					array(
				  		'url' => $url_scheda, 
				  		'class_item' => 'clienti', 
				  		'image_listing' => $image_listing
						)
					)
			
			
				
		@endif
		
		
		
	@endforeach
	
	<div class="clearfix"></div>

</section>


<section id="listing">
	
	@foreach ($clienti as $cliente)
	
		@if (is_null($cliente->vetrina_id) && !$cliente instanceof App\VetrinaBambiniGratisTopLingua && $cliente->getTop() == '')
			   
			@if ($listing_bambini_gratis)
				
				
				@php $url_scheda = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id); @endphp
				
				{{-- DISEGNO IL CLIENTE COME CLIENTE BG NORMALE --}}
				@component('components.log_view',['log_view' => $log_view])
					CLIENTE BG NORMALE
				@endcomponent

				{{-- adesso i VAAT sono disegnati QUI --}}
				@include('draw_item_cliente.draw_item_cliente_bg_listing',array('url' => $url_scheda, 'class_item' => 'clienti draw_item_cliente_bg_listing', 'image_path' => 'listing', 'image_listing' => $cliente->getListingImg('220x220', true)))

					
			@else
		  			  		
				@php $url_scheda = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id);@endphp
	
				@if (isset($cms_pagina) && $cms_pagina->listing_gruppo_servizi_id && !is_null($cliente->first_image_group))
				  
					@php $passing_image = $cliente->first_image_group; $image_listing = $cliente->getListingImg('220x220', true, $passing_image);@endphp
					
				@else
				  
					@php $image_listing = $cliente->getListingImg("220x220", true, $cliente->listing_img); @endphp
					
				@endif
								
					
				{{-- DISEGNO IL CLIENTE COME CLIENTE NORMALE --}}
				@component('components.log_view',['log_view' => $log_view])
					CLIENTE NORMALE
				@endcomponent
				
				@include('draw_item_cliente.draw_item_cliente_vetrina_listing',
					array(
				  		'url' => $url_scheda, 
				  		'class_item' => 'clienti', 
				  		'image_listing' => $image_listing,
				  		'wishlist' => $wishlist
						)
					)
			  	
		  @endif
		  
		@endif
		
		
	@endforeach
	
	<div class="clearfix"></div>
	
</section>


<?php
	
	/**
	 * Costruisco i dati che mi servono
	 */
	 
	$listing_gruppo_piscina = Utility::getGruppoPiscina();
	$listing_gruppo_benessere = Utility::getGruppoBenessere();

?>

@component('components.log_view',['log_view' => $log_view])
draw_item_cliente/snippet_offerta_prenota_prima.blade.php
<br/>
CLIENTI NELLA VIEW IN PARTENZA {{$clienti->count()}}
@endcomponent

@if (isset($filter_text) && !empty($filter_text))
 	<div id="filtroattivo">{{ trans('listing.order') }}: <strong>{{$filter_text}}</strong></div>
@endif

<div class="prenota_prima_listing">
	
	@if(!isset($page_number) || (isset($page_number) && $page_number < 2))
	<section id="vetrine" class="evidenziati">
		
		@foreach ($clienti as $cliente)
			
			 @if (!is_null($cliente->vetrina_id))
			 	
			 	{{-- DISEGNO LA VETRINA --}}
			 	@include('draw_item_cliente.slotVetrina',['slot' => $cliente, 'locale' => $locale, 'class_item' => 'snippet_offerta_generica slotVetrina'])
			 	
			 @elseif ($cliente instanceof App\VetrinaOffertaTopLingua) 
			 	
			 		{{-- DISEGNO IL VOT --}}	
			 	@include('draw_item_cliente.vot_prenota_prima',['vot' => $cliente, 'locale' => $locale, 'class_item' => 'snippet_offerta_generica vot_prenota_prima'])
			 	
			 @endif
		
		@endforeach
		
	</section>
	@endif
	
	<section id="listing">
		
		@foreach ($clienti as $cliente)

	    	@if (is_null($cliente->vetrina_id) && !$cliente instanceof App\VetrinaOffertaTopLingua)
				
				{{-- adesso i VOT sono disegnati QUI --}}
				@include('draw_item_cliente.draw_item_cliente_offerta_listing_prenota_prima',
					array(
						'class_item' => 'snippet_offerta_generica draw_item_cliente_offerta_listing_prenota_prima', 
						'image_path' => 'listing', 
						'image_listing' => $cliente->getListingImg('220x220', true)
					)
				)
		      
		    @endif
		
		@endforeach  
		  
	</section>
	
</div>
 


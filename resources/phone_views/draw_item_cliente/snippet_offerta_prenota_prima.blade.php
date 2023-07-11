
@if (isset($filter_text) && !empty($filter_text))
 	<div id="filtroattivo">{{ trans('listing.order') }}: <strong>{{$filter_text}}</strong></div>
@endif


<?php
$listing_gruppo_piscina = Utility::getGruppoPiscina();
$listing_gruppo_benessere = Utility::getGruppoBenessere();
?>

@foreach ($clienti as $cliente)
	
		@if (!is_null($cliente->vetrina_id))
			
			@if ($evidenza_vetrina)
				<div class="inVetrina">	
			@endif
			
			@include('draw_item_cliente.slotVetrina',['slot' => $cliente, 'locale' => $locale])
			
			@if ($evidenza_vetrina)
				<div class="clear"></div></div>
			@endif
			
		{{-- è una vetrinaOffertaTOP --}}
		@elseif ($cliente instanceof App\VetrinaOffertaTopLingua) 
			
				@if ($evidenza_vetrina)
					@php
						$class_item = 'inVetrina vot';
						if(!$cliente->getMarkAsEvidenza())
							{
							$class_item .= ' not_mark_as_evidenza';	
							}
					@endphp
					
					<div class="{{$class_item}}">	
				@endif
			
					@include('draw_item_cliente.vot_prenota_prima',['vot' => $cliente, 'locale' => $locale])
			
				@if ($evidenza_vetrina)
					<div class="clear"></div></div>
				@endif
		
		@endif

@endforeach 

@foreach ($clienti as $cliente)
	
	@if (is_null($cliente->vetrina_id) && !$cliente instanceof App\VetrinaOffertaTopLingua)

		@include('draw_item_cliente.draw_item_cliente_offerta_listing_prenota_prima',array('tipo_offerta' => 'prenotaprima', 'class_item' => 'item_wrapper_cliente', 'image_listing' => $cliente->getListingImg("360x200",true), 'image_listing_retina' => $cliente->getListingImg("720x400",true)))

	@endif

@endforeach 



@if (isset($filter_text) && !empty($filter_text))
 	<div id="filtroattivo">{{ trans('listing.order') }}: <strong>{{$filter_text}}</strong></div>
@endif

<?php
$listing_gruppo_piscina = Utility::getGruppoPiscina();
$listing_gruppo_benessere = Utility::getGruppoBenessere();
?>

@component('components.log_view',['log_view' => $log_view])
	CLIENTI NELLA VIEW IN PARTENZA {{$clienti->count()}}
@endcomponent

@foreach ($clienti as $cliente)

	@if (!is_null($cliente->vetrina_id) )
		
		@if ($evidenza_vetrina)
			<div class="inVetrina">	
		@endif
		
		{{-- DISEGNO LA VETRINA --}}	
		@include('draw_item_cliente.slotVetrina',['slot' => $cliente, 'locale' => $locale])
		
		@if ($evidenza_vetrina)
			<div class="clear"></div></div>
		@endif
		
	{{-- è una vetrinaOffertaTOP --}}
	@elseif ($cliente instanceof App\VetrinaOffertaTopLingua ) 
		
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
				
			{{-- DISEGNO IL VOT --}}	
			@include('draw_item_cliente.vot_generico',['vot' => $cliente, 'locale' => $locale])
		
			@if ($evidenza_vetrina)
				<div class="clear"></div></div>
			@endif

	@endif 
@endforeach 

@php
	$clienti_view = 0;
@endphp

@foreach ($clienti as $cliente)

	@if (is_null($cliente->vetrina_id) && !$cliente instanceof App\VetrinaOffertaTopLingua )
		
		@if ($cms_pagina->listing_coupon)

			@include('draw_item_cliente.draw_item_cliente_coupon_listing',array('class_item' => 'item_wrapper_cliente', 'image_listing' => $cliente->getListingImg("360x200",true), 'image_listing_retina' => $cliente->getListingImg("720x400",true)))

		@else

			@if ($cms_pagina->listing_parolaChiave_id)

			  @php $offerte = $cliente->offerteLast;
			  $tipo_offerta = 'offers'; @endphp

			@elseif ($cms_pagina->listing_offerta == 'offerta') 

			  @php $offerte = $cliente->offerte; 
			  $tipo_offerta = 'offers'; @endphp

			@elseif ($cms_pagina->listing_offerta == 'lastminute') 

			  @php $offerte = $cliente->last;
			  $tipo_offerta = 'lastminute'; @endphp

			@endif
			
			@component('components.log_view',['log_view' => $log_view])
      	OFFERTA	{{$cliente->prezzo_to_order}}
      @endcomponent

			{{-- adesso i VOT sono disegnati QUI --}}	
			@include('draw_item_cliente.draw_item_cliente_offerta_generica',array('tipo_offerta' => $tipo_offerta, 'class_item' => 'item_wrapper_cliente', 'image_listing' => $cliente->getListingImg("360x200",true), 'image_listing_retina' => $cliente->getListingImg("720x400",true)))

		@endif
		
	@endif

	@php
		$clienti_view++;
	@endphp
	
@endforeach 

@component('components.log_view',['log_view' => $log_view])
CLIENTI NELLA VIEW {{$clienti_view}}
@endcomponent

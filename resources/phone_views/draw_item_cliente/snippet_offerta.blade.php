
@if (isset($filter_text) && !empty($filter_text))
 	<div id="filtroattivo">{{ trans('listing.order') }}: <strong>{{$filter_text}}</strong></div>
@endif

@if (isset($pagination) && $pagination ) 
    @include("widget.pagination", ["clienti" => $clienti, "position" => 0])
@endif

@foreach ($clienti as $cliente)
	{{-- @if (!isset($page_number) ||  $page_number < 2)
	@if (!is_null($cliente->vetrina_id)) --}}
		
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
				<div class="inVetrina vot">	
			@endif
		
				@include('draw_item_cliente.vot',['vot' => $cliente, 'locale' => $locale])
		
			@if ($evidenza_vetrina)
				<div class="clear"></div></div>
			@endif
	
	@else
	
		@if ($listing_offerta == 'coupon')
			@include('draw_item_cliente.draw_item_cliente_coupon_listing',array('listing_offerta' => $listing_offerta, 'class_item' => 'item_wrapper_cliente', 'image_listing' => $cliente->getListingImg("360x200",true), 'image_listing_retina' => $cliente->getListingImg("720x400",true)))
		@else
			@include('draw_item_cliente.draw_item_cliente_offerta_listing',array('listing_offerta' => $listing_offerta, 'class_item' => 'item_wrapper_cliente', 'image_listing' => $cliente->getListingImg("360x200",true), 'image_listing_retina' => $cliente->getListingImg("720x400",true)))
		@endif
	
	{{-- @endif
	@endif --}}

@endforeach 

@if (isset($pagination) && $pagination ) 
    @include("widget.pagination", ["clienti" => $clienti, "position" => 1])
@endif
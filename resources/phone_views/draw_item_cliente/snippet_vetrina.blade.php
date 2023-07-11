

@if (isset($filter_text) && !empty($filter_text))
 	<div id="filtroattivo">Gli hotel sono ordinati per: <strong>{{$filter_text}}</strong></div>
@endif

@foreach ($clienti as $cliente)

  	 @if (!is_null($cliente->vetrina_id))
    
  	 	@include('draw_item_cliente.slotVetrina',['slot' => $cliente, 'locale' => $locale])
    
    @else

		<?php $url_scheda = Utility::getUrlWith($locale.'/hotel.php?id='.$cliente->id); ?>
		@include('draw_item_cliente.draw_item_cliente_vetrina_listing',array('url' => $url_scheda, 'class_item' => 'item_wrapper_cliente', 'image_path' => 'listing', 'image_listing' => $cliente->getListingImg('360x200', true), 'image_listing_retina' => $cliente->getListingImg('720x400', true)))
		
    @endif


@endforeach
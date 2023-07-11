@if (isset($filter_text) && !empty($filter_text))
	<div id="filtroattivo">{{ trans('listing.order') }}<br /><strong>{{$filter_text}}</strong></div>
@endif

@foreach ($clienti as $cliente)

    @php		
        $url_scheda = Utility::getUrlWithLang($locale, '/hotel.php?id='.$cliente->id);
        $image_listing = $cliente->getListingImg('360x200', true);	
    @endphp
			
	@include('draw_item_cliente.draw_item_cliente_filter_listing',
		
    array(
                
            'url' => $url_scheda, 
            'class_item' => 'item_wrapper_cliente', 
            'image_listing' => $image_listing, 
            'image_listing_retina' => $cliente->getListingImg('720x400', true)
        )
    )	
@endforeach

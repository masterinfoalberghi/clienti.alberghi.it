
<section id="listing">
	@foreach ($clienti as $cliente)

    @php $url_scheda = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id);@endphp
        @php $image_listing = $cliente->getListingImg("220x220", true, $cliente->listing_img); @endphp
      
        @include('draw_item_cliente.draw_item_cliente_filter_listing',
            array(
                'url' => $url_scheda, 
                'class_item' => 'clienti', 
                'image_listing' => $image_listing,
                )
            )

	@endforeach
	<div class="clearfix"></div>	
</section>


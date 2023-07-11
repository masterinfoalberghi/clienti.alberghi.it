
@if (isset($filter_text) && !empty($filter_text))

 	<div id="filtroattivo">{{ trans('listing.order') }}: <strong>{{$filter_text}}</strong></div>
 	
@endif


<div class="snippet_offerta">

@foreach ($clienti as $cliente)
    
  	@if (!is_null($cliente->vetrina_id))
  	
       	<section id="vetrine" class="evidenziati">
        	@include('draw_item_cliente.slotVetrina',['slot' => $cliente, 'locale' => $locale, 'class_item' => 'snippet_offerta slotVetrina'])
        </section>

    @elseif ($cliente instanceof App\VetrinaOffertaTopLingua) 

		<section id="vetrine" class="evidenziati">
			
         	@include('draw_item_cliente.vot_generico',['vot' => $cliente, 'locale' => $locale, 'class_item' => 'snippet_offerta vot_generico'])
         	          
        </section>

    @else
      
	    @if ($listing_offerta == 'coupon')
	    
	        @include('draw_item_cliente.draw_item_cliente_coupon_listing',array('listing_offerta' => $listing_offerta, 'class_item' => 'snippet_offerta draw_item_cliente_coupon_listing', 'image_path' => 'listing', 'image_listing' => $cliente->getListingImg('360x200', true)))
	        
	    @else
	    
	        @include('draw_item_cliente.draw_item_cliente_offerta_listing',array('listing_offerta' => $listing_offerta, 'class_item' => 'snippet_offerta draw_item_cliente_offerta_listing', 'image_path' => 'listing', 'image_listing' => $cliente->getListingImg('360x200', true)))

	    @endif
      
    @endif

@endforeach 

</div>

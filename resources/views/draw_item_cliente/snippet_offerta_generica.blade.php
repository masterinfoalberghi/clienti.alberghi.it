<?php
	
	/**
	 * Costruisco i dati che mi servono
	 */
	 
	 $listing_gruppo_piscina = Utility::getGruppoPiscina();
	 $listing_gruppo_benessere = Utility::getGruppoBenessere();
	 
?>


@component('components.log_view',['log_view' => $log_view])
draw_item_cliente/snippet_offerta_generica.blade.php
<br/>
CLIENTI NELLA VIEW IN PARTENZA {{$clienti->count()}}
@endcomponent

@if (isset($filter_text) && !empty($filter_text))
 	<div id="filtroattivo">{{ trans('listing.order') }}: <strong>{{$filter_text}}</strong></div>
@endif

@if ($cms_pagina->listing_offerta == 'lastminute') 
<div class="lastminute_listing">
@else 
<div class="offers_listing">
@endif
	
	@if(!isset($page_number) || (isset($page_number) && $page_number < 2))
	<section id="vetrine" class="evidenziati">
		
		@foreach ($clienti as $cliente)
					
			@if (!is_null($cliente->vetrina_id))
				
				{{-- DISEGNO LA VETRINA --}}	
				@component('components.log_view',['log_view' => $log_view])
        	VETRINA draw_item_cliente.slotVetrina
        @endcomponent

				@include('draw_item_cliente.slotVetrina',['slot' => $cliente, 'locale' => $locale, 'class_item' => 'snippet_offerta_generica slotVetrina'])
			
			@elseif ( $cliente instanceof App\VetrinaOffertaTopLingua ) 
				
				<?php
				$tipo_offerta = "offers"; 
				
				if ($cms_pagina->listing_offerta == 'offerta') {
					$tipo_offerta = "offers"; 
				}
				elseif ($cms_pagina->listing_offerta == 'lastminute') {
					$tipo_offerta = "lastminute";
				} 
				
				?>

				@component('components.log_view',['log_view' => $log_view])
        	VOT draw_item_cliente.vot_generico
        @endcomponent

				{{-- DISEGNO IL VOT --}}	
				@include('draw_item_cliente.vot_generico',['vot' => $cliente, 'locale' => $locale, 'class_item' => 'snippet_offerta_generica vot'])
			
			@endif
			
		@endforeach 

	</section>
	@endif
	
	<section id="listing" >
		@php
			$clienti_view = 0;
		@endphp
		@foreach ($clienti as $cliente)
	    	
	    	
				@if (is_null($cliente->vetrina_id) && !$cliente instanceof App\VetrinaOffertaTopLingua )
						
						@if ($cms_pagina->listing_coupon)
						
							@include('draw_item_cliente.draw_item_cliente_coupon_listing',array('class_item' => 'item_wrapper_cliente', 'image_path' => 'listing', 'image_listing' => $cliente->getListingImg('220x220', true)))
					   
				    @else
				        <?php
				        $tipo_offerta = "offers"; 
				        
				        if ($cms_pagina->listing_parolaChiave_id)
				          $offerte = $cliente->offerteLast;
				
				        elseif ($cms_pagina->listing_offerta == 'offerta') 
				        {
				          //$tipo_offerta = "offerta"; 
				          $offerte = $cliente->offerte; 
				          
				        } elseif ($cms_pagina->listing_offerta == 'lastminute') {

				          $tipo_offerta = "lastminute";
				          $offerte = $cliente->last;
				          
				        } ?>
				        
				        @component('components.log_view',['log_view' => $log_view])
				        	OFFERTA (con VOT)	{{$cliente->prezzo_to_order}} draw_item_cliente.draw_item_cliente_offerta_generica
				        @endcomponent

				        {{-- adesso i VOT sono disegnati QUI --}}
				        @include('draw_item_cliente.draw_item_cliente_offerta_generica',array('offerte' => $offerte,'class_item' => 'item_wrapper_cliente', 'image_path' => 'listing', 'image_listing' => $cliente->getListingImg('220x220', true)))
				        
				    @endif
					    
				@endif
		@php
			$clienti_view++;
		@endphp
		@endforeach 
		
		@component('components.log_view',['log_view' => $log_view])
			CLIENTI NELLA VIEW {{$clienti_view}}
		@endcomponent

	</section>
</div>


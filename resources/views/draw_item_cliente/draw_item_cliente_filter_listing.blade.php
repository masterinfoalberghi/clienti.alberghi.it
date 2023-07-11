<?php
	
	/**
	 * Costruisco i dati che mi servono
	 */
	
	if (!isset($class_item))
		$class_item = "";
		
	if(!isset($wishlist))
		$wishlist = 0;
	
	/**
	 * Definisco sempre chi sono per prima cosa
	 */
	 
	$class_item .= " draw_item_cliente_filter_listing";
	$tipoContenuto = "itemFilter";	
    $n_foto = $cliente->immagini_gallery_count;
    $noff 	= $cliente->numero_offerte_attive->isEmpty() ? 0 : $cliente->numero_offerte_attive->first()->tot;
    $nlast 	= $cliente->numero_last_attivi->isEmpty() ? 0 : $cliente->numero_last_attivi->first()->tot;
    $npp 	= $cliente->numero_pp_attivi->isEmpty() ? 0 : $cliente->numero_pp_attivi->first()->tot;
    $top 	= $cliente->offerteTop;
	
?>
	
<article class="click_all animation item-listing @if(isset($class_item)) {{$class_item}} @endif " data-url="{{$url}}"  data-id="{{$cliente->id}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}">
    
    @include("widget.item-figure")
	
	<div class="item-listing-content">
	
		@include("widget.item-title")
		
		<div class="item-listing-footer">

		
		</div>
			
		<div class="clearfix"></div>

		<div class="item-listing-middle">
			
			@if (!$wishlist)			
				@include("widget.item-favourite")
			@endif
			
			<div class="padding-top-3 padding-bottom-6">
			
                <div class="item-listing-pdf">
                    @include('composer.puntiDiForza')
                </div>
					
				@include('widget.caparre')
				<div class="clearfix"></div>
					
			</div>
				
			<div class="clearfix"></div>
			
            @include("draw_item_cliente.draw_item_offers")
            <div class="clearfix"></div>
			
		</div>
	
	</div>
	
	@if (!$wishlist)
		@include("widget.item-footer-min-max")
	@endif
		
	<div class="clearfix"></div>
			
</article>{{-- /.item_wrapper --}}



@include("chiuso")
		@include("widget.bonus-vacanze")
@include("widget.item-covid")
@include("widget.item-review")
	@php
	
		if(!isset($listing_gruppo_piscina))
			$listing_gruppo_piscina = 8;

		if(!isset($listing_gruppo_benessere))
			$listing_gruppo_benessere = 7;

		$n_foto = $cliente->immagini_gallery_count;
	
	@endphp

	@if (($class_item == "item_wrapper_vetrina" && $evidenza_vetrina == true) || ($cliente->getTop() && $evidenza_vetrina == true) )
		<div class="inVetrina">
	@endif

	<article data-id="{{$cliente->id}}" data-url="{{$url}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="draw_item_cliente_vetrina_listing link_item_slot @if(isset($class_item)) {{$class_item}} @endif ">
		
		@include("widget.item-figure", ["cliente" => $cliente, 'image_listing' => $image_listing ])
		
		<div class="col-text-draw">
			
			@include("widget.item-title", ["cliente" => $cliente, "locale" => $locale, "url" => $url])
			@include("widget.item-favourite")
			@include("widget.item-price", ["cliente" => $cliente])
			@include("widget.item-address", ["cliente" => $cliente])
			@include("chiuso")
            @include("widget.bonus-vacanze")
            @include("widget.item-review")
            @include("widget.item-covid")

			@if ($cms_pagina->listing_gruppo_servizi_id == $listing_gruppo_piscina && $cms_pagina->listing_categorie == '')
				@include('composer.infoPiscina')
			@elseif ($cms_pagina->listing_gruppo_servizi_id == $listing_gruppo_benessere && $cms_pagina->listing_categorie == '')
				@include('composer.infoBenessere')
			@else
				@include('composer.puntiDiForza')
				@include('widget.item-distance')
			 @endif

			 @include("widget.caparre")

		</div>
		<div class="clear"></div>

	</article>

	@if (($class_item == "item_wrapper_vetrina" && $evidenza_vetrina == true) || ($cliente->getTop() && $evidenza_vetrina == true) )
		</div>
	@endif

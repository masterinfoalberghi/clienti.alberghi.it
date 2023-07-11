	@php
	
		if (
			$cms_pagina->listing_gruppo_servizi_id != Config::get("services.listing_gruppo_piscina") && 
			$cms_pagina->listing_gruppo_servizi_id != Config::get("services.listing_gruppo_benessere") 
		) { 
			$tipoContenuto = "itemVetrina";
		} else if ($cms_pagina->listing_gruppo_servizi_id == Config::get("services.listing_gruppo_piscina")) {
			$tipoContenuto = "itemPiscina";
		} else if ($cms_pagina->listing_gruppo_servizi_id == Config::get("services.listing_gruppo_benessere")) {
			$tipoContenuto = "itemBenessere";
		}
		
		/**
		 * Logica per scrivere la distanza dalla piscina
		 */
		
		if ($tipoContenuto == "itemPiscina") {
			$label_piscina = "";
			if ($cliente->relationLoaded('servizi') && !is_null($cliente->servizi)):
				foreach ($cliente->servizi as $servizio):
					if ($servizio->translate_it->nome == 'piscina fuori hotel') {
						$label_piscina = Lang::get('listing.piscina') . " " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri') . " " . Lang::get('labels.dall_hotel'); break;
						}
					elseif ($servizio->translate_it->nome == 'piscina in spiaggia') 
						{
						$label_piscina = Lang::get('listing.piscina') . " " . Lang::get('listing.pos_spiaggia') . " " . Lang::get('listing.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri');
						 break;
						}
				endforeach;
			endif;
		}
		
		/**
		 * Logica per scrivere la distanza dalla SPA
		 */
		
		if ($tipoContenuto == "itemBenessere") {
			$label_spa = "";
			if ($cliente->relationLoaded('servizi') && !is_null($cliente->servizi)):
				foreach ($cliente->servizi as $servizio):
					if ($servizio->translate_it->nome == 'spa fuori hotel') {
						$label_spa = "SPA " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri') . " " . Lang::get('labels.dall_hotel');
						break;
					}
				endforeach;
			endif;
		}
		
		$n_foto = $cliente->immagini_gallery_count;

	@endphp
		
	@if (($class_item == "item_wrapper_vetrina" && $evidenza_vetrina == true) || ($cliente->getTop() && $evidenza_vetrina == true) )
		<div class="inVetrina">
	@endif
	
	<article data-id="{{$cliente->id}}" data-url="{{$url}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="draw_item_cliente_vetrina_listing link_item_slot @if(isset($class_item)) {{$class_item}} @endif ">
		
		@include("widget.item-figure", ["cliente" => $cliente, 'image_listing' => $image_listing ])
		
		@if(isset($label_piscina) && $label_piscina != "") <div class="info_distanza">{{$label_piscina}}</div> @endif
		@if(isset($label_spa) && $label_spa != "") <div class="info_distanza">{{$label_spa}}</div> @endif
		
		<div class="col-text-draw">

            @include("widget.item-title", 	["cliente" => $cliente, "locale" => $locale, "url" => $url])
			@include("widget.item-favourite")
		
			@include("widget.item-address", ["cliente" => $cliente])
			@include("chiuso")
            @include("widget.bonus-vacanze")
            @include("widget.item-review")
            @include("widget.item-covid")
			
			@if ($tipoContenuto == "itemPiscina" && $cms_pagina->listing_categorie == '')
				@include('composer.infoPiscina')
			@elseif ($tipoContenuto == "itemBenessere" && $cms_pagina->listing_categorie == '')
				@include('composer.infoBenessere')
			@else
				@include('composer.puntiDiForza')
				
			@endif
			
			@if ($tipoContenuto != "itemPiscina" && $tipoContenuto != "itemBenessere")
				@include('widget.caparre')
			@endif

            @include("widget.item-price", 	["cliente" => $cliente])
			
		</div>
		<div class="clear"></div>

	</article>
	
	@if (($class_item == "item_wrapper_vetrina" && $evidenza_vetrina == true) || ($cliente->getTop() && $evidenza_vetrina == true) )
		</div>
	@endif

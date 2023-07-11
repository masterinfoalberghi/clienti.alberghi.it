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
	 
	$class_item .= " draw_item_cliente_vetrina_listing";
	$tipoContenuto = "itemVetrina";
	$noff 	= 0;
	$nlast 	= 0;
	$npp 	= 0;
	$top 	= 0;
	
	if (
		isset($cms_pagina) &&
		$cms_pagina->listing_gruppo_servizi_id != Config::get("services.listing_gruppo_piscina") && 
		$cms_pagina->listing_gruppo_servizi_id != Config::get("services.listing_gruppo_benessere") && 
		$cms_pagina->listing_gruppo_servizi_id != Config::get("services.listing_gruppo_piscina_fuori")
	) { 

		$noff 	= $cliente->numero_offerte_attive->isEmpty() ? 0 : $cliente->numero_offerte_attive->first()->tot;
		$nlast 	= $cliente->numero_last_attivi->isEmpty() ? 0 : $cliente->numero_last_attivi->first()->tot;
		$npp 	= $cliente->numero_pp_attivi->isEmpty() ? 0 : $cliente->numero_pp_attivi->first()->tot;
		$top 	= $cliente->offerteTop;
		
		foreach($top as $t):
			
			if ($t->tipo == "lastminute")
				$nlast++;
				
			if ($t->tipo == "offerta")
				$noff++;
				
			if ($t->tipo == "prenotaprima")
				$npp++;
			
		endforeach;
				
	} else if (isset($cms_pagina) && ($cms_pagina->listing_gruppo_servizi_id == Config::get("services.listing_gruppo_piscina") || $cms_pagina->listing_gruppo_servizi_id == Config::get("services.listing_gruppo_piscina_fuori"))) {
		
		$tipoContenuto = "itemPiscina";
		$class_item .= " itemPiscina";
		
	} else if (isset($cms_pagina) && $cms_pagina->listing_gruppo_servizi_id == Config::get("services.listing_gruppo_benessere")) {
		
		$tipoContenuto = "itemBenessere";
		$class_item .= " itemBenessere";
		
	}

	$n_foto = $cliente->immagini_gallery_count;
	
	/**
	 * Sono un cliente TOP
	 */
	 
	$top 	= $cliente->getTop() != '' ? ' top' : '';  
	$cliente->gettEvidenzaBB() ? $top = ' top' : $top = '';
	$class_item .= $top;


/**
 * Logica per scrivere la distanza dalla piscina
 */
	
if ($tipoContenuto == "itemPiscina") 
	{

	$label_piscina = "";
	
  if ($cliente->relationLoaded('servizi') && !is_null($cliente->servizi)):
		foreach ($cliente->servizi as $servizio):
			
			if ($servizio->translate_it->nome == 'piscina fuori hotel')
				{

				$label_piscina = Lang::get('listing.piscina') . " " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri') . " " . Lang::get('labels.dall_hotel');
				 break;
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
	
	if ($tipoContenuto == "itemBenessere") 
		{

		$label_spa = "";


		if ($cliente->relationLoaded('servizi') && !is_null($cliente->servizi)):
			foreach ($cliente->servizi as $servizio):
				if ($servizio->translate_it->nome == 'spa fuori hotel')
					{
					$label_spa = "SPA " . Lang::get('labels.a') . " " . $servizio->pivot->note . " " . Lang::get('labels.metri') . " " . Lang::get('labels.dall_hotel');
					 break;
					}
			endforeach;
		endif;

		}

		
?>
	
<article class="click_all animation item-listing @if(isset($class_item)) {{$class_item}} @endif " data-url="{{$url}}"  data-id="{{$cliente->id}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}">
	@include("widget.item-figure")
	
	<div class="item-listing-content">
	
		@include("widget.item-title")
		
		<div class="item-listing-footer">
			
			@if ($cms_pagina->template == 'localita' || !empty($cms_pagina->listing_categorie) || $cms_pagina->listing_gruppo_servizi_id >0)
				{{-- se vengo da una pagina località con le vetrine --}}

			    @if (isset($slot->n_off_in_evidenza) && $slot->n_off_in_evidenza > 0)
					
					<div class="offertaInEvidenza">
						<strong>{{Utility::descrizioneOfferteInEvidenza($id_offerta)}}</strong>: {{$slot->n_off_in_evidenza}} @if($slot->n_off_in_evidenza ==1) offerta @else offerte @endif
					</div>

				@elseif(isset($cliente->n_off_in_evidenza) && $cliente->n_off_in_evidenza > 0)
			    	
			    	<div class="offertaInEvidenza">
			    		<strong>{{Utility::descrizioneOfferteInEvidenza($id_offerta)}}</strong>: {{$cliente->n_off_in_evidenza}} @if($cliente->n_off_in_evidenza ==1) offerta @else offerte @endif
			    	</div>

			    @endif

			
			@endif
		</div>
			
		<div class="clearfix"></div>
		

		<div class="item-listing-middle">

			
			@if (!$wishlist)			
				@include("widget.item-favourite")
			@endif
			
			<div class="padding-bottom-6">
			
				@if ($tipoContenuto == "itemPiscina" && $cms_pagina->listing_categorie == '')
				
					@include('composer.infoPiscina')
					
				@elseif ($tipoContenuto == "itemBenessere" && $cms_pagina->listing_categorie == '')
				
					@include('composer.infoBenessere')
					
				@else
					
					<div class="item-listing-pdf">
						@include('composer.puntiDiForza')
					</div>
					
				@endif

				@if ($tipoContenuto != "itemPiscina" && $tipoContenuto != "itemBenessere")
					@include('widget.caparre')
				@endif
				@if (!$cliente->caparre_attive_count)
				<div style="margin-top: 12px;"></div>
				@endif

				<div class="clearfix"></div>
					
			</div>
				
			<div class="clearfix"></div>
			
			@if ($tipoContenuto != "itemPiscina" && $tipoContenuto != "itemBenessere")
				
				@include("draw_item_cliente.draw_item_offers")
				<div class="clearfix"></div>
			
			@endif
			
		</div>
	
	</div>
	
	@if (!$wishlist)
		@include("widget.item-footer-min-max")
	@endif
		
	<div class="clearfix"></div>
			
</article>{{-- /.item_wrapper --}}



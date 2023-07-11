@php
	$url_hotel = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id );
	$url = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id). "&prenotaprima";

	/**
	 * Potrebbe avere dei VOT
			SE LI HA TUTTO LO SNIPPET PRENDE L'URL del VOT per non perdere
			CLICK nelle stats
	 */

	$vot = $cliente->getEvidenza();
	
	if($vot)
		{
		$url =Utility::getUrlWithLang($locale, "/vot/".$cliente->id."/".$vot->id);
		}

@endphp

<article data-id="{{$cliente->id}}" data-url="{{$url_hotel}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="draw_item_cliente_offerta_listing_prenota_prima link_item_slot link_item_slot_count @if(isset($class_item)) {{$class_item}} @endif">

	@include("widget.item-figure", ["cliente" => $cliente, 'image_listing' => $image_listing, 'url' => $url ])

	<div class="col-text-draw">

		@include("widget.item-title", 	["cliente" => $cliente, "locale" => $locale, "url" => $url_hotel])
		@include("widget.item-favourite")
		
		@include("widget.item-address", ["cliente" => $cliente])
		@include("chiuso")
        @include("widget.bonus-vacanze")
        @include("widget.item-review")
        @include("widget.item-covid")
		
		{{--  Offerte --}}
		
		@foreach ($cliente->offertePrenotaPrima as $offerteLast)
			@if ( $offerteLast->offerte_lingua->count() )
				@php 
					$off_lingua = $offerteLast->offerte_lingua->first(); 
					$url_hotel .= "&prenotaprima#" . $offerteLast->id;
				@endphp
				
				<a class="linktextoffer no_upper prenota-prima-listing" href="{{$url_hotel}}">
					<span class="trattamento">
						<span>
							-{{$offerteLast->perc_sconto}}%
						</span>
					</span>
					<span class="upper title-offer-listing"><b>{!!Utility::getExcerpt($off_lingua->titolo, $limit = 50)!!}</b>&nbsp;{{ trans('hotel.prenota_entro') }}&nbsp;<b class="prenota_entro">{{Utility::getLocalDate($offerteLast->prenota_entro, '%d %B')}}.</b></span>
					<br /><span class="date-validita">{{trans("listing.per_soggiorni_dal")}} <b>{{Utility::getLocalDate($offerteLast->valido_dal, '%d %B', $locale)}}</b> {{trans("listing.fino_al")}} <b>{{Utility::getLocalDate($offerteLast->valido_al, '%d %B', $locale)}}</b></span>
					
				</a>
                <div class="clear"></div>

			@endif
		@endforeach
		{{-- Fine Offerte --}}
		

		
		{{-- Offerta top --}}
		@if ($vot)
		<a class="linktextoffer no_upper prenota-prima-listing" href="{{$url}}">
			<span class="trattamento"><span>-{{$vot->offerta->perc_sconto}}%</span></span>
			<span class="upper title-offer-listing"><b>{!!Utility::getExcerpt($vot->titolo, $limit = 50)!!}</b>&nbsp;{{ trans('hotel.prenota_entro') }}&nbsp;<b class="prenota_entro">{{Utility::getLocalDate($vot->offerta->prenota_entro, '%d %B')}}.</b></span>
			<br /><span class="date-validita">{{trans("listing.per_soggiorni_dal")}} <b>{{Utility::getLocalDate($vot->offerta->valido_dal, '%d %B', $locale)}}</b> {{trans("listing.fino_al")}} <b>{{Utility::getLocalDate($vot->offerta->valido_al, '%d %B', $locale)}}</b></span>
			
		</a>
		@endif

        <div class="clear"></div> 
		{{-- Fine Offerta top --}}

	</div>
	<div class="clear"></div>
</article>

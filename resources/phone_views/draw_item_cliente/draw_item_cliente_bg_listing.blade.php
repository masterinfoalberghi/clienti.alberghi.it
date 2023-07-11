@php
	$url_hotel = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id);

	$vaat = $cliente->getEvidenza();

	if($vaat)
		{
		$url_vaat = Utility::getUrlWithLang($locale,"/vaat/".$cliente->id."/".$vaat->id);
		}

@endphp
<article data-id="{{$cliente->id}}" data-url="{{$url_hotel}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="draw_item_cliente_bg_listing link_item_slot link_item_slot_count @if(isset($class_item)) {{$class_item}} @endif">
	 
	 @include("widget.item-figure", ["cliente" => $cliente, 'image_listing' => $image_listing ])
	 
	 <div class="col-text-draw">
		
		@include("widget.item-title", ["cliente" => $cliente, "locale" => $locale, "url" => $url_hotel])
		@include("widget.item-favourite")
		{{-- @include("widget.item-price", ["cliente" => $cliente]) --}}
		@include("widget.item-address", ["cliente" => $cliente])
		@include("chiuso")
        @include("widget.bonus-vacanze")
        @include("widget.item-review")
        @include("widget.item-covid")
        
		
        
		@if ($vaat)
			<a class="linktextoffer no_upper bambini-gratis-listing" href="{{$url_vaat}}/{{$vaat->offerta->id}}">
				<b class="upper title-offer-listing">{{strtoupper(trans('hotel.gratis_fino_a'))}} <span class="rosso">{{strtoupper($vaat->offerta->_fino_a_anni())}} {{strtoupper($vaat->offerta->_anni_compiuti())}}</span></b><br />
				<span class="date-validita">{{trans("listing.per_soggiorni_dal")}} <b>{{Utility::myFormatLocalized($vaat->offerta->valido_dal, '%e %B', $locale)}}</b> {{trans("listing.fino_al")}} <b>{{Utility::myFormatLocalized($vaat->offerta->valido_al, '%e %B', $locale)}}</b></span>
				
			</a>
		@endif

        <div class="clearfix"></div>


		
		{{-- Offerte --}}
		
		@foreach ($cliente->bambiniGratisAttivi as $bg)
			<a class="linktextoffer no_upper bambini-gratis-listing" href="{{$url_hotel}}&children-offers#{{$bg->id}}">
				<b class="upper title-offer-listing">{{strtoupper(trans('hotel.gratis_fino_a'))}} <span class="rosso">{{strtoupper($bg->_fino_a_anni())}} {{strtoupper($bg->anni_compiuti)}}</span></b><br />
				<span class="date-validita">{{trans("listing.per_soggiorni_dal")}} <b>{{Utility::myFormatLocalized($bg->valido_dal, '%e %B', $locale)}}</b> {{trans("listing.fino_al")}} <b>{{Utility::myFormatLocalized($bg->valido_al, '%e %B', $locale)}}</b></span>
				
			</a>
		@endforeach
		
        <div class="clearfix"></div>
        
		{{-- Fine Offerte --}}

		
	</div>
	<div class="clear"></div>
</article>
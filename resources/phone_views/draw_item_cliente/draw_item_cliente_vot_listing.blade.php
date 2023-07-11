	@php
	   $url_hotel = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id);
	@endphp
	
	<article data-id="{{$cliente->id}}" data-url="{{$url_hotel}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="draw_item_cliente_vot_listing link_item_slot link_item_slot_count @if(isset($class_item)) {{$class_item}} @endif">

		@include("widget.item-figure", ["cliente" => $cliente, 'image_listing' => $image_listing ])

		<div class="col-text-draw">

			@include("widget.item-title", ["cliente" => $cliente, "locale" => $locale])
			@include("widget.item-favourite")

			{{--@if ($vot->offerta->tipo == 'prenotaprima')
				@include("widget.item-price", ["cliente" => "", "percentuale" => $vot->offerta->perc_sconto])
			@else
				@include("widget.item-price", ["cliente" => "", "prezzo" => $vot->offerta->prezzo_a_persona])
			@endif--}}

			@include("widget.item-address", ["cliente" => $cliente])
		
            @include('widget.item-distance')
            @include("chiuso")
            @include("widget.bonus-vacanze")
            @include("widget.item-review")
            @include("widget.item-covid")

			@if ($vot->offerta == "lastminute")
				@php $classe="lastminute-listing" @endphp
			@else
				@php $classe="offer-listing" @endphp
			@endif

			{{-- Offerta --}}

			<a class="linktextoffer no_upper {{$classe}}" href="{{$vot}}/{{$vot->offerta->id}}">

				<span class="trattamento"><span>{!!Utility::setPriceFormat($vot->offerta->prezzo_a_persona)!!}</span></span>
				<span class="upper title-offer-listing">{!!Utility::getTrattamentoOfferte($vot->offerta->formula, $beforeIn = false)!!}&nbsp;<b>{!!Utility::getExcerpt($vot->titolo, $limit = 50)!!}</b></span>
				<br/><span class="date-validita">{{trans("listing.per_soggiorni_dal")}} <b>{{Utility::getLocalDate($vot->offerta->valido_dal, '%d %B', $locale)}}</b> {{trans("listing.fino_al")}} <b>{{Utility::getLocalDate($vot->offerta->valido_al, '%d %B', $locale)}}</b></span>
				
			</a>

			{{-- Fine Offerta --}}
			@include("widget.caparre")

		</div>
		<div class="clear"></div>

	</article>

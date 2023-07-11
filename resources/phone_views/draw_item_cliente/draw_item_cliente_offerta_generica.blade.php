@php
	$url_hotel = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id );
	$url = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id). "&" . $tipo_offerta;



	/**
	 * Potrebbe avere dei VOT
			SE LI HA TUTTO LO SNIPPET PRENDE L'URL del VOT per non perdere
			CLICK nelle stats
	 */

	$vot = $cliente->getEvidenza();
	
	if($vot)
		{
		$url = Utility::getUrlWithLang($locale,"/vot/".$cliente->id."/".$vot->id);
		}


@endphp

<article data-id="{{$cliente->id}}" data-url="{{$url_hotel}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="draw_item_cliente_offerta_generica_listing link_item_slot link_item_slot_count @if(isset($class_item)) {{$class_item}} @endif">
	
	@include("widget.item-figure", ["cliente" => $cliente, 'image_listing' => $image_listing, 'url' => $url ])
	
	<div class="col-text-draw">
		
		@include("widget.item-title", 	["cliente" => $cliente, "locale" => $locale, "url" => $url_hotel])
		@include("widget.item-favourite")
			
		@include("widget.item-address", ["cliente" => $cliente])
		@include("chiuso")
        @include("widget.bonus-vacanze")
        @include("widget.item-review")
        @include("widget.item-covid")

		@if ($vot)
			@if ($vot->offerta == "lastminute")
				@php $classe="lastminute-listing" @endphp
			@else
				@php $classe="offer-listing" @endphp
			@endif
      
			<a class="linktextoffer no_upper {{$classe}}" href="{{$url}}/{{$vot->offerta->id}}">
				
				<span class="trattamento"><span>{!!Utility::setPriceFormat($vot->offerta->prezzo_a_persona)!!}</span></span>
				<span class="title-offer-listing upper">{!!Utility::getTrattamentoOfferte($vot->offerta->formula,$beforeIn = false)!!}&nbsp;<b>{!!Utility::getExcerpt($vot->titolo, $limit = 50)!!}</b></span>
				<br /><span class="date-validita">{{trans("listing.per_soggiorni_dal")}} <b>{{Utility::getLocalDate($vot->offerta->valido_dal, '%d %B', $locale)}}</b> {{trans("listing.fino_al")}} <b>{{Utility::getLocalDate($vot->offerta->valido_al, '%d %B', $locale)}}</b></span>
				
			</a>

            <div class="clearfix"></div>
		@endif
		
		{{-- Offerte --}}
		@foreach ($offerte as $offerteLast)
			
			@if ($offerteLast->tipologia == "lastminute")
				@php 
					$classe="lastminute-listing";
					$url_hotel .= '&lastminute';
				@endphp
			@else
				@php 
					$classe="offer-listing";
					$url_hotel .= '&offers';
				@endphp
			@endif
			
			@if ( $offerteLast->offerte_lingua->count() )	
				@php $off_lingua = $offerteLast->offerte_lingua->first(); @endphp
		
				<a class="linktextoffer no_upper {{$classe}}" href="{{$url_hotel}}#{{$offerteLast->id}}">
					
					<span class="trattamento"><span>{!!Utility::setPriceFormat($offerteLast->prezzo_a_persona)!!}</span></span>
					<span class="title-offer-listing upper">{!!Utility::getTrattamentoOfferte($offerteLast->formula, $beforeIn = false)!!}&nbsp;<b>{!!Utility::getExcerpt($off_lingua->titolo, $limit = 50)!!}</b></span>
					<br /><span class="date-validita">{{trans("listing.per_soggiorni_dal")}} <b>{{Utility::getLocalDate($offerteLast->valido_dal, '%d %B', $locale)}}</b> {{trans("listing.fino_al")}} <b>{{Utility::getLocalDate($offerteLast->valido_al, '%d %B', $locale)}}</b></span>			
									
				</a>
                <div class="clearfix"></div>
				
			@endif
		@endforeach
		{{-- Fine Offerte --}}
		
	


	</div>
<div class="clear"></div>
</article>

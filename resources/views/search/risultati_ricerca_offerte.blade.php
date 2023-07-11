@if(isset($offerte) && count($offerte))

<section class="padding-top padding-bottom container ricerca">

	<header class="row">
		<h2 class="col-sm-12">{{trans("title.offerte")}}</h2>
	</header>

	@foreach ($offerte as $offerta)
		@if ($offerta->tipologia == 'offerta')
			@php
				$tipologia = "offers";
			@endphp
		@else
			@php
				$tipologia = $offerta->tipologia;
			@endphp
		@endif
		
		@php
			$cliente = Utility::getHotelFromOfferta($offerta->id);
		@endphp

		<article class="row padding-bottom-2 item-ricerca" style="padding-bottom: 100px;">
			
			<div class="col-xs-3">
				<figure>
					<img src="{{Utility::asset($cliente->getListingImg('360x200', true))}}"  alt="{{{$cliente->nome}}}">
				</figure>
			</div>
			
			<div class="col-xs-9">
				<header class="item-listing-title">
					<a class="r_nome_hotel" href="{{ url('hotel.php?id='.$cliente->id) }}">
						<h2>{{ $cliente->nome }}</h2>
					</a>
					<span class="rating">{{{$cliente->stelle->nome}}}</span>
					<br>
					<address class="item-listing-address "><span class="localita"><i class="icon-location"></i> {{ $cliente->localita->nome }}</span></address>				
					
					<header class="item-listing-title">
							<a class="r_nome_hotel" href="{{ url('hotel.php?id='.$offerta->hotel_id.'&'.$tipologia) }}">
								<h2>{!! strtoupper($offerta->titolo) !!}</h2>
							</a>
					</header>
					
                    {{-- <div class="uri">{{ url('hotel.php?id='.$offerta->hotel_id) }}</div> --}}
                    
					@if($offerta->testo)
                        <p>{!! substr(strip_tags($offerta->testo),0,300) !!}
                            <a href="{{ url('hotel.php?id='.$offerta->hotel_id.'&'.$tipologia) }}">{{trans("hotel.leggi_tutto")}}</a>
                        </p>
                    @endif
				
				</header>
				
			</div>	
			
		</article>
	
	@endforeach

</section>

@endif

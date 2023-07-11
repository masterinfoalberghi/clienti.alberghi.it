@if(isset($hotels) && count($hotels))

<section class="padding-top padding-bottom container ricerca">

	<header class="row">
		<h2 class="col-sm-12">{{trans("title.lista")}}</h2>
	</header>
	
	@foreach ($hotels as $cliente)
		
		<article class="row item-ricerca">
			
			<div class="col-xs-3">
				<figure>
					<img src="{{Utility::asset($cliente->getListingImg('360x200', true))}}"  alt="{{{$cliente->nome}}}">
				</figure>
			</div>
			
			<div class="col-xs-9">
				<header class="item-listing-title">
					<hgroup>
					
					<a class="r_nome_hotel" href="{{ Utility::getUrlWithLang($locale, '/hotel.php?id='.$cliente->id) }}">
						<h2>{{ $cliente->nome }}</h2>
					</a>
					<span class="rating">{{{$cliente->stelle->nome}}}</span><br />
					
					<address class="item-listing-address ">
						<span class="localita"><i class="icon-location"></i> {{{ $cliente->localita->nome }}}</span> - <span  class="indirizzo">{{ $cliente->indirizzo }}</span>
					</address>
                    @include("widget.bonus-vacanze")
                    @include("widget.item-review")
                    @include("widget.item-covid")
					<hgroup>
				</header>
			
				
				<div class="item-listing-pdf">
					@include('composer.puntiDiForza')
				</div>
								
			</div>
			
			
		</article>

	@endforeach

</section>

@endif
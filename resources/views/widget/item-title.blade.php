
	<header class="item-listing-title">
		<hgroup>
			
			<a href='{{$url}}'><h2>{{$cliente->nome}}</h2></a>
			<span class="rating padding-right-2">{{{$cliente->stelle->nome}}}</span>
			
			<address class="item-listing-address">
		
				<span class="hide indirizzo">{{{ $cliente->indirizzo }}}</span>
				<span class="hide cap">{{{ $cliente->cap }}}</span>
				<span class="hide macrolocalita">{{{ $cliente->localita->nome.' ('. $cliente->localita->prov . ')' }}}</span>
				<span class="localita"><i class="icon-location"></i> {{{ $cliente->localita->nome }}}</span> - <span  class="indirizzo">{{ $cliente->indirizzo }}</span>
				
            </address>
            
            @if (isset($tipoContenuto) && $tipoContenuto != "itemPiscina" && $tipoContenuto != "itemBenessere")			
				@include("widget.item-distance")
			@endif
			
            @include("widget.bonus-vacanze")
            @include("widget.item-review")
            @include("widget.item-covid")

	
		</hgroup>
    </header>
    

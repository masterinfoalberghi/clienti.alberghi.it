<div class="info-listing">
	<div class="nomehotel ">
		<header class="item-name tover">
			<h2>
				<a href='{{$url}}'>{{{$cliente->nome}}}</a>
			</h2>
		</header>
		<span class="rating">
			@if (isset($cliente->stelle))
			<meta content="{{$cliente->stelle->ordinamento}}" />{{$cliente->stelle->nome}}
			@endif
		</span>
	   <br /><small>{{{ $cliente->localita->nome }}} - {{trans("labels.spiaggia")}} m<b>{{round($cliente->distanza_spiaggia)}}</b> - {{trans("labels.stazione")}} <b>km {{round($cliente->distanza_staz)}}</b></small>
	</div>
</div>
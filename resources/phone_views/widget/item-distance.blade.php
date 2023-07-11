<footer class="distanze-list">
	
	<div class="dist-listing">
		@if ( Utility::getDistanzaDalCentroPoi($cliente) == trans("labels.in_centro") )
			<span class="distance"><b>{{trans("labels.in_centro")}}</b></span>
		@else
			<span class="distance">{{trans("labels.centro")}} <b>{{Utility::getDistanzaDalCentroPoi($cliente)}} </b></span>
		@endif
	</div>				
	
	<div class="dist-listing">
		<span class="distance">{{trans("labels.spiaggia")}} <b>m {{round($cliente->distanza_spiaggia)}}</b></span>
	</div>
	
	<div class="dist-listing">
		<span class="distance">{{trans("labels.stazione")}} <b>km {{round($cliente->distanza_staz)}}</b></span>
	</div>
	
	{{--
	<div class="dist-listing">
		<span class="distance">{{trans("labels.fiera")}} <b>km {{round($cliente->distanza_fiera)}}</b></span>
	</div>
	--}}

	<div class="clear"></div>
	
</footer>
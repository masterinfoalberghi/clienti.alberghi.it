<div class="item-listing-distance ">
						
	@if ( Utility::getDistanzaDalCentroPoi($cliente) == trans("labels.in_centro") )
		<span class="distance"><b>{{trans("labels.in_centro")}}</b></span>&nbsp;&nbsp;
	@else
		<span class="distance">{{trans("labels.centro_tab")}}:   <b>{{Utility::getDistanzaDalCentroPoi($cliente)}} </b></span>&nbsp;&nbsp;
	@endif
	<span class="distance">{{trans("labels.spiaggia_tab")}}: <b>m {{round($cliente->distanza_spiaggia)}}</b></span>
	<span class="distance">{{trans("labels.stazione_tab")}}: <b>km {{$cliente->distanza_staz}}</b></span>
	<span class="distance">{{trans("labels.fiera_tab")}}:	 <b>km {{ round($cliente->distanza_fiera) > 0 ? round($cliente->distanza_fiera) : $cliente->distanza_fiera }}</b></span>
	
</div> 



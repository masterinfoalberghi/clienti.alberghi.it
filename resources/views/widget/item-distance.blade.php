
<div class="item-listing-distance ">
    <i class="icon-map-o"></i>
	@if ( Utility::getDistanzaDalCentroPoi($cliente) == trans("labels.in_centro") )
		<a href="#" @if (!empty($cliente->localita->centro_coordinate_note)) title="{{$cliente->localita->centro_coordinate_note}}" class="tooltip" @endif><span class="distance"><b>{{trans("labels.in_centro")}}</b></span></a>&nbsp;&nbsp;
	@else
		<a href="#" @if (!empty($cliente->localita->centro_coordinate_note)) title="{{$cliente->localita->centro_coordinate_note}}" class="tooltip" @endif><span class="distance">{{trans("labels.centro")}}: <b>{{Utility::getDistanzaDalCentroPoi($cliente)}} </b></span></a>&nbsp;&nbsp;
	@endif						
	
	<span class="distance">{{trans("labels.spiaggia")}}: <b>{{round($cliente->distanza_spiaggia)}} m </b></span>&nbsp;&nbsp;
	
	<a href="" @if (!empty($cliente->localita->staz_coordinate_note)) title="{{$cliente->localita->staz_coordinate_note}}" class="tooltip @endif"><span class="distance">{{trans("labels.stazione")}}: <b>{{($cliente->distanza_staz)}} km</b></span></a>&nbsp;&nbsp;
	
	{{-- <a href="#" title="Fiera di Rimini" class="tooltip"><span class="distance">{{trans("labels.fiera")}}:	 <b>km {{ round($cliente->distanza_fiera) > 0 ? round($cliente->distanza_fiera) : $cliente->distanza_fiera }}</b></span></a>&nbsp;&nbsp; --}}
</div> 

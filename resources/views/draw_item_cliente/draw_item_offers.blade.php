@if ($noff>0 || $nlast>0 || $npp>0)
	<div class="padding-top-4 padding-bottom-2">
@else
	<div class="padding-top-0">
@endif
	
	@if ($noff>0)
		<span  class="tag offers">{{ trans('hotel.offerte_generiche_box') }} <span class="badge">{{$noff}}</span></span>
	@endif

	@if ($nlast>0)
		<span  class="tag last">{{ trans('hotel.last') }} <span class="badge">{{$nlast}}</span></span>
	@endif

	@if ($npp>0)
		<span  class="tag pp">{{ trans('hotel.offerte_prenota_prima_box') }} <span class="badge">{{$npp}}</span></span>
	@endif

	<div class="clearfix"></div>
	
</div>
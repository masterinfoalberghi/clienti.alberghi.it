@if ($sub_target)
	<span class="title-servizi" ">{{trans("labels.parametri")}}</span>
	@foreach ($sub_target as $tag)
		<span class="tag">{!! $tag !!}</span>
	@endforeach
@endif
<br />
@if ($localita_tag)
	<span class="title-servizi" ">{{trans("listing.localita")}}</span>
	@foreach ($localita_tag as $tag)
		<span class="tag white" >{!! $tag !!}</span>
	@endforeach
@endif
<br />
@if ($tag_servizi)
	<span class="title-servizi">{{trans("hotel.servizi")}}</span>
	@foreach ($tag_servizi as $tag)
		<span class="tag_servizi">{!! $tag !!}</span>
	@endforeach
@endif
<div class="clear"></div>


<div class="cerca_button">
				
	<div class="left">
		<a style="padding:10px 20px;" class="button green mailmultipla" href="{{url("/ricerca_avanzata.php")}}">{{strtoupper(trans('labels.cambia_parametri'))}}</a>
	</div>
	
	<div class="right">
		<a href="{{url("/trova_hotel.php")}}" class="link opzioni">{{ trans("labels.ric_nome") }} &#8594;</a>
	</div>
	
	<div class="clear"></div>
</div>

<!-- <a href="">Ricerca per nome &#8594;</a> -->
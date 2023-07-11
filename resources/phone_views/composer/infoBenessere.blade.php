
@if (isset($scheda) !== true)

	<table class="row-info-piscina row-table content row-info-table">
		
@else
	
	@if ($paragrafo->title) <h4>{!!$paragrafo->title!!}</h4> @endif
	@if ($paragrafo->subtitle) <h5>{!!$paragrafo->subtitle!!}</h5> @endif
	@if ($paragrafo->testo) 
	
		@if ($paragrafo->mirror != "")
			<div>{!! $paragrafo->mirror !!}... <a href="#" class="readmorescheda">{{trans("hotel.leggi_tutto")}}</a><br /><br /></div>
		@else 
			<div>{!! Utility::getExcerpt($paragrafo->testo, $limit = 20, $strip = true) !!} <a href="#" class="readmorescheda">{{trans("hotel.leggi_tutto")}}</a><br /><br /></div>
		@endif
		
		<div style="display:none;">{!! $paragrafo->testo !!}<br /><br /></div>
		
	@endif
	
	@if (isset($fotoSpa) && $fotoSpa->count())	
				
		<img src="{{Utility::asset('/images/gallery/360x200/' . $fotoSpa->first()->foto)}}" style="display: block; vertical-align: middle; width:100%; height:auto;" />
	
	@endif

	@if($label_spa)
		<span style="background:#d90000; padding:0px 10px;margin:0px 0 15px 0; text-align: center;  color:#fff; display:block; font-size: 12px; text-transform: uppercase;">{{$label_spa}}</span>	
	@endif
	
	<table class="row-info-piscina row-table content row-piscina-table">
		
@endif

	@foreach ($info as $row => $row_elements)
	<tr>
		<th>{{$row}}</th>
		@if(is_array($row_elements))
			<td>{!! implode(", " , $row_elements) !!}</td>
		@else
			<td>{!! $row_elements !!}</td>
		@endif
	</tr>
	
@endforeach
	
</table>

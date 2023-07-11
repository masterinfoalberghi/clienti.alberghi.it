@if (isset($scheda) !== true)

	<table class="row-info-piscina row-table content row-info-table">
		
@else
	
	@if ($titolo) <h3 style="padding:0; ">{!!$titolo!!}</h3> @endif
	@if ($sottotitolo) <h4 style="padding:0;">{!!$sottotitolo!!}</h4> @endif
	
	<table class="row-table content row-piscina-table">
		
	@if (isset($fotoSpa) && $fotoSpa->count())	
		
		<tr>
			<td colspan="2" style="background:#fff; padding:0 ; ">
				
				<div style="float:left; margin:0 15px 15px 0">
					<img src="{{Utility::asset('/images/gallery/360x200/' . $fotoSpa->first()->foto)}}" style="display: block; vertical-align: middle" />
					@if($label_spa)
						<span style="background:#d90000; padding:0px 10px; text-align: center;  color:#fff; display:block; font-size: 12px; text-transform: uppercase;">{{$label_spa}}</span>	
					@endif
				</div>
				
				@if ($testo) <p>{!!$testo!!}</p> @endif
	
			</td>
		</tr>
	
	@else
		
		@if($label_spa)
			<span style="background:#d90000; padding:0px 10px; text-align: center;  color:#fff; display:block; font-size: 12px; text-transform: uppercase;">{{$label_spa}}</span>	
		@endif
		<tr>
			<td colspan="2" style="background:#fff; padding:0 0 15px 0; ">
				@if ($testo) <p>{!!$testo!!}</p> @endif
			</td>
		</tr>
	
	@endif
		
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


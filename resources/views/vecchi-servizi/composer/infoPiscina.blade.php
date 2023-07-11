@if (isset($scheda) !== true)
	
	
	<table class="row-info-piscina row-table content row-info-table">
		
@else 
	
	@if ($titolo) <h3 style="padding:0; ">{!!$titolo!!}</h3> @endif
	@if ($sottotitolo) <h4 style="padding:0;">{!!$sottotitolo!!}</h4> @endif
	
	@if (isset($fotoPiscina) && $fotoPiscina->count())

	<table class="row-table content row-piscina-table">
		<tr>
			<td colspan="2" style="background:#fff; padding:0; ">
				
				<div style="float:left; margin:0 15px 15px 0">
					<img src="{{Utility::asset('/images/gallery/360x200/' . $fotoPiscina->first()->foto)}}" style="vertical-align: middle" />
					@if($label_piscina)
						<span style="background:#d90000; padding:0px 10px;  text-align: center;  color:#fff; display:block; font-size: 12px; text-transform: uppercase;">{{$label_piscina}}</span>	
					@endif
				</div>
				
				@if ($testo) <p>{!!$testo!!}</p> @endif
	
			</td>
		</tr>
	
	@else
	
		@if($label_piscina)
			<span style="background:#d90000; padding:0px 10px;margin:5px 0 10px 0; text-align: center;  color:#fff; display:inline-block; font-size: 12px; text-transform: uppercase;">{{$label_piscina}}</span>	
		@endif
		
		@if ($testo) <p>{!!$testo!!}</p> @endif
		<br />
		<table class="row-table content row-piscina-table">
		
	@endif
		
@endif
	
	@foreach ($info as $row => $row_elements)
		@if ($row == "-")
		
			<tr>
				<td colspan="2" class="intestazione_tr"><b>{{$row_elements}}</b></td>
			</tr>
		
		@else

			<tr>
				<th>{{$row}}</th>
				@if(is_array($row_elements))
					<td>{!! implode(", " , $row_elements) !!}</td>
				@else
					<td>
						
						@if ($row == trans('listing.caratteristiche') || $row == trans('listing.caratter'))
							@php
								if (count($info_vasca)) 
									{
									$row_elements .= ', <b class="evidenziato">'. trans("listing.vasca_b").'</b>';
									}
								if (count($info_idro)) 
									{
									$row_elements .= ', <b class="evidenziato">'. trans("listing.vasca_idro").'</b>';
									}
							@endphp
						@endif
							
						{!! $row_elements !!}	
							
					</td>
				@endif
			</tr>
		
		@endif
		
	@endforeach
	
	@if (isset($scheda) === true)

		@foreach ($info_vasca as $row => $row_elements)
			
			@if ($row == "-")
			
				<tr>
					<td colspan="2" class="intestazione_tr"><b>{{$row_elements}}</b></td>
				</tr>
			
			@else
			
				<tr>
					<th>{{$row}}</th>
					@if(is_array($row_elements))
						<td>{!! implode(", " , $row_elements) !!}</td>
					@else
						<td>{!! $row_elements !!}</td>
					@endif
				</tr>
			
			@endif
		
		@endforeach
		
		@foreach ($info_idro as $row => $row_elements)
			
			@if ($row == "-")
			
				<tr>
					<td colspan="2" class="intestazione_tr"><b>{{$row_elements}}</b></td>
				</tr>
			
			@else
			
				<tr>
					<th>{{$row}}</th>
					@if(is_array($row_elements))
						<td>{!! implode(", " , $row_elements) !!}</td>
					@else
						<td>{!! $row_elements !!}</td>
					@endif
				</tr>
			
			@endif
		
		@endforeach

	
	@endif
	

</table>

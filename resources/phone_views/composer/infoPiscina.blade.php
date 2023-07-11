@if (isset($scheda) !== true)

	<table class="row-info-piscina row-table content row-info-table">
		
@else
			
	@if ($paragrafo->title) <h4>{!!$paragrafo->title!!}</h4> @endif
	@if ($paragrafo->subtitle) <h5>{!!$paragrafo->subtitle!!}</h5> @endif
			
	@if ($paragrafo->testo) 
	
		@if ($paragrafo->mirror != "")
			<div>{!! $paragrafo->mirror !!}... <a href="#" class="readmorescheda">{{trans("hotel.leggi_tutto")}}</a><br /><br /></div>
		@else 
			<div>{!! Utility::getExcerpt($paragrafo->testo, $limit = 20) !!} <a href="#" class="readmorescheda">{{trans("hotel.leggi_tutto")}}</a><br /><br /></div>
		@endif
		
		<div style="display:none;">{!! $paragrafo->testo !!}<br /><br /></div>
		
	@endif
	
		
	@if (isset($fotoPiscina) && $fotoPiscina->count())
	
		<img src="{{Utility::asset('/images/gallery/360x200/' . $fotoPiscina->first()->foto)}}" style="vertical-align: middle; width:100%; height:auto; " />

	@endif
	
	@if($label_piscina)
		<span style="background:#d90000; padding:0px 10px; margin: 0 0 15px 0; color:#fff; display:block; font-size: 12px; text-align: center; text-transform: uppercase;">{{$label_piscina}}</span>	
	@endif

	
	<table class="row-info-piscina row-table content row-piscina-table">
		
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
					<td>
						@if (isset($scheda) !== true)
							{!!Utility::getExcerpt(implode(", " , $row_elements), $limit = 20)!!}
						@else
							{!!implode(", " , $row_elements)!!}
						@endif
					</td>
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
						<td>
							{!! $row_elements !!}
						</td>
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
						<td>
							{!! $row_elements !!}
						</td>
					@endif
				</tr>
			
			@endif
		
		@endforeach	
		
	
		
	
	@endif
	
</table>

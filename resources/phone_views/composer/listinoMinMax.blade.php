<?php

// visualizzo il listino con i prezzi min e max
// @parameters : $listinoMinMax (collection listinoMinMax)

$titolo_scritto = false;

?>

@if (isset($listinoMinMax) && count($listinoMinMax) > 0)
<div class="row" id="listinoMinMax">
	
	@foreach ($listinoMinMax as $listino)
			@if (!$titolo_scritto)
				<div class="col-xs-12">
					<h2>{{$titolo}}</h2>
				</div>
			@endif

			@php $titolo_scritto = true; @endphp
			
			<div class="col-xs-12 listino_items">
				<div class="listino_periodo">
					{{Utility::getLocalDate($listino->periodo_dal, '%d %B')}} / {{Utility::getLocalDate($listino->periodo_al, '%d %B')}}
				</div>
				@foreach (['ai','pc','mp','bb','sd'] as $t)
					@php
						$column_min = 'prezzo_'.$t.'_min'; 
						$column_max = 'prezzo_'.$t.'_max';
						$label = 'hotel.trattamento_'.$t; 
					@endphp
					@if ( ($listino->$column_min != '' && $listino->$column_min != '/') || ($listino->$column_max != '' && $listino->$column_max != '/') )
					<div class="listino_item"><span>
						{{ (trans($label)) }}</span>
						<span class="price">
						@if ($listino->$column_min != '' && $listino->$column_min != '/')
							<span class="min_price">min</span> {!! Utility::setPriceFormat($listino->$column_min) !!}
							<br />
						@endif
						@if ($listino->$column_max != '' && $listino->$column_max != '/')
						<span class="max_price">max</span>@endif {!! Utility::setPriceFormat($listino->$column_max) !!}</span>
							<div class="clear"></div>
					</div>
					@endif
				@endforeach
			</div>		
	@endforeach

</div>
@endif


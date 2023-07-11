<?php 
/**
 *
 * visualizzo servizi associati all'hotel:
 * @parameters: cat_servizi (array ctaegorie e servizi in lingua corrispondenti)
 *
 */ ?>


@if (count($cat_servizi))
	<div class="servizi">
		@php
			$count = 0;
		@endphp
		@foreach ($cat_servizi as $categoria_nome => $array_servizi)
			
			@php 
			$key = strtolower(str_replace(' ', '_',$categoria_nome)); 
			@endphp 
			
			{{-- @if ($key != "servizi_gratuiti" ) --}}
			
			<div class="column">
				<span @if ($count == 0) class="first" @endif>
				{{trans('hotel.'.$key)}}
				</span>
				
				@foreach($array_servizi as $servizio)
					<li><i class="icon-ok"></i> {{trim($servizio)}};</li>
				@endforeach
			</div>			

			@php
				$count++;
			@endphp
			
			{{-- @endif --}}
			
		@endforeach
		
	<div class="clearfix"></div>
	</div>
	
@endif

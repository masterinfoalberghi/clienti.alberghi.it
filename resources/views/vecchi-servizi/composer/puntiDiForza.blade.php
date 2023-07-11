<?php

// visualizzo i punti di forza 
// @parameters : $array_punti_di_forza 



?>
@if (!empty($array_punti_di_forza))
	
	@if ($in_hotel)
		
		<div class="padding-bottom">
			
			<header>
				<h3>{{ trans('labels.9punti_forza') }}</h3>
			</header>
			
			<div class="item-listing-pdf">
				<span>{!! implode("<span></span></span>  <span>" , $array_punti_di_forza) !!}</span>
			</div>
			
		</div>
	
	@else
	
		<span>{!! implode(" <span></span></span>  <span>" , $array_punti_di_forza) !!}</span> 
			
	@endif

@endif

<?php /*
@if (!empty($array_punti_di_forza))
	
	@if ($in_hotel)
		
		<div class="padding-bottom">
			
			<header>
				<h3>{{ trans('labels.9punti_forza') }}</h3>
			</header>
			
			<div class="item-listing-pdf">
				<span class="tag pdf">{!! implode('</span><span class="tag pdf">' , $array_punti_di_forza) !!}</span>
			</div>
			
		</div>
	
	@else
	
		<span class="tag pdf">{!! implode('</span><span class="tag pdf">' , $array_punti_di_forza) !!}</span> 
			
	@endif

@endif */ ?>






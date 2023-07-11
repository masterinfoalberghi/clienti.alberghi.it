<?php

// visualizzo i punti di forza 
// @parameters : $array_punti_di_forza, $pathDeviceType (mi dice se sono nella vista del mobile oppure no)
?>

@if (!empty($array_punti_di_forza))
  	
	@if ($in_hotel)
	
	<div class="row" id="puntiforza">
		<h3>{{ $titolo }}</h3>
		<ul class="inline-list">
			@foreach ($array_punti_di_forza as $element)
				<li><i class="icon-ok"></i> {!! $element !!}</li>
			@endforeach
		</ul>
	</div>
	
	@else
	
		{{-- nella versione mobile sia nel listing che gli hotel simili hanno la stessa classe --}}
		<?php $array_punti_di_forza_6 = array_slice($array_punti_di_forza, 0, 4) ?>
		
		<div class="puntiforza-listing">
			
			<?php /* @foreach ($array_punti_di_forza_6 as $element) */ ?>
				<div class="puntiforza" >
                    <i class='icon-ok'></i> {!! implode(" <br /><i class='icon-ok'></i> " ,$array_punti_di_forza_6) !!}<br />
                    {{-- @if ( $cliente->bonus_vacanze_2020 )
                        <i class='icon-ok'></i> <b style="color:#f44336">Bonus vacanze accettato</b></span>
                    @endif --}}
                </div>
			<?php /* @endforeach */ ?>
		
		</div>
	
	
	@endif
		
	
@endif 

<?php

// visualizzo i poi
// @parameters : $array_poi (array associativo nome => distanza)
// 		 $titolo (header titolo oppure '')


?>




@if (count($array_poi))

	<div class="row" id="puntiinteresse">
		<h3>{{ trans("hotel.distanze_poi") }}</h3>
		<ul>
			@foreach ($array_poi as $nome => $distanza)
				<li><img src='{{Utility::asset("/mobile/img/indirizzo.svg")}}'  />{{$nome}} <strong>{{$distanza[0]}}</strong></li>
			@endforeach
		</ul>        
				
	</div>

@endif
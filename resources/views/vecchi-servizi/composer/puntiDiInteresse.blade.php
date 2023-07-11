
<?php

// visualizzo i poi
// @parameters : $array_poi (array associativo nome => distanza)
// 		 $titolo (header titolo oppure '')
?>
@if (count($array_poi) > 0)

	@if(isset($mappa_model))

		@foreach ($array_poi as $nome => $distanza) 

			<div style="display: block; position: relative; ">
				@if( $nome != "Spiaggia")
					<a data-title="{{$nome}}" data-lat="{{$distanza[1]}}" data-lon="{{$distanza[2]}}" data-dis="{{$distanza[3]}}" class="marker_poi" href="#">
					    <i class="icon-location"></i>{{$nome}} <strong>{{$distanza[0]}}</strong>
					</a> <span href="#" class="closePDFModel"><i class="icon-cancel"></i></span>
				@else
					<span class="marker_poi"><i class="icon-location"></i>{{$nome}} <strong>{{$distanza[0]}}</strong></span>
				@endif
				<div class="clearfix"></div>
			</div>

		@endforeach

	@else

		<div class="row">
		  	
			<?php $count_poi = 1; ?>
			
			<ul class="poi col-md-6 col-sm-6">
			@foreach ($array_poi as $nome => $distanza) 
		
			    @if ($count_poi % 7 == 0) 
			    	</ul><ul class="poi col-md-6 col-sm-6">
				    <?php $count_poi = 1; ?>	
			    @endif
			    
			    <li><i class="icon-location"></i>
			    	<?php /*<a data-title="{{$nome}}" data-lat="{{$distanza[1]}}" data-lon="{{$distanza[2]}}" data-dis="{{$distanza[3]}}" class="marker_poi" href="#">*/ ?>
			    		{{$nome}} <strong>{{$distanza[0]}}</strong>
			    	<?php /*</a>*/ ?>
			    </li>
				<?php $count_poi++; ?>
				
		    @endforeach

		    </ul>
		    <div class="clearfix"></div>

		</div>
		
		<br />

		<div class="row"> 
		    <div class="note padding-top-3 col-md-12">
		    	{{ trans('hotel.distanze_poi_alert') }}
		  	</div>
	  	</div>
	    
	    <div class="clearfix"></div>

	@endif

@endif

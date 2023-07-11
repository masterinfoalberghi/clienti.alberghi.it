<?php 
/**
 *
 * visualizzo servizi gratuiti associati all'hotel:
 * @parameters: servizi (array servizi in lingua)
 *
 *
 *
 */
?>


	<div class="content-scheda padding-bottom">
		<div class="servizi-in-evidenza servizi-content">
			
			<h4 class="title">{{trans('hotel.serviziGratuiti')}}</h4>
			<ul>
			@if ($all_servizi->count() || $servizi_privati_gratuiti->count())
				@foreach ($all_servizi as $servizi) 
					@if (!is_null($servizi->servizi_lingua->first()))
					
						<li>
							<i class="icon-ok"></i>  {{ htmlspecialchars_decode($servizi->servizi_lingua->first()->nome, ENT_QUOTES) }}
							@if ($locale == 'it') {{$servizi->pivot->note}}
							@else <?php $note_lang = 'note_'.$locale; ?> {{htmlspecialchars_decode($servizi->pivot->$note_lang, ENT_QUOTES)}} @endif
						</li>
					
					@endif
					
				@endforeach
					
				@foreach ($servizi_privati_gratuiti as $servizio) 
				
					@if (!is_null($servizio->servizi_privati_lingua->first()))
						<li><i class="icon-ok"></i>  {{$servizio->servizi_privati_lingua->first()->nome}}</li>
					@endif
					
				@endforeach
				
			@endif
			</ul>
			
			<div class="note content-scheda margin-top-6 padding-bottom">
				{{trans('labels.sg_meglio')}}
			</div>
			
		</div>
					
	</div>
	
	<div class="clearfix"></div>
	
	
	
	
	
	

 
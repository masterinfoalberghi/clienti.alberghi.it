@if ($all_servizi->count() || $servizi_privati_gratuiti->count())
	<div class="row green-bg ">
		<div class="col-xs-12" id="gratis">
		
			<h3>{{ trans('hotel.serviziGratuiti') }}</h3>
			
			<ul>
			
				@foreach ($all_servizi as $servizi)
				
				<?php $note_lang = 'note_'.$locale; ?>
					
					@if (!is_null($servizi->servizi_lingua->first()))
						<li>
							<i class="icon-ok"></i> {{ htmlspecialchars_decode($servizi->servizi_lingua->first()->nome, ENT_QUOTES) }} <br />
							<small>@if ($locale == 'it') {{$servizi->pivot->note}} @else {{htmlspecialchars_decode($servizi->pivot->$note_lang, ENT_QUOTES)}} @endif</small>
						</li>
					@endif
					
				@endforeach
				
				@foreach ($servizi_privati_gratuiti as $servizio)
					@if (!is_null($servizio->servizi_privati_lingua->first()))
						<li><i class="icon-ok"></i> {!! $servizio->servizi_privati_lingua->first()->nome !!}</li>
					@endif
				@endforeach
			</ul>
			
			<small><em>{{ trans('labels.sg_meglio') }}</em></small>
			
		</div>
	</div>
@endif
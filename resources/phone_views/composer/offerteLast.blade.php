<div class="row">   
	<section class="col-xs-12 {{$tipo}}">
		
		<header>
			<h2>{{$titolo}}</h2>   
		</header>	
		
		@if ($offersTop->count())

			<div class="offerte-top">
				
				@php $count = 1; @endphp
				
				@foreach ($offersTop as $offerteTop)
					
					@php  $offerteTopLingua = $offerteTop->offerte_lingua->first(); @endphp
					
					@if (!is_null($offerteTopLingua))
					
						<a name="{{$offerteTop->id}}" id="{{$offerteTop->id}}" ></a>
						
						<article class="offerta-container">
						
							<div class="row offerta-container-row">
								<div class="col-xs-8">
									<div class="offerta-container-info">
									
									<ul>
										
										@if ($offerteTop->per_persone != 0 || $offerteTop->per_giorni != 0) 
										
											<li><small>
												<b>{{ trans('hotel.valido_da_al_3') }} </b>
												@if ($offerteTop->per_persone != 0) {{$offerteTop->per_persone}} @if ($offerteTop->per_persone == 1) {{ trans('hotel.valido_da_al_4') }} @else {{ trans('hotel.valido_da_al_4s') }} @endif @endif
												@if ($offerteTop->per_giorni != 0) @if ($offerteTop->per_persone != 0){{ trans('hotel.valido_da_al_4_1') }} @endif {{$offerteTop->per_giorni}} @if ($offerteTop->per_giorni == 1) {{ trans('hotel.valido_notte') }} @else {{ trans('hotel.valido_notti') }} @endif @endif
											</small></li>
											
										@endif
										
										@if(isset($offerteTop->valido_dal))
											
											@php
												if(\Carbon\Carbon::parse($offerteTop->valido_dal)->format("Y") == \Carbon\Carbon::parse($offerteTop->valido_al)->format("Y"))
													$format = "%d %b";
												else 
													$format = "%d %b %Y";
											@endphp

											<li><small>
												<b>{{ trans('hotel.valido_da_al_1') }}</b> {{Utility::getLocalDate($offerteTop->valido_dal, $format)}}
												   {{ trans('hotel.valido_da_al_2') }} {{Utility::getLocalDate($offerteTop->valido_al, $format)}}
											 </small></li>
						
										@endif
								
										@if(isset($offerteTop->formula) && $offerteTop->formula != "")
											
											<li><small>
												{!!Utility::getTrattamentoOfferte($offerteTop->formula)!!}
											</small></li>
											
										@endif
										
										@if ($offerteTop->label_costo_a_persona == "1")
											<li><small>{{ trans('hotel.valido_da_al_6') }}</small></li>
										@endif
										
									</ul>			
								</div>
							</div>
								
							<div class="col-xs-4">
								
								<div class="offerta-container-price">
									
									@if ($offerteTop->prezzo_a_partire_da == "1")
										<small>{{ ucfirst(trans('hotel.da')) }}</small><br />
									@endif
									
									<strong class="price">{!!Utility::setPriceFormat($offerteTop->prezzo_a_persona)!!}</strong>
									
								</div>
							</div>	
						</div>
							
							<header>
								<h1>{{$offerteTopLingua->titolo}}</h1>
							</header>
							
							<span class="testohotel" id="offerte-{{$offerteTop->id}}">
								{!!$offerteTopLingua->testo!!}
							</span>
							
							<a href="#" data-id="offerte-{{$offerteTop->id}}" class="readall " >{{trans("hotel.leggi_tutto")}}</a><br />
							
							<div class="clear"></div>
							
							<a data-id="{{$cliente->id}}" data-cliente="{{$cliente->nome}}" class="pulsante_chiama_scheda  button green white-fe small callbutton" href="tel:{{explode(',',$cliente->telefoni_mobile_call)[0]}}">
								{{trans("hotel.chiama")}}
							</a>
							
							@if ($cliente->attivo == -1)
								{!! Form::open(['url' =>url(Utility::getLocaleUrl($locale)."/hotel.demo?id=demo&contact")]) !!} 
							@else
								{!! Form::open(['url' => url(Utility::getLocaleUrl($locale).'/hotel.php?id='. $cliente->id . "&contact")]) !!} 
							@endif
							
							{!! Form::hidden('locale',$locale) !!}
							{!! Form::hidden('ids_send_mail', $cliente->id)!!}
							{!! Form::hidden('referer', Utility::getUrlWithLang($locale, "/hotel.php?id=".$cliente->id ."&lastminute", true)) !!}
							{!! Form::hidden('offerta', trim($offerteTopLingua->titolo) )!!}
							{!! Form::hidden('testo_offerta', trans("hotel.last" ))!!}
							{!! Form::hidden('tipo_offerta', "lm" )!!}
							{!! Form::submit(trans("hotel.scrivi"),["class"=>"invioemailhotel button orange emailbutton small"]) !!}      
							{!! Form::close() !!}
							
							<div class="clear"></div>
							
							<div class="stato_offerta approvata">
								{{ trans('hotel.offerta_verificata') }} 
								@if ( !is_null($offerteTop->updated_at) ) 
									&nbsp;{{ trans('hotel.offerta_verificata_il') }}&nbsp;{{Utility::getLocalDate(new \Carbon\Carbon($offerteTop->updated_at) , '%e %B %Y ore %T')}} 
								@endif
							</div>
							
						</article>
						
					@endif			
				 	
					@php $count++; @endphp
					
				@endforeach
				
			</div>
			
		@endif

		@if ($offers->count())
			@php $count=1; @endphp
			@foreach ($offers as $offerteLast)
					
				<?php $offerteLastLingua = $offerteLast->offerte_lingua->first(); ?>
					
				@if (!is_null($offerteLastLingua))
					
					<a name="{{$offerteLast->id}}" id="{{$offerteLast->id}}" ></a>
					
					<article class="offerta-container">
					
						<div class="row offerta-container-row">
							<div class="col-xs-8">
								<div class="offerta-container-info">
								
									<ul>
										
										@if ($offerteLast->per_persone != 0 || $offerteLast->per_giorni != 0) 
											<li><small>
												<b>{{ trans('hotel.valido_da_al_3') }} </b>
												@if ($offerteLast->per_persone != 0) {{$offerteLast->per_persone}} @if ($offerteLast->per_persone == 1) {{ trans('hotel.valido_da_al_4') }} @else {{ trans('hotel.valido_da_al_4s') }} @endif @endif
												@if ($offerteLast->per_giorni != 0) @if ($offerteLast->per_persone != 0){{ trans('hotel.valido_da_al_4_1') }} @endif {{$offerteLast->per_giorni}} @if ($offerteLast->per_giorni == 1) {{ trans('hotel.valido_notte') }} @else {{ trans('hotel.valido_notti') }} @endif @endif
											</small></li>
										@endif
										
										@if(isset($offerteLast->valido_dal))

											@php
												if(\Carbon\Carbon::parse($offerteLast->valido_dal)->format("Y") == \Carbon\Carbon::parse($offerteLast->valido_al)->format("Y"))
													$format = "%d %b";
												else 
													$format = "%d %b %Y";
											@endphp

											<li><small>
												<b>{{ trans('hotel.valido_da_al_1') }}</b> {{Utility::getLocalDate($offerteLast->valido_dal, $format)}} {{ trans('hotel.valido_da_al_2') }} {{Utility::getLocalDate($offerteLast->valido_al, $format)}}
											</small></li>
										@endif
															
										@if(isset($offerteLast->formula) && $offerteLast->formula != "")
											<li>
												<small>{!!Utility::getTrattamentoOfferte($offerteLast->formula)!!}</small>
											</li>
										@endif
										
										@if ($offerteLast->label_costo_a_persona == "1")
											<li>
												<small>{{ trans('hotel.valido_da_al_6') }}</small>
											</li>
										@endif
										
									</ul>
								</div>
							</div>
						
							<div class="col-xs-4">
								<div class="offerta-container-price">
									
									@if ($offerteLast->prezzo_a_partire_da)
										<small>{{ ucfirst(trans('hotel.da')) }}</small>
									@endif
									
									<strong class="price">{!!Utility::setPriceFormat($offerteLast->prezzo_a_persona)!!}</strong>
									
								</div>
							</div>
						</div>
						
						<header>
							<h1>{{$offerteLastLingua->titolo}}</h1>
						</header>

						<span class="testohotel" id="offerte-{{$offerteLast->id}}">{!!$offerteLastLingua->testo!!}</span>

						<a href="#" data-id="offerte-{{$offerteLast->id}}" class="readall " >{{trans("hotel.leggi_tutto")}}</a><br />
						<a data-id="{{$cliente->id}}" data-cliente="{{$cliente->nome}}" class="pulsante_chiama_scheda button green white-fe small callbutton" href="tel:{{explode(',',$cliente->telefoni_mobile_call)[0]}}">{{trans("hotel.chiama")}}</a>
						
						@if ($cliente->attivo == -1)
							{!! Form::open(['url' =>url(Utility::getLocaleUrl($locale)."/hotel.demo?id=demo&contact")]) !!} 
						@else
							{!! Form::open(['url' =>url(Utility::getLocaleUrl($locale).'/hotel.php?id='. $cliente->id . "&contact")]) !!} 
						@endif
						
						{!! Form::hidden('locale',$locale) !!}
						{!! Form::hidden('ids_send_mail', $cliente->id)!!}
						{!! Form::hidden('referer', Utility::getUrlWithLang($locale, "/hotel.php?id=".$cliente->id . "&lastminute", true)) !!}
						{!! Form::hidden('offerta', trim($offerteLastLingua->titolo) )!!}
						{!! Form::hidden('testo_offerta', trans("hotel.last" )) !!}
						{!! Form::hidden('tipo_offerta', "lm" ) !!}
						{!! Form::submit(trans("hotel.scrivi"),["class"=>"invioemailhotel button orange emailbutton small"]) !!}      
						{!! Form::close() !!}
						
						<div class="clear"></div>
						
						@if ($offerteLastLingua->approvata)
							<div class="stato_offerta approvata">
							{{ trans('hotel.offerta_verificata') }} 
							 @if ( !is_null($offerteLastLingua->data_approvazione) ) 
								&nbsp;{{ trans('hotel.offerta_verificata_il') }}&nbsp;{{ Utility::getLocalDate($offerteLastLingua->data_approvazione, '%d %B %Y %T') }} 
							 @endif
							</div>	
						@else
							<div class="stato_offerta inattesa">
								{{ trans('hotel.offerta_in_attesa') }}
							</div>
						@endif
						
						@php $count++; @endphp
					
					</article>
					
				@endif
			@endforeach
		@endif

	</section>	
</div>




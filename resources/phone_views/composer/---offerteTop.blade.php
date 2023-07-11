<div class="row">   
	<section class="col-xs-12 offers">
			
		<header>
			<h2>{{$titolo}}</span></h2>   
		</header>	
		
		@if ($offers->count())
			
			<div class="offerte-top">
			<?php $count = 1 ?>
			@foreach ($offersTop as $offerteTop)

				@if ($offerteTop == "offerta")
			 		@php $offerteTopLingua = $offerteTop->offerte_lingua->first(); @endphp
			 			@if (!is_null($offerteTopLingua))
				
						<a name="{{$offerteTop->id}}" id="{{$offerteTop->id}}" ></a>
						
						<article class="offerta-container">
							<div class="row offerta-container-row">
								<div class="col-xs-8">
									<div class="offerta-container-info">
										
										<ul>
											
											@if ($offerteTop->tipo == 'prenotaprima')
												<li><small><b>{{ trans('hotel.prenota_entro') }}</b> {{Utility::getLocalDate($offerteTop->prenota_entro, '%d %B %Y')}}</small></li>
											
												
												
											@endif
											
											@if ($offerteTop->per_persone != 0 || $offerteTop->per_giorni != 0) 
												
												<li><small>
													<b>{{ trans('hotel.valido_da_al_3') }} </b>
													@if ($offerteTop->per_persone != 0) {{$offerteTop->per_persone}} {{ trans('hotel.valido_da_al_4') }} @endif
													@if ($offerteTop->per_giorni != 0) @if ($offerteTop->per_persone != 0){{ trans('hotel.valido_da_al_4_1') }} @endif {{$offerteTop->per_giorni}} {{ trans('hotel.valido_da_al_5') }}@endif
												</small></li>
												
											@endif
											
											@if(isset($offerteTop->valido_dal))
												
												<li><small>
													
													{{ trans('hotel.valido_da_al_1') }}</b> {{Utility::getLocalDate($offerteTop->valido_dal, '%d %B')}}
													   {{ trans('hotel.valido_da_al_2') }} {{Utility::getLocalDate($offerteTop->valido_al, '%d %B')}}
												</small></li>
												
											@endif
											
											@if(isset($offerteTop->formula) && $offerteTop->formula != "")
											
												<li><small>
													{{ trans('listing.in') }} {{Utility::getTrattamentoOfferte($offerteTop->formula)}}
												</small></li>
											
											@endif
									
										</ul>
									</div>
								</div>
								
								<div class="col-xs-4">
									<div class="offerta-container-price">
										@if ($offerteTop->tipo == 'prenotaprima')
											<strong class="price">- {{$offerteTop->perc_sconto}}%</strong>
										@else
											@if ($offerteTop->prezzo_a_partire_da)
												<small>{{ ucfirst(trans('hotel.prezzi_a_partire')) }}</small><br />
											@endif
											<strong class="price">{{Utility::setPriceFormat($offerteTop->prezzo_a_persona)}}</strong>
										@endif
									</div>
								</div>
								
								<header>
									<h1>{{$offerteTopLingua->titolo}}</h1>
								</header>
									
								<span class="testohotel" id="offerte-{{$offerteTop->id}}">
									{!!$offerteTopLingua->testo!!}
								</span>
							
								<a href="#" data-id="offerte-{{$offerteTop->id}}" class="readall button small cyan" >{{trans("hotel.leggi_tutto")}}</a><br />
							
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
								{!! Form::hidden('referer', Utility::getUrlWithLang($locale, "/hotel.php?id=".$cliente->id . "&offers", true)) !!}
								{!! Form::hidden('offerta', trim($offerteTopLingua->titolo) )!!}
								{!! Form::hidden('testo_offerta', trans("hotel.offerte_top_box" ))!!}
								{!! Form::hidden('tipo_offerta', "ot" )!!}
								{!! Form::submit(trans("hotel.scrivi"),["class"=>"invioemailhotel button orange emailbutton small"]) !!}      
								{!! Form::close() !!}
								
								<div class="clear"></div>
				
							</article>
						@endif		
					<?php $count++; ?>
				@endforeach
			@endif

	</section>	
</div>








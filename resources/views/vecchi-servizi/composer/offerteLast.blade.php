<?php

// visualizzo le info sul periodo bambino gratis
// @parameters : $offers (array indicizzato con i valori per creare lo snippet)
//               $titolo (header titolo oppure '')
//               $tipo (offerte|lastminute)
  
?>

@if ($offers->count())
	
  	@if($titolo)
	  	<header>	
		  	<hgroup>
		  		<a name="lastminute"></a>
		  		<h2  class="content-section box-offers-title">{{$titolo}}</h2>
		  	</hgroup>
	  	</header>
  	@endif
  
  	@foreach ($offers as $offerteLast)
      
     	<?php $offerteLastLingua = $offerteLast->offerte_lingua->first(); ?>
      
	 	@if (!is_null($offerteLastLingua))
	 		
	 		<article class="box-offers-article margin-bottom-6">
		        <header>
			        <h4 class="box-offers-article-title">{{$offerteLastLingua->titolo}}</h4>
		        </header>
	 		
	 		
		 		<div class="box-offers-article-content content-scheda">
							
		 			<div class="col-sm-9" style="padding-right: 10px;">
		 			
		 				<div class="box-offers-article-content-up">
						
						@if (Utility::getLocalDate($offerteLast->valido_dal, '%d/%m/%y'))
							@php
								if(\Carbon\Carbon::parse($offerteLast->valido_dal)->format("Y") == \Carbon\Carbon::parse($offerteLast->valido_al)->format("Y"))
									$format = "%e %B";
								else 
									$format = "%e %B %Y";
							@endphp
							
		 					{{ ucfirst(trans('hotel.valido_da_al_1')) }} <strong>{{Utility::myFormatLocalized($offerteLast->valido_dal, $format, $locale)}}</strong> {{ trans('hotel.valido_da_al_2') }} <strong>{{Utility::myFormatLocalized($offerteLast->valido_al, $format, $locale)}}</strong>
		 				@endif
		 				
		 				@if ($offerteLast->per_persone != 0 || $offerteLast->per_giorni != 0)
		 					{{ trans('hotel.con') }} {{ trans('hotel.valido_da_al_3') }}
		 					@if ($offerteLast->per_persone != 0) <strong>{{$offerteLast->per_persone}} @if ($offerteLast->per_persone == 1) {{ trans('hotel.valido_da_al_4') }} @else {{ trans('hotel.valido_da_al_4s') }} @endif</strong> @endif
		 					@if ($offerteLast->per_giorni != 0) @if ($offerteLast->per_persone != 0){{ trans('hotel.valido_da_al_4_1') }} @endif <strong>{{$offerteLast->per_giorni}} @if ($offerteLast->per_giorni == 1) {{ trans('hotel.valido_notte') }} @else {{ trans('hotel.valido_notti') }} @endif</strong> @endif
		 				@endif

		 			</div>
		 				
						@if (strlen($offerteLastLingua->testo) > 30)
						
							<div class="box-offers-article-content-small">
								{!!Utility::getExcerpt($offerteLastLingua->testo, $limit = 30, $strip = true)!!}<br />
								<a class="btn btn-small btn-cyano " href="#" >{{ trans('hotel.leggi_tutto') }}</a> 
							</div>
							
							<div style="display: none;" class="box-offers-article-content-full">
								{!!$offerteLastLingua->testo!!}<br />
								<a href="#" class="btn btn-small btn-rosso btn-cyano">{{ trans('hotel.chiudi') }}</a>
							</div>
							
						@else
						
							{!!$offerteLastLingua->testo!!}
							
						@endif
					
		 			</div>
		 			
		 			<div class="col-sm-3">
			 			
			 			<footer class="box-offers-article-spot">
					
										                
			                	@if ($offerteLast->prezzo_a_partire_da)
									{{ ucfirst(trans('listing.da')) }}
								@endif
								
								<div class="box-offers-article-price">
									{{$offerteLast->prezzo_a_persona}} &euro;
								</div>
								
								@if ($offerteLast->label_costo_a_persona == "1")
									{{ trans('hotel.valido_da_al_6') }}<br/>
								@endif
								
								<div class="box-offers-article-formula">
									{!!Utility::getTrattamentoOfferte($offerteLast->formula)!!}
								</div>
			                
						</footer>
						
		 			</div>
					
					<div class="clearfix"></div>
					
					@if ($offerteLastLingua->approvata)
						<div class="box-offers-article-verifica tipped" title="<b>{{ trans('hotel.offerta_verificata') }}</b>@if ( !is_null($offerteLastLingua->data_approvazione) ) <br/>{{ Utility::getLocalDate($offerteLastLingua->data_approvazione, '%e %B %Y ore %T')}} @endif ">
							<i class="check icon-ok"></i> <i style="margin-left:-15px;" class="check icon-ok"></i>
						</div>
					@else
						<div class="box-offers-article-verifica tipped" title="<b>{{ trans('hotel.offerta_in_attesa') }}</b>">
							<i class="check icon-ok"></i> <i style="margin-left:-15px;" class="no-check icon-ok"></i>
						</div>
					@endif
				
		 		</div>
			
			</article>
					 	
			@endif
			
			
       
	@endforeach
	

@endif






<?php

// visualizzo le info sul periodo bambino gratis
// @parameters : $offers (array indicizzato con i valori per creare lo snippet)
//               $titolo (header titolo oppure '')
//               $tipo (offerte|lastminute)

switch ($tipoTop) {
	
case 'prenotaprima':
	$anchor = '<a id="prenotaprima-scroll" name="prenotaprima"></a>';
	$titolo = trans('hotel.offerte_prenota_prima_box');
	$include = 'composer.offertePrenotaPrima';
	$class="pp";
	$offerte_tooltip = "offerte_prenotaprima_tooltip";
	break;

case 'lastminute':
	$anchor ='<a id="lastminute-scroll" name="lastminute"></a>';
	$titolo = trans('hotel.last');
	$include = 'composer.offerteLast';
	$class="lm";
	$offerte_tooltip = "offerte_lastminute_tooltip";
	break;

case 'offerta':
	$anchor = '<a id="offers-scroll" name="offers"></a>';
	$titolo = trans('hotel.offerte_generiche_box');
	$include = 'composer.offerteLast';
	$class="os";
	$offerte_tooltip = "offerte_speciale_tooltip";
	break;

}

?>

{!! $anchor !!}

@if ($offers->count())
		
	@php
  
    $no=0;
	foreach ($offers as $offerteTop)
		if($tipoTop == $offerteTop->tipo)
			$no++;

	@endphp
	
	<section class="box-offers {{$class}} padding-bottomx2 ">
	
		@if ($no > 0)
		
			<header>
				<hgroup>
					<h2 class="content-section box-offers-title">@if($tipoTop != "offerta"){{trans("title.offerte")}}@endif <b>{{$titolo}}</b></h2> 
				</hgroup>
			</header>
			<?php $titolo = ""; ?>
			
		@endif
		
		@foreach ($offers as $offerteTop)
	    
			@if($tipoTop == $offerteTop->tipo)
			
				<?php $offerteTopLingua = $offerteTop->offerte_lingua->first(); ?>
				
				@if (!is_null($offerteTopLingua))
									
					<article class="box-offers-article margin-bottom-6 top">
						
				    	<header>
							<h4 class="box-offers-article-title">{{$offerteTopLingua->titolo}}</h4>
				    	</header>
						
						<div class="box-offers-article-content content-scheda">
							
								<div class="col-sm-9" style="padding-right: 10px;">
									

									<div class="box-offers-article-content-up">
										@if (Utility::getLocalDate($offerteTop->valido_dal, '%d/%m/%y'))

										@php
										if(\Carbon\Carbon::parse($offerteTop->valido_dal)->format("Y") == \Carbon\Carbon::parse($offerteTop->valido_al)->format("Y"))
											$format = "%e %B";
										else 
											$format = "%e %B %Y";
										@endphp

											{{ ucfirst(trans('hotel.valido_da_al_1')) }} <strong>{{Utility::myFormatLocalized($offerteTop->valido_dal, $format, $locale)}}</strong> {{ trans('hotel.valido_da_al_2') }} <strong>{{Utility::myFormatLocalized($offerteTop->valido_al, $format, $locale)}}</strong>
										@endif
										
										@if ($offerteTop->per_persone != 0 || $offerteTop->per_giorni != 0)
											{{ trans('hotel.con') }} {{ trans('hotel.valido_da_al_3') }}
						 					@if ($offerteTop->per_persone != 0) <strong>{{$offerteTop->per_persone}} @if ($offerteTop->per_persone == 1) {{ trans('hotel.valido_da_al_4') }} @else {{ trans('hotel.valido_da_al_4s') }} @endif</strong> @endif
						 					@if ($offerteTop->per_giorni != 0) @if ($offerteTop->per_persone != 0){{ trans('hotel.valido_da_al_4_1') }} @endif <strong>{{$offerteTop->per_giorni}} @if ($offerteTop->per_giorni == 1) {{ trans('hotel.valido_notte') }} @else {{ trans('hotel.valido_notti') }} @endif</strong> @endif
										@endif

									</div>
									
									@if (strlen($offerteTopLingua->testo) > 30)
								
										<div class="box-offers-article-content-small">
											{!! Utility::getExcerpt($offerteTopLingua->testo, $limit = 30, $strip = true) !!}<br />
											<a class="btn btn-small btn-cyano " href="#" >{{ trans('hotel.leggi_tutto') }}</a>  
										</div>
									
										<div style="display: none;" class="box-offers-article-content-full">
											{!! $offerteTopLingua->testo !!}<br />
											<a href="#" class="btn btn-small btn-rosso btn-cyano">{{ trans('hotel.chiudi') }}</a>
										</div>
										
									@else
									
										{!!$offerteTopLingua->testo!!}
										
									@endif
								
								</div>
								
								<div class="col-sm-3">
									
									<footer class="box-offers-article-spot">
					
							            @if ($offerteTop->tipo == 'prenotaprima')
							              	
							              	<div class="box-offers-article-price">
							                	- {{$offerteTop->perc_sconto}}%
							              	</div>
							              	
											{{ trans('hotel.prenota_entro') }}<br>
											<b>{{Utility::myFormatLocalized($offerteTop->prenota_entro, '%e %B %Y', $locale)}}</b>
							                 
							            @else
							
							                @if ($offerteTop->prezzo_a_partire_da)
												{{ ucfirst(trans('listing.da')) }}
							                @endif
							                
							                <div class="box-offers-article-price">
												{{$offerteTop->prezzo_a_persona}} &euro;
							                </div>
							                
							                @if ($offerteTop->label_costo_a_persona == "1")
												{{ trans('hotel.valido_da_al_6') }}<br/>
											@endif
							                
							                <div class="box-offers-article-formula">
								                @if(isset($offerteTop->formula) && $offerteTop->formula != "")
													{!!Utility::getTrattamentoOfferte($offerteTop->formula)!!}
												@endif
							                </div>
							                 
										@endif
											
									</footer>    
									
								</div>
								
								<div class="clearfix"></div>

								<div class="box-offers-article-top tipped"  title="{{trans("labels." . $offerte_tooltip)}}">
									<i class="icon-heart"></i>
								</div>

								@if ( !is_null($offerteTop->updated_at) ) 
									<div class="box-offers-article-verifica tipped" title="<b>{{ trans('hotel.offerta_verificata') }}</b><br/>{{ date( 'j F Y \o\r\e H:i:s',strtotime($offerteTop->updated_at)) }} ">
										<i class="check icon-ok"></i> <i style="margin-left:-15px;" class="check icon-ok"></i>
									</div>
								@else
									<div class="box-offers-article-verifica tipped" title="<b>{{ trans('hotel.offerta_verificata') }}</b>">
										<i class="check icon-ok"></i> <i style="margin-left:-15px;" class="no-check icon-ok"></i>
									</div>
								@endif
							  
							              
						</div>
						
					</article>
					
				@endif
		    @endif
	      
		@endforeach
		
		@include($include, array('titolo' => $titolo, 'tipo' => $tipoTop))
		
	</section>

@else
	
	<section class="box-offers {{$class}} padding-bottomx2 ">
		@include($include, array('titolo' => $titolo, 'tipo' => $tipoTop))
	</section>

@endif


 



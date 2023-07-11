<?php

// visualizzo le info sul periodo bambino gratis
// @parameters : $offers (array indicizzato con i valori per creare lo snippet)
//               $titolo (header titolo oppure '')
//               $tipo (offerte|lastminute)
  
?>

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
				{!! $anchor !!}
				<h2  class="content-section box-offers-title">{{trans("title.offerte")}} <b>{{$titolo}}</b></h2> 
			</hgroup>
		</header>
  	@endif

  	@foreach ($offers as $offertePrenotaPrima)
  	
	    <?php $offertePrenotaPrimaLingua = $offertePrenotaPrima->offerte_lingua->first(); ?>
	    
	    @if (!is_null($offertePrenotaPrimaLingua))
	    	
    		<article class="box-offers-article margin-bottom-6">
				
		    	<header>
					<h4 class="box-offers-article-title">{{$offertePrenotaPrimaLingua->titolo}}</h4>
		    	</header>
				
				<div class="box-offers-article-content content-scheda">
					
					<div class="col-sm-9" style="padding-right: 10px;">
						
						<div class="box-offers-article-content-up"> 
						
						@php
							if(\Carbon\Carbon::parse($offertePrenotaPrima->valido_dal)->format("Y") == \Carbon\Carbon::parse($offertePrenotaPrima->valido_al)->format("Y"))
								$format = "%e %B";
							else 
								$format = "%e %B %Y";
						@endphp

						{{ ucfirst(trans('hotel.valido_da_al_1')) }} <strong>{{Utility::myFormatLocalized($offertePrenotaPrima->valido_dal, $format, $locale)}}</strong> {{ trans('hotel.valido_da_al_2') }} <strong>{{Utility::myFormatLocalized($offertePrenotaPrima->valido_al, $format, $locale)}}</strong>
		               
		                
			     	@if ($offertePrenotaPrima->per_persone != 0 || $offertePrenotaPrima->per_giorni != 0)
			     		{{ trans('hotel.con') }} {{ trans('hotel.valido_da_al_3') }}
			     		@if ($offertePrenotaPrima->per_persone != 0) <strong>{{$offertePrenotaPrima->per_persone}} @if ($offertePrenotaPrima->per_persone == 1) {{ trans('hotel.valido_da_al_4') }} @else {{ trans('hotel.valido_da_al_4s') }} @endif</strong> @endif
			     		@if ($offertePrenotaPrima->per_giorni != 0) @if ($offertePrenotaPrima->per_persone != 0){{ trans('hotel.valido_da_al_4_1') }} @endif <strong>{{$offertePrenotaPrima->per_giorni}} @if ($offertePrenotaPrima->per_giorni == 1) {{ trans('hotel.valido_notte') }} @else {{ trans('hotel.valido_notti') }} @endif</strong> @endif
			     	@endif    
						
						</div>
						@if (strlen($offertePrenotaPrimaLingua->testo) > 30)
						
							<div class="box-offers-article-content-small">
								{!!Utility::getExcerpt($offertePrenotaPrimaLingua->testo, $limit = 30, $strip = true)!!}<br />
								<a class="btn btn-small btn-cyano " href="#" >{{ trans('hotel.leggi_tutto') }}</a>  
							</div>
						
							<div style="display: none;" class="box-offers-article-content-full">
								{!!$offertePrenotaPrimaLingua->testo!!}
								<a href="#" class="btn btn-small btn-rosso btn-cyano">{{ trans('hotel.chiudi') }}</a>
							</div>
										
						@else
						
							{!!$offertePrenotaPrimaLingua->testo!!}
							
						@endif
					</div>
					
					<div class="col-sm-3">
						
						<footer>
		                	
		                	<footer class="box-offers-article-spot">
								<div class="box-offers-article-price">- {{$offertePrenotaPrima->perc_sconto}}%</div>
								
			                    {{ trans('hotel.prenota_entro') }}<br>
			                    <b>{{Utility::myFormatLocalized($offertePrenotaPrima->prenota_entro, '%e %B %Y',$locale)}}</b>
		                	</footer>
	              
						</footer>
	
					</div>
					
	                <div class="clearfix"></div>
								
					@if ($offertePrenotaPrimaLingua->approvata)
						<div class="box-offers-article-verifica tipped" title="<b>{{ trans('hotel.offerta_verificata') }}</b><br/>{{ Utility::getLocalDate($offertePrenotaPrimaLingua->data_approvazione, '%e %B %Y ore %T') }}  ">
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




              
<?php

// @parameters : $offers (array indicizzato con i valori per creare lo snippet)
//               $titolo (header titolo oppure '')
?>

@if ($offers->count())
		
		<?php $count = 1 ?>
			
			<div class="offerte-top">
				
			@foreach ($offers as $offerteTopBambiniGratis)
				
				<?php $offerteTopBambiniGratisLingua = $offerteTopBambiniGratis->offerte_lingua->first(); ?>
								
				@if (!is_null($offerteTopBambiniGratisLingua))
					
					<a name="{{$offerteTopBambiniGratis->id}}" id="{{$offerteTopBambiniGratis->id}}"></a>
					
					<article class="offerta-container">
						<div class="row offerta-container-row">
							<div class="col-xs-8">
								<div class="offerta-container-info">
								
								<header style="margin-top:7px; ">
									<h1>{{ trans('title.bg') }}</h1>
								</header>
								
								<ul>
									@if(isset($offerteTopBambiniGratis->valido_dal))

											@php
												if(\Carbon\Carbon::parse($offerteTopBambiniGratis->valido_dal)->format("Y") == \Carbon\Carbon::parse($offerteTopBambiniGratis->valido_al)->format("Y"))
													$format = "%e %b";
												else 
													$format = "%e %b %Y";
											@endphp

										<li><small>
											<b>{{ trans('hotel.valido_da_al_1') }}</b> {{Utility::myFormatLocalized($offerteTopBambiniGratis->valido_dal, $format, $locale) }}
											   {{ trans('hotel.valido_da_al_2') }} {{Utility::myFormatLocalized($offerteTopBambiniGratis->valido_al, $format, $locale) }}
										 </small></li>
									@endif
								</ul>
							</div>
						</div>
						
						<div class="col-xs-4">
							<div class="offerta-container-price">
								<small>{{ trans('labels.fino') }}</small><br/>
								<strong>{{strtoupper($offerteTopBambiniGratis->_fino_a_anni())}}<br />{{strtoupper($offerteTopBambiniGratis->_anni_compiuti())}}</strong>
							</div>
						</div>
					</div>
						
						<span class="testohotel" id="offerte-{{$offerteTopBambiniGratis->id}}">
							{!!$offerteTopBambiniGratisLingua->note!!}<br /><br />	
						</span>	
					
									
						<a href="#" data-id="offerte-{{$offerteTopBambiniGratis->id}}" class="readall" >{{trans("hotel.leggi_tutto")}}</a><br />
						
						<div class="clear"></div>
						
						<a data-id="{{$cliente->id}}" data-cliente="{{$cliente->nome}}" class="pulsante_chiama_scheda button green white-fe small callbutton" href="tel:{{explode(',',$cliente->telefoni_mobile_call)[0]}}">
							{{trans("hotel.chiama")}}
						</a>
						
						@if ($cliente->attivo == -1)
							{!! Form::open(['url' =>url(Utility::getLocaleUrl($locale)."/hotel.demo?id=demo&contact")]) !!} 
						@else
							{!! Form::open(['url' => url(Utility::getLocaleUrl($locale).'/hotel.php?id='. $cliente->id . "&contact")]) !!} 
						@endif
						
						{!! Form::hidden('locale',$locale) !!}
						{!! Form::hidden('ids_send_mail', $cliente->id)!!}
						{!! Form::hidden('referer', Utility::getUrlWithLang($locale, "/hotel.php?id=".$cliente->id . "&children-offers", true)) !!}
						{!! Form::hidden('offerta', trim($titolo) )!!}
						{!! Form::hidden('testo_offerta', trans("hotel.offerte_bg_top_box") )!!}
						{!! Form::hidden('tipo_offerta', "bg" )!!}
						{!! Form::submit(trans("hotel.scrivi"),["class"=>"invioemailhotel button orange emailbutton small"]) !!}      
						{!! Form::close() !!}
						
						<div class="clear"></div>
						
						<div class="stato_offerta approvata">
							{{ trans('hotel.offerta_verificata') }} 
							@if ( !is_null($offerteTopBambiniGratis->updated_at) ) 
								&nbsp;{{ trans('hotel.offerta_verificata_il') }}&nbsp;{{Utility::getLocalDate(new \Carbon\Carbon($offerteTopBambiniGratis->updated_at) , '%e %B %Y ore %T')}} 
							@endif
						</div>
						
					<?php $count++; ?>
					
					</article>
			@endif
			
			@endforeach
            
	
    
  </div>

@endif








<?php

// @parameters : $offers (array indicizzato con i valori per creare lo snippet)
//               $titolo (header titolo oppure '')
?>
  

<?php

// @parameters : $offers (array indicizzato con i valori per creare lo snippet)
//               $titolo (header titolo oppure '')

$first_title = false;
?>

@if ($offers->count())
			
	<?php $count = 1 ?>
	@foreach ($offers as $offerteTop)
			
		@if (!$first_title && $offerteTop == "offerta")
		
		<div class="row">   
			<section class="col-xs-12 datiofferta">
			
				<header>
					<h2>{{$titolo}} <span style="float:right;"><img src="{{Utility::asset('/images/heart.svg')}}" width="15" height="15"></span></h2>   
				</header>	
				
				<?php $first_title = true; ?>
		
		@endif
		
		@if ($offerteTop == "offerta")
		
			 <?php $offerteTopLingua = $offerteTop->offerte_lingua->first(); ?>
			
			 @if (!is_null($offerteTopLingua))
				
				<a name="{{$offerteTop->id}}" id="{{$offerteTop->id}}" ></a>
				
				<article class="offerta-container">
				
					<header>
						<h1>{{$offerteTopLingua->titolo}}</h1>
					</header>
						
						<div class="row"  style="margin:0 0 15px 0 ; ">
							
							@if(isset($offerteTop->valido_dal))
							<div class="col-xs-6">
								<small>{{ trans('hotel.valido_da_al_1') }}</small><br />
								<strong>{{Utility::getLocalDate($offerteTop->valido_dal, '%d/%m/%y')}}</strong><br /><br />
							</div>
							
							<div class="col-xs-6">
								<small>{{ trans('hotel.valido_da_al_2') }}</small><br />
								<strong>{{Utility::getLocalDate($offerteTop->valido_al, '%d/%m/%y')}}</strong>
							</div>
							@endif
							
							@if(isset($offerteTop->formula) && $offerteTop->formula != "")
							<div class="col-xs-12">
								<small>{{ trans('hotel.trattamento') }}</small><br />
								<strong>{{Utility::getTrattamentoOfferte($offerteTop->formula)}}</strong>	 
							</div>
							@endif
							
						</div>
						
						<div class="row"  style="margin:0 0 15px 0 ; ">
							
							<small>{{ trans('labels.soggiorno') }}</small><br />
							
							@if ($offerteTop->per_persone != 0 || $offerteTop->per_giorni != 0) 
								{{ trans('hotel.valido_da_al_3') }} 
								
								@if ($offerteTop->per_persone != 0) 
									<strong>{{$offerteTop->per_persone}} {{ trans('hotel.valido_da_al_4') }} </strong>
								@endif
							
								@if ($offerteTop->per_giorni != 0) 
									@if ($offerteTop->per_persone != 0){{ trans('hotel.valido_da_al_4_1') }}  
										<strong>{{$offerteTop->per_giorni}} {{ trans('hotel.valido_da_al_5') }}</strong>
									@endif
								@endif

							@endif
	     					
						</div>
                  		
						@if ($offerteTop->tipo == 'prenotaprima')
							<div class="offertaprezzo">
								<small>{{ trans('hotel.prenota_entro') }}&nbsp;<span class="prenota_entro">{{Utility::getLocalDate($offerteTop->prenota_entro, '%d/%m/%y')}}</span></small>
								<strong class="price">- {{$offerteTop->perc_sconto}}%</strong>
							</div>
						@else
							<div class="offertaprezzo">
								
									@if ($offerteTop->prezzo_a_partire_da)
										<br /><small>{{ ucfirst(trans('hotel.prezzi_a_partire')) }}</small><br />
									@endif
									<strong class="price">{{$offerteTop->prezzo_a_persona}} &euro;</strong>
								
							</div>
						@endif
                  	
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
					
					<br style="clear:both" />	
					
					<footer>
						<small>Note e informazioni aggiuntive:</small><br>
						<span class="testohotel" id="offerte-{{$offerteTop->id}}">
						{!!$offerteTopLingua->testo!!}
						</span>
					
						<a href="#" data-id="offerte-{{$offerteTop->id}}" class="readall button small cyan" >{{trans("hotel.leggi_tutto")}}</a><br />
					</footer>
				
				</article>
				
				@endif			
				
			<?php $count++; ?>
		
		@endif
		
		@endforeach
    
    @if ($first_title) 
		 	</section> 	   
		</div>
	@endif


@endif










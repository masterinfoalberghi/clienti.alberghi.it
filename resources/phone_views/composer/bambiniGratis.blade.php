<?php

// visualizzo le info sul periodo bambino gratis
// @parameters : $periodi (array indicizzato con i valori per creare lo snippet)
//               $titolo (header titolo oppure ''), 
//               $nbg: numero offerte bambini gratis
//               
?>

@if (count($periodi))
	
			
 		<?php $count = 0; ?>
		@foreach ($periodi as $periodo)
		<?php 
		
		$fino_a_anni = $periodo['fino_a_anni'];
		$anni_compiuti = $periodo['anni_compiuti'];
		$dal = \App\Utility::myFormatLocalized($periodo['dal'], '%e %b', $locale);
		$al =  \App\Utility::myFormatLocalized($periodo['al'], '%e %b', $locale);
		$periodo["id"] != '' ? $id = $periodo["id"] : $id = 0;
		//$periodo['note'] == '' ? $note = '' : $note = $periodo['note'];

		$approvata = $periodo['approvata'];
		$data_approvazione = $periodo['data_approvazione'];
		$periodo['note'] == '' ? $note = '' : $note = $periodo['note'];
		?>
		
		<article class="offerta-container"> {{-- position:relative per label approvazione --}}
			
			<div class="row offerta-container-row">
				<div class="col-xs-8">
					<div class="offerta-container-info">
						<header style="margin-top:7px; ">
							<h1>{{ trans('title.bg') }}</h1>
						</header>
					<ul>
						@if(isset($dal))
						
							<li><small>
								<b>{{ trans('hotel.valido_da_al_1') }}</b> {{$dal}}
								   {{ trans('hotel.valido_da_al_2') }} {{$al}}
							 </small></li>
							 
						@endif
					</ul>
					
					</div>
				</div>
				
				<div class="col-xs-4">
					
					<div class="offerta-container-price">
						
						<small>{{ trans('labels.fino') }}</small><br />				
						<strong>{{$fino_a_anni}}<br />{{$anni_compiuti}}</strong>
						
					</div>
				</div>	
			</div>
			
			
			
			@if ($note)

				<span class="testohotel" id="offerte-{{$id}}">
					{!!$note!!}
				</span><a href="#" data-id="offerte-{{$id}}" class="readall" >{{trans("hotel.leggi_tutto")}}</a><br />

			@endif
			
			<div class="clear"></div>
			
			<a data-id="{{$cliente->id}}" data-cliente="{{$cliente->nome}}" class="pulsante_chiama_scheda button green white-fe small callbutton" href="tel:{{explode(',',$cliente->telefoni_mobile_call)[0]}}">{{trans("hotel.chiama")}}</a>
			
			@if ($cliente->attivo == -1)
				{!! Form::open(['url' =>url(Utility::getLocaleUrl($locale)."/hotel.demo?id=demo&contact")]) !!} 
			@else
				{!! Form::open(['url' => url(Utility::getLocaleUrl($locale).'/hotel.php?id='. $cliente->id . "&contact")]) !!} 
			@endif
			
			{!! Form::hidden('locale',$locale) !!}
			{!! Form::hidden('ids_send_mail', $cliente->id)!!}
			{!! Form::hidden('referer', Utility::getUrlWithLang($locale, "/hotel.php?id=".$cliente->id ."&lastminute", true)) !!}
			{!! Form::hidden('offerta', "Bambini Gratis" )!!}
			{!! Form::hidden('testo_offerta', trans("hotel.offerte" ))!!}
			{!! Form::hidden('tipo_offerta', "bg" )!!}
			{!! Form::submit(trans("hotel.scrivi"),["class"=>"invioemailhotel button orange emailbutton small"]) !!}      
			{!! Form::close() !!}
			
			<div class="clear"></div>
			
			@if ($approvata)
				<div class="stato_offerta approvata">
				{{ trans('hotel.offerta_verificata') }} 
				 @if ( !is_null($data_approvazione) ) 
					&nbsp;{{ trans('hotel.offerta_verificata_il') }}&nbsp;{{ $data_approvazione }} 
				 @endif
				</div>	
			@else
				<div class="stato_offerta inattesa">
					{{ trans('hotel.offerta_in_attesa') }}
				</div>
			@endif
			
		</article>
		
		<?php $count++; ?>
	@endforeach
@endif

@if (count($servizi_finali))
<div class="col-xs-12 white-bg datibg offerta-container" >
	  <header>
	  	<h4 style="text-align:left;">{{ trans('title.servizi_bambini') }}</h4>                 
  		</header>
	  
	  <ul class="inline-list">
		  @foreach ($servizi_finali as $servizio)
			  <li><img src='{{Utility::asset("images/punti.png")}}'  />{{$servizio}}</li>
		  @endforeach
	  </ul>
</div>      
@endif   


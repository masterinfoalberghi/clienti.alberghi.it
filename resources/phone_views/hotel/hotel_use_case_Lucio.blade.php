@extends('templates.scheda_hotel', ['cliente' => $cliente, 'ref' => $ref])

@section('seo_title') {{$title}} @endsection
@section('seo_description') {{$description}} @endsection

@if (!isset($ref))
   <?php $ref = ''; ?>
@endif 

@section('content')

<div class="container">
	<div class="row white-bg">
		@include('composer.hotelGallery',['gallery' => 'scheda'])
	</div>
</div>

{{-- TODO - rimuovere microdata da questa view (è una view creata per testare?) --}}
<div class="container" itemscope itemtype="https://schema.org/LocalBusiness">    
	
	<div class="row" id="datihotel">
		
		<div class="nome-hotel">
		
	  		<span class="item-name" itemprop="name">
	  			<div class="h1">{{{$cliente->nome}}}</div>
	        </span>
	        
	        {{{$cliente->stelle->nome}}}
	       <div class="dati-hotel" style="line-height:22px;">
		       	
		       	<div class="row" style="margin:0;">
			       	{!! $cliente->n_camere > 0 ? '<div class="col-xs-6"><b>'.$cliente->n_camere .'</b> '.trans('hotel.camere') . '</div>' : '' !!}
			        {!! $cliente->n_suite > 0 ? '<div class="col-xs-6"><b>'.$cliente->n_suite  .'</b> '.trans('hotel.suite') . '</div>' : '' !!}
			        {!! $cliente->n_appartamenti > 0 ? '<div class="col-xs-6"><b>'.$cliente->n_appartamenti  .'</b> '.trans('hotel.app') . '</div>' : '' !!}
			        {!! $cliente->n_posti_letto > 0 ? '<div class="col-xs-6"><b>'.$cliente->n_posti_letto  .'</b> '.trans('hotel.posti_letto') . '</div>' : '' !!}
		       	</div>
		        
		       
		        
			   	<div class="distanze-list">
			   		<b>{{trans("hotel.distanze")}}:</b><br />
			      	<div class="dist-listing"><img src="{{ Utility::asset('/mobile/img/centro.svg') }}"><span class="dist-text"> {{$cliente->getDistanzaDalCentroPoi($cliente->distanza_centro,$cliente->localita->centro_raggio)}}</span></div>
			      	<div class="dist-listing"><img src="{{ Utility::asset('/mobile/img/spiaggia.svg') }}"><span class="dist-text"> {{$cliente->distanza_spiaggia}} m</span></div>
			      	<div class="dist-listing"><img src="{{ Utility::asset('/mobile/img/treno.svg') }}"><span class="dist-text"> {{$cliente->distanza_staz}} Km</span></div>
				</div> 
		        
			</div>
			
		
		</div>
		
	</div>
	
	@include("share" , ["marginbottom"=>1, "text" => 'Guarda questo hotel a *'.$selezione_localita.'*'])
	
	{{-- pulsantiera --}}
		
   	<div class="tofix" >
   	
		<div class="row pulsantiera" >
			
			@if (!isset($disabled))
				
				<div class="col-xs-4 pulsante cyan ">
				 
				 @if ($cliente->attivo == -1)
			      	<a href="{{Utility::getUrlWithLang($locale,'/hotel.demo?id=demo&price-list')}}"  name="services_anchor" id="services_anchor" class="white-fe-pulsante">
				 @else
			     	<a href="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id.'&price-list')}}"  name="services_anchor" id="services_anchor" class="white-fe-pulsante">
				 @endif
			           <img src="{{Utility::asset("images/ReportCard.png")}}" />
			           {{ strtoupper(trans('hotel.listino_prezzi')) }}
			      </a>
				  
				</div>

				
				<div class="col-xs-4 pulsante green">
					<a data-id="{{$cliente->id}}" data-cliente="{{$cliente->nome}}" class="pulsante_chiama_scheda link_call white-fe-pulsante" href="tel:{{explode(',',$cliente->telefoni_mobile_call)[0]}}">
						<img style="padding:2px 0;" src="{{ Utility::asset('/mobile/img/telefono-call.svg') }}">
						{{trans("hotel.chiama")}}
					</a>
				</div>
				
								
				<div class="col-xs-4 pulsante orange">
					
					<div id="pulsante_scrivi_scheda" class="padding white-fe-pulsante email">
						<img src="{{Utility::asset("images/mail.png")}}">{{trans("hotel.scrivi")}}
						@if ($cliente->attivo == -1)
							{!! Form::open([ 'id'=>'emailmobileforms', 'url' => Utility::getUrlWithLang($locale,"/hotel.demo?id=demo&contact")]) !!} 
						@else
							{!! Form::open([ 'id'=>'emailmobileforms', 'url' => Utility::getUrlWithLang($locale,'/hotel.php?id='. $cliente->id . "&contact")]) !!} 
						@endif
						{!! Form::hidden('locale',$locale) !!}
						{!! Form::hidden('ids_send_mail', $cliente->id)!!}
						{!! Form::hidden('no_execute_prefill_cookie', true)!!}
						{!! Form::hidden('referer', $ref)!!}      
						{!!Form::close()!!}
					</div>
				
				</div>

				
				{{-- 
				NUOVI BOTTONI PER FORM DI PROVA WHATSAPPP 
				ATTENZIONE per gestire i form vedi Utility::assets/mobile/js/js-above/scheda.js
				--}}
				@if ($cliente->id == 17)
					<div class="row pulsantiera">

							<div class="col-xs-12 pulsante orange nuovo">
								
								<div id="pulsante_scrivi_scheda1" class="padding white-fe-pulsante email_2_bottoni">
									<img src="{{Utility::asset("images/mail.png")}}">{{trans("hotel.scrivi")}}								
									
									{!! Form::open([ 'id'=>'emailmobileforms_2_bottoni', 'url' => Utility::getUrlWithLang($locale,'/hotel.php?id='. $cliente->id . "&contact_2_bottoni")]) !!} 
									
									{!! Form::hidden('locale',$locale) !!}
									{!! Form::hidden('ids_send_mail', $cliente->id)!!}
									{!! Form::hidden('no_execute_prefill_cookie', true)!!}
									{!! Form::hidden('referer', $ref)!!}      
									{!!Form::close()!!}
								</div>
							
							</div>

							{{-- <div class="col-xs-4 pulsante orange">
								
								<div id="pulsante_scrivi_scheda2" class="padding white-fe-pulsante email_modal_first">
									<img src="{{Utility::asset("images/mail.png")}}">{{trans("hotel.scrivi")}}
									
									{!! Form::open([ 'id'=>'emailmobileforms_modal_first', 'url' => Utility::getUrlWithLang($locale,'/hotel.php?id='. $cliente->id . "&contact_modal_first")]) !!} 
									
									{!! Form::hidden('locale',$locale) !!}
									{!! Form::hidden('ids_send_mail', $cliente->id)!!}
									{!! Form::hidden('no_execute_prefill_cookie', true)!!}
									{!! Form::hidden('referer', $ref)!!}      
									{!!Form::close()!!}
								</div> --}}
							
							</div>
					</div> {{--  /pulsantiera --}}

				    <div class="button-write-all">
				    	{!! Form::open([ 'id'=>'emailmobileforms_modal_first', 'url' => Utility::getUrlWithLang($locale,'/hotel.php?id='. $cliente->id . "&contact_modal_first")]) !!} 
				    	
				    	{!! Form::hidden('locale',$locale) !!}
				    	{!! Form::hidden('ids_send_mail', $cliente->id)!!}
				    	{!! Form::hidden('no_execute_prefill_cookie', true)!!}
				    	{!! Form::hidden('referer', $ref)!!}      
				    	{!!Form::close()!!}
					    <button class="button small orange" onclick="document.getElementById('emailmobileforms_modal_first').submit();"><img src="{{Utility::asset('images/mail.png')}}" />&nbsp;<span>{{trans("hotel.scrivi")}}</span></button>
				    </div>
				@endif	


			
			@endif
			
	    </div>
	    
	</div>
    
    {{-- pulsantiera offerte --}}
        
    <?php
        
        $class="col-xs-3";
        
        if ($locale != "it"):
        	$class="col-xs-4";
        endif;
        
        $noff 	= $cliente->offerteTop->count();
        $noff  += $cliente->offerte->count();
        
        $nopp	= $cliente->offertePrenotaPrima->count();
        $nlast 	= $cliente->last->count();
        $nbg 	= $cliente->bambiniGratisAttivi->count();
        $nbg 	= $cliente->offerteBambiniGratisTop->count() + $nbg;
        $nc 	= $cliente->coupon->count();
        
             
       foreach($cliente->offerteTop as $ot) {
		   
		   if ($ot->tipo == "lastminute") {
		       $nlast++;
		       $noff--;
		   } 
		  
		   if ($ot->tipo == "prenotaprima") {
		       $nopp++;
		       $noff--;
		   } 
		  		  
       }
       
       ?>
    
    @include('hotel.pulsantiera' , array('margin' => 'si'))
     
    
    {{-- Scheda hotels --}}
    
    
    <div class="row 
    	@if (!$nlast && !$noff && !$nbg && !$nc) 
	    	margin-to-fix 
	    	@endif
	    " 
	    id="infohotel">
        	
      	@if ($cliente->cell != "" || $cliente->telefono != "")
    	
    	<div class="infodati_img"><img src="{{ Utility::asset('/mobile/img/telefono.svg') }}"></div>
    	
      	<div class="infodati">
      		<span>
      			@if ($cliente->telefono != "" ) <span itemprop="telephone">{{$cliente->telefono}}</span>@endif
      			@if ($cliente->cell != "" && $cliente->telefono != "")<br />@endif
      			@if ($cliente->cell != "")<span itemprop="telephone">{{$cliente->cell}}</span>@endif
      		</span>
      	</div>
      	
      	<br class="clear" /><br />
      	
      	@endif
      	
      	@if ($cliente->whatsapp != "")
    	<div class="infodati_img"><img src="{{ Utility::asset('/mobile/img/whatsapp_green.svg') }}" ></div>
      	
	      	<div class="infodati whatsapp">
	      		<span><small>WhatsApp</small>&nbsp;<span itemprop="telephone">{{$cliente->whatsapp}}</span></span>
	      			      		<?php /*<span><small><a href="whatsapp://send?text={{$title}} - http://www.info-alberghi.com{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id)}}">WhatsApp</a></small>&nbsp;<span itemprop="telephone">{{$cliente->whatsapp}}</span></span>*/ ?>
	      	</div>
      	
      	<br class="clear" /><br />
      	
      	@endif
    	
		@if ($cliente->link != '')
		
		<div class="infodati_img"><img src="{{ Utility::asset('/mobile/img/sitoweb.svg') }}"></div>
		
		<div class="infodati">
			<a href="{{ url('/away/'.$cliente->id) }}" target="_blank" rel="nofollow">
				{{ $cliente->testo_link != '' ? Utility::stripProtocol($cliente->testo_link) : Utility::stripProtocol($cliente->link) }}
			</a>
		</div>
		
		<br class="clear" /><br />
		
		@endif
    	
      	<div class="infodati_img"><img src="{{ Utility::asset('/mobile/img/indirizzo.svg') }}"></div>
      	
      	<div class="infodati" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" >
      	
      		<span itemprop="streetAddress">{{{ $cliente->indirizzo}}}</span><br />
      		<span itemprop="postalCode">{{{ $cliente->cap }}}</span> - <span itemprop="addressLocality">{{{ $cliente->localita->nome.' ('. $cliente->localita->prov . ')' }}}</span><br />
	  		
	  		@if ($cliente->attivo == -1)
		  		<a style="margin:10px 5px 0 0;display:inline-block; margin-bottom:0; padding:4px 10px;  " class="button small green" href="{{Utility::getUrlWithLang($locale,"/hotel.demo?id=demo&map")}}" title="{{trans('hotel.vedi_mappa')}}">
	  		@else
		  		<a style="margin:10px 5px 0 0;display:inline-block; margin-bottom:0; padding:4px 10px;  " class="button small green" href="{{Utility::getUrlWithLang($locale,"/hotel.php?id=" . $cliente->id . "&map")}}" title="{{trans('hotel.vedi_mappa')}}">
	  		@endif
	  			{{ trans('hotel.vedi_mappa') }}
	  		</a>
	  		
	  		
	  		<a style="margin:10px 0px 0 0;display:inline-block; padding:4px 10px;  " class="button small cyan" href="https://maps.google.com/?daddr={{$cliente->mappa_latitudine}},{{$cliente->mappa_longitudine}}" title="{{trans('hotel.indicaz')}}">
	  			{{ trans('hotel.indicaz') }}
	  		</a>
	  		
	  		
	  		
      	</div>
      	
      	<div class="clear"></div>
        
    </div>
    
    <div class="row" id="aperturahotel">
    
   		@include('composer.aperture')
    
    </div>
    
   	<div class="clear" ></div>
    
    
</div><!-- FNE ITEMSCOPE -->

<?php /*
<div class="container" id="socialbar">
<div class="row">
	
	<div class="col-xs-4 text">
		<span>{{trans("hotel.condividi")}}</span>
	</div>
	
	<div class="col-xs-8 social">
		<div class="addthis_sharing_toolbox" data-url="http://www.info-alberghi.com{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id)}}" data-title="{{$title}}" data-description="Tutte le specifiche sul sito di Info-Alberghi.com" data-media="{{$gallery_mobile[0][1]}}"></div>
	</div>
	

</div>
</div> */ ?>

<div class="container" style="margin-bottom:30px;">
	
	
	@include('composer.serviziGratuiti')
	
	@if (count($cliente->descrizioneHotel))
		<div class="row">
			<div class="col-xs-12" id="testohotel">
			<h3>{{trans("hotel.descrizione")}}</h3>
			
			<div class="testohotel">
			
				{!! $cliente->descrizioneHotel->descrizioneHotel_lingua->first()->testo !!}
				
			</div>
			
			</div>
		</div>
	@endif
	
	@include('hotel.pulsantiera', array('margin' => 'no'))
	@include('composer.puntiDiForza', array('titolo' => trans('labels.9punti_forza'), 'hotel_simili' => 0,  'in_hotel' => 1))
	@include('composer.servizi', array('titolo' => trans('hotel.servizi')))
	@include('composer.orari', array('titolo' => trans('hotel.orari')))
	
</div>

@endsection


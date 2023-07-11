@extends('templates.page_scheda', ['cliente' => $cliente, 'ref' => $ref])

@section('seo_title') {{$title}} @endsection
@section('seo_description') {{$description}} @endsection

@if (!isset($ref))
   <?php $ref = ''; ?>
@endif 

@section('content')
<div class="container">
	<div class="row white-bg">
		@if ($cliente->whatsapp != "")
			@include("phone_views/covid-banner")
			@include("share_wa" , ["marginbottom"=>1, "text" => 'Guarda questo hotel a *'.$selezione_localita.'*'])
		@else
			@include('phone_views/covid-banner')
		@endif
		@include("widget.item-favourite")
		@include("chiuso")
		@include('composer.hotelGallery',['gallery' => 'scheda'])
	</div> <!-- end row white-bg -->
</div> <!-- end 1 container -->


<div class="container">    
	
    @include("widget.pulsantiera-scheda")
    @include('hotel.pulsantiera' , array('margin' => 'si'))
    
    {{-- Scheda hotels --}}
    
    <div id="datihotel">

        <div class="row" >
            <div class="nome-hotel">
                <span class="item-name">
                    <div class="h1">{{{$cliente->nome}}}</div>
                    <span class="rating">
                        {{{$cliente->stelle->nome}}}
                    </span>
                </span>
                <div class="dati-hotel" style="line-height:22px;">
                    <div class="row" style="margin:0;">
                        {!! $cliente->n_camere > 0 ? '<div class="col-xs-6"><b>'.$cliente->n_camere .'</b> '.trans('hotel.camere') . '</div>' : '' !!}
                        {!! $cliente->n_suite > 0 ? '<div class="col-xs-6"><b>'.$cliente->n_suite  .'</b> '.trans('hotel.suite') . '</div>' : '' !!}
                        {!! $cliente->n_appartamenti > 0 ? '<div class="col-xs-6"><b>'.$cliente->n_appartamenti  .'</b> '.trans('hotel.app') . '</div>' : '' !!}
                        {!! $cliente->n_posti_letto > 0 ? '<div class="col-xs-6"><b>'.$cliente->n_posti_letto  .'</b> '.trans('hotel.posti_letto') . '</div>' : '' !!}
                    </div>
                    <div class="distanze-list">
                        <b>{{trans("hotel.distanze")}}:</b><br />
                        <div class="dist-listing"><img src="{{ Utility::asset('/mobile/img/centro.svg') }}"><span class="dist-text"> {{Utility::getDistanzaDalCentroPoi($cliente)}}</span></div>
                        <div class="dist-listing"><img src="{{ Utility::asset('/mobile/img/spiaggia.svg') }}"><span class="dist-text">m {{$cliente->distanza_spiaggia}}</span></div>
                        <div class="dist-listing"><img src="{{ Utility::asset('/mobile/img/treno.svg') }}"><span class="dist-text">km {{$cliente->distanza_staz}}</span></div>
                    </div> 
                </div>
            </div>
        
            @include("widget.item-review")
            
            <div id="infodati">
                @if ($cliente->cell != "" || $cliente->telefono != "")
                    <div class="infodati_img">
                        <img src="{{ Utility::asset('/mobile/img/telefono.svg') }}">
                    </div>
                    <div class="infodati">
                        <span>
                            @if ($cliente->telefono != "" ) <span>{{$cliente->telefono}}</span>@endif
                            @if ($cliente->cell != "" && $cliente->telefono != "")<br />@endif
                            @if ($cliente->cell != "")<span>{{$cliente->cell}}</span>@endif
                        </span>
                    </div>
                    <br class="clear" /><br />
                @endif

                @if ($cliente->whatsapp != "")
                    <div class="infodati_img">
                        <img src="{{ Utility::asset('/mobile/img/whatsapp_green.svg') }}" >
                    </div>
                    <a href="whatsapp://send?phone={{Utility::telephoneLink($cliente->whatsapp)}}&text=Gentile%20{{str_replace("+", "%20", urlencode($cliente->nome))}}%20" class="infodati whatsapp pulsante_whatsappa_scheda" data-id="{{$cliente->id}}">
                        <span><small>WhatsApp</small>&nbsp;<span>{{$cliente->whatsapp}}</span></span>
                    </a>
                    @php $field = 'notewa_'.$locale; if($cliente->$field != '') { echo "<br><span style='padding-left:35px;'><small style='color:#000;'>(".$cliente->$field.")</small></span>";}@endphp
                    <br class="clear" /><br />
                @endif

                @if ($cliente->link != '' && !$cliente->nascondi_url)
                    <div class="infodati_img">
                        <img src="{{ Utility::asset('/mobile/img/sitoweb.svg') }}">
                    </div>
                    <div class="infodati">
                        <a href="{{ url('/away/'.$cliente->id) }}" target="_blank" rel="nofollow">
                            {{ $cliente->testo_link != '' ? Utility::stripProtocol($cliente->testo_link) : Utility::stripProtocol($cliente->link) }}
                        </a>
                    </div>
                    <br class="clear" /><br />
                @endif

                <div class="infodati_img"><img src="{{ Utility::asset('/mobile/img/indirizzo.svg') }}"></div>
                <div class="infodati">

                    <span>{{{ $cliente->indirizzo}}}</span><br />
                    <span>{{{ $cliente->cap }}}</span> - <span>{{{ $cliente->localita->nome.' ('. $cliente->localita->prov . ')' }}}</span><br />

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
            </div>
            <div class="clear"></div>
        </div>
    
        <div class="row" id="aperturahotel">
        
            @include('composer.aperture')
        
        </div>
    
   	    <div class="clear" ></div>
    
    </div> 
    
</div> <!-- end 2 container -->


<div class="container" style="margin-bottom:30px;">
	
	@include('composer.serviziGratuiti')
	
	@include("covid-banner", [ 'testo_covid_banner' => $testo_covid_banner, "alternate" => true , "button" => false, "color" => "#B3E5FC", "etichetta" => "Covid-19: misure per la sicurezza in struttura"])
    @include('composer.serviziCovid', ["color" => "#E1F5FE"])	

	@if ($locale == "it" && $cliente->bonus_vacanze_2020 == 1)
		@include("covid-banner", [ 'link' => asset("bonus-vacanze"), 'testo_covid_banner' => "In questa struttura puoi usare il <strong>Bonus Vacanze</strong> valido fino al 31 Dicembre 2021.", "alternate" => true, "button" => false, "color" => "#FFF9C4", "etichetta" => "Bonus vacanze 2021" ])
	@endif

	@if ($cliente->descrizioneHotel)
		<div class="row">
			<div class="col-xs-12" id="testohotel">
			<h3>{{trans("hotel.descrizione")}}</h3>
			
			<div class="testohotel">
				
			@if(Utility::is_JSON($cliente->descrizioneHotel->descrizioneHotel_lingua->first()->testo ))
			
				<?php $paragrafi = json_decode($cliente->descrizioneHotel->descrizioneHotel_lingua->first()->testo); ?>	
				
				@if(!is_null($paragrafi) && count($paragrafi))
					@include("widget.scheda_content", ["paragrafi" => $paragrafi, "cliente" => $cliente])
				@else
					{!! $cliente->descrizioneHotel->descrizioneHotel_lingua->first()->testo !!}
				@endif
				
			@else
			
				{!! $cliente->descrizioneHotel->descrizioneHotel_lingua->first()->testo !!}
				
			@endif
			</div>
			
			</div>
		</div>
	@endif
	
	@include('hotel.pulsantiera', array('margin' => 'no'))
	@include('composer.puntiDiForza', array('titolo' => trans('labels.9punti_forza'), 'hotel_simili' => 0,  'in_hotel' => 1))
	@if ($cliente->caparreAttive()->count() && $locale == "it")
		<div class="row" id="caparre">
			<h3 class='title'>{{trans('hotel.canc_caparra')}}</h3>
			<div class='testo'>
				@include('widget.caparre', ['scheda_hotel' => true])
			</div>
		</div>
	@endif
	@include('composer.servizi', array('titolo' => trans('hotel.servizi')))
	@include('composer.orari', array('titolo' => trans('hotel.orari')))
	
	@if (isset($cliente) && isset($paragrafi))
		@include('composer.schemaOrgHotel', ['cliente' => $cliente, 'paragrafi' => $paragrafi])
	@endif

</div> <!-- end 3 container -->

@endsection


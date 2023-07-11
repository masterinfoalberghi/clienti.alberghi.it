 <?php
	 
	/**
	 * Costruzione dati pagina
	 */
	
	$noff = 0;
	$nopp = 0;
	$nlast = 0;
	$nbg = 0;
	
    $noff 	= $cliente->offerteTop()->attiva()->visibileInScheda()->count();
    $noff  += $cliente->offerte()->attiva()->count();   
    $nopp	= $cliente->offertePrenotaPrima()->attiva()->count();
    $nlast 	= $cliente->last()->attiva()->count();
    $nbg 	= $cliente->bambiniGratisAttivi->count();
    $nbg   += $cliente->offerteBambiniGratisTop()->attiva()->count();
    
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

@extends('templates.page_scheda')

@section("seo_title"){{$title}}@endsection
@section("seo_description"){{$description}}@endsection

@section('content')
	
	<a id="gallery-scroll" name="gallery"></a>

	@include('composer.hotelGallery')
    
    <div class="clearfix"></div>
    
    <a id="informations-scroll" name="informations"></a>
    
    <section id="informazioni" >   
	    
	    <header>
			<h2 class="content-section">{{$cliente->nome}}</h2>
        </header>
        
	    
	    <div class="infowindowcontent">
	    <div class="col-sm-12 @if (\Browser::isDesktop()) font-size-plus-hotel @endif">
	    	<?php 

				$numeri = array();  

				$cliente->telefono != '' ? $numeri[] = '<span><i  class=" icon-phone"></i>&nbsp;<b>' . $cliente->telefono .'</b></span>' : '';
				$cliente->cell != '' 	 ? $numeri[] = '<span>&nbsp;<b>' . $cliente->cell .'</b></span>' : '';
				// $cliente->fax != '' 	 ? $numeri[] = '<span>Fax:&nbsp;<b>' . $cliente->fax . '</b></span>' : '';
				
				$contatti = array(); 

				/////////////////////////////////////////////
				// GESTIONE WhatsApp con le note in lingua //
                /////////////////////////////////////////////
                
				$wa = '';
				if($cliente->whatsapp != '') 
					{
					$wa = '<i class="icon-whatsapp"></i>&nbsp;<strong><a href="https://wa.me/39'. str_replace([" ","-","/"],"", $cliente->whatsapp).'" target="_blank"><u>'.$cliente->whatsapp.'</u></a></strong>';
					$field = 'notewa_'.$locale;
					if($cliente->$field != '')
						{
						$wa .= ' ('.$cliente->$field.') ';
						}
					
					}
				
				$wa != '' ? $contatti[] = $wa : '';
				// $cliente->skype != '' 	 ? $contatti[] = '<i class="icon-skype"></i>&nbsp;<a href="skype:'.$cliente->skype.'?chat"><u><strong>'.$cliente->skype.'</strong></u></a>' : '';
				
				$url_cliente = "";
				
				if($cliente->link != '' && !$cliente->nascondi_url)
					$url_cliente .= '<i class="icon-globe"></i>&nbsp;<a href="'. url("/away/".$cliente->id) .'" target="_blank" rel="nofollow"><u><strong>'.($cliente->testo_link != '' ? Utility::stripProtocol($cliente->testo_link) : Utility::stripProtocol($cliente->link)).'</strong></u></a>';
									
				$distanze = array(); 
				$distanze[] = trans("labels.centro")   . ': <b>' . Utility::getDistanzaDalCentroPoi($cliente) . '</b></span>';
				$distanze[] = trans("labels.spiaggia") . ': <b>m ' . $cliente->distanza_spiaggia . '</b></span>';
				$distanze[] = trans("labels.stazione") . ': <b>km ' . $cliente->distanza_staz .'</b></span>';
				$distanze[] = trans("labels.fiera")    . ': <b>km ' . $cliente->distanza_fiera .'</b></span>';
				
			?>
	        
	        <div class="col-xs-12">
			    
			    {{ trans('hotel.cat') }}: <strong>{{{$cliente->stelle->descrizione()}}}</strong>
		    	{!! $cliente->n_camere > 0 ? ' - <strong>'.$cliente->n_camere . '</strong>'.' '.trans('hotel.camere') : '' !!}
			    {!! $cliente->n_appartamenti > 0 ? ' - <strong>'.$cliente->n_appartamenti . '</strong>' .' '.trans('hotel.app') : '' !!}
			    {!! $cliente->n_suite > 0 ? ' - <strong>'.$cliente->n_suite . '</strong>' .' '.trans('hotel.suite') : '' !!}
			    {!! $cliente->n_posti_letto > 0 ? ' - <strong>'.$cliente->n_posti_letto . '</strong>' .' '.trans('hotel.posti_letto') : '' !!}
			    
			</div><br />
            <div style="font-size:16px;">
							@include("widget.item-review")
            </div>

		    <div class="clearfix"></div>
		    <br />

		    <div class="col-xs-12">

				<i class="icon-location"></i> {{ $cliente->indirizzo}} - {{ $cliente->cap }} {{ $cliente->localita->nome.' ('. $cliente->localita->prov . ')' }}<br/>
				{!! implode("&nbsp;&nbsp;&nbsp;" , $numeri); !!} @if(count($contatti))/@endif {!! implode("&nbsp;&nbsp;&nbsp;" , $contatti); !!} <br /> {!! $url_cliente !!}

			</div>
			<div class="clearfix"></div>
			@if ($cliente->chiuso_temp)

				<div class="chiusoTemp" style="color:#EE4337;">
					{{__("labels.chiusura_temporanea")}}
				</div>

		@endif

			<div class="clearfix"></div>
			<br />

			<div class="col-xs-12">
				<b>{{trans('hotel.distanze')}}:</b>&nbsp;{!! implode("&nbsp;-&nbsp;" , $distanze); !!}
			</div>

			<div class="clearfix"></div>
			
		</div>
		
		<div class="clearfix"></div>
	  	
		<div class="padding-top">

			{{-- @if ($nbg || ($nlast && $nlast>0) || ($locale=="it" && $nopp) || ($noff && $noff>0)) --}}
							
				@if ($nbg)
				
					<a id="btn-children-offers" class="btn btn-cyano btn-scroll" href="#children-offers" data-scroll="children-offers" data-url="children-offers">
						{{ trans('title.offerte_bambini') }}
						<span class="badge">{{$nbg}}</span>
					</a>

				@else

					<a id="btn-children-offers" class="btn btn-scroll grayButton" href="" data-url="children-offers">
						{{ trans('title.offerte_bambini') }}
						<span class="badge grayButton">{{$nbg}}</span>
					</a>

				@endif
				
				
				@if ($nlast && $nlast>0)
				
					<a id="btn-lastminute" class="btn btn-rosso btn-scroll" href="#lastminute" data-scroll="lastminute">
						{{ trans('hotel.last') }}
						<span class="badge">{{$nlast}}</span>
					</a>
				@else

					<a id="btn-lastminute" class="btn btn-scroll grayButton" href="">
						{{ trans('hotel.last') }}
						<span class="badge grayButton">{{$nlast}}</span>
					</a>

				@endif
				    
				@if ($locale=="it" && $nopp) 
									
					<a id="btn-prenotaprima"  class="btn btn-viola btn-scroll" href="#prenotaprima" data-scroll="prenotaprima" >
						{{ trans('labels.prenota') }}
						<span class="badge">{{$nopp}}</span>
					</a>

				@else
					
					<a id="btn-prenotaprima"  class="btn btn-scroll grayButton" href="">
						{{ trans('labels.prenota') }}
						<span class="badge grayButton">{{$nopp}}</span>
					</a>
									
				@endif
					
				@if ($noff && $noff>0)
					
					<a id="btn-offers" class="btn btn-acqua btn-scroll" href="#offers" data-scroll="offers">
					    {{ trans('hotel.offerte_generiche') }}
					    <span class="badge">{{$noff}}</span>
					</a>

				@else
					
					<a id="btn-offers" class="btn btn-scroll grayButton" href="">
					    {{ trans('hotel.offerte_generiche') }}
					    <span class="badge grayButton">{{$noff}}</span>
					</a>

				@endif

			{{-- @endif --}}

			@if ( 
					!\Browser::isTablet() && (
						$cliente->listini->count() != 0 ||
						$cliente->listiniMinMax->count() != 0 ||
						$cliente->listiniCustom->count() != 0 
					)
				)
			<a id="btn-price-list" href="#price-list" data-scroll="price-list" class="btn btn-blu btn-scroll" style="float:right;">{{trans("title.prezzi")}}</a>
			@endif

				<div class="clearfix"></div>
		</div>

	    <div class="padding-top content-scheda">
	    
	    	@include('composer.serviziGratuiti')	    	
			@include('composer.aperture', array('titolo' => trans('hotel.apertura')))
			@include('composer.puntiDiForza', array('titolo' => trans('labels.9punti_forza'), 'class' => 'list-9-servizi', 'in_hotel' => 1, 'hotel_simili' => 0))

			@if ($cliente->descrizioneHotel)
		
				<div class="padding-bottom">
					{{-- @include('hotel.covid19_hotel_banner', [ 'testo_covid_banner' => $testo_covid_banner ]) --}}
                    @include("covid-banner", [ 'testo_covid_banner' => $testo_covid_banner, "alternate" => true, "button" => false, "color" => "#B3E5FC", "etichetta" => "Covid-19: misure per la sicurezza in struttura"  ])
                    @include('composer.serviziCovid', ["color" => "#E1F5FE"])	
				</div>
				
				@if ($locale == "it" && $cliente->bonus_vacanze_2020 == 1)
					<div class="padding-bottom">
						@include("covid-banner", [ 'link' => asset("bonus-vacanze"), 'testo_covid_banner' => "In questa struttura puoi usare il <strong>Bonus Vacanze</strong> valido fino al 31 Dicembre 2021.", "alternate" => true, "button" => false, "color" => "#FFF9C4", "etichetta" => "Bonus vacanze 2021" ])
					</div>
				@endif
			
				<div class="padding-bottom">
					@if(Utility::is_JSON($cliente->descrizioneHotel->descrizioneHotel_lingua->first()->testo ))
						
						<?php $paragrafi = json_decode($cliente->descrizioneHotel->descrizioneHotel_lingua->first()->testo); ?>
							{{-- dd($paragrafi) --}}
						@if(!is_null($paragrafi) && count($paragrafi))
							@include("widget.scheda_content", ["paragrafi" => $paragrafi])
						@else
							{!! $cliente->descrizioneHotel->descrizioneHotel_lingua->first()->testo !!}
						@endif
						
					@else
					
						{!! $cliente->descrizioneHotel->descrizioneHotel_lingua->first()->testo !!}
						
					@endif
				
				</div>

				@if ($cliente->caparreAttive()->count() && $locale == "it")
                    <div class="padding-bottom">
                        <div class="box-caparra" style="background: #ECF3E5; border: 1px solid #8CC152; padding:30px; border-radius:3px; margin-left:-30px;">
                            <h3 >{{trans('labels.politiche_canc')}}</h3>
                            @include("widget.caparre", ['scheda_hotel' => true])
                        </div>
                    </div>
			    @endif
				
			@endif 
	        
			@include('composer.orari', array('titolo' => trans('hotel.orari')))
			
			
		</div>
		
		<div class="clearfix"></div>
	
		@if (isset($cliente) && isset($paragrafi))
			@include('composer.schemaOrgHotel', ['cliente' => $cliente, 'paragrafi' => $paragrafi])
		@endif

    </section> {{-- end Informazioni --}}
    
	<a id="price-list-scroll" name="price-list"></a>

		<section id="listino" class="padding-bottomx2 ">   
			
			<header>
				<h2 class="content-section">{{trans("title.prezzi")}}</h2>
			</header>
	
			@if ( 
				
					$cliente->listini->count() != 0 ||
					$cliente->listiniMinMax->count() != 0 ||
					$cliente->listiniCustom->count() != 0
				
			)
					@include('composer.listino', 			array('titolo' => trans('hotel.listino_prezzi')))
					@include('composer.listinoMinMax', 		array('titolo' => trans('hotel.listino_minmax')))
					@include('composer.listiniCustom', 		array('titolo' => trans('hotel.listino_variabile')))
						
					@if ($cliente->notaListino)
						
						@if ( $cliente->notaListino->noteListino_lingua->first()->testo != '')
							<div class="note content-scheda">
								<div>
									<h4>{!! Lang::get('hotel.listino_note') !!}</h4>
									{!! $cliente->notaListino->noteListino_lingua->first()->testo !!}
									
									 @php
										$tassaSoggiorno = App\TassaSoggiorno::getTassaLabel($cliente->id);
										if (isset($tassaSoggiorno[0])):
											echo strtoupper($tassaSoggiorno[0]) . "<br />- ";
											unset($tassaSoggiorno[0]); 
											echo  implode("<br/>- ", $tassaSoggiorno) . "<br/><br/>";
										endif;
									@endphp
									
								</div>
							</div>
		
						@endif
						
					@endif
					<div class="clearfix"></div>
					
			@else
			
				{!! trans("hotel.no_listino") !!}
			
			@endif
	
	
	</section>
		
	@if ($nbg || ($nlast && $nlast>0) || ($nopp && $nopp>0) || ($noff && $noff>0))
	
		<section id="offers"> 
			
			<header>
				<h2>{{trans("title.offerte")}}</h2>
			</header>
			
			<section class="box-offers bg">
				
			    @include('composer.offerteBambiniGratisTop', array('titolo' => trans('title.bg')))
				@include('composer.bambiniGratis', array('titolo' => trans('title.bg')))
			    
			</section>
			
			@include('composer.offerteTop', array('tipoTop' => 'lastminute'))
	    	@include('composer.offerteTop', array('tipoTop' => 'prenotaprima'))               
	        @include('composer.offerteTop', array('tipoTop' => 'offerta'))     
		    
		</section>
	
	@endif

	<div class="clearfix"></div>	
	
@endsection




<?php
	
	/**
	 * Costruisco i dati che mi servono
	 */
		
	$n_foto = $cliente->immagini_gallery_count; 
	$noff = $cliente->numero_offerte_attive->isEmpty() ? 0 : $cliente->numero_offerte_attive->first()->tot;
	$nlast = $cliente->numero_last_attivi->isEmpty() ? 0 : $cliente->numero_last_attivi->first()->tot;
	$bg = $cliente->numero_bambini_gratis_attivi->isEmpty() ? trans('labels.no') : trans('labels.si');
	$coupon = $cliente->numero_coupon_attivi->isEmpty() ? 0: $cliente->coupon->first()->valore . '&euro;';	
	
	/*
		
		$n_foto = $cliente->immagini_gallery_count; 
        $noff 	= $cliente->offerteTop()->attiva()->count();
        $noff   += $cliente->numero_offerte_attive->isEmpty() ? 0 : $cliente->numero_offerte_attive->first()->tot;
        $nbg 	 = $cliente->offerteBambiniGratisTop()->attiva()->count();
        $nbg 	+= $cliente->bambiniGratisAttivi->isEmpty() ? 0 : $cliente->bambiniGratisAttivi->first()->tot;
        $nopp	= $cliente->offertePrenotaPrima()->attiva()->count();
        $nlast 	= $cliente->numero_last_attivi->isEmpty() ? 0 : $cliente->numero_last_attivi->first()->tot;

                     
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
       
       $coupon = $cliente->numero_coupon_attivi->isEmpty() ? 0: $cliente->coupon->first()->valore . '&euro;';
	   $bg = $nbg == 0 ? trans('labels.no') : trans('labels.si');
			
	*/

	if(!isset($listing_gruppo_piscina))
		$listing_gruppo_piscina = Config::get("services.listing_gruppo_piscina");

	if(!isset($listing_gruppo_benessere))
		$listing_gruppo_benessere = Config::get("services.listing_gruppo_benessere");
		
	/**
	 * Sono un cliente TOP
	 */
	 
	$top 	= $cliente->getTop() != '' ? ' top' : '';  
	$cliente->gettEvidenzaBB() ? $top = ' top' : $top = '';
	$class_item .= $top;

?>
	
<article class="item_listing animation @if(isset($class_item)) {{$class_item}}  @endif" data-url="{{$url}}"  data-id="{{$cliente->id}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}">
				
	<header>
		<hgroup>
			<a href='{{$url}}'><h2>{{$cliente->nome}}</h2></a>
			<span class="rating">{{{$cliente->stelle->nome}}}</span>
		</hgroup>
	</header>
			
	<address>
		<span class="hide indirizzo">{{{ $cliente->indirizzo }}}</span>
		<span class="hide cap">{{{ $cliente->cap }}}</span>
		<span class="hide macrolocalita">{{{ $cliente->localita->macrolocalita->nome.' ('. $cliente->localita->prov . ')' }}}</span>
		<span class="localita">{{{ $cliente->localita->nome }}}</span>
	</address>

	<figure>
		<img src='{{Utility::asset($image_listing)}}' class="alignleft" alt="{{{$cliente->nome}}}">
	</figure>		
			
			
	<div class="content">								
		
		@include('composer.puntiDiForza')
				
	</div>
		
	<div class="prezzi">
		
			@if ( $cliente->prezzo_min > 0  || $cliente->prezzo_max >0)
		        <strong>{{strtoupper(trans('labels.prezzi'))}}</strong><br/>
		        @if($cliente->prezzo_min > 0) {{ trans('listing.p_min') }} {{ $cliente->prezzo_min }} &euro;<br/> @endif
		        @if($cliente->prezzo_max > 0) {{ trans('listing.p_max') }} {{ $cliente->prezzo_max }} &euro;<br/> @endif
	        @endif
	    
	</div>

	<footer>
		
			<div class="offerte">
				<strong>{{ trans('labels.off') }}</strong>: {{$noff}} - <strong>{{ trans('labels.last') }}</strong>: {{$nlast}} - <strong>{{ trans('labels.coupon') }}</strong>: {{$coupon}} - <strong>{{ trans('labels.bg') }}</strong>: {{$bg}}
			</div>		
		

		<div class="distanze">
			<strong>{{ trans('hotel.distanze') }}:&nbsp;</strong> 
			<span><img src="{{ Utility::asset('images/ico_centro.png') }}" alt="Distanza dal centro" title="Distanza dal centro" />{{Utility::getDistanzaDalCentroPoi($cliente)}}</span> 
			<span><img src="{{ Utility::asset('images/ico_spiaggia.png') }}" alt="Distanza dalla spiaggia" title="Distanza dalla spiaggia" />m {{$cliente->distanza_spiaggia}}</span> 
			<span><img src="{{ Utility::asset('images/ico_treno.png') }}" alt="distanza stazione fs" title="Distanza stazione FS"/>km {{$cliente->distanza_staz}}</span>
		</div>

		<span class="hearth"><img src="{{ Utility::asset('images/cuore.png') }}" /></span>
		
		<div>
			<label class="css-label" for="checkbox_{{{$cliente->id}}}">{{ trans('listing.seleziona') }}</label>
			<input type="checkbox" id="checkbox_{{{$cliente->id}}}" class="css-checkbox beautiful_checkout" />
		</div>
		
		<span class="labels">
			

			@if ($cliente->annuale)

				<span class="apertura">{{ trans('listing.annuale') }}</span>
			
			@endif
			
			<span class="foto">{{$n_foto}} {{ trans('labels.foto') }}</span>

			<?php 
			
			/**
			 * VERIFICO I SERVIZI LISTING 
			 * ATTENZIONE QUESTO FUNZIONA SOLO SE HO EAGERLOADATO LE MODEL
			 * ALTRIMENTI LUI FA LE QUERY E NON E' MAI NULLO PERCHE' $cliente->servizi prende tutti i servizi e basta (è nell' eager loading che ho il filtro per gruppo e categoria) 
			 * ed anche $servizio->categoria prende la categoria SENZA filtro listing=1
			 * QUINDI DEVO VERIFICARE SE il CLIENTE HA CARICATO LA RELAZIONE CON I SERVIZI; utilizzo il metodo della model relationLoaded (Determine if the given relation is loaded.)
			 */ ?>
			 
			@if ($cliente->relationLoaded('servizi') && !is_null($cliente->servizi))
				@foreach ($cliente->servizi as $servizio)
					@if ($servizio->relationLoaded('categoria') && !is_null($servizio->categoria))
					
						<?php /** è un servizio che appartiene ad una categoria di tipo Listing */ ?>
						
						<span class="servizio_listing">	
							
							<?php 
								/**
								 * per la pisicna la label NON E' DEL TIPO "<nome servizio> a x metri" (ES: "Piscina fuori hotel a 200 mt") 
								 * MA del tipo "piscina a x metri dall'hotel"
								 * QUINDI SI UTILIZZANO DELLE LABEL
								 */
							?>
							
							@if ($tipoContenuto=="itemPiscina" && $servizio->translate('it')->first()->nome == 'piscina fuori hotel')
								{{ trans('listing.piscina') }} {{ trans('labels.a') }} {{ $servizio->pivot->note }} {{ trans('labels.metri') }} {{ trans('labels.dall_hotel') }}
							@else
								{{ $servizio->servizi_lingua->first()->nome }} {{ trans('labels.a') }} {{ $servizio->pivot->note }} {{ trans('labels.metri') }}
							@endif
							
						</span>
						
					@endif
				@endforeach
			@endif
			
		</span>
		
		<button>{{ trans('listing.vedi_hotel') }}</button>
		
	</footer>
			
	
</article>
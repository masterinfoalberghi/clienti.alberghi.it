<?php
	
	/**
	 * Costruisco i dati che mi servono
	 */
	 
	 $n_foto = $cliente->immagini_gallery_count;
	 
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
		
		<a href="{{$url}}">{{$vot->titolo}}</a>
							
			@if ($vot->offerta->per_persone != 0 || $vot->offerta->per_giorni != 0)
					
					{{ trans('hotel.valido_da_al_3') }} 
					@if ($vot->offerta->per_persone != 0) 
						{{$vot->offerta->per_persone}} {{ trans('hotel.valido_da_al_4') }} 
					@endif
					
					@if ($vot->offerta->per_giorni != 0) 
						@if ($vot->offerta->per_persone != 0) 
							{{ trans('hotel.valido_da_al_4_1') }}
						@endif 
						{{$vot->offerta->per_giorni}} {{ trans('hotel.valido_da_al_5') }}
					@endif
					
			@endif
			
			{!!Utility::getExcerpt($vot->testo, $limit = 30, $strip = true)!!}
	
	</div>

			
	<div class="prezzi">
		@if ($vot->offerta->tipo == 'prenotaprima')
			<span class="price">
			{{$vot->offerta->perc_sconto}}%
			{{ trans('hotel.prenota_entro') }}<br><span class="prenota_entro">{{Utility::getLocalDate($vot->offerta->prenota_entro, '%d/%m/%y')}}</span>
			</span>
		@else
			@if ($vot->offerta->prezzo_a_persona > 0)
				<span class="price">
				{{ trans('listing.da') }} &euro; {{$vot->offerta->prezzo_a_persona}}
				</span>
			@endif
		@endif
	</div>
			
	<footer>
		
		@if ($tipoContenuto == "itemVetrina")
		
			<div class="offerte">
				<strong>{{ trans('labels.off') }}</strong>: {{$noff}} - <strong>{{ trans('labels.last') }}</strong>: {{$nlast}} - <strong>{{ trans('labels.bg') }}</strong>: {{$bg}}
			</div>		
			
		@endif
		
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
						
		</span>
		
		<button>{{ trans('listing.vedi_hotel') }}</button>
		
	</footer>
		
</article>{{-- /.item_wrapper --}}	
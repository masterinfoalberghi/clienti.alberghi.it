<?php

	/**
	 * Costruisco i dati che mi servono
	 */

	/**
	 * ATTENZIONE METTO NELLO SCOPE SESSION l'associazione hotel->offerta in modo da poterla riproporre mentre faccio gli ordinamenti che sono AJAX
	 * IN REALTA' LA SESSION ESISTE SEMPRE
	 * Perché nel controller CmsPaginaController::index/listing
	 *
	 * if($request->hasSession())
	 * {
	 * $cliente_offerta = [];
	 * $request->session()->forget('cliente_offerta');
	 * $request->session()->put('cliente_offerta',$cliente_offerta);
	 * }
	 */

	 $tipo_offerta = "offers";

	 /**
	  * se ho questo array che associa id_hotel a offerta in session
	  */

	if (app('request')->session()->has('cliente_offerta'))
	{

		$cliente_offerta = app('request')->session()->get('cliente_offerta');

		if (array_key_exists($cliente->id,$cliente_offerta)) {

		    /**
			 * se il cliente è in questo array prendo l'offerta in sessione
			 */

		    $offerteLast = $cliente_offerta[$cliente->id];
		    $off_lingua = $offerteLast->offerte_lingua->first();

		} else {

			if ( isset($offerte_selezionate) && isset($offerte_selezionate[$cliente->id]) &&  !is_null($offerte_selezionate[$cliente->id]) ) {

				$offerteLast = $offerte_selezionate[$cliente->id];
				$off_lingua = $offerteLast->offerte_lingua->first();

			} else {

				/**
				 * se il cliente NON è in questo array prendo un'offerta RANDOM e la metto in sessione per gli ordinamenti ajax
				 * collezione di tutte le offerte, anche quelle con offerte_lingua = [],
				 * cioè quelle CHE NON HANNO IL TESTO DA PRENDERE (non sono in italiano OPPURE NON HANNO LA PAROLA CHIAVE)
				 */

				 if ($listing_offerta == 'offerta')
				 	$off_collection = $cliente->offerte;

				 elseif ($listing_offerta == 'lastminute')
		        {
			        $tipo_offerta = "lastminute";
			        $off_collection = $cliente->last;
		        }
				elseif ($listing_offerta == 'offerta_prenota_prima')
					$off_collection = $cliente->offertePrenotaPrima;
				else
					$off_collection = $cliente->offerteLast;

				if (!is_null($off_collection)) {
			        $filtered = $off_collection->reject(function ($offerteLast) {
			            return !$offerteLast->offerte_lingua->count();
			        });
				}

				if(isset($filtered) && !$filtered->isEmpty()) {

			        $offerteLast = $filtered->random(1);
			        $off_lingua = $offerteLast->offerte_lingua->first();

		        } else {

				    $offerteLast = null;
				    $off_lingua = null;

			    }

			}

			$cliente_offerta[$cliente->id] = $offerteLast;
			app('request')->session()->put('cliente_offerta',$cliente_offerta);

	    }

	} else {

	    if (isset($offerte_selezionate) && !is_null($offerte_selezionate[$cliente->id])) {

	      $offerteLast = $offerte_selezionate[$cliente->id];
	      $off_lingua = $offerteLast->offerte_lingua->first();

	    } else {

	      /**
		   * collezione di tutte le offerte, anche quelle con offerte_lingua = [],
	       * cioè quelle CHE NON HANNO IL TESTO DA PRENDERE (non sono in italiano OPPURE NON HANNO LA PAROLA CHIAVE)
	       * però SOLO SE CARICO DAL EAGER LOADING !!!!
	       */

		   	if ($listing_offerta == 'offerta')
		   		$off_collection = $cliente->offerte;

		   	elseif ($listing_offerta == 'lastminute')
		   	{
		        $tipo_offerta = "lastminute";
		        $off_collection = $cliente->last;
	        }
			elseif ($listing_offerta == 'offerta_prenota_prima')
		        $off_collection = $cliente->offertePrenotaPrima()->attiva()->get();

			else
		        $off_collection = $cliente->offerteLast;

			$filtered = $off_collection->reject(function ($offerteLast) {
				return !$offerteLast->offerte_lingua->count();
			});

	        if(!$filtered->isEmpty()){

				$offerteLast = $filtered->random(1);
				$off_lingua = $offerteLast->offerte_lingua->first();

	        } else {

	          $offerteLast = null;
	          $off_lingua = null;

	        }

	    }

	}

	$n_foto = $cliente->immagini_gallery_count;

?>

<article class="item_listing animation @if(isset($class_item)) {{$class_item}}  @endif" data-url="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id)}}&{{$tipo_offerta}}"  data-id="{{$cliente->id}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}">

	<header>
		<hgroup>
			<a href='{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id)}}&{{$tipo_offerta}}'><h2>{{$cliente->nome}}</h2></a>
			<span class="rating">{{{$cliente->stelle->nome}}}</span>
		</hgroup>
	</header>

	<address>
		<span class="hide indirizzo">{{{ $cliente->indirizzo }}}</span>
		<span class="hide cap">{{{ $cliente->cap }}}</span>
		<span class="hide macrolocalita">{{{ $cliente->localita->macrolocalita->nome.' ('. $cliente->localita->prov . ')' }}}</span>
		<span class="localita">{{{ $cliente->localita->nome }}} - {{ $cliente->indirizzo }}</span>
	</address>

	<figure>
		<img src='{{Utility::asset($image_listing)}}' class="alignleft" alt="{{{$cliente->nome}}}">
	</figure>

	<div class="description">

        <a  href="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id)}}&{{$tipo_offerta}}" >
        	{{$off_lingua->titolo}}
        </a>

        {{ trans('hotel.valido_da_al_1') }} {{Utility::getLocalDate($offerteLast->valido_dal, '%d/%m/%y')}}
        {{ trans('hotel.valido_da_al_2') }} {{Utility::getLocalDate($offerteLast->valido_al, '%d/%m/%y')}}
        @if ($offerteLast->per_persone != 0 || $offerteLast->per_giorni != 0)
          - {{ trans('hotel.valido_da_al_3') }} {{$offerteLast->per_persone}} {{ trans('hotel.valido_da_al_4') }} {{$offerteLast->per_giorni}} {{ trans('hotel.valido_da_al_5') }} </span>
        @endif

        {!!Utility::getExcerpt($off_lingua->testo, $limit = 30, $strip = true)!!}


  	</div>

    <div align="right" class="prezzi">

          @if ($listing_offerta == 'offerta_prenota_prima')
	          - {{$offerteLast->perc_sconto}}%</strong><br>{{ trans('hotel.prenota_entro') }}&nbsp;<span class="prenota_entro">{{Utility::getLocalDate($offerteLast->prenota_entro, '%d/%m/%y')}}
          @else
	          {{ trans('listing.da') }} &euro; {{$offerteLast->prezzo_a_persona}}<br/>
	          {!!Utility::getTrattamentoOfferte($offerteLast->formula)!!}
          @endif
    </span>

  </div>{{-- ./right --}}

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

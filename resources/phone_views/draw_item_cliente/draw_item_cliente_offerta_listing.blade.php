@php


        
    /** 
     * ATTENZIONE METTO NELLO SCOPE SESSION l'associazione hotel->offerta in modo da poterla riproporre mentre faccio gli ordinamenti che sono AJAX
     */

    $tipo_offerta = "offers";

    /** se ho questo array che associa id_hotel a offerta in session */ 
    
    if (app('request')->session()->has('cliente_offerta')) { 
    
        /** leggo dalla sessione */
        $cliente_offerta = app('request')->session()->get('cliente_offerta');

        if (array_key_exists($cliente->id,$cliente_offerta))
        {
            
            /** Se il cliente è in questo array prendo l'offerta in sessione */
            $offerteLast = $cliente_offerta[$cliente->id];
            $off_lingua = $offerteLast->offerte_lingua->first();

        } else {
            
            /** 
             * Se il cliente NON è in questo array prendo un'offerta RANDOM e la metto in sessione per gli ordinamenti ajax
             * ollezione di tutte le offerte, anche quelle con offerte_lingua = [], 
             * cioè quelle CHE NON HANNO IL TESTO DA PRENDERE (non sono in italiano OPPURE NON HANNO LA PAROLA CHIAVE)
             */
    
            if ($listing_offerta == 'offerta') 
                $off_collection = $cliente->offerte;

            elseif ($listing_offerta == 'lastminute') 
            {
                $tipo_offerta = 'lastminute';
                $off_collection = $cliente->last;
            }
            elseif ($listing_offerta == 'offerta_prenota_prima') 
                $off_collection = $cliente->offertePrenotaPrima;

            else 
                $off_collection = $cliente->offerteLast;
    
            $filtered = $off_collection->reject(function ($offerteLast) {
                return !$offerteLast->offerte_lingua->count();
            });
    
            if(!$filtered->isEmpty())
            {
                $offerteLast = $filtered->random(1);
                $off_lingua = $offerteLast->offerte_lingua->first();
            } else {
                $offerteLast = null;
                $off_lingua = null;
            }

            $cliente_offerta[$cliente->id] = $offerteLast; 
            app('request')->session()->put('cliente_offerta',$cliente_offerta);
    
        }

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
            $tipo_offerta = 'lastminute';
            $off_collection = $cliente->last;
        }
        elseif ($listing_offerta == 'offerta_prenota_prima') 
            $off_collection = $cliente->offertePrenotaPrima()->attiva()->get();

        else 
            $off_collection = $cliente->offerteLast;

        $filtered = $off_collection->reject(function ($offerteLast) {
            return !$offerteLast->offerte_lingua->count();
        });

        if(!$filtered->isEmpty())
        {
            $offerteLast = $filtered->random(1);
            $off_lingua = $offerteLast->offerte_lingua->first();
        } else {
            $offerteLast = null;
            $off_lingua = null;
        }

    }  

    $n_foto = $cliente->immagini_gallery_count;

@endphp

@if (!is_null($offerteLast) && !is_null($off_lingua))

    <article data-id="{{$cliente->id}}" data-url="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id . '&' . $tipo_offerta )}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="draw_item_cliente_offerta_listing link_item_slot @if(isset($class_item)) {{$class_item}} @endif">
        
        <figure class="col-img-draw">
        	<img width="360" height="200" class="img-listing" src="{{asset($image_listing)}}" alt="<?php echo htmlspecialchars($cliente->nome); ?>">
    			@if (isset($n_foto) && $n_foto > 0)
    				<span class="foto">{{$n_foto}} {{ trans('labels.foto') }}</span>
    			@endif
                @if ($cliente->annuale)
                    <div class="labels">
                        <span class="apertura">{{ trans('listing.annuale') }}</span>
                    </div>
                @endif
        </figure>

        <div class="col-text-draw">
            <div class="info-listing">
                <div class="nomehotel ">
                    <header class="item-name tover">
                        <h2><a href='{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id . '&' . $tipo_offerta, false )}}'>{{{$cliente->nome}}}</a></h2>
                    </header>
                    
                    <span class="rating">
                        <meta content="{{$cliente->stelle->ordinamento}}" />{{$cliente->stelle->nome}}
                    </span>
                    
                    <br /><small>{{{ $cliente->localita->nome }}}</small>
                    
                </div>
            </div>
            
            @if ($offerteLast->prezzo_a_persona) 
            	<var class="price">{{$offerteLast->prezzo_a_persona}} <span title="EUR">&euro;</span></var> 
            @else 
				@if (isset($cliente->prezzo_min) && $cliente->prezzo_min != "0.00")
				<div class="price">
					<small>{{trans("labels.hp_a_partire_da")}}</small><br />
					<var>{{ $cliente->prezzo_min }} <span title="EUR">&euro;</span></var>
				</div>
				@endif   
            @endif

            <address class="hidden"> 
						{{-- TODO SEO - Rimosso microdata da questo listing, sconsigliato da google nei listing - per futuro valutare se togliere tutto l'address nascosto poiché ora i dati di schema_org li inseriamo tramite json nelle singole schede --}}
                <span><span>{{$cliente->indirizzo}}</span> <span>{{$cliente->cap}}</span> <span>{{$cliente->localita->alias}}</span></span>
            </address>

            @include('widget.item-distance')
            @include("chiuso")
            @include("widget.bonus-vacanze")
            @include("widget.item-review")
            @include("widget.item-covid")
			
            <div class="linktextoffer">
                {{$off_lingua->titolo}} <span>&#10095;</span>
            </div>

            <div class="clear"></div>

            <div class="note">
                @if ($listing_offerta == 'offerta_prenota_prima') - {{$offerteLast->perc_sconto}}%&nbsp;{{ trans('hotel.prenota_entro') }}&nbsp;<span class="prenota_entro">{{Utility::getLocalDate($offerteLast->prenota_entro, '%d/%m/%y')}} @else <b>{{Utility::getTrattamentoOfferte($offerteLast->formula)}}</b> @endif</span>

                <p class="note-small"><span class="prenota_entro">{{ trans('hotel.valido_da_al_1') }} {{Utility::getLocalDate($offerteLast->valido_dal, '%d/%m/%y')}} {{ trans('hotel.valido_da_al_2') }} {{Utility::getLocalDate($offerteLast->valido_al, '%d/%m/%y')}}<br>
                @if ($offerteLast->per_persone != 0 || $offerteLast->per_giorni != 0) {{ trans('hotel.valido_da_al_3') }} {{$offerteLast->per_persone}} {{ trans('hotel.valido_da_al_4') }} {{$offerteLast->per_giorni}} {{ trans('hotel.valido_da_al_5') }} @endif</span></p>
            </div>

	

        </div>

        <div class="clear"></div>
    </article>

@endif


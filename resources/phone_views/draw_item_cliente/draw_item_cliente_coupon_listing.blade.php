<?php
if ($cliente->coupon->count()) 
{
  $coupon = $cliente->coupon->first(); 
}
?>

	<?php $n_foto = $cliente->immagini_gallery_count; ?>


    @if (isset($coupon))

    <article data-id="{{$cliente->id}}" data-url="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id . "&coupon")}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="draw_item_cliente_coupon_listing link_item_slot @if(isset($class_item)) {{$class_item}} @endif">
        

        
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
                        <h2>
                        	<a href='{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id . "&coupon", false )}}'>
                        		{{{$cliente->nome}}}
                        	</a>
                        </h2>
                    </header>
                    
                     <span class="rating" >
                    	<meta content="{{$cliente->stelle->ordinamento}}" />{{$cliente->stelle->nome}}
                    </span>
                    
                    <br /><small>{{{ $cliente->localita->nome }}} - {{ $cliente->indirizzo }}</small>
                    
                    
                </div>
            </div>
            
            @if (isset($cliente->prezzo_min) && $cliente->prezzo_min != "0.00")
                <div class="price">
                    <small>{{trans("labels.hp_a_partire_da")}}</small><br />
                    <var>{{ $cliente->prezzo_min }} <span title="EUR">&euro;</span></var>
                </div>
				@else 
					<div class="price">
						<small>&nbsp;</small><br>
						<var>&nbsp;</var>
					</div>
            @endif


            <address class="hidden">
                <span><span>{{$cliente->indirizzo}}</span> <span>{{$cliente->cap}}</span> <span>{{$cliente->localita->alias}}</span></span>
            </address>
			
			  @include('widget.item-distance')
			
            <div class="linktextoffer ">
                {{ trans('hotel.coupon_sconto_1') }} {{ trans('hotel.buono_sconto_1') }} {{ trans('hotel.buono_sconto_2') }} {{ trans('hotel.di') }} {{$coupon->valore}} € <span>&#10095;</span>
            </div>

            <div class="clearfix"></div>
            
            <div class="note">
                {{ trans('hotel.coupon_valido_dal') }} {{Utility::getLocalDate($coupon->periodo_dal, '%d/%m/%y')}} {{ trans('hotel.coupon_valido_al') }} {{Utility::getLocalDate($coupon->periodo_al, '%d/%m/%y')}}

                <p class="note-small">{{ trans('hotel.coupon_valido_per_gg') }} {{$coupon->durata_min}}<br>
                {{ trans('hotel.coupon_min_persone') }} {{$coupon->adulti_min}}<br>
                {{ ucfirst(strtolower(trans('hotel.buono_sconto_dispo'))) }} <strong>{{$coupon->disponibile()}}</strong> coupon</p>
            </div>	
          
        </div>

        <div class="clear"></div>
    </article>@endif


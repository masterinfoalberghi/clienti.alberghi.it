@php
	$n_foto = $cliente->immagini_gallery_count;
@endphp

<figure class="col-img-draw item-listing-figure  @if ($cliente->chiuso_temp) item-listing-figure-closed @endif">
   @if (isset($url))
	   <a href="{{ url($url) }}" title="<?php echo htmlspecialchars($cliente->nome); ?>">
	   	<img class="img-listing" src="{{Utility::asset($image_listing)}}" alt="<?php echo htmlspecialchars($cliente->nome); ?>">
	   </a>
   @else
	   <img class="img-listing" src="{{Utility::asset($image_listing)}}" alt="<?php echo htmlspecialchars($cliente->nome); ?>">
   @endif
   @if (isset($n_foto) && $n_foto > 0)
	   <span class="foto">{{$n_foto}} {{ trans('labels.foto') }}</span>
   @endif
	@if ($cliente->annuale && !$cliente->chiuso_temp)
	   <div class="labels">
		   <span class="apertura">{{ trans('listing.annuale') }}</span>
	   </div>
   @endif
 
   @if (Utility::isValidIP() )
	   <span class="tag click">
		   ({{$cliente->numero_click}})
	   </span>
   @endif
   
</figure>

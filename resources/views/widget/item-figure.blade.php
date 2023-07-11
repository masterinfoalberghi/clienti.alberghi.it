
	<figure class="item-listing-figure @if ($cliente->chiuso_temp) item-listing-figure-closed @endif">
		
		<img src="data:image/gif;base64,R0lGODlhCgAKAPAAAP///wAAACH5BAAAAAAALAAAAAAKAAoAAAIIhI+py+0PYysAOw==" data-src='{{Utility::asset($image_listing)}}' class="alignleft lazy" alt="{{{$cliente->nome}}}">
		
		@if(isset($label_piscina) && !empty($label_piscina))
			<span class="tag info_distanza">{{$label_piscina}}</span>
		@endif

		@if(isset($label_spa) && !empty($label_spa))
			<span class="tag info_distanza">{{$label_spa}}</span>
		@endif

		<span class="tag foto">{{$n_foto}} {{ trans('labels.foto') }}</span>
		
		@if (Utility::isValidIP() )
			<span class="tag click">
			    ({{$cliente->numero_click}})
			</span>
		@endif
		
		@if ($cliente->chiuso_temp)
			<div class="chiusoTemp">
				{{__("labels.chiusura_temporanea")}}
			</div>
		@endif

		@if ($cliente->annuale && !$cliente->chiuso_temp)
			<span class="tag apertura">{{ trans('listing.annuale') }}</span>
		@endif
		
		
	</figure>

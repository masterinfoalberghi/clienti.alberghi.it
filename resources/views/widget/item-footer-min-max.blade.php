<footer class="item-listing-footer">
		
	<div class="item-listing-price">
		
		
		<div class="item-listing-price-cost">
			@if ( $cliente->prezzo_min > 0  || $cliente->prezzo_max >0)
				@if($cliente->prezzo_min > 0) <small class="label">{{ trans('listing.p_min') }}</small> <span class="price">{{{ $cliente->prezzo_min }}} &euro;</span><br/> @endif
				@if($cliente->prezzo_max > 0) <small class="label">{{ trans('listing.p_max') }}</small> <span class="price">{{{ $cliente->prezzo_max }}} &euro;</span><br/> @endif
			@endif
			<span class="btn btn-verde item-listing-button">{{ trans('listing.vedi_hotel') }}</span>
		</div>
		
		<div class="item-listing-select">
			<label class="css-label" for="checkbox_{{$cliente->id}}">
				<input type="checkbox" id="checkbox_{{$cliente->id}}" value="{{$cliente->id}}" class="item-listing-checkbox" />
				{{ trans('listing.seleziona') }}
			</label>
		</div>
	   
	</div>
	
</footer>
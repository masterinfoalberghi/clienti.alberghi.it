<?php
	
	/**
	 * Costruisco i dati che mi servono
	 */
    $n_foto = $cliente->immagini_gallery_count;
    
?>

<article class="click_all animation item-listing @if(isset($class_item)) {{$class_item}}  @endif" data-id="{{$cliente->id}}" data-categoria="{{$cliente->categoria_id}}" data-url="{{$url}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" >

	@include("widget.item-figure")
	
	<div class="item-listing-content">
			
		@include("widget.item-title")
	
		<div class="item-listing-middle">
			
				@include("widget.item-favourite")


					<div class="padding-top-4 padding-bottom-6">
						
						<table class="row-table row-pp-table">
							
							<tr>
								
								<td height="24">
									<h3>
										{{strtoupper(trans('hotel.gratis_fino_a'))}} {{strtoupper($vaat->offerta->_fino_a_anni())}} {{strtoupper($vaat->offerta->_anni_compiuti())}}
									</h3>
								</td>
								
							</tr>
							
							<tr>
								<td>
									<div class="item-listing-validita">
									{{ ucfirst(trans('listing.per_soggiorni_dal')) }} <b>{{Utility::myFormatLocalized($vaat->offerta->valido_dal, '%e %B', $locale)}}</b> {{ trans('listing.fino_al') }} <b>{{Utility::myFormatLocalized($vaat->offerta->valido_al, '%e %B', $locale)}}</b>
									</div>
								</td>
							</tr>
							
							@if(!empty($vaat->note))
								<tr>
									<td>
										{!!Utility::getExcerpt($vaat->note, $limit = 30, $strip = true)!!}
									</td>
								</tr>
							@endif
						</table>
						
					</div>

					{{-- altre offerte --}}
					@if (!is_null($altre_offerte) && $altre_offerte->count())
						@foreach ($altre_offerte as $bg)

							<div class="padding-bottom-6">
								
								<table class="row-table row-pp-table">
									
									<tr>
										
										<td height="24">
											<h3>
												{{strtoupper(trans('hotel.gratis_fino_a'))}} {{strtoupper($bg->_fino_a_anni())}} {{strtoupper($bg->_anni_compiuti())}}
											</h3>
										</td>
										
									</tr>
									
									<tr>
										<td>
											<div class="item-listing-validita">
											{{ ucfirst(trans('listing.per_soggiorni_dal')) }} <b>{{Utility::myFormatLocalized($bg->valido_dal, '%e %B', $locale)}}</b> {{ trans('listing.fino_al') }} <b>{{Utility::myFormatLocalized($bg->valido_al, '%e %B', $locale)}}</b>
											</div>
										</td>
									</tr>
									
									@if(!empty($bg->translate($locale)->first()->note))
										<tr>
											<td>
												{!!Utility::getExcerpt($bg->translate($locale)->first()->note, $limit = 30, $strip = true)!!}
											</td>
										</tr>
									@endif
								</table>
								
							</div>

						@endforeach
					@endif



			<div class="clearfix"></div>
			@include('widget.caparre')
		</div> 
	
	</div>
		
	@include("widget.item-footer-min-max")
	
	<div class="clearfix"></div>
			
</article>{{-- /.item_wrapper --}}

<?php
	
	/**
	 * Costruisco i dati che mi servono
	 */
	 
	 $n_foto = $cliente->immagini_gallery_count;
	 
?>

<article class="click_all animation item-listing @if(isset($class_item)) {{$class_item}}  @endif" data-url="{{$url}}"  data-id="{{$cliente->id}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}">
	
	@include("widget.item-figure")
		
	<div class="item-listing-content">
  	
	  	@include("widget.item-title")
		
		<div class="item-listing-middle">
			
			@include("widget.item-favourite")
			
			<div class="padding-top-4 padding-bottom-6">
			


				<table class="row-table row-of-table">
					<tr>
						
						<td height="24">
							<h3>
								{{$vot->titolo}}
							</h3>
						</td>
						
						<td rowspan="2" width="155" align="center" class="item-listing-price-cost-table">
							<span class="item-listing-box-offers">
								<span class="label padding-bottom-4">@if ($vot->offerta->prezzo_a_partire_da) {{ trans('listing.da') }} @endif</span>
								<span class="price">{{$vot->offerta->prezzo_a_persona}} &euro;</span><br />
								@if ($vot->offerta->label_costo_a_persona == "1")
									<span class="label">{{ trans('hotel.valido_da_al_6') }}</span>
								@endif
								@if ($vot->offerta->formula != "" && $vot->offerta->formula != "tt")
									<b class="tag offers margin-top-6">{!!Utility::getTrattamentoOfferte($vot->offerta->formula)!!}</b>
								@endif
							</span>
						</td>
						
					</tr>
					<tr>
						<td>
							<div class="item-listing-validita">
								
								{!! Utility::getConditions($vot->offerta, $locale) !!}
							
							</div>
												
							{!!Utility::getExcerpt($vot->testo, $limit = 30, $strip = true)!!}
						</td>
						
					</tr>
				</table>
				

			</div>
			
			@if ($altre_offerte->count())
							
				@foreach ($altre_offerte as $offerteLast)
						@if ( $offerteLast->offerte_lingua->count() )
							<div class="padding-top-4 padding-bottom-6">
							 
						   <?php $off_lingua = $offerteLast->offerte_lingua->first(); ?>
						   
							<table class="row-table row-of-table">
								<tr>
									
									<td height="24">
										<h3>
											{{$off_lingua->titolo}}
										</h3>
									</td>
									
									<td rowspan="2" width="155" align="center" class="item-listing-price-cost-table">
										<span class="item-listing-box-offers">
											<span class="label padding-bottom-4">@if ($offerteLast->prezzo_a_partire_da) {{ trans('listing.da') }} @endif</span>
											<span class="price">{{$offerteLast->prezzo_a_persona}} &euro;</span><br />
											@if ($offerteLast->label_costo_a_persona == "1")
												<span class="label">{{ trans('hotel.valido_da_al_6') }}</span>
											@endif
											
											@if ($offerteLast->formula != "" && $offerteLast->formula != "tt")
												<b class="tag offers margin-top-6">{!!Utility::getTrattamentoOfferte($offerteLast->formula)!!}</b>
											@endif
											
										</span>
									</td>
									
								</tr>
								<tr>
									<td>
										<div class="item-listing-validita">
							
											{!! Utility::getConditions($offerteLast, $locale) !!}
											
										</div>
															
										{!!Utility::getExcerpt($off_lingua->testo, $limit = 30, $strip = true)!!}
										
									</td>
									
								</tr>
							</table>
				    	</div>
					  @endif
				@endforeach
			
			@endif

			

			<div class="clearfix"></div>
			
		</div>
		@include('widget.caparre')
	</div>
	
	@include("widget.item-footer-short")

	<div class="clearfix"></div>

</article>{{-- /.item_wrapper --}}
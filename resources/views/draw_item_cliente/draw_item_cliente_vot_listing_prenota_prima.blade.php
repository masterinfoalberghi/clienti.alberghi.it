<?php
	
	/**
	 * Costruisco i dati che mi servono
	 */
	 
	 $tipoContenuto = "itemVetrina";
	 $n_foto = $cliente->immagini_gallery_count; 
	
?>

<article class="click_all animation item-listing @if(isset($class_item)) {{$class_item}}  @endif" data-url="{{$url}}"  data-id="{{$cliente->id}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}">
	
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
									{{$vot->titolo}}
								</h3>
							</td>
							
							<td rowspan="2" width="155" align="center" class="item-listing-price-cost-table">
								<span class="item-listing-box-offers">
									<span class="price">- {{$vot->offerta->perc_sconto}}%</span><br /><br />
									{{ ucfirst(trans('listing.scade')) }}<br /><span class="label evidenziato">{{Utility::getLocalDate($vot->offerta->prenota_entro, '%e %B')}}</span>
								</span>
							</td>
							
						</tr>
						<tr>
							
							<td>
								<div class="item-listing-validita">
									{!! Utility::getConditionsPP($vot->offerta, $locale) !!}
								</div>
								<div class="item-listing-testo">
									{!!Utility::getExcerpt($vot->testo, $limit = 30, $strip = true)!!}
								</div>
							</td>
							
						</tr>
					</table>
				</div>
				
				@if ($altre_offerte->count())
					@foreach ($altre_offerte as $offerteLast)
					    @if ( $offerteLast->offerte_lingua->count() )
					    	<?php $off_lingua = $offerteLast->offerte_lingua->first(); ?>
							<div class="padding-top-4 padding-bottom-6">
								<table class="row-table row-pp-table">
									<tr>
										<td height="24">
											<h3>
												{{$off_lingua->titolo}}
											</h3>
											
										</td>
										
										<td rowspan="2" width="155" align="center" class="item-listing-price-cost-table">
											<span class="item-listing-box-offers">
												<span class="price">- {{$offerteLast->perc_sconto}}%</span><br /><br />
												{{ ucfirst(trans('listing.scade')) }}<br /><span class="label evidenziato">{{Utility::getLocalDate($vot->offerta->prenota_entro, '%e %B')}}</span>
											</span>
										</td>
										
									</tr>
									<tr>
										
										<td>
											<div class="item-listing-validita">
												{!! Utility::getConditionsPP($offerteLast, $locale) !!}
											</div>
											<div class="item-listing-testo">
												{!!Utility::getExcerpt($off_lingua->testo, $limit = 10, $strip = true)!!}
											</div>
										</td>
										
									</tr>
								</table>
							</div>
						@endif
					@endforeach
				@endif
				
				<div class="clearfix"></div>					
					
			</div>
	
	</div>
	
	@include("widget.item-footer-short")
	
	<div class="clearfix"></div>
		
</article>{{-- /.item_wrapper --}} 

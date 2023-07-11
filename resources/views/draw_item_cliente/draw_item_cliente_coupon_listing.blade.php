<?php  
	
	/**
	 * Costruisco i dati che mi servono
	 */	
	 
	if ($cliente->coupon->count()) 
		$coupon = $cliente->coupon->first(); 
	
	$n_foto = $cliente->immagini_gallery_count;
	$url = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id) . "&coupon";
	
?>

<article class="click_all animation item-listing @if(isset($class_item)) {{$class_item}}  @endif" data-id="{{$cliente->id}}" data-categoria="{{$cliente->categoria_id}}" data-url="{{$url}}"  data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" >
	
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
									{{ trans('hotel.coupon_sconto_1') }} {{ trans('hotel.buono_sconto_1') }} {{ trans('hotel.buono_sconto_2') }} {{ trans('hotel.di') }} {{$coupon->valore}} &euro;
								</h3>
							</td>
							
						</tr>
						<tr>
							<td>
								{{ trans('hotel.coupon_valido_per_gg') }} <b>{{$coupon->durata_min}}</b><br />
								{{ trans('hotel.coupon_min_persone') }} <b>{{$coupon->adulti_min}}</b><br />
								<b>{{ ucfirst(strtolower(trans('hotel.buono_sconto_dispo'))) }} {{$coupon->disponibile()}} coupon</b>
							</td>
						</tr>
					</table>
				</div>

			<div class="clearfix"></div>

		</div> 
	

	</div>

	@include("widget.item-footer-min-max")
	
	<div class="clearfix"></div>
			
</article>{{-- /.item_wrapper --}}


 
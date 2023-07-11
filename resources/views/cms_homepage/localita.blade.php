<section id="localita" class="padding-bottom">
			
	<header>
		<h2>{{trans("title.localita")}}</h2>
	</header>
	
	<?php $c = 0; $cambio_layout = 0; ?>
			
	@foreach ($valoriHomepage["macro"] as $ml)
				
		{{-- @if ($cambio_layout <= count($template_homepage["items"]))
			<div class="clearfix"></div>
			@php $c=0; @endphp
		@endif --}}
		
		<article class="box">
			
			<div class="col-sm-4 click_all">
				<div class="@if ($c<2) margin-right-6 @endif @if ($c>0) margin-left-6 @endif margin-bottom-6 margin-top-6 ">
					<div class="box_background padding-2" style="background: url({{Utility::asset('images/home/' . $ml["image"])}}) center center no-repeat;">
						<div class="pellicola">
							
							<header class="box_header animation">
								<hgroup>
									
									<a href="{{$ml['link']}}">
										<h3 class="box_title">{{$ml["nome"]}} <span>{{$ml["n_hotels"]}} {{ trans('labels.hp_hotel') }}</span></h3>
									</a>
									
									@if ($ml["n_hotels"]>0)
									<small class="box_subtitle padding-bottom-6" style="	position: relative; top: 70px;">
										<ul>
											{{-- <li><b>{{$ml["n_hotels"]}}</b> {{ trans('labels.hp_hotel') }}</li> --}}
											@if (isset($new_values))
													
											<li><b>{{$new_values[$ml["id"]]['n_hotel_bonus_vacanze_macro']}}</b> {{trans('labels.accettano_bonus')}}</li>
											
											<li><b>{{$new_values[$ml["id"]]['n_hotel_caparre_flessibili']}}</b> {{trans('labels.cancellazione')}}</li>
											
											<li><b>{{$new_values[$ml["id"]]['n_servizi_covid']}}</b> {{trans('labels.anti_covid')}}</li>
											
											@endif
											{{-- <li><b>
											@if ($ml["n_offerte"]==1)
												{{trans('hotel.1_off')}}
											@elseif ($ml["n_offerte"]>=1)
												{{$ml["n_offerte"]}} {{trans('hotel.n_off')}}
											@endif
											</b>
												presenti</li> --}}
						
										</ul>
									</small>
									@endif
									
									
								</hgroup>
							</header>
							
							{{-- @if ($ml["prezzo_min"]>0)
							<div class="box_content">
								<small class="padding-bottom-6 label">{{ trans('labels.hp_a_partire_da') }}</small>
								<div class="price">{{ $ml["prezzo_min"] }} &euro;</div>
							</div>
							@endif --}}
							
						</div>
					</div>
				</div>
			</div>
			
			<?php $c == 2 ? $c=0 : $c++; $cambio_layout++; ?>
			
		</article>
		
	@endforeach
	
	<div class="clearfix"></div>
	
</section>
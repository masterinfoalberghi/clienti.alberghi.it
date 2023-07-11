<section id="localita" class="padding-bottom col-md-8" >
	<header>
		<h2 class="hidden">{{trans("title.localita")}}</h2>
	</header>
	@php $c = 0; $cambio_layout = 0; @endphp
	@foreach ($valoriHomepage["macro"] as $ml)
		@if ($cambio_layout <= count($template_homepage["items"]))
			@php $c=0; @endphp
		@endif
		<article class="box" >
			<div class="col-sm-6 col-md-4 click_all">
				<div class="">
					<div class="box_background padding-2" style=" margin:3px;background: url({{Utility::asset('images/home/' . $ml["image"])}}) center center no-repeat;">
						<div class="pellicola">
							<header class="box_header animation">
								<hgroup>
									<a href="{{$ml['link']}}">
										<h3 class="box_title">{{$ml["nome"]}}</h3>
									</a>
									@if ($ml["n_hotels"]>0)
									<small class="box_subtitle padding-bottom-6">
										{{$ml["n_hotels"]}} {{ trans('labels.hp_hotel') }}
										@if ($ml["n_offerte"]==1)
											, {{trans('hotel.1_off')}}
										@elseif ($ml["n_offerte"]>=1)
											, {{$ml["n_offerte"]}} {{trans('hotel.n_off')}}
										@endif
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
			<?php $c == 1 ? $c=0 : $c++; $cambio_layout++; ?>
		</article>
	@endforeach
	<div class="clearfix"></div>
</section>
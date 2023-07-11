<?php

// visualizzo le info sul periodo bambino gratis
// @parameters : $offers (array indicizzato con i valori per creare lo snippet)
//               $titolo (header titolo oppure '')
//               $tipo (offerte|lastminute)
  
?>

<a name="children-offers" id="children-offers-scroll"></a>

@if ($offers->count())
	
	<div class=" padding-bottomx2">
	@foreach ($offers as $offerteTopBambiniGratis)
		
		<header>
			<h2 class="content-section box-offers-title"><b>{{$titolo}}</b> {{trans("title.offerte")}}</h2>
		</header>
		
		<?php $offerteTopBambiniGratisLingua = $offerteTopBambiniGratis->offerte_lingua->first(); ?>
		
		@if (!is_null($offerteTopBambiniGratisLingua))
			
			<article class="box-offers-article margin-bottom-6 top">

				<header>
					<h4 class="box-offers-article-title">{{strtoupper(trans('hotel.gratis_fino_a'))}} {{strtoupper($offerteTopBambiniGratis->_fino_a_anni())}} {{strtoupper($offerteTopBambiniGratis->_anni_compiuti())}}</h4>
				</header>
				
				<div class="box-offers-article-content content-scheda">
				
					<div class="validita">
						<div class="box-offers-article-content-up">

							@php
							if(\Carbon\Carbon::parse($offerteTopBambiniGratis->valido_dal)->format("Y") == \Carbon\Carbon::parse($offerteTopBambiniGratis->valido_al)->format("Y"))
								$format = "%e %B";
							else 
								$format = "%e %B %Y";
							@endphp

							<strong>{{Utility::myFormatLocalized($offerteTopBambiniGratis->valido_dal, $format, $locale)}}</strong> &rarr; <strong>{{Utility::myFormatLocalized($offerteTopBambiniGratis->valido_al, $format, $locale)}}</strong>
						</div>
					</div>
					
					@if (strlen($offerteTopBambiniGratisLingua->note) > 30)
					
						<div class="box-offers-article-content-small">
							{!! Utility::getExcerpt($offerteTopBambiniGratisLingua->note, $limit = 30, $strip = true) !!}<br />
							<a class="btn btn-small btn-cyano " href="#" >{{ trans('hotel.leggi_tutto') }}</a>  
						</div>
					
						<div style="display: none;" class="box-offers-article-content-full">
							{!! $offerteTopBambiniGratisLingua->note !!}
							<a href="#" class="btn btn-small btn-rosso btn-cyano">{{ trans('hotel.chiudi') }}</a><br />
						</div>
						
					@else
					
						{!!$offerteTopBambiniGratisLingua->note!!}
						
					@endif
				
				</div>
				
				<div class="box-offers-article-top" >
					<i class="icon-heart"></i>
				</div>

				<div class="box-offers-article-verifica tipped" title="<b>Verificata</b>">
					<i class="check icon-ok"></i> <i style="margin-left:-15px;" class="check icon-ok"></i>
				</div>
				
				<div class="clearfix"></div>
			 
			</article>
			
		@endif

  @endforeach
  </div>

@endif




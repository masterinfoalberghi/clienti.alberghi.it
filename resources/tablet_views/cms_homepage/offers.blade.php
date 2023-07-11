

@if ($offers_homepage["items"])

	<section id="offers" class="padding-bottom">
		
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<header>
						<h2>{{trans("title.offerte")}}</h2>
					</header>
				</div>
			</div>
			
			<div class="row">
				{{-- <div class="col-md-6 col-sm-6 margin-bottom-3">
						<article class="offer_spot margin-right-6 animation click_all" style="background: url(images/spothome/580x225/blubooking.png) center center no-repeat; padding:30px 15px; text-align: center;">
							<img src="blubooking/img/logo-blubooking-2019.png" width="300px" height="auto"  /><br />
							<p style="font-size: 14px; font-weight:bold; text-shadow: 0 1px 2px rgba(0, 0, 0, .3); position: relative; top:-5px; line-height: 19px; ">PRENDIAMOCI CURA DEL MARE<br/>UN SORSO ALLA VOLTA</p>
							<a href="/blubooking" class="offer_button animation"><b>il progetto</b> <i class="icon-right"></i></a>
						</article>
				</div>
				<div class="col-md-6 col-sm-6 margin-bottom-3">
						<article class="offer_spot margin-ledt-6 animation click_all" style="background: url(images/spothome/580x225/greenbooking.png) center center no-repeat; padding:30px 15px; text-align: center;">
							<img src="greenbooking/img/logo-greenbooking-2019.png" width="300px" height="auto"  /><br />
							<p style="font-size: 14px; font-weight:bold; text-shadow: 0 1px 2px rgba(0, 0, 0, .3); position: relative; top:-5px; line-height: 19px; ">INSIEME PER UNA RIVIERA PIÙ VERDE!</p>
							<a href="/greenbooking" class="offer_button animation"><b>il progetto</b> <i class="icon-right"></i></a>
						</article>
				</div> --}}
			<?php $c = "margin-right-6"; ?>
			@foreach($offers_homepage["items"] as $offer)
								
				@if ($offer['image'])
						
						<div class="col-md-6 col-sm-6 margin-bottom-3">
							<article class="offer_spot {{$c}} click_all" style="background: {{$offer['colore']}}" data-page-edit="{{$offer["id_page"]}}">
									
									<div class="col-md-4 offer_image" style=" background-image: url({{Utility::asset('images/spothome/300x225/' . $offer['image'])}})"></div>
									
									<div class="col-sm-12 col-md-8">
										<div class="offer_content margin-right-2 padding-2">
											
										    <header>
											    <hgroup>
												    <small class="offer_subtitle">{{$offer["sottotitolo"]}}</small>
												    <a class="offer_link" href="{{$offer['link']}}"><h3 class="news_title">{{$offer["titolo"]}}</h3></a>
											    </hgroup>
										    </header>
										    
											<p class="page-edit">{!! $offer["testo"] !!}</p>
											<a href="{{$offer['link']}}" class="offer_button"><b>{{trans("labels.scopri_offerta")}}</b> <i class="icon-right"></i></a>
											
										</div>
									</div>
									
							</article>
						</div>
					
					@else
					
						<div class="col-md-6 col-sm-6  margin-bottom-3 ">
							<article class="offer_spot {{$c}} click_all " style="background: {{$offer['colore']}}" data-page-edit="{{$offer["id_page"]}}">
								<div class="offer_content margin-right-2 padding-2">
								    <header>
									    <hgroup>
										    <small class="offer_subtitle">{{$offer["sottotitolo"]}}</small>
										    <a class="offer_link" href="{{$offer['link']}}"><h3 class="offer_title">{{$offer["titolo"]}}</h3></a>
									    </hgroup>
								    </header>
									<p class="page-edit">{!! $offer["testo"] !!}</p>
									<a href="{{$offer['link']}}" class="offer_button"><b>{{trans("labels.scopri_offerta")}}</b> <i class="icon-right"></i></a>
								</div>
							</article>
						</div>
					
					@endif
				
				<?php $c == "margin-right-6" ? $c = "margin-left-6" : $c = "margin-right-6"; ?>
				
			@endforeach
			
			</div>
			
	</section>
	
@endif
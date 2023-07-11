@if ($offers_homepage["items"])

	<?php $class=""; ?>

	<section id="offers" class="container">
		
			
			
			{{-- Mega banner --}}
			<div class="row" id="boxhome">
				
				<div id="spot-project">

					{{-- <div class="col-md-12 ">
						<article class="smartlink" style="background-image: url(images/spothome/580x225/blubooking.png); ">
							<a href="/blubooking">
								<img src="blubooking/img/logo-blubooking-2019.png" width="200px" height="auto" style="position: absolute; top:0; right:0; bottom:0; left:0; margin:auto;"/><br />
							</a>
						</article>
					</div>

					<div class="col-md-12 ">
						<article class="smartlink" style="background-image: url(images/spothome/580x225/greenbooking.png);">
							<a href="/greenbooking">
								<img src="greenbooking/img/logo-greenbooking-2019.png" width="200px" height="auto" style="position: absolute; top:0; right:0; bottom:0; left:0; margin:auto;"/><br />
							</a>
						</article>
					</div> --}}

				</div>

				<h3 class="header-line">
					<span>{{trans("title.other_offerte")}}</span>
				</h3>

				<div id="spot-offer">

					{{-- Offerte --}}
					@foreach($offers_homepage["items"] as $offer)
						
						<article class="smartlink {{$class}}" data-page-edit="{{$offer["id_page"]}}" >
							
							<a href="{{$offer['link']}}">
								
								<span class="content">
									<header>
										<hgroup>
											<small>{{$offer["sottotitolo"]}}</small>
											<span>{{$offer["titolo"]}}</span>
										</hgroup>
									</header>
								</span>
								
							</a>

							{{-- <span style="font-size:12px;">
								{{$offer["listing_count"]}} Hotel
							</span> --}}
							
						</article>
											
					@endforeach
						<div class="clearfix"></div>
				</div>
			
	</section>
	
@endif
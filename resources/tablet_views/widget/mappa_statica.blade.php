<section class="padding-bottom"> 
	
		<div class="container">
			<div class="row">
				<header>
					<h2 class="title-menu-main content-section padding-bottom-2">{{trans("title.mappa")}}</h2>					
				</header>
			</div>
		</div>
	
		<article>
		
			<header>
				<h4 class="title hidden">{{trans('hotel.dove_siamo')}}</h4>
			</header>
			
			<div id="mappa-statica-container" class="container">
				
				<div class="row">

					<div class="col-xs-12">
						<div id="mappa-statica">
							{{-- <a href="/mappa-hotel/{{$cliente->id}}" data-vbtype="iframe" class="venobox mappa margin-bottom-3 margin-right-3"> --}}
							<a href="{{Utility::getUrlWithLang($locale, '/mappa-hotel/'.$cliente->id)}}"  data-fancybox data-type="iframe" class="venobox mappa margin-bottom-3 margin-right-3">
								
								@if (isset($img_mappa_localita))
									<img src="{{Utility::asset('images/mappa_localita/tablet/'.$img_mappa_localita)}}" alt="{{$cliente->localita->nome}}" title="{{$cliente->localita->nome}}" width="750px" height="360px">
								@else
									<img src="https://maps.googleapis.com/maps/api/staticmap?key={{Config::get("google.googlekey")}}&center={{$cliente->mappa_latitudine}},{{$cliente->mappa_longitudine}}&zoom=15&size=640x400&language=it&markers=icon:https://static.info-alberghi.com/images/markers/red.png%7c{{$cliente->mappa_latitudine}},{{$cliente->mappa_longitudine}}&label:{{$cliente->nome}}%7C{{$cliente->mappa_latitudine}},{{$cliente->mappa_longitudine}}" />
								@endif

								<div class="boxtesto">
							    	<span class="testo">{{ trans('hotel.click_ingrandire') }}</span>
							  	</div>
							</a>
						</div>
						
					</div>

					<div class="col-xs-12" >
						@if (!isset($map_source))
							@include('composer.puntiDiInteresse') 
						@endif
					</div>
				</div>
			</div>
			
		</article>
	
</section>

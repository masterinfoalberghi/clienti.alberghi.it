@if(isset($pages_localita) && count($pages_localita))

<section class="padding-top padding-bottom container ricerca ">

	<header class="row">
		<h2 class="col-sm-12">{{trans("title.localita")}}</h2>
	</header>

	@foreach ($pages_localita as $page)
	
		
		@php 
			
			$coords = Utility::getCoordsByLocalitaID($page->menu_macrolocalita_id, $page->menu_localita_id);
			$img = 'https://maps.googleapis.com/maps/api/staticmap';
			
			$center 		= '?center=' . $coords->latitudine . ',' . $coords->longitudine;
			$zoom 			= '&zoom=13&size=280x180';
			$styler 	 	= '&style=element:labels|visibility:off';
			$styler    	   .= '&style=element:geometry.stroke|visibility:off';		
			$key 			= '&key=' . Config::get("google.googlekey");
			$marker			= '&markers=icon:https://www.info-alberghi.com/images/markers/red.png%7C' .$coords->latitudine . ',' . $coords->longitudine;
			$mappa 			= $img . $center . $zoom . $styler . $key. $marker;
	
			
		@endphp
		
		<article class="row padding-bottom-2 item-ricerca">
			
			<div class="col-xs-3 ">
				<figure>
					<img src="{{$mappa}}" />
				</figure>
			</div>
			
			<div class="col-xs-9">
				
				<header class="item-listing-title">
					<a class="r_nome_hotel" href="{{ url($page->uri) }}">
						<h2>{!! $page->ancora !!}</h2>
					</a>
				</header>
				
				{{$page->listing_count}} {{ trans('labels.hp_hotel') }}
					@if ($page->n_offerte==1)
						, {{trans('hotel.1_off')}}
					@else
						, {{$page->n_offerte}} {{trans('hotel.n_off')}}
					@endif
				
				
				
				<p class="uri">
				{{ url($page->uri) }}
                </p>
                @if($page->descrizione_1)
				    <p>{!! \Illuminate\Support\Str::limit(strip_tags($page->descrizione_1),300); !!}</p>
				@endif	
			</div>
			
			
		</article>
		
	@endforeach

</section>

@endif
@if(isset($pages) && count($pages))

<section class="padding-top padding-bottom container ricerca">

	<header class="row">
		<h2 class="col-sm-12">{{trans("title.informazioni")}}</h2>
	</header>

@foreach ($pages as  $page)

	<article class="row padding-bottom-2 item-ricerca">
			
			<div class="col-sm-12">
				
				<header class="item-listing-title">
					<a class="r_nome_hotel"  href="{{ url($page->uri) }}">
						<h2>{!! str_replace([
						'{HOTEL_COUNT}',
						'a {LOCALITA}',
						'nei {LOCALITA}',
						'{OFFERTE_COUNT}',
						'{LOCALITA}',
						'{PREZZO_MIN}',
                        '{CURRENT_YEAR}',
                        '{CURRENT-YEAR}'
						 ], 
						 [$page->listing_count,
						 'a '.Utility::getLocalitaFromPage($page),
						 'nei ' .Utility::getLocalitaFromPage($page),
						 $page->n_offerte == 0 ? '' : $page->n_offerte,
						 Utility::getLocalitaFromPage($page),
                         $page->prezzo_minimo == 0 ? ' ... ' : $page->prezzo_minimo,
                         date("Y")+Utility::fakeNewYear(),
                         date("Y")+Utility::fakeNewYear()
						 ], $page->seo_title) !!}</h2>
					</a>
				</header>
			
				<div class="uri">{{ url($page->uri) }}</div>
                    
                @if($page->descrizione_1)
                    <p>{!! substr(strip_tags($page->descrizione_1),0,300) !!}
                        <a href="{{ url($page->uri) }}">{{trans("hotel.leggi_tutto")}}</a>
                    </p>
                @endif
				
			</div>
			
			
		</article>

@endforeach

</section>


@endif
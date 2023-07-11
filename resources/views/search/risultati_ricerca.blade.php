@extends(

	'templates.cms_pagina_statica',
	[
		'redirect_url' => "",
		'is_thankyou' => "",
		'for_canonical' => "",
		'previous_page_url' => "",
		'next_page_url' => "",
		'cms_pagina' => ""
	]

)

@section('seo_title'){{ trans('listing.cerca') }}@endsection
@section("css")
@include("desktop.css.search")
@endsection

@section("briciole")

<div id="briciole">
	<div class="container">
		<div class="row">
			<div cass="col-sm-12" style="text-align: center; padding-top: 10px;">
				<p>
					<h2><strong>{{$n_ris}}</strong> risultati relativi a "<strong>{{$da_cercare}}</strong>"</h2>
				</p>
			</div>
		</div>
	</div>
</div>

@endsection

@section('content')

	<div class="panel-body" id="ricerca">
		
		@if ( 
				( is_null($pages_localita) || !$pages_localita->count() ) && 
				( is_null($pages) || !$pages->count() ) && 
				( is_null($hotels) || !$hotels->count() ) && 
				( is_null($offerte) || !count($offerte) ) && 
				( is_null($nome_hotels) || !count($nome_hotels) ) 
			)
			
			@include("search.risultati_ricerca_no")
					
		@else
			@include("search.risultati_ricerca_hotels",['hotels' => $nome_hotels])
			@include("search.risultati_ricerca_localita")
			@include("search.risultati_ricerca_hotels",['hotels' => $hotels])
			@include("search.risultati_ricerca_pagine")
			@include("search.risultati_ricerca_offerte")

		@endif


	</div>
@stop


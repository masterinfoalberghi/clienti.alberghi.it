@extends('templates.page')
@section('seo_title'){{ trans('labels.trova_hotel_title') }}@endsection
@section('seo_description'){{ trans('labels.trova_hotel_desc') }}@endsection

@section('content')

<section id="listing">
	
  @include('errors')
		
	<h1>{!!$target!!}@if ($sub_target != "no_search") {{ count($clienti) }} Strutture trovate @endif </h1><br />
	
	<div class="ricerca-page">
	@if($avanzata == "no")
		@include("menu.sx_ricerca_avanzata")
	@else
		@include("search.tag_ricerca")
	@endif
	</div>
    

	
	@if ($clienti->count())
	{{-- @include('cms_pagina_listing.filtri') --}}
	@include('cms_pagina_listing.wishform')
	
	@endif	
	
	<div class="wrapper_risultati_ricerca">
	<div class="risultati_ricerca">
	
	@if ($clienti->count())
	
	@include('cms_pagina_listing.clienti', array('clienti' => $clienti))
	
	@else
	
	@if ($sub_target != "no_search")
	<div class="no_risultati_ricerca">
	<img src="{{Utility::asset("images/no_result.png")}}" /><br /><br />
	<p>{{ trans('labels.no_result') }}</p>
	</div>
	@endif
	
	@endif
	
	</div>
    
      
  </div>
  </section>
@endsection
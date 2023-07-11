@extends('templates.cms_homepage')
@section('seo_title'){{$valoriHomepage["n_hotel_tot"] . trans('labels.hp_title') }}@stop
@section('seo_description'){{trans('labels.hp_desc_1') . $valoriHomepage["n_hotel_tot"] . trans('labels.hp_desc_2') }}@stop

@section('content')
	
	<header>
		<h1 class="logo_claim">{{$valoriHomepage["n_hotel_tot"]}} {{ trans('labels.hp_fascia_blu') }}</h1>
	</header>
	
	<div class="container_home">
		
		@include("cms_homepage.evidenza")
		@include("cms_homepage.localita")
	
	</div>
	
	<div class="clearfix"></div>

	@include("cms_homepage.offers")
	
	@include("cms_homepage.news")
   
@endsection
	
		

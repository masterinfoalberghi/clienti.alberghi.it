@extends('templates.cms_homepage')

@section('seo_title'){{$valoriHomepage["n_hotel_tot"] . trans('labels.hp_title') }} @endsection

@section('seo_description'){{trans('labels.hp_desc_1') . $valoriHomepage["n_hotel_tot"] . trans('labels.hp_desc_2') }} @endsection

@section('content')

<div class="container">
	
	<section class="row" id="lochome">
		
		<h2 class="hidden">{{trans("title.localita")}}</h2>
		@include("cms_homepage." . $template_homepage["template"])
		
	</section>
	
</div>

@include("cms_homepage.offers")

@endsection


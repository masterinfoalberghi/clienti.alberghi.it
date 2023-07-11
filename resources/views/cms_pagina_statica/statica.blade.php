@extends('templates.cms_pagina_statica')
@section('seo_title') {{$cms_pagina->seo_title}} @endsection
@section('seo_description') {{$cms_pagina->seo_description}} @endsection
@include('flash')

@section('css')
	
	#main-content { font-size:16px!important; }
	
	.main-content h2, .main-content h3 {
	    padding-top: 20px!important;
	}
	
@endsection


@section('content')
	
	
	
@endsection
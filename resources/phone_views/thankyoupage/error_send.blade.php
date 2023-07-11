
@extends('templates.cms_pagina_statica', ['redirect_url' => $redirect_url, 'is_thankyou' => 'thankyou'])

@section('seo_title'){{ trans('listing.conferma') }}@endsection
@section('seo_title')@endsection

@section('css')
		
	#contenuto { padding:170px 0 130px 0px; overflow: hidden; text-align: center; background: #fff  } 
	#contenuto h1 { margin-bottom: 0; font-weight: normal;}
	#contenuto p { line-height: 1.5em; margin:30px;  }
	#contenuto .button { display: inline-block; }

@endsection

@section('javascript')
	
	var i = 10;
	$("#mycountdown").text(" 10 ");
	
	var d = setInterval(function () {  
		
		i--; 
		if (i==-1) {
			
			window.location.replace("{{$redirect_url}}");
			clearInterval(d);
			
		} else {
			$("#mycountdown").html("<br />" + i + " ");
		}
		
	}, 1000);
	
	$(".come_back").click(function (e) {

		e.preventDefault();
		window.location.replace("{{$redirect_url}}");
		
	})

@endsection

@section('content')

<div id="contenuto" >
	
	<img src="/images/nosend.png" style="width:64px; height:auto; margin:0 auto; text-align:center;" />
	<h1 class="red-fe">{{ trans('listing.no_conferma') }}</h1>
	
	<p>{{ trans('listing.mail_ko') }}</p>
	
	@if($listing=="no_listing")
		
		<p align="center">{{ trans('listing.redirect_to') }} <a class="cyan-fe come_back" href="{{Utility::getUrlWithLang($locale,$redirect_url)}}">{{ trans('listing.prec') }}</a> {{ trans('listing.entro') }} <span id="mycountdown"></span>{{ trans('listing.sec') }}</p>      
		<a class="button green come_back" href="{{Utility::getUrlWithLang($locale,$redirect_url)}}"><strong>{{ trans('listing.prec') }}</strong></a>
	
	@endif 
		 
</div>

@endsection

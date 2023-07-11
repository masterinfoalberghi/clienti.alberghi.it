@if (!isset($redirect_url))
  {{$redirect_url = '/'}}
@endif

@extends('templates.page', ['redirect_url' => $redirect_url])

@section('seo_title'){{ trans('listing.conferma') }}@endsection
@section('seo_title')@endsection

@section('javascript')
	
	var i = 10;
	$("#mycountdown").text(" 10 ");
	
	var d = setInterval(function () {  
		
		i--; 
		if (i==-1) {
			document.location.href='{{Utility::getUrlWithLang($locale,$redirect_url)}}';
			clearInterval(d);
		} else {
			$("#mycountdown").text(" " + i + " ");
		}
		
	}, 1000);
	
@endsection

@section('content')

@section('css')
	h1,h2{text-align:center; font-weight:normal; font-size:26px; }
	#main { text-align:center; padding:85px 30px 30px; color:#444; background:#fff;   }
	a { color:#000; text-decoration:underline;  }
	#mycountdown { color:#990000; }
@endsection

<div class="clearfix"></div>

<div id="main" >
	
		{!!$content_h1!!}
		{!!$content_body!!}
	  
      {{ session('message') }}
      
      <p align="center">{{ trans('listing.redirect_to') }} <a href="{{Utility::getUrlWithLang($locale,$redirect_url)}}">{{ trans('listing.prec') }}</a> {{ trans('listing.entro') }} <span id="mycountdown"></span>{{ trans('listing.sec') }}</p>      
      <p align="center">{{ trans('listing.no_redirect') }} <a href="{{Utility::getUrlWithLang($locale,$redirect_url)}}"><strong>{{ trans('listing.click') }}</strong></a></p>
      
  
</div>

@endsection

@extends(

	'templates.cms_pagina_statica', 
	[
		'redirect_url' => $redirect_url,
		'is_thankyou' => 'thankyou',
		'for_canonical' => "",
		'previous_page_url' => "",
		'next_page_url' => "",
		'cms_pagina' => ""
	]
)

@section('seo_title')
	{{ trans('listing.conferma') }}
@endsection

@section("css")
	@include("desktop.css.thankyou")
@endsection

@section('content')
	
	<div class="warningTY margin-top margin-bottom">
		
		<header>
			<h1><span><i class="icon-mail-alt"></i></span> {{ trans('listing.conferma') }}</h1>
		</header>
		
		<p class="padding-top-4">
			{{ trans('listing.mail_ok') }}<br />
			{{ trans('listing.redirect_to') }} <a class="come_back" href="{{Utility::getUrlWithLang($locale,$redirect_url)}}">{{ trans('listing.prec') }}</a> {{ trans('listing.entro') }} <span id="mycountdown"></span>{{ trans('listing.sec') }}</p>      
			<a class="come_back" href="{{Utility::getUrlWithLang($locale, $redirect_url)}}">
				<strong><i class="icon-left"></i> {{ trans('listing.prec') }}</strong>
			</a>
		</p>
		
	</div>
	
@endsection

@section("js")
			
	var i = 5; $("#mycountdown").text(" 5 ");
	
	var d = setInterval(function () {  
		
		i--; 
		if (i==-1) {
			
			window.location.replace("{!! url($redirect_url) !!}");
			clearInterval(d);
			
		} else {
			$("#mycountdown").html( i + " ");
		}
		
	}, 1000);
	
	$(".come_back").click(function (e) {
		
		e.preventDefault();
		window.location.replace("{!! url($redirect_url) !!}");
		
	})			
	
	{{--
		
		Track obbiettivi Google analytics tramite GTM
		Il primo viene bocciato perchè è la visualizzazione della pagina fisica
		Nelle email multiple traccio una pagina ogni hotel contattato
		
	--}}
	
	@php $ids = explode(",",$ids_send_mail); $notFirst = false; @endphp
	
	@foreach($ids as $id)
		
		dataLayer.push({ 'event':'GAdsThankyouEvent'});

		@if ($notFirst)
			dataLayer.push({
				'event':'VirtualPageview',
				'virtualPageURL':'/thankyou',
				'virtualPageTitle' : 'Conferma di invio mail'
			});

		@endif
		
		@php $notFirst = true; @endphp
		
	@endforeach
	
	@if (isset($subscribe) && $subscribe != "")
		
		dataLayer.push({ 
			'event':'VirtualPageNewsletter',
			'virtualPageURL': '/iscrizione_newsletter', 
			'virtualPageTitle': "Conferma iscrizione alla newsletter"
		}); 
		
		dataLayer.push({ 
			'event':'VirtualEventNewsletter'
		}); 
		
	@endif
		
@endsection



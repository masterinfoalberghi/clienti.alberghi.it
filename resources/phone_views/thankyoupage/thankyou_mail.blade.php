@extends('templates.cms_pagina_statica', ['redirect_url' => $redirect_url])

@section('seo_title'){{ trans('listing.conferma') }}@endsection
@section('seo_title')@endsection

@section('css')
		
	#contenuto { padding:170px 0 130px 0px; overflow: hidden; text-align: center; background: #fff  } 
	#contenuto h1 { margin-bottom: 0; font-weight: normal;}
	#contenuto p { line-height: 1.5em; margin:30px;  }
	#contenuto .button { display: inline-block; }

@endsection

@section('javascript')

	var i = 3;
	$("#mycountdown").text(" 3 ");
	
	var d = setInterval(function () {  
		
		i--; 
		if (i==-1) {
			
			window.location.replace("{!! url($redirect_url) !!}");
			clearInterval(d);
			
		} else {
			$("#mycountdown").html("<br />" + i + " ");
		}
		
	}, 1000);
	
	$(".come_back").click(function (e) {
		e.preventDefault();
		window.location.replace("{!! url($redirect_url) !!}");
	})
	

@endsection

@section('content')

<div id="contenuto" >
	
	<img src="/images/oksend.png" style="width:64px; height:auto; margin:0 auto; text-align:center;" />
	<h1 style="color:#69C441">{{ trans('listing.conferma') }}</h1>
	
	<p>{{ trans('listing.mail_ok') }}</p>
	
	@if($listing=="no_listing")
		
		<p align="center">{{ trans('listing.redirect_to') }} <a class="cyan-fe come_back" href="{{Utility::getUrlWithLang($locale,$redirect_url)}}">{{ trans('listing.prec') }}</a> {{ trans('listing.entro') }} <span id="mycountdown"></span>{{ trans('listing.sec') }}</p>      
		<a class="button green come_back" href="{{Utility::getUrlWithLang($locale, $redirect_url)}}"><strong>{{ trans('listing.prec') }}</strong></a>
	
	@endif 
	
	<script>
	
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
		
	</script>
		 
</div>

@endsection
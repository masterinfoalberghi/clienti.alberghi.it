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
	
	<div class="warningTY error margin-top margin-bottom">
		
		<header>
			<h1><span><i class="icon-mail-alt"></i></span> {{ trans('listing.no_conferma') }}</h1>
		</header>
		
		<p class="padding-top-4">
			{{ trans('listing.mail_ko') }}<br />
			{{ trans('listing.redirect_to') }} <a class="come_back" href="{{Utility::getUrlWithLang($locale,$redirect_url)}}">{{ trans('listing.prec') }}</a> {{ trans('listing.entro') }} <span id="mycountdown"></span>{{ trans('listing.sec') }}</p>      
			<a class="come_back" href="{{Utility::getUrlWithLang($locale, $redirect_url)}}">
				<strong><i class="icon-left"></i> {{ trans('listing.prec') }}</strong>
			</a>
		</p>
		
	</div>
	
@endsection

@section("js")
			
	var i = 10; $("#mycountdown").text(" 10 ");
	
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

	/**
	 * Monitoraggio contatto su GA
	 */
	
	var $url ="";<?php 
		
	$ids = explode(",",$ids_send_mail);	
	foreach($ids as $id):
		?>$url = '{{$id}}/{{$multipla}}/{{$mobile}}/{{$listing}}/';	dataLayer.push({'event':'VirtualPageview','virtualPageURL':'/thankyou/' + $url,'virtualPageTitle' : '{{ trans('listing.no_conferma') }}'});<?php 
	endforeach; ?>
	
	dataLayer.push({ 'event': 'contatto_sito', 'contact_email': "{{$subscribe_email}}", 'contact_name': "{{$subscribe_name}}", 'contact_phone': "{{$subscribe_phone}}" }); 
	
	/**
     * Monitoraggio iscrizione alla newsletter
     */
     
	@if (isset($subscribe) && $subscribe != "")
		dataLayer.push({ 'event': 'iscrizione_newsletter', 'subscribe_email': "{{$subscribe_email}}", 'subscribe_device': "Desktop", 'subscribe_type': "Form di contatto" }); 
	@endif
	
		
@endsection





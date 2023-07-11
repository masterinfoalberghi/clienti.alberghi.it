@if (!isset($redirect_url))
  {{$redirect_url = '/'}}
@endif

@if(!isset($listing))
	<?php $listing = ""; $multipla = ""; $mobile = "";?>
@endif


@extends('templates.page', ['redirect_url' => $redirect_url, 'is_thankyou' => 'thankyou'])

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
	
	<?php if ( !empty($ids_not_send_mail) && !empty($ids_send_mail) ) { ?>

		
		<img src="/images/oksend.png" style="width:64px; height:auto; margin:0 auto; text-align:center;" />
		<h1 style="color:#69C441">{{ trans('listing.conferma') }}</h1>
		<p>{{ trans('listing.mail_ok') }}</p><br />
		
		<img src="/images/alert.png" style="width:64px; height:auto; margin:0 auto; text-align:center;" />
		<h1 style="color:#FD9337">{{ trans('listing.conferma_more_multipla') }}</h1>
		<p>{{ trans('listing.mail_ok_more') }}</p><br />
		
	<?php } else { ?>
		
		<img src="/images/alert.png" style="width:64px; height:auto; margin:0 auto; text-align:center;" />
		<h1 style="color:#FD9337">{{ trans('listing.conferma_more') }}</h1>
		
		<p>{{ trans('listing.mail_ok_more') }}</p>
	
	<?php } ?>
		
	
	@if($listing=="no_listing")
		
		<p align="center">{{ trans('listing.redirect_to') }} <a class="cyan-fe come_back" href="{{Utility::getUrlWithLang($locale,$redirect_url)}}">{{ trans('listing.prec') }}</a> {{ trans('listing.entro') }} <span id="mycountdown"></span>{{ trans('listing.sec') }}</p>      
		<a class="button green come_back" href="{{Utility::getUrlWithLang($locale, $redirect_url)}}"><strong>{{ trans('listing.prec') }}</strong></a>
	
	@endif 
	
		
	<script><?php 
		
		// Monitoraggio contatto su GA
		$ids = explode(",",$ids_send_mail);
		foreach($ids as $id):
		
			?>var $url = '{{$id}}/{{$multipla}}/{{$mobile}}/{{$listing}}/';	dataLayer.push({'event':'VirtualPageview','virtualPageURL':'/thankyou/' + $url,'virtualPageTitle' : '{{ trans('listing.conferma') }}'});
					
		<?php endforeach; ?>
		
		// Monitoraggio inscrizione alla newsletter
		@if (isset($subscribe) && $subscribe != "")
			dataLayer.push({ 'event': 'iscrizione_newsletter', 'subscribe_email': "{{$subscribe_email}}", 'subscribe_device': "Telefono", 'subscribe_type': "Form di contatto" }); 
			<?php /*fbq('track', 'CompleteRegistration', { subscribe_email: {{$subscribe_email}}, subscribe_type: "Form di contatto" }); */ ?>
		@endif
		
		// Monitoraggio Contatto su FB
		dataLayer.push({ 'event': 'contatto_sito', 'contact_email': "{{$subscribe_email}}", 'contact_name': "{{$subscribe_name}}", 'contact_phone': "{{$subscribe_phone}}" }); 
		<?php /*fbq('track', 'Lead', { name: {{$subscribe_name}}, email: {{$subscribe_email}}, phone: {{$subscribe_phone}} });*/ ?>
	</script>
	 
</div>

@endsection

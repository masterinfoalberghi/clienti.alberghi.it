<!DOCTYPE html>
<html lang="{{$locale}}">
  <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="telephone=no" name="format-detection">
    
    {{-- hreflang da middleware --}}
    
    <title>
    	@yield('seo_title')
	</title>
	
    <meta name="description" 
    	content="
    		@yield('seo_description')
	    ">
	
	@if($for_canonical)
    	<link rel="canonical" href="{{url($for_canonical)}}">
    @endif
    
	<style>
		
		@include('vendor.flags.flags')
		
		@include('mobile.css.css-above.above-bootstrap')
		@include('mobile.css.css-above.above-generale-mobile')
		@include('mobile.css.css-above.above-listing-mobile')
		@include('mobile.css.css-above.above-covid')
		@yield('css')
		
	</style>
    
    @include("header")
	@include("gtm")
    
  </head>

   <body class="page-stradario-piazza localita">
   	
	@include("gtm-noscript")
   	@include('cookielaw')
   	
   	<header class="hidden"  role="banner">
		<h1>∏∏$titolo}}</h1>
	</header>
   
    @include('menu.header_menu_page')
	
	<section class="page" style="background:#fff; margin-bottom: -77px;">
		
	    @yield('contentmap')
	    @yield('content')
	    <div class="clear"></div><br/>
	   
   </section>
   
    @include('composer.footer')
	    
    <?php /*
	<div class="button-write-all"  >
		<div class="button-write-all-inside" >
			<button class="button small orange" onclick="document.getElementById('emailmultiplamobileforms').submit();"><img src="{{Utility::asset('images/mail.png')}}" />&nbsp;<span>{{trans("listing.scrivi_tutti")}}</span></button>
		</div>
    </div> */ ?>
   
	@include("widget.loading")
   
	{{-- Fine pagina --}}
	
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app-listing.min.css') }}" />
	
    <script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/script.min.js') }}"></script>

    
	<script type="text/javascript">
		
		var $csrf_token = '<?php echo csrf_token(); ?>';
		var $cms_id = '<?php echo $cms_pagina->id; ?>';
		var $listing_type ='Localita';
		var ids_send_mail_arr = new Array();
		var $map_disabled = false;
		var console = console?console:{log:function(){}};
		 		
		<?php if (isset($array_ids_vot)) { ?>
		var ids_vot = '<?php echo implode(",",$array_ids_vot) ?>';
		<?php } else {  ?>
		var ids_vot = '';
		<?php } ?>
		
		<?php if (isset($array_ids_vaat)) { ?>
		var ids_vaat = '<?php echo implode(",",$array_ids_vaat) ?>';
		<?php } else {  ?>
		var ids_vaat = '';
		<?php } ?>

		var $order = 		'{{Utility::getUrlWithLang($locale,'/order_localita')}}';
		var $writenow = 	'{{Utility::getUrlWithLang($locale,'/mail_listing.php?id=')}}';
		var $viewnow = 		'{{Utility::getUrlWithLang($locale,'/hotel.php?id=', true)}}';
		var google_api = 	false;		
		
		// Tools
		@include("lang.cl")
		@include("lang.listing")
		
		$(function() {
			
			@if(isset($google_maps) && !empty($google_maps))
			
				@include("lang.maps")
				@include('mobile.js.google-maps')
				
				$.getScript('//maps.googleapis.com/maps/api/js?key=AIzaSyCAyCUJ63a6dtvWfdAaqCmLxrWqOombjM8&language=it', function () {
					window.initialize();
				});

				
			
			@endif
			
		});	
					
		
	</script>    
	
	<script src="{{ Utility::asset('/mobile/js/js-above/listing.min.js') }}"></script>
	<script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>
	
	@include('footer')
	
  </body>
</html>
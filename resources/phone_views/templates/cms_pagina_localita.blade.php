<!DOCTYPE html>
<html lang="{{$locale}}">
  <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   
    <meta content="telephone=no" name="format-detection">
    
    {{-- hreflang da middleware --}}
    
    <title>
    	@yield('seo_title')
	</title>
	
    <meta name="description" content="@yield('seo_description')">
	
	@if($for_canonical)
    <link rel="canonical" href="{{url($for_canonical)}}">@endif
    
    @include("header")
	@include("gtm")

	{!! HTML::style('neon/css/font-icons/font-awesome/css/font-awesome.min.css'); !!}
	
    {!! "<style>" !!}
		
		@include('vendor.flags.flags')
		
		@include('mobile.css.css-above.above-bootstrap')
		@include('mobile.css.css-above.above-generale-mobile')
		@include('mobile.css.css-above.above-listing-mobile')
		@include('mobile.css.css-above.above-covid')
		@include('mobile.css.css-above.above-rating')
		@if (session("sf"))
			@include('mobile.css.css-above.above-search-first')
        @endif
        
	{!! "</style>" !!}
    
    
  </head>

   <body class="page-listing localita">
   	
	@include("gtm-noscript")
   	@include('cookielaw')
   	
   	<header class="hidden"  role="banner">

		<h1>{{$titolo}}</h1>

	</header>
   
    @include('menu.header_menu')
	
	<section class="page">
		
		<header class="hidden" >
			<h1>{{trans("title.vetrine")}}</h1>
		</header>
		
	    @yield('content')
	   
   </section>
   
   @include('widget.barusso')
   @include('widget.content')
   
   @include('composer.footer')
	    
	<div class="button-write-all"  >
		<div class="button-write-all-inside" >
	    	<button class="button small orange" onclick="document.getElementById('emailmultiplamobileforms').submit();"><img src="{{Utility::asset('images/mail.png')}}" />&nbsp;<span>{{trans("listing.scrivi_tutti")}}</span></button>
    	</div>
    </div>
    
    <div id="map_container">
    	{{-- <a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a> --}}
	    <button  id="closeinfowindow_container_ricerca"  class="mobile-close btn btn-danger"><i class="icon-left"></i> Chiudi mappa</button>
	    <div id="map"></div>
    </div>

	@include("widget.loading")
    
    <div id="rating" style="display:none;"><p style="padding:15px; ">{{__("labels.rating")}}</p></div>

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
		 		
		<?php /*
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

		var $order = 		'{{Utility::getUrlWithLang($locale,'/order_localita')}}'; */ ?>
		
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
				
			@else
				
				var $map_disabled = true;
				
			@endif

		});	
					
		
	</script>    
	
	<script src="{{ Utility::asset('/mobile/js/js-above/listing.min.js') }}"></script>
	<script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>
	<script src="{{ Utility::asset('/mobile/js/favorites.min.js') }}"></script>
	
	@include('footer')

  </body>
</html>
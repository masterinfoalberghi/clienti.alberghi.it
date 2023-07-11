<!DOCTYPE html>
<html lang="<?php echo $locale ?>">
  <head>

    <title>@yield('seo_title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="@yield('seo_description')">
	<meta content="telephone=no" name="format-detection">
	
	@if(isset($hreflang_it) && $locale != "it")<link rel="alternate" hreflang="it" href="{{$hreflang_it}}">@endif
	@if(isset($hreflang_en) && $locale != "en")<link rel="alternate" hreflang="en" href="{{$hreflang_en}}">@endif
	@if(isset($hreflang_fr) && $locale != "fr")<link rel="alternate" hreflang="fr" href="{{$hreflang_fr}}">@endif
	@if(isset($hreflang_de) && $locale != "de")<link rel="alternate" hreflang="de" href="{{$hreflang_de}}">@endif
	
    @if (isset($for_canonical))
      <link rel="canonical" href="{{url($for_canonical)}}"> 
    @endif
	
	<style>
		
		@include('vendor.flags.flags')
		@include('mobile.css.css-above.above-bootstrap')
		@include('mobile.css.css-above.above-generale-mobile')
		@include('mobile.css.css-above.above-covid')
		
		@yield('css')
  	  
    </style>
    
    @include("header")
    @include("gtm")
	 
  </head>

  <body class="page-cms">
    
	@include("gtm-noscript")
	@include('cookielaw')
    
	@if (isset($cms_pagina_id))
		@include('menu.header_menu', ['filters'=>false, 'cms_pagina_id' => $cms_pagina_id])
	@else
		@include('menu.header_menu', ['filters'=>false])  
	@endif
    
    <article class="page">
		
	    @yield('content')
		
	</article>
	 
    @include('composer.footer')
	@include("widget.loading")
	
	{{-- Fine pagina --}}
	
    <link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app-listing.min.css') }}" />
	
	@yield("cssfiles")

    <script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
		<script src="{{ Utility::asset('/mobile/js/script.min.js') }}"></script>	
		
	<script src="{{Utility::asset('/desktop/js/delete_all_cookies.min.js')}}"></script>	

	<script type="text/javascript">
		
		var $csrf_token = '<?php echo csrf_token(); ?>';
		var $map_disabled = true; 
		
		@if (isset($cms_pagina))
			var $cms_id = '<?php echo $cms_pagina->id; ?>';
		@endif
		 						
		// Tools
		@include("lang.cl")
		
		var console=console?console:{log:function(){}};
		
		$(function() {
			
			// init
			@yield('javascript')
				
		});
		
		
	</script>    
	
	@yield("scripts")

	<script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>
	<script src="{{ Utility::asset('/mobile/js/favorites.min.js') }}"></script>

	@include("footer")
     
  </body>
</html>
<!DOCTYPE html>
<html lang="{{$locale}}">
  	<head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>@yield('seo_title')</title>
        <meta name="description" content="@yield('seo_description')">

        @yield('robots','')
			
		@if($for_canonical)
		<link rel="canonical" href="{{ url($for_canonical) }}">@endif
		
		@if (isset($previous_page_url) && !is_null($previous_page_url))
		<link rel="prev" href="{{$previous_page_url}}" />@endif
		
		@if (isset($next_page_url) &&  !is_null($next_page_url))
		<link rel="next" href="{{$next_page_url}}" />@endif
		
        @include('header')
		
        <style>
	        
	        @include('desktop.css.content')	        
	        @yield("css")
			@include('desktop.css.header-hover')	
			.main-content h3 { text-transform: none; padding-top:30px; }
			table.table_gdpr td, table.table_gdpr th { padding:5px; }
			table.table_gdpr td { border-bottom: 1px solid #ddd; }
		
		</style>
		
		@include("gtm")
		
	</head>


<body class="desktop class-cms-pagina-statica">
	
	@include("gtm-noscript")
	@include('cookielaw')
	@include('menu.header_menu')
	
	<div id="page">
		
	<main id="main-content" @if ($cms_pagina != "") data-page-edit="{{$cms_pagina->id}}" @endif>
		
		@yield("briciole")

		
		@if ($cms_pagina != "")
	    	@include('widget.testa')
		@endif
		
		<div class="main-content-list-item container" >
	        
		    <div class="row">
			  	
				<div class="content-list-item col-md-12">
					@yield('content')
	        	</div>
		        
		        <div class="clearfix"></div>

		    </div>
		    
		</div>
		
		@if ($cms_pagina != "")
			@include("widget.content")
		@endif
		
    </main>   
         
    @include('menu.sx_newsletter')
    @include('composer.footer')
	
	</div>
	
  <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
	<script src="{{Utility::asset('/vendor/sticky/jquery.sticky.min.js')}}"></script>	   

	<script src="{{Utility::asset('/desktop/js/delete_all_cookies.min.js')}}"></script>	
		
    <script type="text/javascript">
            
        var $csrf_token = 	'<?php echo csrf_token(); ?>'; 
		
		@if ($cms_pagina != "")
			var $cms_id = 		'<?php echo $cms_pagina->id; ?>';
		@endif
		
        var console = 		console?console:{log:function(){}}; 
  		
		var dizionario		= {};
        @include("lang.desktop.cookielaw")
        @yield('js')
        
		$(".sticker-menu").stick_in_parent();
		
		@include("lang.desktop.email")	
               
    </script>

	@if (Utility::DetectMobile() == 'tablet')
		<script  src="{{Utility::asset('/tablet/js/generale.min.js')}}"></script>
	@endif
   
   	@include('footer')
   	
  </body>
</html>

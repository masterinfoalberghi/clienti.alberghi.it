<!DOCTYPE html>
<html lang="{{$locale}}">
  <head>
    <meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>@yield('seo_title')</title>
    <meta name="description" content="@yield('seo_description')">
    <link rel="canonical" href="{{ url(Utility::getUrlWithLang($locale,'/compare')) }}">
	
	@include('header')
	
    <style>

		@include('desktop.css.compare')		
		@include('tablet.css.header')	
		
    </style>

	@include("gtm")
    
  </head>
  
  
<body class="tablet page-compare">
	
	@include("gtm-noscript")
	@include('cookielaw')
	@include('menu.header_menu')
	
	<div id="page">
		
		<main id="main-content">
			
			@include('widget.testa',["listing" => 1])
					    	
			<div class="main-content-list-item container" >
		        
			    <div class="row">
				   
					@yield('content')
					
			    </div>
			</div>
			
		</main>
		
		@include('menu.sx_newsletter')
		@include('composer.footer')
	
	</div>
	
	<script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
    
    <script type="text/javascript">
           
        var console=console?console:{log:function(){}}
        var $csrf_token = 	'<?php echo csrf_token(); ?>'; 
		var dizionario		= {};
        @include("lang.desktop.cookielaw")
               
    </script>
			
	<script src="{{Utility::asset('/vendor/sticky/jquery.sticky.min.js')}}"></script>
	<script  src="{{Utility::asset('/tablet/js/generale.min.js')}}"></script>
    	
	@include('footer')
	
</body>  
</html>
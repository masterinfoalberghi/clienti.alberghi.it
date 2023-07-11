<!DOCTYPE html>
<html lang="{{$locale}}">
  <head>
	  
    <title>{{$title}}</title>
    <meta name="description" content="{{$description}}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   
    <link rel="canonical" href="{{url(Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id))}}">
	
    @if (!$cliente->attivo)
		<meta name="robots" content="noindex, follow" />
	@elseif ($cliente->attivo == -1)
    	<meta name="robots" content="noindex, nofollow" />
    @endif
    
    @include('header')

    <style>

	    @include('desktop.css.scheda')
		@include('desktop.css.content')
		@include('tablet.css.above')
		@include('tablet.css.header')	
		@include('tablet.css.scheda')	
		
    </style>
	
	@include("gtm")

  </head>
  
  <body class="tablet class-page-scheda">
  	
	@include("gtm-noscript")
  	@include('cookielaw')
	@include('menu.header_menu')
	
  	<div id="page">
	
		<main id="main-content">
			
			@include('briciole')

			@include('covid-banner')
			
			<div class="container-sticker padding-bottom">	
			
				@include('widget.intestazione_scheda')
				
				<div class="clearfix"></div>
				
			<div class="container">
				<div class="row">
					
					<div class="col-md-8 col-sm-12">
						
						@yield('content')
						
					</div>
					
					@include('widget.preventivo')
					
					<div class="clearfix"></div>
					
				</div>
				
				<div class="clearfix"></div>
				
			</div>
				
			@include('hotel.other')
			@include('hotel.simili');
			<div class="clearfix"></div>	
				
			
			
		</main>
			
	  	@include('menu.sx_newsletter')
	    @include('composer.footer')
		
	</div>
	
	<script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
	
	<script type="text/javascript">
		
		var $csrf_token 	= "{{csrf_token()}}";
		var locale			= "{{$locale}}";
		var __lat 			= "{{$cliente->mappa_latitudine}}";
		var __lon 			= "{{$cliente->mappa_longitudine}}";
		
		var dizionario		= {};
        @include("lang.desktop.cookielaw")
        @include("lang.desktop.email")
        
       	var _sticker_sidebar = 55;
	  
	</script>
	
	<link href="{{Utility::asset('/vendor/slick/slick.min.css')}}" rel="stylesheet" type="text/css" />	
	<link href="{{Utility::asset('/vendor/datepicker3/bootstrap-datepicker-fe.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{Utility::asset('/vendor/venobox/venobox.min.css')}}" rel="stylesheet" type="text/css" media="screen"   />
	<link rel="stylesheet" type="text/css" media="screen"  href="{{Utility::asset('/vendor/fancybox/jquery.fancybox.min.css')}}" />

    <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>    
	<script src="{{Utility::asset('/vendor/sticky/jquery.sticky.min.js')}}"></script>
    <script src="{{Utility::asset('/vendor/slick/slick.min.js')}}"></script>
   	<script src="{{Utility::asset('/vendor/datepicker/moment.min.js')}}"></script>
  	<script src="{{Utility::asset('/vendor/datepicker3/bootstrap-datepicker.min.js')}}"></script>
	<script src="{{Utility::asset('/vendor/datepicker3/locales/bootstrap-datepicker.'.$locale.'.min.js')}}"></script>
 	<script src="{{Utility::asset('/vendor/venobox/venobox.min.js')}}"></script>

    <script src="{{Utility::asset('/desktop/js/mappa.min.js')}}"></script>
    <script src="{{Utility::asset('/desktop/js/scheda.min.js')}}"></script>
    <script src="{{Utility::asset('/tablet/js/generale.min.js')}}"></script>
    <script src="{{Utility::asset('/tablet/js/scheda.min.js')}}"></script>
    <script src="{{Utility::asset('/vendor/fancybox/jquery.fancybox.min.js')}}"></script>
    
	<script type="text/javascript">
		
		$(document).ready(function() { 

			$(".more-photo").trigger('click');
			$('.venobox').venobox(); 

		});
		
	</script>

    @include('footer')
    
  </body>
</html>



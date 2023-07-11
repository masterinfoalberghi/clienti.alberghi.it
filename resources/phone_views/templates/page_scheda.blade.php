@if (!isset($ref))
   <?php $ref = ''; ?>  
 @endif 

<!DOCTYPE html>
<html lang="{{$locale}}">
  <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title>{{$title}}</title>
    <meta name="description" content="{{$description}}">
    <meta content="telephone=no" name="format-detection">    
    <link rel="canonical" href="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id)}}">
    
    @if (!$cliente->attivo)
      <meta name="robots" content="noindex, follow" />
    @elseif ($cliente->attivo == -1)
      <meta name="robots" content="noindex, nofollow" />
    @endif

    {!!"<style>"!!}
		
		@include('vendor.flags.flags')
		@include('mobile.css.css-above.above-bootstrap')
		@include('mobile.css.css-above.above-generale-mobile')
		@include('mobile.css.css-above.above-scheda-mobile')
		@include('mobile.css.css-above.above-covid')
		@include('mobile.css.css-above.above-rating')
		.gallery-hotel { background: #222; }
		
    {!!"</style>"!!}
	
	 @include("header")
	 @include("gtm")
	  
  </head>
  
  <body class="page-scheda">
  	
	@include("gtm-noscript")
  	@include('cookielaw')
  	
  	<header class="hidden" role="banner">
		<h1>{{$cliente->nome}}</h1>
	</header>
  	
    @include('menu.header_menu_scheda')
	
	<article class="page">
	  	
		{{-- Nel mobile gli errori del coupon li metto qua perché non prende l'ancora !! --}}
		@if ( ($errors->any() && Session::has('validazione') && Session::get('validazione') == 'coupon') || Session::has('flash_message'))
			@include('errors')
		@endif
		
	    @yield('content')
	    
	</article>
	
    @include('widget.footer_hotel')
    @include('composer.footer')
    
    <div id="rating" style="display:none;"><p style="padding:15px; ">{{__("labels.rating")}}</p></div>

	{{-- Fine pagina --}}
	
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app-scheda.min.css') }}" />
    <script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>   
	<script type="text/javascript">

		var $csrf_token = '<?php echo csrf_token(); ?>';
		var console = console?console:{log:function(){}};

		// Tools
		@include("lang.cl")
		
		$(function() {
						
			@include('mobile.js.js-above.scheda')
			
		});	
					
		
	</script>    
	
    <script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>
	<script src="{{ Utility::asset('/mobile/js/favorites.min.js') }}"></script>

    @include('footer')

  </body>
</html>

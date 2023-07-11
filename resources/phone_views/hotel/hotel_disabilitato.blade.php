@if (!isset($ref))
   <?php $ref = ''; ?>  
 @endif 

<!DOCTYPE html>
<html lang="<?php echo $locale ?>">
  <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    
    <title>{{$title}}</title>
    <meta name="description" content="{{$description}}">
    
    <link rel="canonical" href="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id)}}">
    
    @if (isset($cliente) && !$cliente->attivo)
      <meta name="robots" content="noindex, follow" />
    @elseif (isset($cliente) && $cliente->attivo == -1)
		<meta name="robots" content="noindex, nofollow" />
    @endif

    {!!"<style>"!!}
		@include('mobile.css.css-above.above-bootstrap')
		@include('mobile.css.css-above.above-generale-mobile')
		@include('mobile.css.css-above.above-scheda-mobile')
		h1,h2{text-align:center; font-weight:bold; font-size:18px; margin:0; }
		.h1 { color:#195c8a; font-size:26px; padding-bottom: 30px; display: block;}
		#main { text-align:center; padding:85px 30px 25px; color:#444; background:#fff;   }
		.rating { font-size: 14px; position:relative; top:-7px; display: inline-block; vertical-align: middle; }
		footer { position:fixed; bottom:0px; left:0px; width:100%; }
	
    {!!"</style>"!!}

    @include("header")
    @include("gtm")
    
  </head>
  
  <body class="page-scheda">
	
    @include("gtm-noscript")
    @include('cookielaw')
    
    
    @include('menu.header_menu_scheda')
    
	<div class="page">
	  
	   

		<div id="main" >
			
			  <img src="{{Utility::asset('/images/sad.png')}}" style="width:50px;height:50px;"/><br /><br />
	          <span class="h1">{!! trans('hotel.disattivato') !!}</span>
			  
				<div>    
				
					<div class="row" id="datihotel">
						<span><h2>{{{$cliente->nome}}} <span class="rating">{{{is_null($cliente->stelle) ? '' : $cliente->stelle->nome}}}</span></h2></span>
					</div>
				
					<div class="row">
						<span>{{{ $cliente->indirizzo}}}</span><br />{{{ $cliente->cap }}} - <span>{{{ $cliente->localita->nome.' ('. $cliente->localita->prov . ')' }}}</span>
					</div>
					
				</div>
	
		  
		</div>
	
	    @include('composer.footer')
    
	</div>
	

	{{-- Fine pagina --}}
	
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app-scheda.min.css') }}" />
	
	
    <script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
   
    
    
    <script src="{{ Utility::asset('/mobile/js/script.min.js') }}"></script>
    
	
	<script type="text/javascript">
		
		var $csrf_token = '<?php echo csrf_token(); ?>';
		var console = console?console:{log:function(){}};

		// Tools
		@include("lang.cl")
		
		$(function() {
			
			@if(isset($google_maps) && !empty($google_maps))
			
				@include("lang.maps")
				@include('mobile.js.google-maps')
				
			@endif
			
			@include('mobile.js.js-above.scheda')
			
		});	
					
		
	</script>    
	
    <script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>
	
    @include('footer')

  </body>
</html>





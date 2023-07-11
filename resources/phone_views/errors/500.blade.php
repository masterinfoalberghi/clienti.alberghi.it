<!DOCTYPE html>
<html>
  <head>
  
    <meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    
	<title>500</title>
	
    <meta name="description" content="500 pagina in errore">   
    
    <style>
	  @include('mobile.css.css-above.above-bootstrap')
	  @include('mobile.css.css-above.above-generale-mobile')
	  #contenuto { padding:170px 30px 130px 30px; overflow: hidden; text-align: center; background: #fff url("//static.info-alberghi.com/images/start-here-2.jpg") right 10px no-repeat;  } 
	  #contenuto h1 { margin-bottom: 0; font-weight: normal;}
	  #contenuto p { line-height: 1.5em; margin:30px;  }
	  #contenuto .button { display: inline-block; }
	  .alert-info { font-size:12px; background:#fce9e9;  padding:15px; word-break: break-all; }
	  #contenuto .alert-info p{ word-break: break-all; margin:5px 0 0 0; } 
	  .alert-info p:nth-child(1) { margin-top: 15px; } 
	  .alert-info p:nth-child(odd) { background:#f5f5f5; padding:10px; }
	  .alert-info p:nth-child(even) { background:#fff; padding:10px; }
	</style>
	<script>window.dataLayer = window.dataLayer || [];</script>

       
  </head>
  
  <body class="error-500">
    	@include('cookielaw')
    @include('menu.header_menu_error')
    
	<div class="page">
		
		<section id="contenuto">
		
			<img src="/images/death.png" style="width:50px;height:50px;"/>
			
			<h1 class="red-fe"><?php  echo print_r($titolo,1); ?></h1>
			<p>{!! $desc2 !!}
						
			<?php if( Utility::isInternallUrl("https://" . Request::server("HTTP_HOST"))): ?>
				<div align="center">
					<div class="alert-info" style="text-align:left;">
						<?php echo preg_replace('/#(.*)/', "<p>#$1</p>" , $e); ?>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="clearfix"></div>
			
			
		
		</section>
		
		 @include('composer.footer')
	
	</div>
	
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	
	<script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/script.min.js') }}"></script>	
	
	<script>
	@include('lang.cl')
	</script>
	
	<script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>

	
	@include('cookielaw')
	@include('footer')
	
  </body>
</html>


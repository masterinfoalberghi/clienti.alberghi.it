<!DOCTYPE html>
<html>
  <head>
  
    <meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    
	<title>404</title>
	
    <meta name="description" content="404 Page not found">   
     
    <style>
	  @include('mobile.css.css-above.above-bootstrap')
	  @include('mobile.css.css-above.above-generale-mobile')

	  #contenuto { padding:170px 30px 30px 30px; overflow: hidden; text-align: center; background: #fff;  } 
	  #contenuto h1 { margin-bottom: 0; font-weight: normal; }
	  #contenuto p { line-height: 1.5em; margin:30px;  }
	  #contenuto .button { display: inline-block; }
	  #covid {  padding-top:50px}
	</style>
	<script>window.dataLayer = window.dataLayer || [];</script>
    
  </head>
  
  <body class="error-404">
    
	@incl ude('cookielaw')
	@include('menu.header_menu_error')
		
	<div class="page">
	
	
		<section id="contenuto">
			
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
					
						<img src="{{Utility::assetsImage('/icons/sad.png')}}" style="width:50px;height:50px;"/>
						<h1 style="color: #069">404 Page not find</h1>

						<p>{!! $desc1 !!}</p>
					
					<div class="clearfix"></div>
					</div>
				</div>
			</div>
			
		
		</section>
		
	</div>
	
	<link rel="stylesheet" href="{{ Utility::assets('/phone/css/menu.min.css', true) }}" />
	<link rel="stylesheet" href="{{ Utility::assets('/phone/css/app.min.css', true) }}" />
	
	<script src="{{ Utility::assets('/vendor/jquery/jquery.min.js', true) }}"></script>
    <script src="{{ Utility::assets('/phone/js/generale.min.js', true) }}"></script>
    <script src="{{ Utility::assets('/phone/js/script.min.js', true) }}"></script>	
	<script src="{{ Utility::assets('/phone/js/menu.min.js', true) }}"></script>
	
	
	@include('footer')
	
  </body>
</html>


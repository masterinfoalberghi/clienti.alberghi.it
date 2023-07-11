<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Pagina non trovata - 404</title>
    <meta name="description" content="404 pagina non trovata o non esistente">

    <link 	href="{{Utility::assets('/vendor/fontello/animation.min.css', true)}}" rel="stylesheet" type="text/css" />	
	<link 	href="{{Utility::assets('/vendor/fontello/fontello.min.css',  true)}}" rel="stylesheet" type="text/css" />
	<link 	href="{{Utility::assets('/vendor/flags/flags.min.css',  true)}}" rel="stylesheet" type="text/css" />
	<link 	href="{{Utility::assets('/desktop/css/above.min.css',  true)}}" rel="stylesheet" type="text/css" />
	<link 	href="{{Utility::assets('/desktop/css/404.min.css',  true)}}" rel="stylesheet" type="text/css" />

    @include('header') 
    
  </head>
  
<body class="class-page-home">
  
    @include('menu.header_menu', ["home"=>1]) 
    
    <main id="main-content">
        
        <div class="container">
	        <div class="row">
		        
		        <div class="warning404 margin-top margin-bottom">
			        <header>
				        <img src="{{ Utility::assetsImage('/icons/sad.png') }}" style="display: inline-block; " />
				        <h1>{{$titolo}}</h1>
			        </header>
					<p class="padding-top-4">{!!$desc1!!}</p>
		        </div>
		        
	        </div>
        </div>
        
    </main>
    
    <script  src="{{Utility::assets('/vendor/jquery/jquery.min.js', true)}}"></script>
    <script  src="{{Utility::assets('/desktop/js/generale.min.js', true)}}"></script>
    @include('footer')
    
</body>
</html>

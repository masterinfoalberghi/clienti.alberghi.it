<!DOCTYPE html>
<html lang="{{$locale}}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Update Old Browser</title>
    <meta name="description" content="Update Old Browser">
    
	@include('header')

    <style>
    	@include('css.css-above.above-menu')
    	header { height:auto !important; } section#contenuto { width:100% !important; text-align: center; padding:50px 0; }.link_localita { text-decoration: none !important; }
    	section#contenuto { font-size: 18px; } section#contenuto a { text-decoration: none !important; color: #666 !important; }
    	section#contenuto img{ width:auto !important} section#contenuto li { display:inline-block; margin:0 20px; }
    	#browser { margin:50px 0; } section#contenuto h1 { line-height: 1.2em; margin-bottom: 50px;  }
    </style>
	
	@include("gtm")
    
  </head>
<body class="class-page-old-browser">
  
  @include("gtm-noscript")
  
  <div id="page">
  
  <div id="main-content">
  
	<header>
		<div class="container">
			<div id="logos" style="text-align:center"><img src="{{ Utility::asset('images/logo.png') }}" class="logo" alt="Info Alberghi srl" /></div>
		</div>
	</header>
    
  <div class="clearfix"></div>

    <div class="container">
        
    <section id="contenuto">
	
	<header style="background:none;">
		<h1>{!! trans("labels.old_browser_title") !!}</h1>
	</header>
	<p>{!! trans("labels.old_browser_content") !!}</p>
	<div id="browser">
	<ul>
	<li><a target="_blank" href="https://www.google.it/chrome/"><img src="/images/browser/chrome.png" border=0><br/>Chrome</a></li>
	<li><a target="_blank" href="https://www.apple.com/{{$locale}}/safari/"><img src="/images/browser/safari.png" border=0><br/>Safari</a></li>
	<li><a target="_blank" href="https://www.mozilla.org/{{$locale}}/firefox/"><img src="/images/browser/firefox.png" border=0><br/>Firefox</a></li>
	<li><a target="_blank" href="https://www.microsoft.com/{{$locale}}-{{$locale}}/windows/microsoft-edge"><img src="/images/browser/ie.png" border=0><br/>Microsoft Edge</a></li>
	</ul>
	</div>
         	
           <div class="clearfix"></div>
           
    </section>
        
  </div>{{-- /.container --}}
    
	<div class="clear"></div>
	
	</div>
	
	<footer>
	
		<div class="copyright">
			<div class="container">
				<p id="copy">
				
					<a href="/" title="Italiano"><img src="/images/it.png" alt="italiano" 		width="34" height="23" /></a> 
					<a href="/ing" title="English"><img src="/images/uk.png" alt="english" 		width="34" height="23"/></a> 
					<a href="/fr" title="Francaise"><img alt="Francaise" src="/images/fr.png" 	width="34" height="23" /></a>
					<a href="/ted" title="Deutsch"><img src="/images/de.png" alt="Deutsch" 		width="34" height="23" /></a>
					<br /> <br />
					{{ trans('hotel.diritti') }} | <a href="/privacy-policy.php" target="_blank">{{ trans('hotel.privacy') }}</a>
				</p>
				
				<div class="clear"></div>
			
			</div>
		</div>
		
	</footer>
	
	<link href="{{ Utility::asset('/css/css-above/above-generale.min.css') }}" rel="stylesheet">
	<link href="{{ Utility::asset('/css/style.min.css') }}" rel="stylesheet" type="text/css" />
	
	<script>
		
		var dizionario		= {};
        @include("lang.desktop.cookielaw")
		
	</script>
	
	@include('cookielaw')
	</div>
	
	@include('footer')
	
  </body>
</html>


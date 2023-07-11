<!DOCTYPE html>

<html lang="{{$locale}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="@yield('seo_description')">

    <title>@yield('seo_title')</title>
    
    @if($locale == "it")
    <link rel="canonical" href="{{url('/')}}/">
    @endif 
    @if($locale == "en")
    <link rel="canonical" href="{{url('/')}}/ing">
    @endif 
    @if($locale == "fr")
    <link rel="canonical" href="{{url('/')}}/fr">
    @endif 
    @if($locale == "de")
    <link rel="canonical" href="{{url('/')}}/ted">
    @endif 

    <link rel="alternate" hreflang="x-default" href="{{url('/')}}/">
    <link rel="alternate" hreflang="it" href="{{url('/')}}/">
    <link rel="alternate" hreflang="en" href="{{url('/')}}/ing">
    <link rel="alternate" hreflang="fr" href="{{url('/')}}/fr">
    <link rel="alternate" hreflang="de" href="{{url('/')}}/ted">

    @include('header')
	
    <style>

	    @include('desktop.css.homepage')
	    @include('tablet.css.header')	
	    @include('tablet.css.homepage')	
	    @include('tablet.css.footer')	
		
    </style>
	
	@include("gtm")
    
</head>

<body class="class-page-home tablet">
    
	@include("gtm-noscript")
    @include('cookielaw') 
    
    @include('menu.header_menu', array("home"=>1)) 
    
    <div id="page">
    <main id="main-content">
        @include('covid-banner')
        <div class="container">
	        <div class="row">
                @include('flash_home')
		        @yield('content')
	        </div>
        </div>
        
    </main>
    
    @include('menu.sx_newsletter')
    @include('composer.footer') 
	
	<script  src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>  
	    
    <script type="text/javascript">
            
        var $csrf_token = '<?php echo csrf_token(); ?>'; 
        var console = console?console:{log:function(){}}; 

		var dizionario	= {};
		
        @include("lang.desktop.cookielaw")
        @include("lang.desktop.email")	
                   
    </script>
    
    </div>

    @include('footer')
    
</body>
</html>

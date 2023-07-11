<!DOCTYPE html>
<html lang="<?php echo $locale ?>">
  <head>

    <title>@yield('seo_title')</title>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
    <meta name="description" content="@yield('seo_description')" >
	  <meta content="telephone=no" name="format-detection">
	
    @if($locale == "it" )
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
    
    <style>
	    
	    @include('vendor.flags.flags')
  	  @include('mobile.css.css-above.above-bootstrap')
  	  @include('mobile.css.css-above.above-generale-mobile')
  	  @include('mobile.css.css-above.above-home-mobile')
      @include('mobile.css.css-above.above-covid')
      @include('mobile.css.css-above.above-mail-mobile')

      @if (session("sf"))
        @include('mobile.css.css-above.above-search-first')
      @endif
  	  
    </style>
	
	<link href="{{Utility::asset('/vendor/slick/slick.min.css')}}" rel="stylesheet" type="text/css" />	
	
	 @include("header")
	 @include("gtm")
	 
  </head>
  
  <body class="page-home">
	
	@include("gtm-noscript")
	@include('cookielaw')
    
    @if (session("sf"))
      @include("composer.search_first")
    @endif

    @include('phone_views/covid-banner')
    @include('menu.header_menu_home')
	
    @yield('content')  

    @include('composer.footer')


	{{-- Fine pagine --}}

    <link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
    <link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app-home.min.css') }}" />

    @if (session("sf"))
      <link rel="stylesheet" href="{{ Utility::asset('/vendor/datepicker/datepicker.min.css') }}" />
    @endif

    {{-- Fine Css --}}

    <script type="text/javascript" src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ Utility::asset('/vendor/jquery/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
    <script type="text/javascript" src="{{ Utility::asset('/mobile/js/js-above/home.min.js') }}"></script>
    <script src="{{Utility::asset('/vendor/slick/slick.min.js')}}"></script>
  
	  <script type="text/javascript">
		
      var $csrf_token = '<?php echo csrf_token(); ?>';
      var console		= console?console:{log:function(){}};
            
      // Tools
      @include("lang.cl")
      var locale = "{{$locale}}";

      $(function() {

        if ( $('#boxspot a').length >0 )
          
            $('#boxspot').slick(
              {
                  dots: true,
                  infinite: true,
                  arrows: false,
                  autoplay: true,
                  autoplaySpeed: 5000,
                  swipe: true
              }
            );

      });
	
  </script>
  
  @if (session("sf"))
    <script src="{{Utility::asset('/vendor/datepicker/moment.min.js')}}"></script>
    <script src="{{ Utility::asset('/vendor/datepicker/bootstrap-datepicker.min.js') }}" type="text/javascript" /></script>
    <script src="{{ Utility::asset('/vendor/datepicker/locales/bootstrap-datepicker.'.$locale.'.min.js') }}" type="text/javascript" /></script>
    <script type="text/javascript" src="{{ Utility::asset('/mobile/js/form-home.min.js') }}"></script>
  @endif
	
	@include('footer')
	
  </body>
</html>


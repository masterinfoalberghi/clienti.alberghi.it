<!DOCTYPE html>
<html lang="<?php echo $locale ?>">
  <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    
    <title>{{$title}}</title>
    <meta name="description" content="{{$description}}">
    <link rel="canonical" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id , true )}}" />
    
    <style>
		@include('mobile.css.css-above.above-bootstrap')
		@include('mobile.css.css-above.above-generale-mobile')
		@include('mobile.css.css-above.above-scheda-mobile-gallery')
	</style>
	
	@section('seo_title') {{$title}} @endsection
	@section('seo_description') {{$description}} @endsection
    @include("header")
    
  </head>
  
  <body class="page-scheda info">
	
	@include('cookielaw')
	
	<header class="hidden" role="banner">
		<h1>{{trans("title.gallery")}}</h1>
	</header>
	
	@include('menu.header_menu_scheda')
	
	<article class="page">
	  	
		<div class="container">
	    			
			<div class="row white-bg" style="padding-top:50px;">
				<div class="col-xs-12"  id="datihotel" style="text-align:center; ">
					<header>
						<h1 style="margin-bottom:0px;">{{$cliente->nome}} <span class="rating">{{$cliente->stelle->nome}}</span></h1>
					<p style="font-size: 12px; line-height: 18px;">
					{{{$cliente->indirizzo}}}, {{{$cliente->cap}}}, {{{$cliente->localita->nome}}} <br />
					Offerte da €{{$cliente->prezzo_min}} - €{{$cliente->prezzo_max}}
					</p>
					</header>
					<a class="tornahotel" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id)}}">&larr; {{trans("labels.torna_a")}}</b></a>
          @include("share" , ["marginbottom"=>1, "text" => 'Guarda questo hotel a *'.$selezione_localita.'*' ])
				</div>
			</div>
			
			@include('composer.hotelGallery', ['gallery' => 'gallery'])
			
	    </div>
	    
	</article>
	
	@include('composer.footer')
	
	<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe. 
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. 
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>

	{{-- Fine pagina --}}
	
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/photoswipe/photoswipe.css') }}"> 
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/photoswipe/default-skin/default-skin.css') }}"> 
	
    <script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/photoswipe/photoswipe.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/photoswipe/photoswipe-ui-default.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/gallery.min.js') }}"></script>
    
	
	<script type="text/javascript">
		
		var $csrf_token = '<?php echo csrf_token(); ?>';
		var console=console?console:{log:function(){}};

		@include("lang.cl")
		
		$(function() {
			
		});	
					
		
	</script>    
	
    <script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>
	
    @include('footer')
    
        
  </body>
</html>


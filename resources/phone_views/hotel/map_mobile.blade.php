<!DOCTYPE html>
<html lang="{{$locale}}">
  <head>
  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    
    <title>{{$title}}</title>
    <meta name="description" content="{{$description}}">
    <link rel="canonical" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id , true )}}" />

    
    <style>
		@include('mobile.css.css-above.above-bootstrap')
		@include('mobile.css.css-above.above-generale-mobile')
		@include('mobile.css.css-above.above-scheda-mobile-map')
	</style>
	
	@section('seo_title') {{$title}} @endsection
	@section('seo_description') {{$description}} @endsection
    @include("header")
	
  </head>
  
  <body class="page-scheda info">
	
	
	@include('cookielaw')
	
	<header class="hidden" role="banner">
		<h1>{{trans("title.mappa")}}</h1>
	</header>
	
	@include('menu.header_menu_scheda')
	
	<article class="page">
	  	
		<div class="container">
	    
			<div class="row white-bg" style="padding-top:50px;">
				{{-- <div class="col-xs-12"  id="datihotel" style="text-align:center; ">
					<header>
						<h1 style="margin-bottom:0px;">{{$cliente->nome}} <span class="rating">{{$cliente->stelle->nome}}</span></h1>
					</header>
					<a style="display:inline-block" class="button green small" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id)}}">{{trans("labels.torna_a")}}</b></a>
				</div> --}}
				<div style="padding:0 15px 15px;">
				@include("share")
				</div>
				
			</div>
			
			<div class="risultati_ricerca">
		    	<div data-id="{{$cliente->id}}" data-url="" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="link_item_slot"></div>
	    	</div>
			
			{{-- bottone che visualizza i PuntiDiForza con uno scroll verso il bottom --}}
			
			<a title="Close" id="close" class="fancybox-item fancybox-close" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id , true )}}"></a>
			<div id="show_pdf" class="pdf" alt="Punti di interesse" width="48" height="48"></div>
			
	    	<div id="map"></div>
			
	    	@include('composer.puntiDiInteresse', array('titolo' => trans('labels.9punti_forza'), 'hotel_simili' => 0,  'in_hotel' => 1))

	    </div>
	    
	</article>

	
	{{-- Fine pagina --}}
	
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	
    <script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/markerclusterer.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/google-maps-scheda-hotel.min.js') }}"></script>
    
	<script type="text/javascript">
		
		var $csrf_token = '<?php echo csrf_token(); ?>';
		var console=console?console:{log:function(){}};
		var $viewnow = ""; 
		var $vedi = ""; 
		
		@include("lang.cl")
		@include("lang.maps")
		
		$(function() {
			
			var h = $(window).height() + 55;

			console.log(h);
			/*if (h > 515) h= 517;*/
			$("#map").height(h);
			
			$.getScript( '//maps.googleapis.com/maps/api/js?key=AIzaSyCAyCUJ63a6dtvWfdAaqCmLxrWqOombjM8&amp;language=it' ).done(function( script, textStatus ) {initialize();})




			$("#show_pdf").click(function(){
				$("#map").slideToggle();
				$(this).toggleClass("map");
			});
			
		});
		
	</script>    
	
    <script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>
	
        
  </body>
</html>





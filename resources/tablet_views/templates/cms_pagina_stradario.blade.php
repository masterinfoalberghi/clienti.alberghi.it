

<!DOCTYPE html>
<html lang="{{$locale}}">
  	<head>
	  	
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <title>@yield('seo_title')</title>
	    
        <meta name="description" content="@yield('seo_description')">
			
		@if($for_canonical)
		<link rel="canonical" href="{{ url($for_canonical) }}">@endif
        
        @include('header')
        
        <style>

        	@include('desktop.css.stradario')
        	@include('desktop.css.content')
			@include('tablet.css.header')	
			@include('tablet.css.listing')	
        	
        </style>

		@include("gtm")

	</head>

<body class="tablet class-cms-pagina-stradario">
		
		@include("gtm-noscript")
		@include('cookielaw')
		@include('menu.header_menu')
		
		<div id="page">
				
			<main id="main-content" data-page-edit="{{$cms_pagina->id}}">
				
				@include('widget.testa',["listing" => 1])
				
				<div class="padding-top">
					
					@include('widget.mappa' , ['tipo'=>'stradario', 'map_source' => []])
					
				</div>
			
				<div class="clearfix"></div>
				
		    	<div class="main-content-list-item container padding-bottom" >
		        
				    <div class="row">
					   
					   @yield('contentmap')
					   <div class="clearfix"></div>
					   
				    </div>
									    	
		    	</div>
			    		 
		    	<div class="clearfix"></div>
		    	
				@yield('content') 
			
	  	</main>

	 
	  	@include('menu.sx_newsletter')
	    @include('composer.footer')
	    
	</div>
    
    <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
    <script  src="{{Utility::asset('/desktop/js/mappa.min.js')}}"></script>
    
    <script type="text/javascript">
           
        var console=console?console:{log:function(){}}
        var $csrf_token = 	'<?php echo csrf_token(); ?>'; 
		var $cms_id = 		'<?php echo $cms_pagina->id; ?>';
        var console = 		console?console:{log:function(){}}; 
        	
		var dizionario		= {};
        @include("lang.desktop.cookielaw")
		
		var dizionario		= {};
        dizionario.link = "{{trans('listing.vedi_hotel')}}";
        dizionario.prm = "{{trans('listing.p_min')}}";
        dizionario.prM = "{{trans('listing.p_max')}}";
        		
		var __markers_source = {};
		
		@php $t = 0; @endphp
		
		@foreach($clienti as $cliente)
			
			@php
				
				$lat = $cliente->mappa_latitudine;
				$lon = $cliente->mappa_longitudine;
				$img = $cliente->getListingImg("360x200", true, $cliente->listing_img);
				$nom = $cliente->nome;
				$rat = $cliente->stelle->nome;
				$adr = $cliente->indirizzo . " - " . $cliente->cap . "<br />" . $cliente->localita->nome . "  (" .  $cliente->localita->prov . ')';
				$lnk = Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id);
				
				$cnt = [];
				if ($cliente->telefono) $cnt[] = Lang::get('hotel.tel') . " <b>" . $cliente->telefono . "</b>";
				if ($cliente->cell) 	$cnt[] = "Mobile <b>" . $cliente->cell . "</b>";
				if ($cliente->whatsapp) $cnt[] = "Whatsapp <b>" . $cliente->whatsapp . "</b>";
				
				$web = [];
				if ($cliente->link)  $web[] = 'Web <b><a target=\"_blank\" href=\"/away/'.$cliente->id.'\">' . $cliente->link . '</a></b>';
				if ($cliente->email) $web[] = "Email <b>" . $cliente->email . "</b>";
				if ($cliente->skype) $web[] = "Skype <b>" . $cliente->skype . "</b>";
				
				$cnt = implode("<br />", $cnt);
				$web = implode("<br />", $web);
				
				$prm = Utility::setPriceFormat($cliente->prezzo_min);
				$prM = Utility::setPriceFormat($cliente->prezzo_max);
				
			@endphp
		
			__markers_source[{{$t}}] = {

				"lat": "{{$lat}}", 
				"lon": "{{$lon}}",
				"img": "/{{$img}}", 
				"nam": "{{$nom}}",
				"rat": "{{$rat}}", 
				"adr": "{!!$adr!!}", 
				"cnt": "{!! $cnt !!}", 
				"web": "{!! $web !!}", 
				"lnk": "{{$lnk}}", 
				"prm": "{!!$prm!!}", 
				"prM": "{!!$prM!!}"

			};

			@php $t++; @endphp
			
		@endforeach;
		
		$(function() {
			$.getScript( '//maps.googleapis.com/maps/api/js?key={{Config::get("google.googlekey")}}&amp;language=it' ).done(function( script, textStatus ) { window.initializeStradario(__lat, __lon, __markers_source);})
		});
		
	</script>
    
	@include('footer')
	
  </body>
</html>
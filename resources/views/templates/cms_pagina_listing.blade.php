@php
ServerTiming::start('vista')
@endphp

<!DOCTYPE html>
<html lang="{{$locale}}">
	
  	<head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>@yield('seo_title')</title>
        <meta name="description" content="@yield('seo_description')">
			
		@if($for_canonical)
		<link rel="canonical" href="{{ url($for_canonical) }}">@endif
		
		@if (!is_null($previous_page_url))
		<link rel="prev" href="{{$previous_page_url}}" />@endif
		
		@if (!is_null($next_page_url))
		<link rel="next" href="{{$next_page_url}}" />@endif
		
    @include('header')
  
    {!! HTML::style('neon/css/font-icons/font-awesome/css/font-awesome.min.css'); !!}


        		
        {!! "<style>" !!}

			@include('desktop.css.listing')
			@include('desktop.css.content')
	        @include('desktop.css.header-hover')
	        @include('desktop.css.item-listing-hover')
	        @include('desktop.css.sidebar-hover')
			
        {!! "</style>" !!}
		
		@include("gtm")
          
	</head>


<body class="desktop @if($cms_pagina->indirizzo_stradario != "") class-cms-pagina-stradario @else class-cms-pagina-listing @endif">
	
	@include("gtm-noscript")
	@include('cookielaw')
	@include('menu.header_menu')
		
	<div id="page">
	<main id="main-content" data-page-edit="{{$cms_pagina->id}}">

			@include('briciole')
		
    	@include('widget.testa',["listing" => 1])
		
		<div class="main-content-list-item container" >
	        
	        <div class="row">
		        
		        @include('widget.filtri') 

		        <div id="content-listing">

			    @include("widget.sidebar")
		        
			       
				   	<div class="content-list-item col-xs-9" >
				   	
						@if ($clienti->count())
							@yield('content')
						@else
							@include("cms_pagina_listing.no_results")
						@endif
						
			        </div>
			        <div class="clearfix"></div>
		    	</div>
				
				<div class="clearfix"></div>
		
		    </div>
		    
		</div>
		
		@include("widget.content")
		
    </main>   
         
    @include('menu.sx_newsletter')
    @include('composer.footer')
	
	</div>
    
    <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
    
    <script type="text/javascript">
            
        var console=console?console:{log:function(){}}
        var $csrf_token = 	'<?php echo csrf_token(); ?>'; 
		var $cms_id = 		'<?php echo $cms_pagina->id; ?>';
        
        //  Dizionario javascript
		var dizionario		= {};
		dizionario.cambia_localita = '{{trans("labels.cambia_localita")}}';
		dizionario.writeall = '{{trans("listing.scrivi_tutti_short")}}';
		dizionario.writeselected = '{{trans("listing.scrivi_email")}}';
        dizionario.rating = '{{trans("labels.rating")}}';
        @include("lang.desktop.cookielaw")
               
    </script>
    
	<link rel="stylesheet" type="text/css" media="screen"  href="{{Utility::asset('/vendor/venobox/venobox.min.css')}}" />
	<script src="{{Utility::asset('/vendor/sticky/jquery.sticky.min.js')}}"></script>
	<script src="{{Utility::asset('/vendor/lazyloading/jquery.lazy.min.js')}}"></script>
    <script src="{{Utility::asset('/desktop/js/listing.min.js')}}"></script>
    <script src="{{Utility::asset('/vendor/venobox/venobox.min.js')}}"></script>

    <script type="text/javascript">
	    
	    $(function() {
	    		    	
	    	// Attivo tutti i comportamenti degli slot
	    	attivaClickPreferiti();
	    	attivaCheckbox();
	    	attivaFiltri();

	    	// Carico le immagini on demand
	    	$('.lazy').lazy();
	    	$(".sticker-sidebar").stick_in_parent({offset_top: 124 });
		
			@if (Utility::isValidIP() )
				
				$(".tag.click").each(function (i) {
					$(this).html((i+1) + " - " + $(this).html());	
				})
				
			@endif

			@include("lang.desktop.email")	
	    	
	     });
	    
    </script>
   
   	@include('footer')
    
  </body>
</html>

@php
		\ServerTiming::stop('vista')
@endphp


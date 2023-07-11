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
		@include('mobile.css.css-above.above-scheda-mobile-offerte')
		@include('mobile.css.css-above.above-scheda-mobile-lm')
	</style>
	
	@section('seo_title') {{$title}} @endsection
	@section('seo_description') {{$description}} @endsection
    @include("header")
    
  </head>
  
  <body class="page-scheda info">
	
	@include('cookielaw')
	
	<header class="hidden" role="banner">
		<h1>{{trans("title.lastminute")}}</h1>
	</header>
	
	@include('menu.header_menu_scheda')
	
	@if ($cliente->last->count() < 1 && $cliente->offerteTopLast->count() < 1)
	
		<div class="row nessun_risultato" style="padding-top:80px;">   
			<sezione class="page ">
		
				<div class="">
					<img src="//static.info-alberghi.com/images/icons/red/HighPriority.png" alt="warning" width="64" height="64">
					<p class="red-fe">{{trans("labels.no_result")}}</p>
				</div>
				
				<div class="row white-bg" style="padding-top:50px;">
					<div class="col-xs-12"  id="datihotel" style="text-align:center; ">
						<header>
							<h1 style="margin-bottom:0px;">{{$cliente->nome}} <span class="rating">{{$cliente->stelle->nome}}</span></h1>
						</header><br />
						<a class="tornahotel" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id)}}">&larr; {{trans("labels.torna_a")}}</b></a>
						<a style="display:inline-block; width:60%; " class="button green small" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id)}}">{{trans("labels.torna_a")}}</b></a>
					</div>
				</div>
				
			</sezione>
		</div> 	
		
	@else
	
		<article class="page">
		  		
			<div class="container">
		    
				<div class="row white-bg" style="padding-top:50px;">
					<div class="col-xs-12"  id="datihotel" style="text-align:center; ">
						<header>
							<h1 style="margin-bottom:0px;">{{$cliente->nome}} <span class="rating">{{$cliente->stelle->nome}}</span></h1>
						</header>
						<a class="tornahotel" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id)}}">&larr; {{trans("labels.torna_a")}}</b></a>
						@include("share" , ["marginbottom"=>1, "text" => 'Ci sono delle offerte *last-minute* per questo hotel a *'.$selezione_localita.'*'])
					</div>
				</div>
				
				@include('composer.offerteLast', array('titolo' => trans('hotel.last'), 'tipo' => 'lastminute'))
				
		    </div>
		    
	    
		</article>
	
	@endif
	
    @include('composer.footer')

	{{-- Fine pagina --}}
	
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	
    <script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
    
	
	<script type="text/javascript">
		
		var $csrf_token = '<?php echo csrf_token(); ?>';
		var console=console?console:{log:function(){}};

		@include("lang.cl")
		
		$(function() {
			
			$(".readall").click(function (e) {
				
				e.preventDefault();
				$("#" + $(this).data("id")).addClass("all");
				$(this).hide();
			});

			$(".pulsante_chiama_scheda").click(function(e){
				var id = $(this).data("id");
				
				var data = {
					hotel_id: id
				};
				
				$.ajax({ 

					url: '<?=url("/callMe.php") ?>',
					type: 'GET',
					data: data,
				});

				return;
			});
				
		});
				
		
	</script>    
	
    <script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>
	
    @include('footer')
    
        
  </body>
</html>





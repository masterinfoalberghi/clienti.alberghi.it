@if (!isset($ref))
   <?php $ref = ''; ?>  
 @endif 

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
		@include('mobile.css.css-above.above-scheda-mobile-coupon')
		 .col-button { height:auto !important; }
	</style>
	
	@section('seo_title') {{$title}} @endsection
	@section('seo_description') {{$description}} @endsection
    @include("header")
    
  </head>
  
  <body class="page-scheda info">
	
	
	@include('cookielaw')
	
	<header class="hidden" role="banner">
		<h1>{{ trans('title.buono_sconto') }}</h1>
	</header>
	
	@include('menu.header_menu_scheda')
	
	
	@if ( $cliente->coupon->count() < 1 )
	
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
						<a style="display:inline-block;  width:60%; " class="button green small" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id)}}">{{trans("labels.torna_a")}}</b></a>
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
					<a style="display:inline-block" class="button green small" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id)}}">{{trans("labels.torna_a")}}</b></a>
				</div>
			</div>		
					    
			@include('composer.coupon', array('titolo' => trans('hotel.coupon')))
	    
	    </div>
		
	</article>
	@endif
	
  @include('composer.footer')
	
	   
	@include("widget.loading")
	

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
			
			var $datepickerdfree = true;		
			var $alertCampi	= "<?php echo trans('labels.campi_compilati') ?>"; 
			@include("mobile.js.form")
			
		});	
					
		
	</script>    
	
    <script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>

    @include('footer')
    
        
  </body>
</html>





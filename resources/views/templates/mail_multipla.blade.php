<!DOCTYPE html>
<html lang="{{$locale}}">
  	<head>
	  	
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <title>{{ trans('listing.invia_multiplo') }}</title>
        <meta name="description" content="">
        
        @include('header')
        
        {!! "<style>" !!}
	        
        	@include('desktop.css.wishlist')
        	@include('desktop.css.content')
			@include('desktop.css.header-hover')
			.daterange a.select_icon, 
			.daterange a.select_icon:hover { color: #333!important; }		
        	
        {!! "</style>" !!}
 
		 @include("gtm")
		 
	</head>

<body class="desktop class-mail multipla">
	
	@include("gtm-noscript")
	@include('cookielaw') 
	@include('menu.header_menu')
	
	<div id="page">
	
		<main id="main-content">
			
			@if ($count_ids_send_mail)
			<div id="briciole">
				<div class="container">
					<div class="row">
						<p> {{count($count_ids_send_mail)}} Hotel selezionati </p>
					</div>
				</div>
		 	</div>
		 	@endif
			
			<div class="main-content-list-item container padding-top-2" style="margin-bottom: 50px;">
				
				<div class="row">
					<div class="col-xs-2"></div>
					<div class="col-xs-8">
						<header> 
							<h1>{{ trans('listing.invia_multiplo') }}</h1>
						</header>
					</div>
		         </div>
		        
			    <div class="row">
				  	
				  	<?php /* @include("widget.sidebar") */ ?>
				  	
					<div class="col-xs-2"></div>
					<div class="content-list-item col-xs-8">
								
						<div class="main-content-list-item" >
								
	      					@if ($count_ids_send_mail)

								@include("widget.form.preventivo-multipla",
											array(
												'recente' => false,
												'actual_link' => '/wishlist',
												'referer' => '/wishlist',
												'wishlist' => 1,
												'privacy' => $privacy
											)
										)
							@else
							
								@include("widget.form.preventivo-multipla",
											array(
												'recente' => false,
												'actual_link' => '/mail-multipla',
												'referer' => '/mail-multipla',
												'mail_multipla' => 1
											)
										)	
							@endif			
							
							<div class="clearfix"></div>
        						
						</div>			
			           
						
					</div>
					
			        <div class="clearfix"></div>
	
			    </div>
			    
			</div>
	
		</main>
		  	
		@include('menu.sx_newsletter')
	    @include('composer.footer')
	
	</div>
	
	<link  href="{{Utility::asset('/vendor/datepicker3/bootstrap-datepicker-fe.min.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{Utility::asset('/vendor/sticky/jquery.sticky.min.js')}}"></script>
	<script src="{{Utility::asset('/vendor/datepicker3/moment.min.js')}}"></script>
    <script src="{{Utility::asset('/vendor/datepicker3/locales/'.$locale.'.js')}}"></script>
  	<script src="{{Utility::asset('/vendor/datepicker3/bootstrap-datepicker.min.js')}}"></script>
	<script src="{{Utility::asset('/vendor/datepicker3/locales/bootstrap-datepicker.'.$locale.'.min.js')}}"></script>
    
	<script type="text/javascript">
           
        var console=console?console:{log:function(){}}
        var $csrf_token = 	'<?php echo csrf_token(); ?>'; 
        	
		var dizionario		= {};
        @include("lang.desktop.cookielaw")
        @include("lang.desktop.email")
               
    </script>
	
	@include('footer')
	
</body>
</html>
	

		
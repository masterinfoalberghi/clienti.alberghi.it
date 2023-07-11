@if (!isset($ref))
   <?php $ref = ''; ?>  
 @endif 

<!DOCTYPE html>
<html lang="<?php echo $locale ?>">
  <head>
	  
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    
    {{-- hreflang da middleware --}}
    
    <title>{{$title}}</title>
    <meta name="description" content="{{$description}}">
    <link rel="canonical" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id , true )}}" />

        
    <style>
		@include('mobile.css.css-above.above-bootstrap')
		@include('mobile.css.css-above.above-generale-mobile')
		@include('mobile.css.css-above.above-scheda-mobile-listino')
		
		
		@if ( 
			$cliente->listini->count() == 0 &&
			$cliente->listiniMinMax->count() == 0 &&
			$cliente->listiniCustom->count() == 0 
		)		
		
		#no_prezzi { background: #fce9e9; }
		@endif
		
	</style>

	@section('seo_title') {{$title}} @endsection
	@section('seo_description') {{$description}} @endsection
    @include("header")
    	
  </head>
  
  <body class="page-scheda info">
	
	
	@include('cookielaw')
	
	<header class="hidden" role="banner">
		<h1>{{trans("title.prezzi")}}</h1>
	</header>
	
	@include('menu.header_menu_scheda')
	
	<article class="page">
	  		
		<div class="container">
	    
			<div class="row white-bg" style="padding-top:50px;">
				<div class="col-xs-12"  id="datihotel" style="text-align:center; ">
					<header>
						<h1 style="margin-bottom:0px;">{{$cliente->nome}} <span class="rating">{{$cliente->stelle->nome}}</span></h1>
					</header>
					<a class="tornahotel" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id)}}">&larr; {{trans("labels.torna_a")}}</b></a>
					@include("share" , ["marginbottom"=>1, "text" => 'Guarda il listino prezzi di questo hotel a *'.$selezione_localita.'*'])
				</div>
			</div>
			
			@if ($cliente->listini->count() != 0)
			<a name="listini"></a>
			@include('composer.listino', array('titolo' => trans('hotel.listino_prezzi')))	
			@endif
			
			@if ($cliente->listiniMinMax->count() != 0)
			<a name="listiniminmax"></a>		
			@include('composer.listinoMinMax', array('titolo' => trans('hotel.listino_minmax')))	
			@endif
			
			@if($cliente->listiniCustom->count() != 0)
			<a name="listinicustom"></a>
			@include('composer.listiniCustom', array('titolo' => trans('hotel.listino_variabile')))
			@endif
			
			@if ( 
				$cliente->listini->count() == 0 &&
				$cliente->listiniMinMax->count() == 0 &&
				$cliente->listiniCustom->count() == 0 
			)
        
				<div class="row cyan-bg" id="no_prezzi">
					<div class="col-xs-12">
						<h2 class="title">{!! Lang::get('hotel.listino_prezzi') !!}</h2>
						
						<p>{!! Lang::get('hotel.no_listino_mobile') !!}</p>
						
						<a data-id="{{$cliente->id}}" class="pulsante_chiama_scheda button green white-fe small callbutton" href="tel:{{explode(',',$cliente->telefoni_mobile_call)[0]}}">
							{{trans("hotel.chiama")}}
						</a>
						
						{!! Form::open(['url' => url(Utility::getLocaleUrl($locale).'hotel.php?id='. $cliente->id .'&contact')]) !!} 
						{!! Form::hidden('locale',$locale) !!}
						{!! Form::hidden('ids_send_mail', $cliente->id)!!}
						{!! Form::hidden('referer', $ref)!!}
						{!! Form::submit(trans("hotel.scrivi"),["class"=>"invioemailhotel button orange emailbutton small"]) !!}      
						{!!Form::close()!!}
						
					</div>
				</div>
			@else
				{{--  
				ELENA 04/09/17: le note listino prezzi si vedono SOLO se c'è almeno un listino prezzi 
				--}}
				@if (isset($cliente->notaListino))
					<?php $testo = $cliente->notaListino->noteListino_lingua->first()->testo; ?>
					@if ( $testo != '')
						<footer id="noteprezzi" class="row white-bg" >
							<div class="col-xs-12">
								<h3>{!! Lang::get('hotel.listino_note') !!}</h3>
								{!! $testo !!}
								@php
									$tassaSoggiorno = App\TassaSoggiorno::getTassaLabel($cliente->id); 
									if (isset($tassaSoggiorno[0])):
										echo strtoupper($tassaSoggiorno[0]) . "<br />- ";
										unset($tassaSoggiorno[0]); 
										echo  implode("<br/>- ", $tassaSoggiorno) . "<br/><br/>";
									endif;
								@endphp
							</div>
						</footer>
					@endif
				@endif
			
			@endif
			
			
			
			</div>
	    
    
	</article>
	
    @include('composer.footer')

	{{-- Fine pagina --}}
	
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	
	
    <script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
    
	
	<script type="text/javascript">
		
		var $csrf_token = '<?php echo csrf_token(); ?>';
		var console = console?console:{log:function(){}};

		// Tools
		@include("lang.cl")
		
		$(function() {
						
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





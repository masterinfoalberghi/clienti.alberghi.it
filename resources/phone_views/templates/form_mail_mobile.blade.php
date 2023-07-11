
<!DOCTYPE html>
<html lang="{{$locale}}">
  <head>

    <title>{{trans("hotel.richiedi_preventivo_1")}} {{trans("hotel.richiedi_preventivo_2")}}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="">
    <meta content="telephone=no" name="format-detection">

	@if(isset($hreflang_it) && $locale != "it")<link rel="alternate" hreflang="it" href="{{$hreflang_it}}">@endif
	@if(isset($hreflang_en) && $locale != "en")<link rel="alternate" hreflang="en" href="{{$hreflang_en}}">@endif
	@if(isset($hreflang_fr) && $locale != "fr")<link rel="alternate" hreflang="fr" href="{{$hreflang_fr}}">@endif
	@if(isset($hreflang_de) && $locale != "de")<link rel="alternate" hreflang="de" href="{{$hreflang_de}}">@endif
	
	@if(!isset($multipla) || $multipla != "si")
		 <link rel="canonical" href="{{Utility::getUrlWithLang($locale, "/hotel.php?id=" . $cliente->id , true )}}" />
	@endif
	
	<style>
		
		@include("vendor.flags.flags") 
		@include("mobile.css.css-above.above-bootstrap") 
		@include('mobile.css.css-above.above-generale-mobile') 
		@include("mobile.css.css-above.above-mail-mobile") 
		@include('mobile.css.css-above.above-covid')
		
	</style>
    
     @include("header")
	 @include("gtm")
     
  </head>

  <body class="page-form">
    
	@include("gtm-noscript")
    @include('cookielaw')
    
    <header class="hidden">
		<h1>{{trans("title.scrivi_tutti")}}</h1>
	</header>
    
	@if (isset($cliente))
		@include('menu.header_menu_scheda')
	@else
		@include('menu.header_menu') 
	@endif

	<article class="page @if(isset($cliente) && $cliente->chiuso_temp) closed @endif">
			
		<div id="mail_multipla_mobile">
			<div class="container orange-body">
				
				@if(isset($multipla) && $multipla == "si")
							
					{!!Form::open(['url' => Utility::getLocaleUrl($locale).'richiesta-mail-multipla', 'id' => 'form_mail_multipla_mobile'])!!}
					{!!Form::hidden('tag', "EM")!!}
					
				@else
					
					{!!Form::open(['url' => Utility::getLocaleUrl($locale).'richiesta-mail-scheda', 'id' => 'form_mail_scheda_mobile'])!!}
					{!!Form::hidden('tag', "ED")!!}

				@endif

				<div class="row" style="background: #FF5722; padding:15px 15px 0; margin:0 -17px">
					<div class="col-xs-12 ">
						<header>
							<hgroup>
								<h1 style="line-height:1em;text-overflow: ellipsis; overflow:hidden; white-space:nowrap;">{{trans("hotel.richiedi_preventivo_1")}}</h1>
								@if (isset($cliente) && $cliente->bonus_vacanze_2020 == 1 && $locale =="it")
									<h2 style="line-height:1em;font-size:16px;">{{trans("hotel.richiedi_preventivo_2_bonus")}}</h2>
								@else
									<h2 style="line-height:1em;font-size:16px;">{{trans("hotel.richiedi_preventivo_2")}}</h2>
								@endif
							</hgroup>
						</header>
					
								
						@if(isset($multipla) && $multipla == "si")
							
							@php
								$list_meal_plan = Utility::getAllMealPlan();
							@endphp
							<p style="text-align: center; color:#ffe9d3; padding:0 15px; margin:2px 0 15px 0">{{trans("listing.scrivi_multipla_mobile")}}</p>
							
						@else
							
							@php
								$list_meal_plan = $cliente->getListingTrattamento();
							@endphp
							<div class="h3">
								{{{$cliente->nome}}} <span class="rating">{{{$cliente->stelle->nome}}}</span>
							</div>
							
						@endif
				
					</div>
				</div>

				{!!Form::hidden('numero_camere', $numero_camere, ["id" => "numero_camere"])!!}
				{!!Form::hidden('type', "mobile")!!}
				
				<?php /*{!!Form::hidden('flex_date_value', $flex_date_value, ["id" => "flex_date_value"])!!} */ ?>
				
				@if ($errors->any() || Session::has('flash_message'))
					@include('errors')
				@endif
		
				<div id="errors">
					
									
				</div>
				
				<div class="warning recently" style="display:none;">
					{{trans("labels.hai_gia_scritto")}}
				</div>

				@if (isset($offerta))
				
					<div class="row"><br />
						<div class="col-xs-12 ">
							<div class="offerta col-form">
								<span class="badge {{$tipo_offerta}}">
									{{$testo_offerta}}
								</span><p>{{$offerta}}</p>
							
							</div>
						</div>
						<div class="clear"></div>
					</div>
					
				@endif
				
				<div id="rooms-list">
					
					@for ($i = 0; $i < $numero_camere; $i++)
					
						<div id="room_{{$i}}" class="room">
	
							<h3 class="camera_label" @if ($numero_camere == 1) style="display:none;" @endif>
								<b>{{ trans('labels.room') }} <span>{{ $i+1 }}</span></b> - <span id="date_night_{{$i}}" class="date_night"></span>
							</h3>
							
							@php
															
								$room = 		$prefill["rooms"][$i];
								if (isset($cliente) && $cliente->chiuso_temp) {

									$lowlimit = Carbon\Carbon::createFromFormat('d/m/Y', "01/10/2020")->format("d/m/Y");
									$today 	   = Utility::ePrimaDiOttobre($prefill["rooms"][$i]["checkin"]);
									$tomorrow  = Utility::ePrimaDiOttobre($prefill["rooms"][$i]["checkout"],1);

								} else {

									$lowlimit = Carbon\Carbon::now()->format("d/m/Y");
									$today 	   = Utility::ePrimaDiOggi($prefill["rooms"][$i]["checkin"]);
									$tomorrow  = Utility::ePrimaDiOggi($prefill["rooms"][$i]["checkout"],1);

								}
																
								$flex_date = 	$prefill["rooms"][$i]["flex_date"];
								$adults = 		$prefill["rooms"][$i]["adult"];
								$children = 	$prefill["rooms"][$i]["children"];
								$age_children = $prefill["rooms"][$i]["age_children"];

								$meal_plan = 		Utility::getMultiMealPlan($prefill["rooms"][$i]["meal_plan"], $list_meal_plan);
								$meal_plan_code = 	Utility::getMultiMealPlanCode($prefill["rooms"][$i]["meal_plan"], $list_meal_plan);
								
								$adulti_select  = \App\MailMultipla::$adulti_select;
								$bambini_select = \App\MailMultipla::$bambini_select;
    							$adulti_over_select = \App\MailMultipla::$adulti_over_select;

								$t = 1;
								foreach($adulti_select as $as) {
									$adulti_select[$t] = str_replace(["As", "Ao"], [__("labels.adulti"),__("labels.adulto")], $adulti_select[$t]);
									$t++;
								}

								$t = 0;
								foreach($bambini_select as $bs) {
									$bambini_select[$t] = str_replace(["Bs", "Bo"], [__("labels.bambini"),__("labels.bambino")], $bambini_select[$t]);
									$t++;
								}

								if (strpos($age_children, ","))
									$age_children = explode("," , $age_children);
								else 
									if ($age_children >= 0)
										$age_children = array($age_children);
									else
										$age_children = array();
																			
								if(isset($multipla) && $multipla == "si") {
									$list_meal_plan = Utility::Trattamenti();
								} else {
									$list_meal_plan = $cliente->getListingTrattamento();
								}
														
							@endphp
							
							@include('widget.form.formDatePicker', 			array('order_number' => $i, 'tomorrow' => $tomorrow, 'today' => $today, "lowlimit" => $lowlimit ))
							@include('widget.form.formTrattamento',			array('order_number' => $i, 'list_meal_plan' => $list_meal_plan, 'meal_plan' => $meal_plan))
							@include('widget.form.formSelectAdultiBambini',	array('order_number' => $i, 'adults' => $adults, 'children' => $children, 'age_children' => $age_children))
							@if ($i == 0)
								@include('widget.form.formEmail')
							@endif

							
							<div class="clear"></div>
							
						</div>
						
						
					@endfor 
				</div>
		
				
				<div class="row row-colums rooms">
					<div class="col-xs-6">
						<div class="col-form" style="background:none;">
							<a href="#" class="small button green light white-fe " id="addCamera" style="display: inline-block; padding:5px; text-align: center; display: block; color:#fff;">{{ trans('labels.add_rooms') }}</a> 
						</div>
					</div>
					<div class="col-xs-6" style="float: right;">
						<div class="col-form" style="background:none;">
							<a href="#" class="small button red light white-fe " id="delCamera" style="display: none; padding:5px; text-align: center; display: block; color:#fff;">{{ trans('labels.del_rooms') }}</a>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<div class="clearfix"></div>
				
		
				<h3>
					{{trans("hotel.dati_personali_whatsapp")}}
				</h3>
				
				<div class="row">
					
					@if(!isset($multipla) || $multipla != "si")
						
							<div class="col-xs-4 ">
								<div class="col-form">
									<label class="cyan-fe">{!! trans('hotel.prefix') !!} <i class="fa fa-info"></i></label>								
									<select id="prefix_input" name="prefix_input">
										<optgroup label="{{__("hotel.fast_international_code")}}">
											<option value="39">+{{__("hotel.international_code")["39"]}}</option> 	{{-- italia --}}
											<option value="49">+{{__("hotel.international_code")["49"]}}</option>	{{-- Germania --}}
											<option value="41">+{{__("hotel.international_code")["41"]}}</option> 	{{--Svizzera --}}
											<option value="33">+{{__("hotel.international_code")["33"]}}</option> 	{{-- Francia --}}
											<option value="1">+{{__("hotel.international_code")["1"]}}</option> 	{{-- USA --}}
											<option value="32">+{{__("hotel.international_code")["32"]}}</option> 	{{-- Belgio --}}
											<option value="44">+{{__("hotel.international_code")["44"]}}</option> 	{{-- Regno unito --}}
											<option value="43">+{{__("hotel.international_code")["43"]}}</option> 	{{-- Austria --}}
											<option value="40">+{{__("hotel.international_code")["40"]}}</option> 	{{-- Romania --}}
											<option value="7">+{{__("hotel.international_code")["7"]}}</option> 	{{-- Russia --}}
											<option value="48">+{{__("hotel.international_code")["48"]}}</option> 	{{-- Polonia --}}
										</optgroup>
										<optgroup label="{{__("hotel.all_international_code")}}">
											@foreach(__("hotel.international_code") as $k => $code)
												<option value="{{$k}}">+{{$code}}</option>
											@endforeach
										</optgroup>
									</select>
								</div>
							</div>

						
							<div class="col-xs-8 tooltip-small tooltip-small-red" >
								<div id="tooltip-small">
								<div class="col-form">
									<label class="cyan-fe">{!! trans('hotel.tel') !!} <i class="fa fa-info"></i></label>
									<i class="icon-whatsapp" style="font-size:18px; position: absolute; top:27px; right:10px; z-index: 10;"></i>
									@php 
									$phone = (array_key_exists('phone', $prefill)) ? $prefill['phone'] : "";
									@endphp
									<input id="phone_input" placeholder="Telefono" name="telefono" type="phone" value="{{$phone}}">
									{{-- {!! Form::text('telefono',,['id'=>'phone_input',"placeholder"=>trans('hotel.tel_phone')]) !!} --}}
								</div>
								</div>
							</div>

							<div class="clearfix"></div>

						
					@endif

					

					<div class="col-xs-12 to-2-col">
						<div class="col-form">
							<label class="cyan-fe">{{trans('hotel.nome')}} ({{trans("labels.obbligatorio")}})</label>
							<i class="icon-address-book" style="font-size:18px; position: absolute; top:23px; right:10px; z-index: 10;"></i>
							{!! Form::text('nome', (array_key_exists('customer', $prefill)) ? $prefill['customer'] : null ,['id'=>'nome_input',"placeholder" => trans('hotel.nome')]) !!}
						</div>
					</div>

				</div>
				
				<div class="row">
					<div class="col-xs-12">
						<div class="col-form">
							{!! Form::textarea('richiesta',(array_key_exists('information', $prefill)) ? $prefill['information'] : null,["placeholder"=>strtoupper(trans('hotel.comm_text'))]) !!}	                
						</div>
					</div>
				</div>
		
				<div class="row">
					<div class="col-xs-12">
						<div class="col-button" style="height:auto;">
							
							<div class="privacy-checkbox">
								
								<div class="privacy_checkbox">
									<label class="white-fe ">
										<input class=" beautiful_checkbox privacy_accept"  id="accettazione" name="accettazione" type="checkbox" value="1" @if ($privacy) checked="checked" @endif>
										<span>{!! trans('labels.dati_pers') !!}</span>
									</label>
								</div>
								
								<label for="newsletter_check" class="white-fe">
									{!! Form::checkbox('newsletter', "1", (array_key_exists('newsletter', $prefill)) ? $prefill['newsletter'] : false,["class"=>"", "id" => "newsletter_check"]) !!} 
									<span>{{ trans('labels.newsletter') }}</span>
								</label>

								@include('widget.form.flexDate')
								
							</div><br />
							
							<input class="button green big invia_button" type="submit" value="{{strtoupper(trans('hotel.invia'))}}">
														
							<label class="white-fe">
								{{ trans('labels.campi_obb') }}
							</label>
						
							<div class="clear"></div><br /><br />
							
						</div>
					</div>
				</div>
		
				<div class="clear"></div>

				@include('esca_snippet')
				
				{!! Form::hidden('locale',$locale) !!}
				{!! Form::hidden('ids_send_mail', $ids_send_mail)!!}
				
				@if (isset($offerta))
					{!! Form::hidden('offerta', $offerta)!!}
				@endif
				
				@if (isset($cms_pagina_id))
					{!! Form::hidden('cms_pagina_id', $cms_pagina_id)!!}
				@endif

				{!! Form::hidden('IP',isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')  !!}
				{!! Form::hidden('codice_cookie',(array_key_exists('codice_cookie', $prefill)) ? $prefill['codice_cookie'] : "") !!}
				<input name="referer" value="{{$referer}}" type="hidden">

				{!!Form::close()!!}
			</div>
		</div>

			
	</article>
	
	@include('composer.footer')
	@include("widget.loading")
	
	{{-- Fine pagina --}}
	
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app-mail-mobile.min.css') }}" />
	
	<link rel="stylesheet" href="{{ Utility::asset('/vendor/datepicker/datepicker.min.css') }}" />
	<link rel="stylesheet" href="{{ Utility::asset('/vendor/tipped/tipped.min.css') }}" />
	
	<script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
	<script src="{{ Utility::asset('/vendor/jquery/jquery-ui.min.js') }}"></script>
	<script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script>
	<script src="{{ Utility::asset('/mobile/js/script.min.js') }}"></script>
	  <script src="{{ Utility::asset('/vendor/tipped/tipped.min.js') }}"></script>
	
	<script type="text/javascript">
	
		var $csrf_token = '<?php echo csrf_token(); ?>';
		var firstScroll = 0;
		@if (isset($cms_pagina_id))
			var $cms_id = '<?php echo $cms_pagina->id; ?>';
		@endif
		
		var console = console?console:{log:function(){}};
		
		// Tools
		@include("lang.cl")
		
		/* setTimeout(function () {
			Tipped.create("#tooltip-small", "{!!trans("hotel.whatsapp_contact")!!}", {close: true, showOn:false, hideOn: false, title:'<i style="font-size:16px; color:#fff;" class="icon-whatsapp"></i> WhatsApp&reg;', position: "topright", maxWidth: 250, skin: "purple"}).show();	
		}, 100); */

		$(window).click(function() {
			if (firstScroll == 0) {
				Tipped.hideAll();
				firstScroll = 1;
			}
		});


		$(function() {
	
			var $datepickerdfree = false;
			var $eta 			= '<?php echo trans('listing.eta') ?>';
			var $eta_singolo 	= '<?php echo trans('listing.eta_singolo') ?>'; 
			var $data_default	= '<?php echo trans('labels.data_default') ?>'; 
			var $alertCampi		= '<?php echo trans('labels.campi_compilati') ?>';
			var night			= '<?php echo trans('labels.notte') ?>';
			var nights			= '<?php echo trans('labels.notti') ?>';
			var name_day		= ['<?php echo implode("','" , trans('labels.nome_giorni')) ?>'];
			var locale			= '<?php echo $locale; ?>';
			
			@include("mobile.js.form")
	
		});
	
	
	</script>    
	
	<script src="{{Utility::asset('/vendor/datepicker/moment.min.js')}}"></script>
	<script src="{{ Utility::asset('/vendor/datepicker/bootstrap-datepicker.min.js') }}" type="text/javascript" /></script>
	<script src="{{ Utility::asset('/vendor/datepicker/locales/bootstrap-datepicker.'.$locale.'.min.js') }}" type="text/javascript" /></script>
	<script src="{{ Utility::asset('/mobile/js/menu.min.js') }}"></script>
	
	@include('footer')


</body>
</html>


	
	@php

		if ($numero_camere == 0)
			$numero_camere=1;
		
		if (!isset($wishlist))
			$wishlist = 0;
			
		if (!isset($mail_multipla))
			$mail_multipla = 0;
		
		if ($wishlist == 0 && $mail_multipla == 0) {
		
			$preventivo = "scheda";
			$list_meal_plan = $cliente->getListingTrattamento(); 
			$action = Utility::getUrlWithLang($locale,"/richiesta-mail-scheda");
			
		} elseif ($wishlist == 1) {
		
			$preventivo = "wishlist";
			$list_meal_plan = Utility::Trattamenti();
			$action = Utility::getUrlWithLang($locale,"/richiesta-wishlist");
			
		} else {
		
			$preventivo = "mail-multipla";
			$list_meal_plan = Utility::Trattamenti();
			$action = Utility::getUrlWithLang($locale,"/richiesta-mail-multipla");
			
		}

		$scheda_hotel = 0;
		if ($preventivo == "scheda")
			$scheda_hotel = 1;
		
	@endphp
	
	<a name="contact"></a>
	
	@if ($scheda_hotel != 1)
		<div id="sidebar-preventivo" >
	@else
		<div id="sidebar-preventivo" class="sticker-sidebar col-md-4 col-sm-12" style="position:static">
	@endif
	
    <aside id="sidebar">
	    
	    <div class="sidebar sidebar-arancio @if ($preventivo == "scheda") margin-left-3 @endif">
		   
			<header>
				<hgroup>
				    <h2>{{ trans('hotel.richiedi_preventivo_1') }}</h2>
					<h3>{{ trans('hotel.richiedi_preventivo_2') }}</h3>
					
					@if (isset($cliente) && $cliente->chiuso_temp)
						<div class="chiusoTempForm">
							{{ __('labels.politiche_canc') }}
						</div>
					@endif
					
				    @if (isset($recente) && $recente)
						<div class="note margin-top-4">
							{{trans("labels.hai_gia_scritto")}}
						</div>
					@endif
					
				</hgroup>

				<div id="errors" @if ($errors->any() && Session::has('validazione') && (Session::get('validazione') == 'preventivo')) style="display:none" @endif>
			    	@include('errors')
			    	@include('flash')
		    	</div>

			</header>
			
	    	{!!Form::open(['url' => $action, 'id' => 'form_preventivo'])!!}
		    							
				{!!Form::hidden('tag', "ED")!!}
				{!!Form::hidden('numero_camere', $numero_camere, ["id" => "numero_camere"])!!}
				{!!Form::hidden('type', "desktop")!!}
				
				@if ($preventivo == "mail-multipla")
					
					<h4>{{ trans('listing.localita') }} / {{ trans('listing.categoria') }}</h4>
					
					@include('composer.mailMultiplaSelectLocalita')
				
					@if (isset($stelle))

						<div class="col-sm-4 col-right">
							{!! Form::select('categoria',['1'=>$stelle[1],'2'=>$stelle[2],'3'=>$stelle[3],'6'=>$stelle[6],'4'=>$stelle[4],'5'=>$stelle[5]],(array_key_exists('categoria', $prefill)) ? $prefill['categoria'] : null,["class"=>"form-control"]) !!}
						</div>

						<div class="clearfix"></div>
					@endif
				
					<br />
					
				@endif
								
				<div class="row-colums padding-bottom-6">
					
					<div id="rooms-list">	
				 		@for ($i = 0; $i < $numero_camere; $i++)
				 		
					 		<div id="room_{{$i}}" class="room">
			
								<label class="camera_label" @if ($numero_camere==1) style="display:none;" @endif>
									{{ trans('labels.room') }} <b>{{ $i+1 }}</b>
								</label>
								
								@php
																		
									$room = 		$prefill["rooms"][$i];
									$today = 		Utility::ePrimaDiOggi($prefill["rooms"][$i]["checkin"]);
									$tomorrow = 	Utility::ePrimaDiOggi($prefill["rooms"][$i]["checkout"],1);
									$flex_date = 	$prefill["rooms"][$i]["flex_date"];
									
									$meal_plan = 	$prefill["rooms"][$i]["meal_plan"];
									$adults = 		$prefill["rooms"][$i]["adult"];
									$children = 	$prefill["rooms"][$i]["children"];
									$age_children = $prefill["rooms"][$i]["age_children"];
																					
									if (strpos($age_children, ","))
										$age_children = explode("," , $age_children);

									else 
										if ($age_children >= 0)
											$age_children = array($age_children);
										else
											$age_children = array();
																
								@endphp
								
								@include('composer.formDatePicker', 			array('order_number' => $i, 'scheda_hotel' => $scheda_hotel, 'required' => 1, 'tomorrow' => $tomorrow, 'today' => $today, 'flex_date' => $flex_date ))								
								@include('composer.formSelectAdultiBambini',	array('order_number' => $i, 'scheda_hotel' => $scheda_hotel, 'adults' => $adults, 'children' => $children, 'age_children' => $age_children))
								@include('composer.formTrattamento',			array('order_number' => $i, 'scheda_hotel' => $scheda_hotel, 'list_meal_plan' => $list_meal_plan, 'meal_plan' => $meal_plan))
								@if ($scheda_hotel == 1)
								@include('composer.flexDate',					array('order_number' => $i, 'flex_date' => $flex_date))
								@endif
								
								<div class="clearfix"></div>
								
							</div>	
							
							<div class="clearfix"></div>
					
						@endfor 
					</div>
					
					<div class="clearfix"></div>

					<div class="rooms">	

						<div class="col-sm-6">
							<a href="#" id="addCamera" class="btn btn-small btn-verde"><i class="icon-plus-circled-1"></i> {{ trans('labels.add_rooms') }}</a>
						</div>

						<div class="col-sm-6 pull-right" style="text-align:right;">
							<a href="#" id="delCamera" class="btn btn-small btn-rosso" style=" @if($numero_camere<2) display: none; @endif"><i class="icon-cancel-circled-1"></i> {{ trans('labels.del_rooms') }}</a>
						</div>

						<div class="clearfix"></div>

					</div>
					
				</div>
				
				@if ($scheda_hotel == 1)
					<h4>{{trans("hotel.dati_personali_whatsapp")}}</h4>
				@else
					<h4>{{trans("hotel.dati_personali")}}</h4>
				@endif
				
				@include("widget.preventivo-dati-personali", ['scheda_hotel' => 1 ])
				
				<div class="clearfix"></div>

				<div class="col-form">
					{!! Form::textarea('richiesta',(array_key_exists('information', $prefill)) ? $prefill['information'] : null,["class"=>"form-control", "placeholder" => trans("hotel.comm_text") ]) !!}	                
				</div>
			
				<div class="privacy_checkbox">
					<label class="label_checkbox ">
						<input class=" beautiful_checkbox privacy_accept"  id="accettazione" name="accettazione" type="checkbox" value="1" @if ($privacy) checked="checked" @endif>
						{!! trans('labels.dati_pers') !!} 
					</label>
				</div>
				
				<label class="label_checkbox">
					{!! Form::checkbox('newsletter', "1", (array_key_exists('newsletter', $prefill)) ? $prefill['newsletter'] : false,["class"=>" beautiful_checkbox", "id" => "newsletter_check"]) !!}
					{{ trans('labels.newsletter') }}
				</label><div class="clearfix"></div>
				
				<div class="col-form padding-top-2">
					<button id="submit_button" class="btn btn-big btn-verde pull-center" type="submit" data-label="{{trans('hotel.invia')}}" data-action="{{trans('labels.sending')}}"><i class="icon-mail-alt"></i> {{trans('hotel.invia')}}</button><br />
				</div>
					
				@include('esca_snippet')
				
				<input type="hidden" name="ids_send_mail" value="{{$ids_send_mail}}" />

				{!! Form::hidden('locale',$locale) !!}
				{!! Form::hidden('IP',isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')  !!}
				{!! Form::hidden('referer', $referer) !!}
				{!! Form::hidden('actual_link', $actual_link) !!}
				{!! Form::hidden('scheda_hotel', $scheda_hotel) !!}
				{!! Form::hidden('codice_cookie',(array_key_exists('codice_cookie', $prefill)) ? $prefill['codice_cookie'] : "") !!}
		     	
		    {!!Form::close()!!}
		    
		    <div class="loading-ajax">
				<span>
					<i class="animate-spin icon-spin2"></i> 
				</span>
				&nbsp; {{trans("labels.attendi")}}
			</div>
		    
		</div>
		
    </aside>

</div>



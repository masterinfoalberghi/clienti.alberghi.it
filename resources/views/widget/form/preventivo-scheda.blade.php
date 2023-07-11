
	
	@php

		if ($numero_camere == 0)		$numero_camere=1;
		if (!isset($wishlist))			$wishlist = 0;
		if (!isset($mail_multipla))		$mail_multipla = 0;
		
		$list_meal_plan = $cliente->getListingTrattamento(); 
		$action = Utility::getUrlWithLang($locale,"/richiesta-mail-scheda");
		
	@endphp
	
	<a name="contact"></a>

	<div id="sidebar-preventivo" class="sticker-sidebar col-md-4 col-sm-12 @if($cliente->chiuso_temp) closed @endif" style="position:static">
	
    <aside id="sidebar">
	    
	    <div class="sidebar sidebar-arancio margin-left-3">
		   
			@include('widget.form.formHeader', array('recente' => $recente))
			
	    	{!!Form::open(['url' => $action, 'id' => 'form_preventivo'])!!}
		    							
				{!!Form::hidden('tag', "ED")!!}
				{!!Form::hidden('numero_camere', $numero_camere, ["id" => "numero_camere"])!!}
				{!!Form::hidden('type', "desktop")!!}
								
				<div class="row-colums padding-bottom-6">
					
					<div id="rooms-list">	
				 		
				 		@for ($i = 0; $i < $numero_camere; $i++)
				 		
					 		<div id="room_{{$i}}" class="room">
			
								<h4 class="camera_label">
									<b>{{ trans('labels.room') }} {{ $i+1 }}</b> - <span id="dateinfo_{{$i}}" class="dateinfo">...</span>
								</h4>
								
								<div class="clearfix"></div>

								@php
															
									$room = $prefill["rooms"][$i];

									if ($cliente->chiuso_temp) {

										$lowlimit = 	Carbon\Carbon::createFromFormat('d/m/Y', "01/10/2020")->format("d/m/Y");
										$today 	   = 	Utility::ePrimaDiOttobre($prefill["rooms"][$i]["checkin"]);
										$tomorrow  = 	Utility::ePrimaDiOttobre($prefill["rooms"][$i]["checkout"],1);
										
									} else {

										$lowlimit = 	Carbon\Carbon::now()->format("d/m/Y");
										$today 	   = 	Utility::ePrimaDiOggi($prefill["rooms"][$i]["checkin"]);
										$tomorrow  = 	Utility::ePrimaDiOggi($prefill["rooms"][$i]["checkout"],1);

									}

									$flex_date = 		$prefill["rooms"][$i]["flex_date"];

									$meal_plan = 		Utility::getMultiMealPlan($prefill["rooms"][$i]["meal_plan"], $list_meal_plan);
									$meal_plan_code = 	Utility::getMultiMealPlanCode($prefill["rooms"][$i]["meal_plan"], $list_meal_plan);

									$adults = 			$prefill["rooms"][$i]["adult"];
									$children = 		$prefill["rooms"][$i]["children"];
									$age_children = 	$prefill["rooms"][$i]["age_children"];

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
																
								@endphp
								
								@include('widget.form.formDatePicker-scheda', 			array('order_number' => $i, 'scheda_hotel' => 1, 'required' => 1, 'tomorrow' => $tomorrow, 'today' => $today, "lowlimit" => $lowlimit))								
								@include('widget.form.formSelectAdultiBambini-scheda',	array('order_number' => $i, 'scheda_hotel' => 1, 'adults' => $adults, 'children' => $children, 'age_children' => $age_children))
								@include('widget.form.formTrattamento-scheda',			array('order_number' => $i, 'scheda_hotel' => 1, 'list_meal_plan' => $list_meal_plan, 'meal_plan' => $meal_plan, 'meal_plan_code' => $meal_plan_code))

								@if ($i == 0)
									@include('widget.form.formEmail')
								@endif
																
								<div class="clearfix"></div>
								
							</div>	
							
							<div class="clearfix"></div>
					
						@endfor 

					</div><div class="clearfix"></div>

					@include('widget.form.formButton', array('numero_camere' => $numero_camere))

				</div><div class="clearfix"></div>
				

				@include("widget.form.preventivo-dati-personali-scheda")
				

				<div class="col-form padding-top-2">
					<button id="submit_button" class="btn btn-big btn-verde pull-center" type="submit" data-label="{{trans('hotel.invia')}}" data-action="{{trans('labels.sending')}}"><i class="icon-mail-alt"></i> {{trans('hotel.invia')}}</button><br />
				</div>
					
				@include('esca_snippet')
				
				<input type="hidden" name="ids_send_mail" value="{{$ids_send_mail}}" />

				{!! Form::hidden('locale',$locale) !!}
				{!! Form::hidden('IP',isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')  !!}
				{!! Form::hidden('referer', $referer) !!}
				{!! Form::hidden('actual_link', $actual_link) !!}
				{!! Form::hidden('scheda_hotel', 1) !!}
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



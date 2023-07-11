<div class="row">
	<div class="col-xs-6">
		<div class="col-form arrow_tooltip">
			<label class="cyan-fe">{{trans('listing.adulti')}} </label>
			{!! Form::select('adulti[]', $adulti_select, $adults, ["class"=>"numP num_adulti", "id" => "num_adulti_$order_number"]) !!}
		</div>
	</div>
	<div class="col-xs-6">
		<div class="col-form arrow_tooltip">
			<label class="cyan-fe">{{trans('listing.bambini')}}</label>
			{!! Form::select('bambini[]',$bambini_select, $children , ["class"=>"numP num_bambini", "id" => "num_bambini_$order_number"]) !!}
		</div>
	</div>
</div>


<div id="pannello_bambini_{{$order_number}}" class="row width_pannello_bambini"  style="display:none;">
	
	<div class="etabambini">
		
		<div class="col-xs-12">
			<label class="n_eta_bambini cyan-fe">{{trans('listing.eta')}} *</label>
		</div>
	
		<div class="col-xs-12 chiusura-bimbi">
			@for ($ii = 0; $ii < 6; $ii++)    
				<div class="width_bambini_eta_form  bambini_eta_form_{{$ii}}">
					
					<?php 
						
						if (!isset($age_children[$ii])) {
							$array = array("class" => " eta_select", "id" => "eta_".$order_number."_".$ii, "disabled" => "disabled");
							$age_children[$ii] = -1;
						} else 
							$array = array("class" => " eta_select", "id" => "eta_".$order_number."_".$ii);
						?>
				
					{!! Form::select('eta_'.$ii.'[]',[
					'-1'=>'--',
					'0'=>'< 1 ' . __("labels.anno"),
					'1'=>'1 ' . __("labels.anno"),
					'2'=>'2 ' . __("labels.anni"),
					'3'=>'3 ' . __("labels.anni"),
					'4'=>'4 ' . __("labels.anni"),
					'5'=>'5 ' . __("labels.anni"),
					'6'=>'6 ' . __("labels.anni"),
					'7'=>'7 ' . __("labels.anni"),
					'8'=>'8 ' . __("labels.anni"),
					'9'=>'9 ' . __("labels.anni"),
					'10'=>'10 ' . __("labels.anni"),
					'11'=>'11 ' . __("labels.anni"),
					'12'=>'12 ' . __("labels.anni"),
					'13'=>'13 ' . __("labels.anni"),
					'14'=>'14 ' . __("labels.anni"),
					'15'=>'15 ' . __("labels.anni"),
					'16'=>'16 ' . __("labels.anni"),
					'17'=>'17 ' . __("labels.anni")
					], $age_children[$ii], $array) !!}
					
				</div>
				@php 
					if ($ii == 3) echo '<div class="clear"></div>'; 
				@endphp
			@endfor
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	
</div>
<div class="clear"></div>

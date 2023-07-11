<div style="margin-bottom: 5px;">

	<div class="col-form col-sm-6 col-left">
		<span class="select_icon">
			<i class="icon-adult"></i>
			{!! Form::select('adulti[]', $adulti_select, $adults, ["class"=>"numP num_adulti"]) !!}
			<i class="icon-down-open"></i>
		</span>
	</div>
	
	<div class="col-form col-sm-6 col-right">
		<span class="select_icon">
			<i class="icon-child"></i>
			{!! Form::select('bambini[]',$bambini_select, $children , ["class"=>"numP num_bambini", "data-id" => "$order_number"]) !!}
			<i class="icon-down-open"></i>
		</span>
	</div>
	
	<div class="clearfix"></div>

	<div id="pannello_bambini_{{$order_number}}" class="pannello_bambini" @if ($children == 0) style="display:none;" @endif>
	
		<label>{!! trans('listing.eta') !!}</label>
		
		@for ($ii = 0; $ii < 6; $ii++)    
			
			@php

				if ($ii == 1 || $ii == 4): $style="padding: 0 1.5px;"; endif;
				if ($ii == 0 || $ii == 3): $style="padding: 0 1.5px 0 0;"; endif;
				if ($ii == 5 || $ii == 2): $style="padding: 0 0 0 1.5px;"; endif;

			@endphp
		
			<div class="col-sm-4 col-form {{$style}} @if( !isset($age_children[$ii] )) error-form @else success-form @endif bambini_eta bambini_eta_{{$ii}}" style="{{$style}} @if( !isset($age_children[$ii]) || $age_children[$ii]=="-1" ) display:none @endif" >
	
				@php $array = array("class" => " eta_select" ); @endphp
				
				@if( !isset( $age_children[$ii]) )
					@php 
						$age_children[$ii] = -1;
					@endphp
				@endif
				
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
			
		@endfor
		
		<div class="clearfix"></div>
	</div>
	
	<div class="clearfix"></div>

</div>


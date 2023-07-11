<div style="margin-bottom: 5px;">
	<div class="col-sm-12">
		<div class="col-form select_multiline">
			<span class="select_icon">
				<i class="icon-food"></i>
				
				@php
					//dd($meal_plan_code, $meal_plan, $list_meal_plan);
					$meal_plan_item = explode(",",$meal_plan_code);
					$t = 0;
				@endphp

				{!! Form::hidden('trattamento[]', $meal_plan_code, ["class"=>"tipologiaAlloggio"]) !!}

				<div class="meal_plan_input">
					{!!$meal_plan!!}
				</div>
				
				<div class="options_multiline">
					
					@foreach($list_meal_plan as $key => $lmp)
					
						@php
							$t++;
						@endphp

						<label @if (count($meal_plan_item) == 1 && $t == 1) class="disabled" @endif>
							@php
								if (in_array($key, explode("," , $meal_plan_code )))	
									$checked = "checked";
								else
									$checked = "";
							@endphp
							<input style="border:1px solid #ddd;" name="trattamento_option" type="checkbox" value="{{$key}}" @if(count($meal_plan_item) == 1 && $t == 1) class="disabled" disabled="disabled"  @endif {{$checked}}> <span>{{$lmp}}</span>
						</label>

					@endforeach
				</div>

				<i class="icon-down-open"></i>

			</span>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>

		
	
<div class="row">
	<div class="col-xs-12">
		<div class="col-form select_multiline">
			<label class="cyan-fe">{{ trans('listing.trattamento') }}*</label>
			
			{!! Form::hidden('trattamento[]', $meal_plan_code, ["class"=>"tipologiaAlloggio"]) !!}
			
			<div class="meal_plan_input arrow_tooltip">
				{!!$meal_plan!!}
			</div>

			@php
				$meal_plan_item = explode(",",$meal_plan_code);
				$t = 0;
			@endphp

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
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
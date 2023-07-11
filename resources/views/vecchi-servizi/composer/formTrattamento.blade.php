
	@if ($scheda_hotel == 1)

		<div class="col-sm-4 col-md-12">
			<div class="col-form select_singleline ">
				<span class="select_icon">
					<i class="icon-food"></i>
					{!! Form::select('trattamento[]', $list_meal_plan, $meal_plan,["class"=>"tipologiaAlloggio",'id'=>"trattamento"]) !!}
				</span>
			</div>
		</div>
		<div class="clearfix"></div>
		
	@else 

		<div class="col-sm-4 col-left">
			<div class="col-form select_singleline ">
				<span class="select_icon">
					<i class="icon-food"></i>
					{!! Form::select('trattamento[]', $list_meal_plan, $meal_plan,["class"=>"tipologiaAlloggio",'id'=>"trattamento"]) !!}
				</span>
			</div>
		</div>

	@endif

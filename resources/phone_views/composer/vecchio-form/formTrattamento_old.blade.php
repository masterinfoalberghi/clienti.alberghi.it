<div class="row">
	<div class="col-xs-12">
		<div class="col-form">
			<label class="cyan-fe">{{ trans('listing.trattamento') }}*</label>
			
				{!! Form::select('trattamento[]',$list_meal_plan,$meal_plan,["class"=>"tipologiaAlloggio","id"=>"trattamento_" . $order_number]) !!}

		</div>
	</div>
</div>
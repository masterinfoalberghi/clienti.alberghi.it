<div class="row">
		  
	<div class="col-xs-6">

		<div class="col-form arrow_tooltip data_picker">
			<label class="cyan-fe">{{ trans('listing.arrivo') }}</label>
			<a href="#" id="arrivo_button_{{$order_number}}" class="arrivo_button">{{$today}}</a>
			<input class="data datepicker arrivo" id="arrivo_{{$order_number}}" placeholder="{{strtoupper(trans('listing.arrivo'))}}" required="required" name="arrivo[]" type="hidden" value="{{$today}}">

		</div>
	</div>
	
	<div class="col-xs-6">

		<div class="col-form arrow_tooltip data_picker">
			<label class="cyan-fe">{{ trans('listing.partenza') }}</label>
			<a href="#" id="partenza_button_{{$order_number}}" class="partenza_button">{{$tomorrow}}</a>
			<input class="data datepicker partenza" id="partenza_{{$order_number}}" placeholder="{{strtoupper(trans('listing.partenza'))}}" required="required" name="partenza[]" type="hidden" value="{{$tomorrow}}">
		</div>
		
	</div>
	
	<div class="clear"></div>
	
	<div id="daterange_{{$order_number}}" class="daterange"></div>
	
</div>
<div class="daterange_select" data-id="{{$order_number}}" data-flex="{{$flex_date}}">
	
	<input type="hidden" class="arrivo"   id="arrivo_{{$order_number}}"   name="arrivo[]" value="{{$today}}">
	<input type="hidden" class="partenza" id="partenza_{{$order_number}}" name="partenza[]" value="{{$tomorrow}}">

	@if ($scheda_hotel == 1)

		<div id="data_picker_{{$order_number}}" class="daterange" >

			<div class="col-sm-6 date_input col-left" style="margin-bottom: 5px;">
				<a id="arrivo_button_{{$order_number}}" href="#" class="select_icon arrivo_button">
					<i class="icon-calendar"></i>&nbsp;<i class="icon-down-open"></i>
				</a>
			</div>

			<div class="col-sm-6 date_input col-right" style="margin-bottom: 5px">
				<a id="partenza_button_{{$order_number}}" href="#" class="select_icon partenza_button">
					<i class="icon-calendar"></i>&nbsp;<i class="icon-down-open"></i>
				</a>
			</div>

			<div id="arrivo_datepicker_{{$order_number}}" class="datepickerinline arrivo_datepicker"></div>
			<div id="partenza_datepicker_{{$order_number}}" class="datepickerinline partenza_datepicker"></div>

		</div>

	@else

		<div id="data_picker_{{$order_number}}" class="daterange">

			<div class="col-sm-3 date_input col-left">
				<a id="arrivo_button_{{$order_number}}" href="#" class="select_icon arrivo_button"><i class="icon-calendar"></i>&nbsp;<i class="icon-down-open"></i></a>
			</div>

			<div class="col-sm-3 date_input">
				<a id="partenza_button_{{$order_number}}" href="#" class="select_icon partenza_button"><i class="icon-calendar"></i>&nbsp;<i class="icon-down-open"></i></a>
			</div>

		</div>

		<div class="col-sm-2" >

			<label class="label_checkbox tooltip-small tooltip-small-top" data-title="{{trans("hotel.date_flessibili")}}" for="flex_date_{{$order_number}}" style="padding:5px; ">
				<input type="checkbox" class="flex_date  beautiful_checkbox" id="flex_date_{{$order_number}}" name="date_flessibili[]" @if ($flex_date == 1) checked value="1" @else value="0" @endif > 
				<span>{{trans("labels.date_flessibili")}} </span>
			</label>

		</div>

		<div class="col-sm-4" style="padding:5px; ">
			<span id="dateinfo_{{$order_number}}" class="dateinfo"></span>
		</div>

		<div class="clearfix"></div>
		
		<div id="arrivo_datepicker_{{$order_number}}" class="datepickerinline arrivo_datepicker big"></div>
		<div id="partenza_datepicker_{{$order_number}}" class="datepickerinline partenza_datepicker big"></div>

	@endif
			
	<div class="clearfix"></div>
	
</div>







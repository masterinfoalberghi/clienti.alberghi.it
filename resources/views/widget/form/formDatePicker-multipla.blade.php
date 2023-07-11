<div class="daterange_select" data-id="{{$order_number}}" data-flex="{{$flex_date}}" style="margin-bottom: 5px;">
	
	<input type="hidden" class="arrivo"   id="arrivo_{{$order_number}}"   name="arrivo[]" value="{{$today}}">
	<input type="hidden" class="partenza" id="partenza_{{$order_number}}" name="partenza[]" value="{{$tomorrow}}">
	<input type="hidden" class="lowlimit" id="lowlimit_{{$order_number}}" name="lowlimit[]" value="{{$lowlimit}}">

	<div id="data_picker_{{$order_number}}" class="daterange">

		<div class="col-sm-6 date_input col-left" >
			<a id="arrivo_button_{{$order_number}}" href="#" class="select_icon arrivo_button"><i class="icon-calendar"></i>&nbsp;<i class="icon-down-open"></i></a>
		</div>

		<div class="col-sm-6 date_input col-right">
			<a id="partenza_button_{{$order_number}}" href="#" class="select_icon partenza_button"><i class="icon-calendar"></i>&nbsp;<i class="icon-down-open"></i></a>
		</div>
	
		<div id="arrivo_datepicker_{{$order_number}}" class="datepickerinline arrivo_datepicker big"></div>
		<div id="partenza_datepicker_{{$order_number}}" class="datepickerinline partenza_datepicker big"></div>
		
	</div>	
	<div class="clearfix"></div>
	
</div>







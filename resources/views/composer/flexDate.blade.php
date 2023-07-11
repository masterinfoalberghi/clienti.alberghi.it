<div class="col-sm-6">
<label class="label_checkbox tooltip-small tooltip-small-left" data-title="{{trans("hotel.date_flessibili")}}" for="flex_date_{{$order_number}}">
	<input type="checkbox" class="flex_date  beautiful_checkbox" id="flex_date_{{$order_number}}" name="date_flessibili[]" @if ($flex_date == 1) checked value="1" @else value="0" @endif > 
	<span>{{trans("labels.date_flessibili")}} </span>
</label>
</div>

<div class="col-sm-6" style="text-align:right;">
	<span id="dateinfo_{{$order_number}}" class="dateinfo"></span>
</div>

	<div class="col-sm-12">
		<label class="label_checkbox tooltip-small tooltip-small-left" data-title="{{trans("hotel.date_flessibili")}}" >
			<input type="checkbox" class="flex_date  beautiful_checkbox" name="flex_date" @if(isset($prefill["flex_date"]) && $prefill["flex_date"] == "1") checked="checked" @endif value="1"> 
			<b style="color:#fff !important">{{trans("labels.date_flessibili")}}</b>
		</label>
	</div>

	<div class="clearfix"></div>

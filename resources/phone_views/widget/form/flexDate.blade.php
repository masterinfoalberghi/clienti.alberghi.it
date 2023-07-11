
	<div class="col-sm-12">
		<label class="white-fe label_checkbox tooltip-small tooltip-small-left" >
			<input type="checkbox" class="flex_date  beautiful_checkbox" name="flex_date" @if(isset($prefill["flex_date"]) && $prefill["flex_date"] == "1") checked="checked" @endif value="1"> 
			<span>{{trans("labels.date_flessibili")}} *</span>
		</label>
	</div>

	<div class="clearfix"></div>

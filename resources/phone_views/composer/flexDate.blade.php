<div id="date_info_{{$order_number}}" class="date_info">
		<div class="col-xs-6">
			<div class="date-checkbox">
				<label class="white-fe label_checkbox date_flessibili" title="{{trans('hotel.date_flessibili')}}">
					<input class="beautiful_checkbox flex_date"  type="checkbox" @if ($flex_date) checked @endif>
					<input class="flex_date_input" value="{{$flex_date}}" name="date_flessibili[]" type="hidden" >
					<span>{{ trans('labels.date_flessibili') }} *</span>
				</label>
			</div>
		</div>
		
		<div class="col-xs-6">
			<div id="date_night_{{$order_number}}" class="date_night"></div>
		</div>
		</div>
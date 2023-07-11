	
	<h4>{{trans("hotel.dati_personali_whatsapp")}}</h4>

	<div style="margin-bottom: 5px;">
		<div class="col-form col-sm-12">
			<span class="select_icon">
				<i class="icon-address-book"></i>
				{!! Form::text('nome', (array_key_exists('customer', $prefill)) ? $prefill['customer'] : null ,["placeholder" => trans('hotel.nome')  . " (" . trans('labels.obbligatorio') .")","class"=>"form-control with_icon","id"=>"nome_input"]) !!}
			</span>
		</div>
		<div class="clearfix"></div>
	</div>
	
	<div class="tooltip-small tooltip-small-red" data-title="{{trans("hotel.whatsapp_contact")}}" style="margin-bottom: 5px;">
		<div class="col-sm-3 col-left">
			<span class="select_icon">
				<i class="icon-prefix"></i>
				<select id="prefix_input" name="prefix_input" >
					<optgroup label="{{__("hotel.fast_international_code")}}">
						<option value="39">+{{__("hotel.international_code")["39"]}}</option> 	{{-- italia --}}
						<option value="49">+{{__("hotel.international_code")["49"]}}</option>	{{-- Germania --}}
						<option value="41">+{{__("hotel.international_code")["41"]}}</option> 	{{--Svizzera --}}
						<option value="33">+{{__("hotel.international_code")["33"]}}</option> 	{{-- Francia --}}
						<option value="1">+{{__("hotel.international_code")["1"]}}</option> 	{{-- USA --}}
						<option value="32">+{{__("hotel.international_code")["32"]}}</option> 	{{-- Belgio --}}
						<option value="44">+{{__("hotel.international_code")["44"]}}</option> 	{{-- Regno unito --}}
						<option value="43">+{{__("hotel.international_code")["43"]}}</option> 	{{-- Austria --}}
						<option value="40">+{{__("hotel.international_code")["40"]}}</option> 	{{-- Romania --}}
						<option value="7">+{{__("hotel.international_code")["7"]}}</option> 	{{-- Russia --}}
						<option value="48">+{{__("hotel.international_code")["48"]}}</option> 	{{-- Polonia --}}
					</optgroup>
					<optgroup label="{{__("hotel.all_international_code")}}">
						@foreach(__("hotel.international_code") as $k => $code)
							<option value="{{$k}}">+{{$code}}</option>
						@endforeach
					</optgroup>
				</select>
				<i class="icon-down-open"></i>
			</span>
		</div>
		<div class="col-form col-sm-9 col-right" style="margin-bottom: 5px;">
			<span class="select_icon">
				<i class="icon-whatsapp"></i>
				{!! Form::text('telefono',(array_key_exists('phone', $prefill)) ? $prefill['phone'] : null,["id" => "phone_input", "placeholder"=>trans('hotel.tel'),"class"=>"form-control with_icon"]) !!}
			</span>
		</div>
	</div>

	<div class="clearfix"></div>

	<div class="col-form"  style="margin-bottom: 15px;">
		<span class="select_icon">
		{!! Form::textarea('richiesta',(array_key_exists('information', $prefill)) ? $prefill['information'] : null,["class"=>"form-control", "placeholder" => trans("hotel.comm_text") ]) !!}	                
		</span>
	</div>
	
	<div class="privacy_checkbox">
		<label class="label_checkbox ">
			<input class="privacy_accept"  id="accettazione" name="accettazione" type="checkbox" value="1" @if ($privacy) checked="checked" @endif>
			{!! trans('labels.dati_pers') !!} 
		</label>
	</div>
	
	<div class="clearfix"></div>
	
	<label class="label_checkbox">
		{!! Form::checkbox('newsletter', "1", (array_key_exists('newsletter', $prefill)) ? $prefill['newsletter'] : false,[ "id" => "newsletter_check"]) !!}
		{{ trans('labels.newsletter') }}
	</label>
	
	<div class="clearfix"></div>

	@include('widget.form.formFlexDate')
	

	



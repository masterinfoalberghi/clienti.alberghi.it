
	@if ($preventivo == "scheda")
		
		<div class="col-form col-sm-12 "> 
			<i class="icon-mail-alt" style="position: absolute; top:3px; right:5px; z-index: 10;"></i>
			{!! Form::text('email',(array_key_exists('email', $prefill)) ? $prefill['email'] : null,["placeholder"=> trans('hotel.email') . " (" . trans('labels.obbligatorio') .")","id"=>"email_input","class"=>"form-control"]) !!}
		</div>

		<div class="clearfix"></div>
		
		<div class="tooltip-small tooltip-small-red" data-title="{{trans("hotel.whatsapp_contact")}}" >
			<div class="col-sm-3 col-left">
			<select id="prefix_input" name="prefix_input">
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
			</div>
			<div class="col-form col-sm-9 col-right">
				<i class="icon-whatsapp" style="position: absolute; top:3px; right:6px; z-index: 10; font-size: 18px; color:#222;"></i>
				{!! Form::text('telefono',(array_key_exists('phone', $prefill)) ? $prefill['phone'] : null,["id" => "phone_input", "placeholder"=>trans('hotel.tel'),"class"=>"form-control"]) !!}
			</div>
		</div>

		<div class="col-form col-sm-12">
			<i class="icon-address-book" style="position: absolute; top:3px; right:5px; z-index: 10;"></i>
			{!! Form::text('nome', (array_key_exists('customer', $prefill)) ? $prefill['customer'] : null ,["placeholder" => trans('hotel.nome'). " (" . trans('labels.obbligatorio') .")","class"=>"form-control","id"=>"nome_input"]) !!}
		</div><div class="clearfix"></div>
		
	@else

		<div class="col-form  col-sm-4 ">
			<i class="icon-mail-alt" style="position: absolute; top:3px; right:5px; z-index: 10;"></i>
			{!! Form::text('email',(array_key_exists('email', $prefill)) ? $prefill['email'] : null,["placeholder"=>  trans('hotel.email') . " (" . trans('labels.obbligatorio') .")" ,"id"=>"email_input","class"=>"form-control"]) !!}
		</div>

		<div class="clearfix"></div>
		
		<div class="col-form  col-sm-4 ">
			<i class="icon-address-book" style="position: absolute; top:3px; right:5px; z-index: 10;"></i>
			{!! Form::text('nome', (array_key_exists('customer', $prefill)) ? $prefill['customer'] : null ,["placeholder" => trans('hotel.nome'). " (" . trans('labels.obbligatorio') .")","class"=>"form-control","id"=>"nome_input"]) !!}
		</div>
		
		<input type="hidden" name="telefono" value=""  />

	@endif


	



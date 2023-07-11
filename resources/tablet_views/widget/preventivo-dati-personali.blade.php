	
	<div class="col-form col-sm-12">
		{!! Form::text('email',(array_key_exists('email', $prefill)) ? $prefill['email'] : null,["placeholder"=>  trans('hotel.email') . " (" . trans('labels.obbligatorio') .")","id"=>"email_input","class"=>"form-control"]) !!}
	</div><div class="clearfix"></div>

	<div class="col-form col-sm-12">
		{!! Form::text('nome', (array_key_exists('customer', $prefill)) ? $prefill['customer'] : null ,["placeholder" => trans('hotel.nome') . " (" . trans('labels.obbligatorio') .")","class"=>"form-control","id"=>"nome_input"]) !!}
	</div><div class="clearfix"></div>
	
	<div class="col-form col-sm-12">
		{!! Form::text('telefono',(array_key_exists('phone', $prefill)) ? $prefill['phone'] : null,["id" => "phone_input", "placeholder"=>trans('hotel.tel'),"class"=>"form-control"]) !!}
	</div><div class="clearfix"></div>

	@if ($ids_send_mail == 470 || $ids_send_mail == 17)

		<label class="label_checkbox " @if(!array_key_exists('whatsapp', $prefill) || $prefill['whatsapp'] == 0) style="opacity: 0.5;" @endif>
			{!! Form::checkbox('whatsapp_check', "1", (array_key_exists('whatsapp', $prefill)) ? $prefill['whatsapp'] : 0 ,["class"=>" beautiful_checkbox", "id" => "whatsapp_check", "disabled"=> "disabled"]) !!}
			<span>{{ trans('labels.answer_whatsaspp') }}</span>
		</label><div class="clearfix"></div><br />

	@else

		<input type="hidden" name="whatsapp_check" value="0" />
		
	@endif
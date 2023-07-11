
	<h4>{{trans("hotel.dati_personali_whatsapp")}}</h4>

	<div style="margin-bottom: 5px;">

		<div class="col-form co-left col-sm-6" style="padding-right:2px;">
			<span class="select_icon">
				<i class="icon-mail-alt" style="position: absolute; top:3px; left:5px; z-index: 10;"></i>
				{!! Form::text('email',(array_key_exists('email', $prefill)) ? $prefill['email'] : null,["placeholder"=>  trans('hotel.email') . " (" . trans('labels.obbligatorio') .")" ,"id"=>"email_input","class"=>"form-control with_icon"]) !!}
			</span>
		</div>

		<div class="col-form col-right col-sm-6" style="padding-left:2px;">
			<span class="select_icon">
				<i class="icon-address-book" style="position: absolute; top:3px; left:5px; z-index: 10;"></i>
				{!! Form::text('nome', (array_key_exists('customer', $prefill)) ? $prefill['customer'] : null ,["placeholder" => trans('hotel.nome')  . " (" . trans('labels.obbligatorio') .")" ,"class"=>"form-control with_icon","id"=>"nome_input"]) !!}
			</span>
		</div>
		
		<input type="hidden" name="telefono" value="" id="phone_input" />

		<div class="clearfix"></div>

	</div><div class="clearfix"></div>

	<div class="col-form"  style="margin-bottom: 15px;">
		<span class="select_icon">
			{!! Form::textarea('richiesta',(array_key_exists('information', $prefill)) ? $prefill['information'] : null,["class"=>"form-control", "placeholder" => trans("hotel.comm_text") ]) !!}	                
		</span>
	</div>
	
	<div class="privacy_checkbox">
		<label class="label_checkbox ">
			
			<input class=" beautiful_checkbox privacy_accept"  id="accettazione" name="accettazione" type="checkbox" value="1" @if ($privacy) checked="checked" @endif>
			<span>{!! trans('labels.dati_pers') !!} </span>
			<i style="display:none" class="icon-cancel-circled-1"></i>
			
		</label>
	</div>
	
	<label class="label_checkbox">
		{!! Form::checkbox('newsletter', "1", (array_key_exists('newsletter', $prefill)) ? $prefill['newsletter'] : false,["class"=>" beautiful_checkbox", "id" => "newsletter_check"]) !!}
		<span>{{ trans('labels.newsletter') }}</span>
	</label><div class="clearfix"></div>

	@include('widget.form.formFlexDate')

	



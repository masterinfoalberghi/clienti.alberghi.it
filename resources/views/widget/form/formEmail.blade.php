<div class="col-form col-sm-12 emailaddress select_icon"> 
	<i class="icon-mail-alt" ></i>
	{!! Form::text('email',(array_key_exists('email', $prefill)) ? $prefill['email'] : null,["placeholder"=> trans('hotel.email') . " (" . trans('labels.obbligatorio') .")","id"=>"email_input","class"=>"form-control with_icon"]) !!}
</div>

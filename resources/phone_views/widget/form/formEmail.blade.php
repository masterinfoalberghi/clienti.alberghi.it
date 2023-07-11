<div class="row email-mobile">
	<div class="col-xs-12 to-2-col">
		<div class="col-form">
			<label class="cyan-fe">Email ({{trans("labels.obbligatorio")}})</label>
			<i class="icon-mail-alt" style="font-size:18px; position: absolute; top:23px; right:10px; z-index: 10;"></i>
			@php $email = (array_key_exists('email', $prefill)) ? $prefill['email'] : ""; @endphp
			<input id="email" placeholder="EMAIL" name="email" type="email" value="{{$email}}">
		</div>
	</div>
</div>
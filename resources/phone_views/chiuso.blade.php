@if (isset($cliente) && $cliente->chiuso_temp)
	<div class="chiusoTemp" style="margin:5px;">
		{{__("labels.chiusura_temporanea")}}
	</div>
@endif
<header>
	<hgroup>
		<h2>{{ trans('hotel.richiedi_preventivo_1') }}</h2>
		
		@if (isset($cliente) && $cliente->bonus_vacanze_2020 == 1 && $locale =="it")
			<h3 style="margin-top:10px; line-height:1em;">{{ trans('hotel.richiedi_preventivo_2_bonus') }}</h3>
		@else
			<h3 style="margin-top:10px; line-height:1em;">{{ trans('hotel.richiedi_preventivo_2') }}</h3>
		@endif
		
		@if (isset($recente) && $recente)
			<div class="note margin-top-4">
				{{trans("labels.hai_gia_scritto")}}
			</div>
		@endif

		
	</hgroup>
	<div id="errors" @if ($errors->any() && Session::has('validazione') && (Session::get('validazione') == 'preventivo')) style="display:none" @endif>
		@include('errors')
		@include('flash')
	</div>
</header>
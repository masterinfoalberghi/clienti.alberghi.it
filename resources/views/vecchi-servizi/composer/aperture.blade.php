<?php 
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
?>

<div class="padding-bottom">
	<h3>{{$titolo}}</h3>
	<div>
		@if ($cliente->chiuso_temp)

		<div class="chiusoTemp" style="color:#EE4337;">
			{{__("labels.chiusura_temporanea")}}
		</div>

@else

			@php 
				$date = array();
				if ($cliente->annuale) {
					$date[] = trans('listing.annuale');
				} else {
					$cliente->aperto_dal->toDateString() !=  '-0001-11-30' ? $date[] = trans('labels.apertura') .': <strong>' . Utility::myFormatLocalized($cliente->aperto_dal, '%d %B', $locale) .'</strong>' : '';
					$cliente->aperto_al->toDateString() !=  '-0001-11-30' ?  $date[] = trans('labels.chiusura') .': <strong>' . Utility::myFormatLocalized($cliente->aperto_al, '%d %B', $locale)  .'</strong>' : '';
				}
			@endphp
			{!! implode("&nbsp;&nbsp;&nbsp;",$date) !!}<br />
			@php
				$msg = "";
				$disponibile = array();
				$cliente->aperto_eventi_e_fiere ? $disponibile[] = trans('hotel.aperto_eventi_e_fiere') : "";
				$cliente->aperto_pasqua ? $disponibile[] = trans('hotel.aperto_pasqua') : "";
				$cliente->aperto_capodanno ? $disponibile[] = trans('hotel.aperto_capodanno') : "";
				$cliente->aperto_25_aprile ? $disponibile[] = trans('hotel.aperto_25_aprile') : "";
				$cliente->aperto_1_maggio ? $disponibile[] = trans('hotel.aperto_1_maggio') : "";
				$field = 'aperto_altro_'.$locale; 
				$cliente->aperto_altro_check  ? $disponibile[] = $cliente->$field : "";
				$disponibile = implode(", " , $disponibile);
			@endphp
			@if ($disponibile != "")	
				{{trans('labels.dispo')}}: <strong>{!! $disponibile !!}</strong>
			@endif
		@endif
	</div>
</div>


<div @if(isset($alternate) && $alternate == true) id="covid_hotel" @else id="covid" @endif>
	<div id="alert-description">
		{{-- <i class="icon-info-circled-1" style="font-size: 20px; padding-right: 5px;"><span style="display:none;">Info</span></i>  --}}
		<span id="alert">COVID-19</span>
			@if (isset($testo_covid_banner) && isset($alternate))
				<a href="{{asset('note/covid-19')}}" target="_blank" class="alert-link">
					{!! $testo_covid_banner !!}	
				</a>
			@else 
				<a href="{{asset('note/covid-19')}}" target="_blank" class="alert-link">
					informazioni aggiornate sulla Riviera
				</a>
				&nbsp;&nbsp;&nbsp;
				<span id="bonus">BONUS VACANZE</span>
				<a href="{{asset('note/bonus-vacanze')}}" target="_blank" class="alert-link">
					Informazioni
				</a>
				e
				<a href="{{asset('bonus-vacanze-covid19/riviera-romagnola.php')}}" target="_blank" class="alert-link">
					gli hotel
				</a>
			@endif
	</div>
</div>



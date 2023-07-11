

@include('covid-banner')

@if (count($clienti) > 0)
	<div class="titolo-localita" > {{-- style="margin-top: 26px;" TOGLIERE quando si toglie covid-banner --}}

		{{$titolo}}
		
		@if (strpos(Utility::getCurrentUri(), "/hotel-green-booking.php") && $locale == "it")
			<p style="font-size: 16px; ">Vuoi saperne di più sul&nbsp;<strong>progetto Green Booking? <a href="http://www.info-alberghi.com/greenbooking" style="text-decoration: underline; color: #3F97EE; ">Scopri come puoi contribuire al verde in Riviera Romagnola</a>.</strong></p>
		@endif
		
		@include("share", ["text" => 'Guarda questa lista di hotel a *'. $localita_seo . '*'])
		@include("chiuso")

	</div>
@else
	<style>body { background:#fff; }</style>
@endif
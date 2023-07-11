@if(session("sf") == 1)
@include("composer.search_first_short", ["page" => "localita"])
@endif
@include('covid-banner')

@if ($clienti->count() > 0 || (!is_null($vetrina) && $vetrina->slots->count() > 0))
<div class="titolo-localita"> {{-- style="margin-top: 26px;" TOGLIERE quando si toglie covid-banner --}}

	{{$titolo}}
	@include("share", ["text" => 'Guarda questa lista di hotel a *'. $localita_seo . '*'])
	@include("chiuso")

</div>
@endif
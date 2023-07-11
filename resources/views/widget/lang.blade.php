@php $l = Utility::getLocaleByUrl(); @endphp

<a id="changeLanguageButton" class="up-link reverse lang" href="#">
	
	<img src="{{Utility::asset("vendor/flags/blank.gif")}}" class="flag flag-{{$l}}" />
	
	<i style="color:#666; margin-left:-5px; position: absolute; top: 0;" class="icon-down-open"></i>

</a>


<div id="changeLanguageLink" style="display:none">
	
	<ul>

		@if($l != "it")
			<li>
				@if (isset($language_uri["it"]))
					<a href="/{{$language_uri["it"]}}">
				@else
					<a href="/">
				@endif
					<img src="{{Utility::asset("vendor/flags/blank.gif")}}" class="flag flag-it" alt="Italiano" /> Italiano
				</a>
			</li>
		@endif

		@if($l != "en")
			<li>
				@if (isset($language_uri["en"]))
					<a href="/{{$language_uri["en"]}}">
				@else
					<a href="/ing">
				@endif
					<img src="{{Utility::asset("vendor/flags/blank.gif")}}" class="flag flag-en" alt="English" /> English
				</a>
			</li>
		@endif

		@if($l != "fr")
			<li>
				@if (isset($language_uri["fr"]))
					<a href="/{{$language_uri["fr"]}}">
				@else
					<a href="/fr">
				@endif					
					<img src="{{Utility::asset("vendor/flags/blank.gif")}}" class="flag flag-fr" alt="Français" /> Français
				</a>
			</li>
		@endif

		@if($l != "de")
			<li>
				@if (isset($language_uri["de"]))
					<a href="/{{$language_uri["de"]}}">
				@else
					<a href="/ted">
				@endif
					<img src="{{Utility::asset("vendor/flags/blank.gif")}}" class="flag flag-de" alt="Deutsch" /> Deutsch
				</a>
			</li>
		@endif
	</ul>
	
</div>
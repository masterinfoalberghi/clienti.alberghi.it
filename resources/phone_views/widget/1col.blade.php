<article>
	<header>
		<hgroup>
			@if (isset($h1))
				<h1>{{$h1}}</h1>
			@else
				<h2>{!! $h2 !!}</h2>
				@if ($h3)<h3>{!! $h3 !!}</h3>@endif
			@endif
		</hgroup>
	</header>
	@if (isset($immagine) && $immagine != "")
		<figure>
			<img class="main-content-image" src="{{Utility::asset('/images/pagine/300x200/' . $immagine)}}" title="{{$h2}}" >
		</figure>
	@endif
	<div class="main-content-text-1-col">
	    {!! $descrizione !!}
	</div>
</article>
				
			
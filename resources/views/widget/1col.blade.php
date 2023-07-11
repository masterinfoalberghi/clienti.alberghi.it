<div class="main-content-container normal">
    <div class="container">
        <div class="row">
            <article class="main-content">

                <div class="main-content-text @if(isset($h1)) top @endif">

                    <header @if(isset($h1_hide)) class="hidden" @endif>
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
                            <img class="main-content-image" src="{{Utility::asset('/images/pagine/1920x400/' . $immagine)}}" title="{{$h2}}" >
                        </figure>
                    @endif

                    @if (!empty($descrizione))
                        <div class="main-content-text-1-col">
                            {!! $descrizione !!}
                        </div>
                    @else 
                        
                    @endif

                </div>
            </article>
        </div>
    </div>
</div>

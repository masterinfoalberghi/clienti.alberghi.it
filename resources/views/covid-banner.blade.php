<div @if(isset($alternate)) id="covid_hotel" @else id="covid" @endif @if (isset($color)) style="background:{{$color}}; border-color: {{$color}}; @endif; box-shadow:0 1px 3px rgba(0,0,0,0.2);">
	<div id="alert-description">
			@if (isset($testo_covid_banner) && isset($alternate))
				<span id="alert" style="margin:0 0 15px -25px; border: 2px solid @if (isset($color)) {{$color}} @else #2A65C0 @endif; box-shadow:0 1px 3px rgba(0,0,0,0.2);">
					@if (isset($etichetta)) {{$etichetta}} @else COVID-19 @endif
				</span>
				<p style="line-height: 20px; margin-bottom:15px;  @if (isset($color)) color: rgba(0,0,0,0.8) @endif">{!! $testo_covid_banner !!}</p>
				@if (isset($button) && $button == true)
					@if (isset($link))
						<a class="btn btn-sm btn-primary" href="{{$link}}" target="_blank" class="alert-link">{{__("hotel.leggi_tutto")}}</a>
					@else 
						<a class="btn btn-sm btn-primary" href="{{asset('note/covid-19')}}" target="_blank" class="alert-link">{{__("hotel.leggi_tutto")}}</a>
					@endif
                @endif
                
               

			@else 
				<span id="alert">COVID-19</span>
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
        </a>
        <div class="clearfix"></div>
        
       

    </div>
    
   

</div>



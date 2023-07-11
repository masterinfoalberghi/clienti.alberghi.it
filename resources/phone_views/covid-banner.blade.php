<div @if(isset($alternate)) id="covid_hotel" @else id="covid" @endif @if (isset($color)) style="background:{{$color}}; border-color: {{$color}};"  @endif>

	<div id="alert-description">

			@if (isset($testo_covid_banner) && isset($alternate))

				<span id="alert" style="margin:0 0 15px -25px; border: 2px solid @if (isset($color)) {{$color}} @else #2A65C0; @endif">
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
            
                <div id="covid-table">
                    <i class=" icon-attention"></i> Assistenza <b>COVID-19</b> <a href="{{asset('note/covid-19')}}" target="_blank" class="text-right">leggi di più</a><br />
                    <i class="icon-gift"></i> Informazioni <b>Bonus Vacanza</b> <a href="{{asset('note/bonus-vacanze')}}" target="_blank" class="text-right" >leggi di più</a>
                </div>

			@endif
		</a>
    </div>
    
</div>






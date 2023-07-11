@if ($cliente->caparre_attive_count)
	
	@if (!isset($scheda_hotel))
        {{-- <div class="politiche_canc"></div> --}}
        <p class="politiche_cancellazione">
            <span class="tooltip list_periodi" title="Visualizza i periodi" data-id="{{$cliente->id}}">
                @if (!is_null($cliente->getLabelCaparra($locale)) && $cliente->getLabelCaparra($locale) != '')
                    {!!$cliente->getLabelCaparra($locale)!!}
                @else
                    {{ strtoupper( __("labels.politiche_cancellazione") ) }}
                @endif
            </span>
            <a class="tooltip list_periodi" title="{{ __('labels.visualizza_periodi') }}" data-id="{{$cliente->id}}"><i class="icon-info-circled"></i></a>
        </p>
        <span id="{{$cliente->id}}" style="display: none;">
            @include('widget.caparre_list_periodi')				
        </span>
	@else
		{{-- SCHEDA HOTEL  --}}
		@foreach ($cliente->caparreAttive as $caparra)

			<div class="caparre">
				<div class="caparra">
					@php
						
						$dal = Utility::myFormatLocalized($caparra->from, '%d %B', $locale);
						$al = Utility::myFormatLocalized($caparra->to, '%d %B', $locale);

						$option = $caparra->option;
						$option_txt = __("labels.option_" . $option);

						if ($caparra->option == "4")
							$option_txt = str_replace(["$1","$2"], [$caparra->day_before, $caparra->perc], $option_txt);

						else if ($caparra->option == "6")
							$option_txt = str_replace(["$1","$2"],[$caparra->day_before,  $caparra->month_after], $option_txt);

						else if ($caparra->option == "7")
							$option_txt = str_replace(["$1"],[$caparra->day_before], $option_txt);

						else if ($caparra->option == "8")
							$option_txt = str_replace(["$1","$2"],[$caparra->day_before, $caparra->perc], $option_txt);

						else if ($caparra->option == "9")
							$option_txt = str_replace(["$1","$2"],[$caparra->day_before, $caparra->month_after], $option_txt);
						
						else if ($caparra->option == "10")
								$option_txt = str_replace(["$1"],[$caparra->perc], $option_txt);

						else if ($caparra->option == "11")
								$option_txt = str_replace(["$1","$2", "$3"],[$caparra->day_before, $caparra->perc, $caparra->month_after], $option_txt);
						
						else if ($caparra->option == "12")
								$option_txt = str_replace(["$1","$2"],[$caparra->day_before,$caparra->perc], $option_txt);

						else if ($caparra->option == "13")
							$option_txt = str_replace(["$1","$2"],[$caparra->day_before, $caparra->perc], $option_txt);

						$intro = __("labels.options_nel_priodo_dal");

						$intro .= ' '.$dal;

						$intro .= ' '. __("labels.options_al");

						$intro .= ' '.$al;					

					@endphp

					<p class="option_{{$caparra->option}}"><span class="perido_caparra">{{$intro}}</span> - {{$option_txt}}</p>
					
				</div>
			</div>
		
		@endforeach	
	@endif
	
@endif
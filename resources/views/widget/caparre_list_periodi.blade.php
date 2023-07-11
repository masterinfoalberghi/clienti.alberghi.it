@foreach ($cliente->caparreAttive as $caparra)
	<div class="caparre">
			<div class="caparra">
				@php

					$dal = Utility::myFormatLocalized($caparra->from, '%d %B', $locale);
					$al = Utility::myFormatLocalized($caparra->to, '%d %B', $locale);
					
					$option = $caparra->option;
					$option_txt = __("labels.option_" . $option. '_short');

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
						$option_txt = str_replace(["$1","$3", "$2"],[$caparra->day_before, $caparra->perc, $caparra->month_after], $option_txt);

					else if ($caparra->option == "12")
						$option_txt = str_replace(["$1","$2"],[$caparra->day_before,$caparra->perc], $option_txt);

					else if ($caparra->option == "13")
						$option_txt = str_replace(["$1","$2"],[$caparra->day_before, $caparra->perc], $option_txt);

					$intro = __("labels.options_nel_priodo_dal");

					$intro .= ' '.$dal;

					$intro .= ' '. __("labels.options_al");

					$intro .= ' '.$al;					

				@endphp
				<p class="option_{{$caparra->option}}"><span class="perido_caparra">{{$option_txt}}</span> - {{$intro}}</p>
				
			</div>
	</div>
@endforeach

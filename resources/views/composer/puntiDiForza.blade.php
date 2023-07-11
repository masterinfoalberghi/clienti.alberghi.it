@if (!empty($array_punti_di_forza))
    
    @php $array_punti_di_forza_6 = array_slice($array_punti_di_forza, 0, 6) @endphp
	@if ($in_hotel)
		
		<div class="padding-bottom">
			
			<header>
				<h3>{{ trans('labels.9punti_forza') }}</h3>
			</header>
			
			<div class="item-listing-pdf">
				<span>{!! implode("<span></span></span>  <span>" , $array_punti_di_forza) !!}</span>
			</div>
			
		</div>
	
	@else
        
    <div style="column-count: 2">
        <i class='icon-ok'></i> {!! implode(" <br /><i class='icon-ok'></i> " ,$array_punti_di_forza_6) !!}<br />
        {{-- @if ( $cliente->bonus_vacanze_2020)
            <i class='icon-ok'></i> <b style="color:#f44336">Bonus vacanze accettato</b></span>
        @endif --}}
    </div>	
	@endif

@endif







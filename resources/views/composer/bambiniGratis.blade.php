<?php

// visualizzo le info sul periodo bambino gratis
// @parameters : $periodi (array indicizzato con i valori per creare lo snippet)
//               $titolo (header titolo oppure '')
//               
?>

@if (count($periodi))
		
		<header>
			<h2  class="content-section box-offers-title">{{$titolo}}</h2>
		</header>
			
	    @foreach ($periodi as $periodo)
	    	
	    	<article class="box-offers-article  margin-bottom">
	    	
				<?php 
				$fino_a_anni = $periodo['fino_a_anni'];
				$anni_compiuti = $periodo['anni_compiuti'];
				$dal = \App\Utility::myFormatLocalized($periodo['dal'], '%e %b %Y', $locale);
				$al =  \App\Utility::myFormatLocalized($periodo['al'], '%e %b %Y', $locale);
				$periodo['note'] == '' ? $note = '' : $note = $periodo['note'];
				
				$approvata = $periodo['approvata'];
				$data_approvazione = $periodo['data_approvazione'];
				
				?>
				<header>
					<h4 class="box-offers-article-title">{{trans('hotel.gratis_fino_a')}} {{$fino_a_anni}} {{$anni_compiuti}}</h4>
				</header>
				
				<div class="box-offers-article-content content-scheda">
					<div class="box-offers-article-content-up">
						<strong>{{$dal}}</strong> &rarr; <strong>{{$al}}</strong><br />
					</div>
					{!!$note!!}
				</div>
				
				@if ($approvata)
					<div class="box-offers-article-verifica tipped" title="<b>Verificata</b><br/>@if ( !is_null($data_approvazione) ){{ $data_approvazione }} @endif">
						<i class="check icon-ok"></i> <i style="margin-left:-15px;" class="check icon-ok"></i>
					</div>
				@else
					<div class="box-offers-article-verifica tipped" title="<b>In attesa di revisione</b>">
						<i class="check icon-ok"></i> <i style="margin-left:-15px;" class="no-check icon-ok"></i>
					</div>
				@endif
			
	    	</article>
			
	    @endforeach
	
	@if (count($servizi_finali))
	
	<div class="note content-scheda">
		<div>
		<h4>{{ trans('title.servizi_bambini') }}</h4>
		{!! implode(", ",$servizi_finali) !!}
		</div>
	</div>
	@endif
	
	<div class="padding-bottomx2"></div>
	 
@endif
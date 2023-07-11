	@if ($paragrafi)
		
		{{-- 
		@Luigi 040219 - ATTENZIONE adesso le traduzioni in lingua ci sono ed hanno la struttura JSON speculare a quella in italiano: non serve più avere 2 rendering differenti!!!
		 --}}
		@if ($locale == 'it' || true)

			@php $linea = true;  @endphp
			@foreach($paragrafi as $paragrafo)
			@if ($paragrafo->title || $paragrafo->subtitle || $paragrafo->testo)
			
				<div class="p">
					
					@if (isset($paragrafo->piscina) && $paragrafo->piscina && !is_null($cliente->infoPiscina) && $cliente->infoPiscina->sup > 0)

					

						@include('composer.infoPiscina', ["scheda"=>1, "titolo" => $paragrafo->title, "sottotitolo" => $paragrafo->subtitle, "testo" => $paragrafo->testo ])
						
					@elseif (isset($paragrafo->spa) && $paragrafo->spa && !is_null($cliente->infoBenessere) && $cliente->infoBenessere->sup > 0)

						@include('composer.infoBenessere', ["scheda"=>1, "titolo" => $paragrafo->title, "sottotitolo" => $paragrafo->subtitle, "testo" => $paragrafo->testo])

						
					@else
						
						@if (!$linea) <div class="spacerBlu"></div> @endif
						@if ($paragrafo->title) <h3>{!!$paragrafo->title!!}</h3> @endif
						@if ($paragrafo->subtitle) <h4>{!!$paragrafo->subtitle!!}</h4> @endif
						@if ($paragrafo->testo) <p>{!!$paragrafo->testo!!}</p> @endif
						@php $linea = true; @endphp
						
					@endif

				</div>

			@endif	
			@endforeach

		@else
			{{-- 
			SCHEDA IN LINGUA 
			I servizi piscina e benessee NON sono agganciati al paragrafo (che in lingua stanno scomparendo) ma mostrati tutti in fondo
				--}}
			@php $linea = true;  @endphp
			@foreach($paragrafi as $paragrafo)
			@if ($paragrafo->title || $paragrafo->subtitle || $paragrafo->testo)
			
				<div class="p">
						@if (!$linea) <div class="spacerBlu"></div> @endif
						@if ($paragrafo->title) <h3>{!!$paragrafo->title!!}</h3> @endif
						@if ($paragrafo->subtitle) <h4>{!!$paragrafo->subtitle!!}</h4> @endif
						@if ($paragrafo->testo) <p>{!!$paragrafo->testo!!}</p> @endif
						@php $linea = true; @endphp
				</div>

			@endif	
			@endforeach

			@if (!is_null($cliente->infoPiscina) && $cliente->infoPiscina->sup > 0)
				<div style="margin-bottom: 30px;">
					@include('composer.infoPiscina', ["scheda"=>1, "titolo" => "", "sottotitolo" => trans('hotel.servizi_piscina'), "testo" => "" ])
				</div>
			@endif
			
			@if (!is_null($cliente->infoBenessere) && $cliente->infoBenessere->sup > 0)
				<div style="margin-bottom: 30px;">
					@include('composer.infoBenessere', ["scheda"=>1, "titolo" => "", "sottotitolo" => trans('hotel.servizi_benessere'), "testo" => ""])
				</div>
			@endif
		
		@endif
	@endif

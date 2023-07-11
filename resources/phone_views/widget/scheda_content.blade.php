	
	@if ($paragrafi)
		{{-- 
		@Luigi 040219 - ATTENZIONE adesso le traduzioni in lingua ci sono ed hanno la struttura JSON speculare a quella in italiano: non serve più avere 2 rendering differenti!!!
		 --}}
		@if ($locale == 'it' || true)
			
			@foreach($paragrafi as $paragrafo)
				@if ($paragrafo->title || $paragrafo->subtitle || $paragrafo->testo)
					<div class="p">
						
						@if (isset($paragrafo->piscina) && $paragrafo->piscina && !is_null($cliente->infoPiscina) && $cliente->infoPiscina->sup > 0)
						
							@include('composer.infoPiscina', ["scheda"=>1, "titolo" => $paragrafo->title, "sottotitolo" => $paragrafo->subtitle, "testo" => $paragrafo->testo ])
							
						@elseif (isset($paragrafo->spa) && $paragrafo->spa && !is_null($cliente->infoBenessere) && $cliente->infoBenessere->sup > 0)
						
							@include('composer.infoBenessere', ["scheda"=>1, "titolo" => $paragrafo->title, "sottotitolo" => $paragrafo->subtitle, "testo" => $paragrafo->testo])
						
						@else
							
							@if ($paragrafo->title) <h4>{!!$paragrafo->title!!}</h4> @endif
							@if ($paragrafo->subtitle) <h5>{!!$paragrafo->subtitle!!}</h5> @endif
							@if ($paragrafo->testo) 
							
								@if ($paragrafo->mirror != "")
									<div>{!! $paragrafo->mirror !!}... <a href="#" class="readmorescheda">{{trans("hotel.leggi_tutto")}}</a><br /><br /></div>
								@else 
									<div>{!! Utility::getExcerpt($paragrafo->testo, $limit = 20, $strip = true) !!} <a href="#" class="readmorescheda">{{trans("hotel.leggi_tutto")}}</a><br /><br /></div>
								@endif
								
								<div style="display:none;">{!! $paragrafo->testo !!}<br /><br /></div>
								
							@endif
						
						@endif
						
					</div>
				@endif	
			@endforeach
		
		@else
			
			{{-- 
			SCHEDA IN LINGUA 
			I servizi piscina e benessee NON sono agganciati al paragrafo (che in lingua stanno scomparendo) ma mostrati tutti in fondo
				--}}
			@foreach($paragrafi as $paragrafo)
				@if ($paragrafo->title || $paragrafo->subtitle || $paragrafo->testo)
					<div class="p">
						
						@if (!is_null($cliente->infoPiscina) && $cliente->infoPiscina->sup > 0)
						
							@include('composer.infoPiscina', ["scheda"=>1, "titolo" => $paragrafo->title, "sottotitolo" => $paragrafo->subtitle, "testo" => $paragrafo->testo ])
							
						@elseif (!is_null($cliente->infoBenessere) && $cliente->infoBenessere->sup > 0)
						
							@include('composer.infoBenessere', ["scheda"=>1, "titolo" => $paragrafo->title, "sottotitolo" => $paragrafo->subtitle, "testo" => $paragrafo->testo])
						
						@else
							
							@if ($paragrafo->title) <h4>{!!$paragrafo->title!!}</h4> @endif
							@if ($paragrafo->subtitle) <h5>{!!$paragrafo->subtitle!!}</h5> @endif
							@if ($paragrafo->testo) 
							
								@if ($paragrafo->mirror != "")
									<div>{!! $paragrafo->mirror !!}... <a href="#" class="readmorescheda">{{trans("hotel.leggi_tutto")}}</a><br /><br /></div>
								@else 
									<div>{!! Utility::getExcerpt($paragrafo->testo, $limit = 20, $strip = true) !!} <a href="#" class="readmorescheda">{{trans("hotel.leggi_tutto")}}</a><br /><br /></div>
								@endif
								
								<div style="display:none;">{!! $paragrafo->testo !!}<br /><br /></div>
								
							@endif
						
						@endif
						
					</div>
				@endif	
			@endforeach

		@endif
	
	@endif
		

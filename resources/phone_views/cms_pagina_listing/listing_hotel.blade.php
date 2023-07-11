@extends('templates.cms_pagina_listing',['cms_pagina' => $cms_pagina])
@section('seo_title'){{ $seo_title }}@endsection
@section('seo_description'){{ $seo_description }}@endsection
@include('flash')
@section('content')
	
	@component('components.log_view',['log_view' => $log_view])
  	<span>{{$clienti->count()}}</span>
	@endcomponent
		
	@include('cms_pagina_listing.container_centrale')

	{{-- ATTENZIONE: potrebbe essere una collection di oggetti SlotVetrina e Hotel Mischiati  --}}
	


	<div class="wrapper_risultati_ricerca container">  

		<div class="risultati_ricerca row <?php if ($evidenza_vetrina): echo "evidenza_vetrina"; endif; ?>">

            @if (isset($pagination) && $pagination ) 
                @include("widget.pagination", ["clienti" => $clienti, "position" => 0])
		    @endif

			@if ($clienti->count())

				@if ($cms_pagina->listing_parolaChiave_id || !empty($cms_pagina->listing_offerta) )

					@include('draw_item_cliente.snippet_offerta_generica', array('clienti' => $clienti))

				@elseif (!empty($cms_pagina->listing_offerta_prenota_prima)) 

					@include('draw_item_cliente.snippet_offerta_prenota_prima', array('clienti' => $clienti, 'listing_offerta' => $cms_pagina->listing_offerta_prenota_prima))   

				@else
					
					@include('cms_pagina_listing.clienti', array('clienti' => $clienti, 'listing_bambini_gratis' => $cms_pagina->listing_bambini_gratis))  

				@endif

			@else

				@include('cms_pagina_listing.nessun_hotel')

			@endif

            @if (isset($pagination) && $pagination ) 
            @include("widget.pagination", ["clienti" => $clienti, "position" => 1])
		@endif

		</div>

	</div>

	@endsection

	@section('more')

	@if ($cms_pagina->indirizzo_stradario != "")
	<div class="testo">
	<h3>{!!$cms_pagina->h2!!}</h3>
	{!!$cms_pagina->descrizione_2!!}
	</div><!-- /barusso -->
	@endif

@endsection




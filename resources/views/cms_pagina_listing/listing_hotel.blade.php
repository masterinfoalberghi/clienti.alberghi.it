@extends('templates.cms_pagina_listing',['array_ids_vot' => $array_ids_vot])
@section('seo_title'){{ $seo_title }}@endsection
@section('seo_description'){{ $seo_description }}@endsection
@include('flash')
@section('content')  
	@component('components.log_view',['log_view' => $log_view])
  	<h4>{{$clienti->count()}}</h4>
	@endcomponent
	
	<div class="listing-ajax">
		
		@include("widget.filtri_label")
		
        @if (isset($pagination) && $pagination ) 
            @include("widget.pagination", ["clienti" => $clienti])
        @endif

		@if ($cms_pagina->listing_parolaChiave_id || !empty($cms_pagina->listing_offerta) || $cms_pagina->listing_coupon)
			
			@include('draw_item_cliente.snippet_offerta_generica', array('clienti' => $clienti))
					
		@elseif (!empty($cms_pagina->listing_offerta_prenota_prima)) 

			@include('draw_item_cliente.snippet_offerta_prenota_prima', array('clienti' => $clienti, 'listing_offerta' => $cms_pagina->listing_offerta_prenota_prima))           
		
		@elseif ($cms_pagina->evidenza_bb ) 

			@include('cms_pagina_listing.clienti', array('clienti' => $clienti))           
	
		@else

			@include('cms_pagina_listing.clienti', array('clienti' => $clienti, 'listing_bambini_gratis' => $cms_pagina->listing_bambini_gratis))  
		
		@endif
		
        @if (isset($pagination) && $pagination ) 
            @include("widget.pagination", ["clienti" => $clienti])
        @endif
		
		<div class="clearfix"></div>
		
		@include("widget.loading")
		
	</div>
	
	@endsection

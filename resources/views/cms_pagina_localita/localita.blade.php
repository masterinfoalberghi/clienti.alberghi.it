@extends('templates.cms_pagina_localita')
@section('seo_title'){{ $seo_title }}@endsection
@section('seo_description'){{ $seo_description }}@endsection
@include('flash')
@section('content')
    
	<div class="listing-ajax">

		@include("widget.filtri_label")

	    @if (!is_null($vetrina))
	        
	        <section id="vetrine" @if($evidenza_vetrina) class="evidenziati" @endif>
	        
				<header class="hidden"> 
					<h2>{{trans("title.vetrine")}}</h2>
				</header>
	            
	            @include('composer.vetrina')
								
	        </section>
	        
	    @endif
		
	    @if (count($clienti)>0)
	    	
	    	<section id="listing">
	        
				<header class="hidden">
					<h2>{{trans("title.lista")}}</h2>
				</header>
				
				@include('cms_pagina_listing.clienti', array('clienti' => $clienti))
	    	
	    	</section>
	    	
	    @endif
		
		<div class="clearfix"></div>
		
		@include("widget.loading")
		
	</div>

	<div class="clearfix"></div>
    	
@endsection
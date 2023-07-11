@extends('templates.cms_pagina_stradario')
@section('seo_title'){{ $seo_title }}@endsection
@section('seo_description'){{ $seo_description }}@endsection
@include('flash')

@section('contentmap')
	
	@if (count($strade))
	
		<section id="stradario" >
	
			    <header>
					<h2>{{trans("labels.vie")}} <b>{{$macrolocalita->nome}}</b></h2>
			    </header>
		        
		    @foreach($strade as $s)
		    	
		    	<nav class="col-md-4 col-sm-6">
				    	
				    @if ($s->localita == $s->macrolocalita)
				    
						@php
							$uri = Str::slug($s->macrolocalita,'-'). '/' . Str::slug($s->indirizzo,'-');
						@endphp
						
		  		  	@else
		  		  		
		  		  		@php
		  		  			$uri = Str::slug($strada->macrolocalita,'-'). '/' . Str::slug($strada->localita,'-'). '/' .Str::slug($strada->indirizzo,'-');
		  		  		@endphp
		  		  		
		  		    @endif
		  		    
		  		    @php
		  		        $localita_nome = \App\Localita::searchById([$s->localita_id_stradario])[0];
			  			$macrolocalita_nome = App\Macrolocalita::searchById([$s->macrolocalita_id_stradario])[0];
			  		@endphp
		  			
			  		<i class="icon-location"></i><a target="_top" href="/{{$s->uri}}">{{$s->indirizzo_stradario}}</a><span class="badge reverse">{{$s->listing_count}}</span> &nbsp;<small>({{$localita_nome}})</small>
					
		    	</nav>
		    	
		    @endforeach
			 
	    </section>
    
    @endif

@endsection








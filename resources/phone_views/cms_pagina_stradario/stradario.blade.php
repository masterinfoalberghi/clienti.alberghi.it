@extends('templates.cms_pagina_stradario',['cms_pagina' => $cms_pagina])

@section('seo_title'){{ 
    Utility::replacePlaceholder(
        ["{HOTEL_COUNT}","{OFFERTE_COUNT}","{PREZZO_MIN}","{PREZZO_MAX}","{MACRO_LOCALITA}","{LOCALITA}","{CURRENT_YEAR}", "{CURRENT-YEAR}"],
        $cms_pagina->seo_title, [$count,$offerte_count,$prezzo_min, $prezzo_max,$macrolocalita->nome,$localita->nome, date("Y")+Utility::fakeNewYear(),date("Y")+Utility::fakeNewYear()
        ]) }} @endsection

@section('seo_description'){{ Utility::replacePlaceholder(["{HOTEL_COUNT}","{OFFERTE_COUNT}","{PREZZO_MIN}","{PREZZO_MAX}","{MACRO_LOCALITA}","{LOCALITA}","{CURRENT_YEAR}","{CURRENT-YEAR}"],$cms_pagina->seo_description, [$count,$offerte_count,$prezzo_min, $prezzo_max,$macrolocalita->nome,$localita->nome, date("Y")+Utility::fakeNewYear(),date("Y")+Utility::fakeNewYear()]) }}@endsection

@section('css')
	#page-statica a { color:#38A6E9 ;}
@endsection

@section('contentmap')

        <article >
	        
	    	<div class="titolo-localita">
		    	<header>
			   		<h1>{{$cms_pagina->h1}}</h1>
		    	</header>
	    	</div>
	    	
		    <div id="page-statica">		  	 
				<div id="map" style="display: block; width:100%; height:350px;"></div>
				
			    <div class="lista-stradario">
				  
				    @foreach($strade as $s)
				    			  		    
			  		    @php 
				  		    $localita_nome = \App\Localita::searchById([$s->localita_id_stradario])[0];
				  			$macrolocalita_nome = App\Macrolocalita::searchById([$s->macrolocalita_id_stradario])[0];
			  		    @endphp 
				    
				    	<div class="stradario-list">
					    	<p>
						    	<img src="{{Utility::asset("images/markers/red-small.png")}}" valign="middle" /><a href="/{{$s->uri}}">{{$s->indirizzo_stradario}}</a><br />
								<small>{{$localita_nome}}@if ($localita_nome != $macrolocalita_nome), {{$macrolocalita_nome}} @endif </small>
					    	</p>
						</div>
						
				    @endforeach
				 			
			    </div>
		    </div>
	    </article>
			
@endsection

@section('content')
	
	@php 
	$listing_gruppo_piscina = Utility::getGruppoPiscina();
	$listing_gruppo_benessere = Utility::getGruppoBenessere();
	@endphp 

	@include('flash')
	
	<div class="wrapper_risultati_ricerca container" style="display:none; ">
		<div class="risultati_ricerca row">
			@foreach($clienti as $cliente)
		    	
	    	 	@php 
		    	 	$url_scheda = Utility::getUrlWithLang($locale,'/hotel.php?id='.$cliente->id); 
		    	 	$image_listing = $cliente->getListingImg('360x200', true);
	    	 	@endphp 
		        @include('draw_item_cliente.draw_item_cliente_stradario_listing',array('url' => $url_scheda, 'class_item' => 'item_wrapper_cliente', 'image_listing' => $image_listing,  'image_listing_retina' => $cliente->getListingImg('720x400', true)))

				
		    @endforeach
		</div>
	</div>
	     
@endsection

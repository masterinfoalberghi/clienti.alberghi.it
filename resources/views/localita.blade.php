@extends('templates.cms_pagina_localita')

@section('seo_title')
	{{ Utility::replacePlaceholder(["{HOTEL_COUNT}","{OFFERTE_COUNT}","{PREZZO_MIN}","{PREZZO_MAX}","{MACRO_LOCALITA}","{LOCALITA}","{CURRENT_YEAR}", "{CURRENT-YEAR}"],$cms_pagina->seo_title, [$n_hotel,$offerte_count,$prezzo_min, $prezzo_max,$macro_localita_seo,$localita_seo, date("Y")+Utility::fakeNewYear(), date("Y")+Utility::fakeNewYear()]) }}
@endsection

@section('seo_description')
	{{ Utility::replacePlaceholder(["{HOTEL_COUNT}","{OFFERTE_COUNT}","{PREZZO_MIN}","{PREZZO_MAX}","{MACRO_LOCALITA}","{LOCALITA}","{CURRENT_YEAR}", "{CURRENT-YEAR}"],$cms_pagina->seo_description, [$n_hotel,$offerte_count,$prezzo_min, $prezzo_max,$macro_localita_seo,$localita_seo, date("Y")+Utility::fakeNewYear(), date("Y")+Utility::fakeNewYear()]) }}
@endsection

@include('flash')

    @section('menu_tematico')     
        <span id="title-aside">{{ trans('labels.menu_ricerca') }}:</span>
            @if (!$cms_pagina->menu_riviera_romagnola)
                {!! Utility::getMenuTematico(app('request'), $locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id) !!}
            @else
                {!! Utility::getMenuTematico(app('request'), $locale, Utility::getMacroRR(), Utility::getMicroRR()) !!}
            @endif
    @stop

@section('content')
    
    <section id="listing" <?php if($evidenza_vetrina): ?>class="evidenziati"<?php endif; ?>>
		
        <div class="wrapper_risultati_ricerca">
        
        <div id="top_main">
	        @include('cms_pagina_localita.filtri')
			@include("cms_pagina_localita.wishform")
    	</div>
        	
        		
            <div class="risultati_ricerca">
                
                {{-- vetrina --}}
                @if (!is_null($vetrina))
                    
                    @include('composer.vetrina')
                    
                @endif

                @if (!is_null($clienti))
                
                	@include('cms_pagina_listing.clienti', array('clienti' => $clienti))
                	
                @endif
                
            </div>
            
        
        </div>

        <div class="buttonallhotel button green">
            <a href="{{url($vedi_tutti_url)}}">{{ trans('listing.vedi_tutti_hotel') }}</a>
        </div>
        
        <div class="barusso">
        		
				@if (!$cms_pagina->menu_riviera_romagnola)
					{!! Utility::getMenuTematico(app('request'), $locale, $cms_pagina->listing_macrolocalita_id, 0, 1) !!}
				@else
					{!! Utility::getMenuTematico(app('request'), $locale, Utility::getMacroRR(), Utility::getMicroRR(), 1) !!}
				@endif
        	    
            <div class="testo">
            
            	{!!$cms_pagina->descrizione_2!!}
            
            </div><!-- /testo -->
        </div><!-- /barusso -->
        
    </section>
    
   
    
@endsection
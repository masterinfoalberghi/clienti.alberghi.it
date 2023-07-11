@extends('templates.cms_pagina_localita',['cms_pagina' => $cms_pagina])
@section('seo_title'){{ 
    Utility::replacePlaceholder(
        ["{HOTEL_COUNT}","{OFFERTE_COUNT}","{PREZZO_MIN}","{PREZZO_MAX}","{MACRO_LOCALITA}","{LOCALITA}","{CURRENT_YEAR}", "{CURRENT-YEAR}"],
        $cms_pagina->seo_title, [$n_hotel,$offerte_count,$prezzo_min, $prezzo_max,$macro_localita_seo,$localita_seo, date("Y")+Utility::fakeNewYear(), date("Y")+Utility::fakeNewYear()]
    )}} 
@endsection
@section('seo_description'){{
    Utility::replacePlaceholder(
        ["{HOTEL_COUNT}","{OFFERTE_COUNT}","{PREZZO_MIN}","{PREZZO_MAX}","{MACRO_LOCALITA}","{LOCALITA}","{CURRENT_YEAR}", "{CURRENT-YEAR}"],
        $cms_pagina->seo_description, [$n_hotel,$offerte_count,$prezzo_min, $prezzo_max,$macro_localita_seo,$localita_seo, date("Y")+Utility::fakeNewYear(), date("Y")+Utility::fakeNewYear()]
    )}}
@endsection

@section('content')
	
	@include('flash')
	
	@include('cms_pagina_localita.container_centrale')
		
	<div class="wrapper_risultati_ricerca container">
		
		<div class="risultati_ricerca row <?php if ($evidenza_vetrina): echo "evidenza_vetrina"; endif; ?>">
			
		 @if (!is_null($vetrina))
			
			@if ($evidenza_vetrina)
			
				<div class="inVetrina">
				
			@endif
			
			@include('composer.vetrina') 
			  
			@if ($evidenza_vetrina)
			
				<div class="clear"></div></div>
				
			@endif
			       
		@endif
		

		@if ($clienti->count() > 0 ) 
			
			@include('cms_pagina_listing.clienti', array('clienti' => $clienti))
			
		@endif
		
			
		@if (is_null($clienti) && is_null($vetrina))
			
			@include('cms_pagina_localita.nessun_hotel')
			
		@endif
		
		</div>
	
		<div class="button_more">
		  <a class="button green" href="{{url($vedi_tutti_url)}}">{{ trans('listing.vedi_tutti_hotel') }}</a>
		</div>
	
	</div>
     
@endsection
	
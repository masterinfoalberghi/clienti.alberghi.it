@if (!isset($cms_pagina))
    <?php $cms_pagina = null; ?>
@endif

@if (!isset($count) && isset($clienti))
	<?php $count = count($clienti); ?>
@endif

<div class="fixed">
   
	<a class="backbutton" href="javascript:history.go(-1)"><img src="{{ Utility::asset('/mobile/img/back.svg') }}"></a>
	
    <div id="tab-bar">
    
    	<a href="{{Utility::getUrlWithLang($locale, '/')}}" class="logomobile" >
    		<img src="{{ Utility::asset('/mobile/img/logo-ia.svg') }}" alt="Info Alberghi srl" title="Info Alberghi srl">
    	</a>
    	
 		@if (isset($clienti_count) && $clienti_count > 0)
	    	<div class="badge">{{$clienti_count}}</div>
    	@elseif (isset($count) && $count)
	    	<div class="badge">{{$count}}</div>
    	@endif
    	
    </div>
    
    @include("widget.lang")
    
    <a class="menubutton" href="#"><img src="{{ Utility::asset('images/menu.png') }}"></a>
    
</div>


@if (
    	(isset($clienti_for_localita) && $clienti_for_localita->count() > 0) || 
    	(isset($vetrina) && $vetrina->slots->count() > 0)
    ) 

	@include('cms_pagina_listing.filtri')

@else

	@if (isset($clienti) && count($clienti))
        @include('cms_pagina_listing.filtri')
	@endif
	
@endif

<nav id="menu-mobile" role="navigation">
	<div class="menu-mobile">
		<div class="menu-mobile-inside">
			
			<h1 class="hidden" >{{trans("title.navigazione")}}</h1>
			
			<div class="breadcrumb">
			
				<span>
					@if (isset($selezione_localita) && $selezione_localita != "" )
						<img src="{{Utility::asset('/mobile/img/loc-listing.svg')}}" />
						{{$selezione_localita}} 
					@endif
				</span>
				<a href="#" id="cambialocalita" data-txt="{!!trans("labels.cambia_localita")!!}" class="small button cyan">{!!trans("labels.cambia_localita")!!}</a>
				<div class="clear"></div>
				
			</div>
			
			<div class="menu-localita">
				
				{!! Utility::getMenuLocalitaMobile($cms_pagina) !!}
				<div class="clear"></div>
							
			</div>
		
		
		
			@if (isset($cms_pagina) && $cms_pagina->menu_riviera_romagnola)
				{!! Utility::getMenuTematicoMobile( $locale, Utility::getMacroRR(), Utility::getMicroRR()) !!}
		        <div class="clear"></div>
		
		    @elseif (isset($cms_pagina) && !is_null($cms_pagina))
			    {!! Utility::getMenuTematicoMobile( $locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id) !!}
			    <div class="clear"></div>
		    
		    @endif
		</div>
	</div>
</nav>


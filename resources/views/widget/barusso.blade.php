
{{--  PESARO non ha il menu BARUSSO --}}

@if ($cms_pagina->listing_macrolocalita_id != 12)
<div class="main-content-container barusso margin-topx2">	
	
	<div class="container">
		<div class="row">
			
			<nav id="barusso" class="main-content">
				
				@if (!$cms_pagina->menu_riviera_romagnola)
				{!! Utility::getMenuTematico( $locale, $cms_pagina->listing_macrolocalita_id, $cms_pagina->listing_localita_id, 1) !!}
				@else
				{!! Utility::getMenuTematico( $locale, Utility::getMacroRR(), Utility::getMicroRR(), 1) !!}
				@endif
	    	
			</nav><!-- /barusso -->
			
		</div>
	</div>
</div>
<div class="clearfix"></div>
@endif
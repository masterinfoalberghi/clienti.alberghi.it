@if(isset($cms_pagina))
	@if(isset($listing) && $listing == 1 )
		<div class="listing-content">
	@endif
			
		@if($cms_pagina->immagine)
			@include("widget.over", ["id_page" => $cms_pagina->id, "h1" => $titolo, "descrizione" => $testo, "immagine" => $cms_pagina->immagine])
		@else
			@include ("widget.1col", ["id_page" => $cms_pagina->id, "h1" => $titolo, "descrizione" => strip_tags($testo,'<p><b><strong><em><i><a><ul><li><div><h3><span>') ])
		@endif 
			
	@if(isset($listing) && $listing == 1)
		</div>
	@endif
@endif

@if(isset($filter) && $filter == 1 )
    <div class="listing-content">
        <div class="row">
            @include ("widget.1col", ["h1" => "$titolo", "h1_hide" => 1, "descrizione" => $description])
        </div>
    </div>
@endif
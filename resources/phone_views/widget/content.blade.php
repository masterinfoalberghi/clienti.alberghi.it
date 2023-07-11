@if ($cms_pagina->descrizione_2 != "" &&  Utility::is_JSON($cms_pagina->descrizione_2))
	
   	<section id="altro-contenuto" class="testo">
		
	<?php $contenuti = json_decode($cms_pagina->descrizione_2); ?>
		
		@foreach($contenuti as $contenuto)
				
				@if ($contenuto->tipocontenuto == "text")
				
					@include("widget.1col", ["immagine" => $contenuto->immagine, "h2" => $contenuto->h2, "h3" => $contenuto->h3, "descrizione" => $contenuto->descrizione_2])
					
				@elseif ($contenuto->tipocontenuto == "gallery")
				
					@include("widget.gallery", ["immagini" => $contenuto->galleria->immagini, "testo" => $contenuto->galleria->testo])
					
				@elseif ($contenuto->tipocontenuto == "map")
				
					@include("widget.mappa", ["map_source" => $contenuto->map_source, "map_lat_lon" => $contenuto->map_lat_lon])					
				
				@endif
			
		@endforeach
	        
    </section><!-- /testo -->
    
@endif
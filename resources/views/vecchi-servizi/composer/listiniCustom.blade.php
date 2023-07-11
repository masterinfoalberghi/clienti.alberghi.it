<?php

// visualizzo i listini variabili
// @parameters : $listiniVariabili (array di array con i listini ), titolo , locale

?>

@if ($listini->count())
  <div class="margin-bottom">
	  
	@foreach ($listini as $listiniCustom)
		@foreach ($listiniCustom->listiniCustomLingua as $listino)
		
		<?php $listino_dom_id = "listino-custom-".$listino->id;?>
		
			<?php 

				$table = str_replace('<table>', '<table id="'.$listino_dom_id.'" class="listiniCustom content" width="100%" >', $listino->tabella);
				$table = str_replace('euro', '&euro;', $table);
				$table = str_replace('<thead>', '<thead><tr><th class="title-header" colspan="6"><h4 class="title">'.strtoupper($listino->titolo).'</h4><span>'.$listino->sottotitolo .'</span></th></tr>', $table);
				echo $table;
				
			?>
			<div style="font-size:12px; line-height: 20px !important; padding:5px;">
				@if($listino->descrizione)
					{!! strip_tags($listino->descrizione, "<br>") !!}
				@endif
			</div>
		
		@endforeach
	@endforeach
  
  </div>
@endif

 

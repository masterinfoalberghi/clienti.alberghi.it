@if ($locale == "it")
	
	<?php 
		!isset($text) ? $text = "Guarda questo sito di hotel" : "";
		$urltoshare = Utility::getCurrentUri();
		$codice =  \Carbon\Carbon::now()->timestamp;
		
		if (strpos($urltoshare, "?") > 0)
			$urltoshare_completo = $urltoshare . "&ws=" . $codice;
		else
			$urltoshare_completo = $urltoshare . "?ws=" . $codice;
		
	?>
	
	<a @if(isset($marginbottom)) style="margin: 12px 15px 15px; width: 75%; text-align:center;" @endif class="button button-whatsapp" href="whatsapp://send?text=<?php echo rawurlencode( $text  . " \xf0\x9f\x94\x9d " . url($urltoshare_completo))  ?>" onclick="share('{{url($urltoshare)}}', '{{$codice}}');">Condividi su whatsapp</a>

@endif


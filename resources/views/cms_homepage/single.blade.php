
	<div class="boxspot">
	<img src="{{Utility::asset("images/spothome/600x290/" . $th["image"])}}" />
	<a class="boxspot_link" href="{{Utility::getUrlWithLang($locale, $th["link"])}}" alt="<?php echo htmlspecialchars($th["titolo"]); ?>">
		<b class="boxspot_title">{{$th["titolo"]}}</b>
	</a>
	<span class="boxspot_date">{{$th["sottotitolo"]}}</span>
	<span class="boxspot_text">{!! $th["testo"] !!}</span>
	

	
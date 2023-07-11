<?php 
	
	$urlCompleto = true;
	$seo_image_url = "/images/whatsapp/homepage.jpg";
	
	// Sono una scheda
	if (isset($gallery_mobile)) {
		
		$seo_image_url = $gallery_mobile[0][1];
		$urlCompleto = false;
		
	} else if (isset($cliente)) {
		
		$urlCompleto = true;
		$seo_image_url = $cliente->getListingImg("360x200");
		
	}
		
	$seo_image = Utility::getUrlWithLang($locale, "/" . $seo_image_url, $urlCompleto);
	
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0" />

<!-- Share -->
<meta property="fb:app_id" content="183910442149704" >
<meta property="og:title" content="@yield('seo_title')" >
<meta property="og:description" content="@yield('seo_description')" >
<meta property="og:type" content="website" >
<meta property="og:url" content="{{Utility::getCurrentUri()}}" >
<meta property="og:image" content="{{$seo_image}}" >

<meta name="referrer" content="always" />

<script>window.dataLayer = window.dataLayer || [];</script>

	
<!-- Fine Share -->




<?php 
	
	$urlCompleto = true;
	$seo_image_url = "/others/homepage.jpg";
	
	// Sono una scheda
	if (isset($gallery_mobile)) {
		
		$seo_image_url = $gallery_mobile[0][1];
		$urlCompleto = false;
		
	} else if (isset($cliente)) {
		
		$urlCompleto = true;
		$seo_image_url = $cliente->getListingImg("360x200");
	
	}

	if (!isset($locale))
		$locale = "it";

	$seo_image = Utility::assetsImage($seo_image_url);
	 
?>

<!-- Share -->
<meta property="fb:app_id" content="183910442149704" >
<meta property="og:title" content="@yield('seo_title')" >
<meta property="og:description" content="@yield('seo_description')" >
<meta property="og:type" content="website" >
<meta property="og:url" content="{{Utility::getCurrentUri()}}" >
<meta property="og:image" content="{{$seo_image}}" >

<meta name="referrer" content="always" />

<!-- Fine Share -->

<style>
    @include('vendor.flags.flags')
    @include('vendor.fontello.css.animation')
    @include('vendor.fontello.css.fontello')
    @include('desktop.css.above')
</style>






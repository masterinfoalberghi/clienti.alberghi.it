
<?php 
$cliente = $vaat->offerta->cliente;
$altre_offerte = $vaat->altre_offerte;
$url = Utility::getUrlWithLang($locale, "/vaat/".$cliente->id."/".$vaat->id);


if ($evidenza_vetrina)
	$class="item_wrapper_vetrina";
else
	$class="item_wrapper_cliente";
?>

@include('draw_item_cliente.draw_item_cliente_vaat_listing',array( 'url' => $url,'class_item' => $class, 'image_listing' => $cliente->getListingImg("360x200", true), 'image_listing_retina' => $cliente->getListingImg("720x400",true)))
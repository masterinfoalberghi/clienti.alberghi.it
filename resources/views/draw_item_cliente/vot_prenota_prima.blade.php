
<?php 
$cliente = $vot->offerta->cliente;
$altre_offerte = $vot->altre_offerte;
$url = Utility::getUrlWithLang($locale,"/vot/".$cliente->id."/".$vot->id);

if(!$vot->getMarkAsEvidenza())
	{
	$class_item .= ' not_mark_as_evidenza';	
	}


?>

 @include('draw_item_cliente.draw_item_cliente_vot_listing_prenota_prima',array('url' => $url,'class_item' => $class_item, 'image_path' => 'listing', 'image_listing' => $cliente->getListingImg('220x220', true)))
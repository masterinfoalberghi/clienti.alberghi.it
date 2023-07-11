
<?php 
$cliente = $vaat->offerta->cliente;
$altre_offerte = $vaat->altre_offerte;
$url = Utility::getUrlWithLang($locale,"/vaat/".$cliente->id."/".$vaat->id);
?>

@include(

	'draw_item_cliente.draw_item_cliente_vaat_listing',
	array(
		
		'url' => $url,
		'class_item' => $class_item ,
		'image_path' => 'listing',
		'image_listing' => $cliente->getListingImg('220x220', true)
	)
	
)

<?php 
$cliente = $vot->offerta->cliente;
$url = Utility::getUrlWithLang($locale,"/vot/".$cliente->id."/".$vot->id);
?>

@include('draw_item_cliente.draw_item_cliente_vot_listing',array('url' => $url,'class_item' => $class_item, 'image_path' => 'listing', 'image_listing' => $cliente->getListingImg('360x200', true)))
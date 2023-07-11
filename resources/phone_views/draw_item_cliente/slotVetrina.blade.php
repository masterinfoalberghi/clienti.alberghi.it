
<?php 
$vetrina_id = $slot->vetrina_id;
$slot_id = $slot->id;
$cliente = $slot->cliente;

if (!is_null($cliente)) 
	{
	$url_vetrina = Utility::getUrlWithLang($locale,'/vetrina/'.$cliente->id.'/'.$slot_id.'/'.$vetrina_id);

	if ($evidenza_vetrina)
		$class="item_wrapper_vetrina";
	else
		$class="item_wrapper_cliente";
	}
?>


@if (!is_null($cliente))

	@include('draw_item_cliente.draw_item_cliente_vetrina_listing',
		array(
			'url' => $url_vetrina, 
			'class_item' => $class, 
			'image_listing' => $cliente->getListingImg("360x200",true), 
			'image_listing_retina' => $cliente->getListingImg("720x400",true)
			)
		)

@endif
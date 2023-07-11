
<?php 
	
$vetrina_id = $slot->vetrina_id;
$slot_id 	= $slot->id;
$cliente 	= $slot->cliente;


//if($cliente->id == 17) dd($cliente->offerteLast);

if (!is_null($cliente)) {

	/**
	 * url per visualizzare/contare la vetrina
	 */
	
	$url_vetrina = Utility::getUrlWithLang($locale,'/vetrina/'.$cliente->id.'/'.$slot_id.'/'.$vetrina_id, false);
	
}



?>

@if (!is_null($cliente))
	@include(
		
		'draw_item_cliente.draw_item_cliente_vetrina_listing',
		
		array(
			'url' => $url_vetrina,
			'class_item' => 'slotVetrina', 
			'image_path' => 'vetrine', 
			'image_listing' => $cliente->getListingImg("220x220", true, $cliente->listing_img)
		)
		
	)
	
@endif
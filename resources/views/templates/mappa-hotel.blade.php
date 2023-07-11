<?php

$testo = "";
$ico = $cliente->stelle->nome;
$loc = $cliente->localita->nome;

$testo = "<h3>" . $cliente->nome . "<span class='rating padding-right-2'>" . $ico . "</span></h3>" . $cliente->indirizzo . " - " .$loc;

$distanze = '<br /><br /><div style=\"line-height:18px;\">'; 

	if ( Utility::getDistanzaDalCentroPoi($cliente) == trans("labels.in_centro") )
		$distanze .= "<b>".trans("labels.in_centro")."</b><br />";

	else
		$distanze .= trans("labels.centro").":<b>".Utility::getDistanzaDalCentroPoi($cliente)."</b><br />";
		
	$distanze .= trans("labels.spiaggia").": <b>m ".round($cliente->distanza_spiaggia)."</b><br />";
	$distanze .= trans("labels.stazione").": <b>km ".round($cliente->distanza_staz)."</b><br />";
	$distanze .= trans("labels.fiera").": <b>km ".round($cliente->distanza_fiera)."</b><br /><br />";

$testo .= $distanze . '</div>';

$array_punti_di_forza = array();
	
foreach ($cliente->puntiDiForza as $puntiDiForza) {
	
	$pdf_name = $puntiDiForza->puntiDiForza_lingua->first()->nome;
	
	if ($pdf_name != "") {

		$pdf = "";
		
		if ($puntiDiForza->evidenza)  
			$pdf = '<b class="evidenziato">';
		
		$pdf .= $pdf_name;
		
		if ($puntiDiForza->evidenza)  
			$pdf .= '</b>';
			
		$array_punti_di_forza[] = strtolower($pdf);

	}
	
}

?>
<html>

	<head>
	
		<script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
		<script src="{{Utility::asset('/desktop/js/mappa.min.js')}}"></script>
		
		<script type="text/javascript">
			
			var $csrf_token 	= "{{csrf_token()}}";
			var __lat 			= "{{$cliente->mappa_latitudine}}";
			var __lon 			= "{{$cliente->mappa_longitudine}}";
			var __testo 		= "{!! str_replace(PHP_EOL, '', $testo) !!}";
				
		</script>

		<meta name="robots" content="noindex">
		
		@if (Utility::get_client_ip() == '127.0.0.1')
			<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
		@endif

		<style>
		
			@include('desktop.css.above')
        	@include('desktop.css.mappa')
    		@include('vendor.fontello.css.animation')
    		@include('vendor.fontello.css.fontello')

			html, body { width: 100%; height: 100%; display: block; margin:0; padding: 0; min-width: 1px; }
			#map-container { width: 100%; height: 100%; display: block; background: #f5f5f5;  }
			#map { width: 100%; height:100%; display: block; position: fixed !important; }

		</style>
		
		@include("gtm")

	</head>

	<body>
		
		@include("gtm-noscript")

		<div id="map-container">

			<div id="infowindow_container" class=" places margin-bottom-2">
					
					<div class="search-place infowindow_container_content" style="border-bottom: 1px solid #eee; padding-bottom: 15px;" >
						<div id="header">
							{{strtoupper($cliente->nome)}} <span class='rating padding-right-2'> {{$ico}} </span>
							<span class="loc_header">{{ucfirst(strtolower($loc))}}</span>
						</div>
						<h3>{{trans("mappe.cosa_dintorni")}}</h3>
						<label><input type="checkbox" data-item="restaurant|meal_takeaway" class="google-place default_checkbox" name="dove_mangiare" value="1"><span>{{trans("mappe.dove_mangiare")}}</span></label><br />
						<label><input type="checkbox" data-item="bar|cafe" class="google-place default_checkbox" name="bar_caffe" value="1"><span>{{trans("mappe.bar_cafe")}}</span></label><br />
						<label><input type="checkbox" data-item="liquor_store|supermarket" class="google-place default_checkbox" name="supermercato" value="1"><span>{{trans("mappe.supermercato")}}</span></label><br />
						<label><input type="checkbox" data-item="night_club" class="google-place default_checkbox" name="disco_pub" value="1"><span>{{trans("mappe.disco_pub")}}</span></label><br />
						<label><input type="checkbox" data-item="bakery" class="google-place default_checkbox" name="pasticceria" value="1"><span>{{trans("mappe.pasticceria")}}</span></label><br />
						<label><input type="checkbox" data-item="amusement_park" class="google-place default_checkbox" name="divertimenti" value="1"><span>{{trans("mappe.divertimenti")}}</span></label><br />
						<label><input type="checkbox" data-item="pharmacy" class="google-place default_checkbox" name="farmacia" value="1"><span>{{trans("mappe.farmacia")}}</span></label><br />
						<label><input type="checkbox" data-item="clothing_store|jewelry_store|shoe_store|shopping_mall" class="google-place default_checkbox" name="shopping" value="1"><span>{{trans("mappe.shopping")}}</span></label><br />
						<label><input type="checkbox" data-item="bank" class="google-place default_checkbox" name="banca" value="1"><span>{{trans("mappe.banca")}}</span></label><br />
						<label><input type="checkbox" data-item="post_office" class="google-place default_checkbox" name="posta" value="1"><span>{{trans("mappe.posta")}}</span></label>
						<br />
						<br />
						<h3>{{trans("mappe.punti_interesse")}}</h3>
						@include('composer.puntiDiInteresse', ["mappa_model"=>1]) 
					</div>
				
			</div>

		
			<div id="map"></div>
			<div id="infowindow_container"></div>

		</div>

		<script>

			$(document).ready(function() { 
				
				$(".google-place").change(function(e){

					var center_map = new google.maps.LatLng( __lat , __lon );
					var infowindow = new google.maps.InfoWindow();

					clearMarkers();

					$(".google-place:checked").each(function(){
						e.preventDefault(); 
						var item = $(this).data("item");

						$.each(item.split("|"), function( index, value ) {
							mapInitSearch(center_map, infowindow, value);
						});
					});
				});

			});

			$(function() {
				$.getScript( '//maps.googleapis.com/maps/api/js?key={{Config::get("google.googlekey")}}&libraries=places&language=it' ).done(function( script, textStatus ) {  window.initializeHotel(__lat, __lon, __testo);})
			});
		</script>
	</body>

</html>
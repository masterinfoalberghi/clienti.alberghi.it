<html>

	<head>

		<script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
		<script  src="{{Utility::asset('/desktop/js/mappa.min.js')}}"></script>
		<meta name="robots" content="noindex">
		
		<style>
		
			@include('desktop.css.above')
        	@include('desktop.css.mappa')
    		@include('vendor.fontello.css.animation')
    		@include('vendor.fontello.css.fontello')

			html, body { width: 100%; height: 100%; display: block; margin:0; padding: 0; }
			#map-container { width: 100%; height: 100%; display: block; background: #f5f5f5;  }
			#map { width: 100%; height:100%; display: block; }

		</style>
		
		@include("gtm")

	</head>

	<body>
		
		@include("gtm-noscript")

		<div id="map-container">
			<div id="map"></div>
			<div id="infowindow_container"></div>
			<a id="closeinfowindow_container" href="#"><i class="icon-cancel"></i></a>
		</div>

		<script>

			// Dizionario 
			var dizionario		= {};
	        dizionario.link = "{{trans('listing.vedi_hotel')}}";
	        dizionario.prm = "{{trans('listing.p_min')}}";
	        dizionario.prM = "{{trans('listing.p_max')}}"
	        dizionario.pri = "{{trans('labels.a_partire_da')}}"

	        // Setting coords
			@php $coords = explode(",",$uri); @endphp

			var console=console?console:{log:function(){}}
			var apikey = 'AIzaSyCAyCUJ63a6dtvWfdAaqCmLxrWqOombjM8';
			var __lat = {{$coords[0]}};
			var __lon = {{$coords[1]}};
			var __markers_source = {};

			$(function() {

				// Prendo i punti dalla finestra in parent al'iframe
				parent.$(".item-listing").each( function (i) {
					
					var $me = $(this);
					var __lat_item = $me.data("lat");
					var __lon_item = $me.data("lon");

					var __img_item = $me.find(".item-listing-figure img").attr("data-src")
					
					if (__img_item == undefined) {
						__img_item = $me.find(".item-listing-figure img").attr("src")
					}

					__img_item = __img_item.replace("220x220", "360x200");

					var __nam_item = $me.find(".item-listing-title h2").text();
					var __rat_item = $me.find(".item-listing-title .rating").text();
					var __adr_item = $me.find(".item-listing-address  .indirizzo").text() + "<br/>" + $me.find(".item-listing-address  .cap").text() + " - " + $me.find(".item-listing-address  .localita").text() + " (" + $me.find(".item-listing-address  .localita").text() + ")";
					var __lnk_item = $me.find(".item-listing-title a").attr("href");
					var __prm_item = $me.data("prezzo");
					
					__markers_source[i] = { 

						"lat": __lat_item, 
						"lon": __lon_item, 
						"img": __img_item,
						"nam": __nam_item,
						"rat": __rat_item, 
						"adr": __adr_item, 
						"lnk": __lnk_item,
						"pri": __prm_item
						 
					};

				});

				// Carico asincrono il framework di google maps
				$.getScript( '//maps.googleapis.com/maps/api/js?key=' + apikey + '&amp;language=it' )
					.done(function( script, textStatus ) { 
						window.initializeListing(__lat, __lon, __markers_source);
					}
				);

			});



		</script>
	</body>

</html>
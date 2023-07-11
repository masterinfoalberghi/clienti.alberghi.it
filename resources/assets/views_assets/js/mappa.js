
	var map;
	var bounds;
	var markers = [];
	var _markers = [];

	var console=console?console:{log:function(){}};

	$(function() {

		$("#closeinfowindow_container").click(function (e) {

	    	e.preventDefault();
	    	$(this).hide();
	    	$("#infowindow_container").hide();

	    });

	});

	function mapInit(mapOptions) {

		map = new google.maps.Map(document.getElementById("map"), mapOptions);
		var noPoi = [
			{
				featureType: "poi",
				stylers: [
					{ visibility: "off" }
				]
			},
			{
				featureType: "administrative",
				stylers: [
					{ visibility: "on" }
				]
			},


			{
				featureType: "road",
				stylers: [
					{ visibility: "simplified" }
				]
			},



		];

		map.setOptions({styles: noPoi});

	}


	function mapInitSearch(center_map, infowindow, item) {

			if (item!='') {

				var service = new google.maps.places.PlacesService(map);

				service.nearbySearch({
					location: center_map,
					radius: 700,
					type: [item]
			    }, callback);

			}

			function callback(results, status) {

			    if (status === google.maps.places.PlacesServiceStatus.OK) {
					for (var i = 0; i < results.length; i++) {
						createMarker(results[i]);
					}
		    	}

			}

			function createMarker(place) {

			  	// grab place info

			    var placeId = place.place_id;
			    var placeInfo;
			    var aperto_adesso;

			    var request = { placeId: placeId };
			    service = new google.maps.places.PlacesService(map);
			    service.getDetails(request, grabInfo);

			    function grabInfo(placeResult, status) {
					if (status == google.maps.places.PlacesServiceStatus.OK) {
						placeInfo = placeResult;
					}
			    }

			    var images, placeLoc = place.geometry.location;

			    /**
				 * Tolgo gli Hotel (alloggi)
				 */

			    if (place.icon.indexOf("lodging") == -1) {

			    	if (place.icon.indexOf("/restaurant") > -1 ) images = "//static.info-alberghi.com/images/markers/restaurant.png";
			    	if (place.icon.indexOf("business") > -1 ) images = "//static.info-alberghi.com/images/markers/business.png";
			    	if (place.icon.indexOf("/shopping") > -1 ) images = "//static.info-alberghi.com/images/markers/business.png";
			    	if (place.icon.indexOf("/generic") > -1 ) images = "//static.info-alberghi.com/images/markers/business.png";
			    	if (place.icon.indexOf("/bar") > -1 ) images = "//static.info-alberghi.com/images/markers/bar.png";
			    	if (place.icon.indexOf("/cafe") > -1 ) images = "//static.info-alberghi.com/images/markers/cafe.png";
			    	if (place.icon.indexOf("/bank") > -1 ) images = "//static.info-alberghi.com/images/markers/bank.png";

				    marker = new google.maps.Marker({
						map: map,
						icon: images,
						position: place.geometry.location
				    });

				    if('opening_hours' in place) {

				    	if('open_now' in place.opening_hours){
				    		if(place.opening_hours.open_now){
				    			aperto_adesso = '<span id="aperto" class="tag verde" style="padding:3px 6px">Adesso aperto</span>';
				    		}
				    		else {
				    			aperto_adesso = '<span id="chiuso" class="tag rosso" style="padding:3px 6px">Adesso chiuso</span>';
				    		}
				    	}

				    } else {

				    	aperto_adesso = '';

				    }

				    google.maps.event.addListener(marker, 'click', function() {

				    content = '<div class="content_mappa">';
				    content += '<h3>' + place.name + '</h3>';

				    if (placeInfo != undefined) {

					    if(placeInfo.rating) {
					    	var perc = placeInfo.rating / 5 * 100;
					    	content += '<span class="rating-user"><span style="width:' + perc + '%;" class="bar"></span></span>';
					    }

					    if(placeInfo.reviews) {
					    	content += '<span class="rating-reviews"><a href="'+placeInfo.url+'" rel="noopener" target="_blank">Recensioni</a></span>';
					    }

					    if(placeInfo.reviews || placeInfo.rating) {
					    	content += '<br /><br />';
						}

					    if(placeInfo.formatted_address){
					    	content += placeInfo.formatted_address + '<br />';
					    }

					    if(placeInfo.formatted_phone_number){
					    	content += placeInfo.formatted_phone_number + '<br />';
					    }

					    if(placeInfo.url){
					    	content += '<a href="' +placeInfo.url+ '" rel="noopener" target="_blank">Visualizza su Google Maps</a><br /><br />';
					    }

					}

				    content += aperto_adesso;
					content += '</div>';

				    infowindow.setContent(content);

				      infowindow.open(map, this);

				    });

				    markers.push(marker);

				}
			}

	}

	// Sets the map on all markers in the array.

	function setMapOnAll(map) {
	    for (var i = 0; i < markers.length; i++) {
	    	markers[i].setMap(map);
	    }
	}

	function clearMarkers() {

      	// Clear out the old markers.
        setMapOnAll(null);

	}

	function putMarkers ( $chi, bounds ) {

		var image = '//static.info-alberghi.com/images/markers/red.png';
		var oldImage = '//static.info-alberghi.com/images/markers/red.png';
		var newImage = '//static.info-alberghi.com/images/markers/blue.png';

		//console.log($chi);

		$.each($chi, function (i,v) {

			var _lat 		= v.lat;
			var _lon 		= v.lon;
			var _image 		= v.img;
			var _title 		= v.nam;
			var _rating 	= v.rat;
			var _address 	= v.adr;
			var _contact 	= v.cnt;
			var _web 		= v.web;
			var _lnk 		= v.lnk;
			var _prm 		= v.prm;
			var _prM 		= v.prM;
			var _pri 		= v.pri;

			//console.log(_image);

			var _point = new google.maps.LatLng(_lat,_lon);


			var _content  = '<div>';
				_content += 	'<img src="'+_image+'" style="width:100%;"/>';
				_content += 	'<div class="infowindow_container_content">';
				_content += 		'<h3 style="display:inline-block;">' + _title + '</h3><span class="rating" style="font-size:12px;">'+_rating+'</span>';
				_content += 		'<p>' + _address + '</p>';

				if (_contact) {
					_content += 		'<small>' + _contact + '</small><br />';
				}

				if (_contact) {
					_web += 		'<small>' + _web  + '</small><br /><br />';
				}

				if (_prm && _prM) {

					_content += 		'<p class="prices">';
					_content += 			'<small class="label">' + dizionario.prm + '</small><span class="price">' + _prm  + '</span>&nbsp;&nbsp;&nbsp;';
					_content += 			'<small class="label">' + dizionario.prM + '</small><span class="price">' + _prM  + '</span>';
					_content += 		'</p>';

				} else if (_pri) {

					_content += 		'<p class="prices">';
					_content += 			'<small class="label">' + dizionario.pri + '</small><span class="price">' + _pri  + ' &euro;</span>';
					_content += 		'</p>';

				}

				_content +=			'<a target="_top" href="'+_lnk+'" class="btn right btn-verde">' + dizionario.link + '</a>';
				_content += 	'</div>';
				_content += '</div>';

			var _marker = new google.maps.Marker({

			    position: _point,
			    map: map,
			    icon: image,
			    oldImage: oldImage,
			    newImage: newImage

	    	});


	    	google.maps.event.addListener(_marker, 'click', function() {

				$("#infowindow_container").html(_content).show();
				$("#closeinfowindow_container").show();

				$.each(_markers, function( index, value ) {
					var img = this.oldImage;
					this.setIcon(img);
				});

				var img = _marker.newImage;
				_marker.setIcon(img);
				_marker.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);

			});

			_markers.push(_marker);
			bounds.extend(_marker.getPosition());

		});

		map.fitBounds(bounds);

	}

	function putHotel ( _lat, _lon, image, _testo) {
		/**
		 * Marker Hotel
		 */

		var infowindow = new google.maps.InfoWindow();
		var _point = new google.maps.LatLng(_lat,_lon);

		var _marker = new google.maps.Marker({
		    position: _point,
		    map: map,
		    icon: image
	    });

		var _infowindow = new google.maps.InfoWindow({
		    content: _testo
		});

		_marker.addListener('click', function() {
			_infowindow.open(map, _marker);
		});

		bounds.extend(_marker.getPosition());

		_markers.push(_marker);

	}

	function putMarkersPoi ($chi, image, boundery) {

		/**
		 * Marker punti di interesse
		 */

		$chi.each(function () {

			var $me = $(this);
			var _lat = $me.data("lat");
			var _lon = $me.data("lon");
			var _point = new google.maps.LatLng(_lat,_lon);
			var _title = $me.data("title");
			var _dist = $me.data("dis");

			var _infowindow = new google.maps.InfoWindow({
			    content: "<b>" + _title + "</b><br />" + _dist,
			});

			var _marker = new google.maps.Marker({
			    position: _point,
			    map: map,
			    icon: image
	    	});

	    	_marker.addListener('click', function() {
				_infowindow.open(map, _marker);
			});

		    _markers.push(_marker);

		    if (boundery) {
		    	bounds.extend(_marker.getPosition());
		    }

		});

		if (boundery) {
			map.fitBounds(bounds);
		}

	}

	function showSteps(directionResult, _startPointTitle, _endPointTitle) {

		var myRoute = directionResult.routes[0].legs[0];
		var duration = directionResult.routes[0].legs[0].duration;
		var lengSteps = myRoute.steps.length;

		/**
		 * Inizio
		 */

		var image = '//static.info-alberghi.com/images/markers/red.png';

		var _marker = new google.maps.Marker({
			position: myRoute.steps[0].start_point,
			map: map,
			icon: image
		});

		var _infowindow = new google.maps.InfoWindow({
		    content: _startPointTitle,
		});

		_marker.addListener('click', function() {
			_infowindow.open(map, _marker);
		});

		_markers.push(_marker);

		/**
		 * Fine
		 */

		image = '//static.info-alberghi.com/images/markers/blue.png';

		var _marker2 = new google.maps.Marker({
			position: myRoute.steps[lengSteps - 1].end_point,
			map: map,
			icon: image
		});


		var _infowindow2 = new google.maps.InfoWindow({
		    content: _endPointTitle,
		});

		_marker2.addListener('click', function() {
			_infowindow2.open(map, _marker2);
		});

		_markers.push(_marker2);

	}


	function calculateAndDisplayRoute(directionsDisplay, directionsService, distance, latOrig, lonOrig, latDest , lonDest, _startPointTitle, _endPointTitle) {

        for (var i = 0; i < _markers.length; i++) {
          _markers[i].setMap(null);
        }

        if (Math.abs(distance) <= 3) {
        	_travelMode = "WALKING";
        } else {
        	_travelMode = "DRIVING";
        }

        directionsService.route({

          origin: latOrig  + ", " + lonOrig,
          destination:  latDest  + ", " + lonDest,
          travelMode: _travelMode

        }, function(response, status) {

          if (status === 'OK') {

            directionsDisplay.setDirections(response);
            showSteps(response, _startPointTitle, _endPointTitle);

          }

        });

    }



	/**
	 * Inizializza la mappa per lo stradario
	 */

	window.initializeStradario = function (_lat, _lon, __markers_source) {

		/**
	     * Opzioni mappa
	     */

	    var mapOptions = {

			center: new google.maps.LatLng( __lat , __lon ),
			zoom: 15,
			scrollwheel: false,
			gestureHandling:"greedy",
			mapTypeId: google.maps.MapTypeId.ROADMAP

		};

		mapInit(mapOptions);
		bounds = new google.maps.LatLngBounds();

		putMarkers( __markers_source,  bounds  );

	};

	/**
	 * Inizializzo la mappa per un hotel
	 */

    window.initializeHotel = function (_lat, _lon, _testo) {

    	/**
	     * Opzioni mappa
	     */
    	var _startPointTitle ;
	    var center_map = new google.maps.LatLng( _lat , _lon );

	    var mapOptions = {

			center: center_map,
			zoom: 15,
			scrollwheel: true,
			gestureHandling:"greedy",
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControlOptions: {
              style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
              position: google.maps.ControlPosition.TOP_RIGHT
          	}

		};


		mapInit(mapOptions);
		bounds = new google.maps.LatLngBounds();

    	if (_testo !== 'undefined') {
			_startPointTitle = _testo;
		} else {
			_startPointTitle = "<b>" + $("h1").parent().html()+ "</b>";
		}

		putHotel( _lat,_lon, '//static.info-alberghi.com/images/markers/red.png', _startPointTitle );
		putMarkersPoi($(".marker_poi"), '//static.info-alberghi.com/images/markers/dot-red.png' , false);

		/**
		 * Directions
		 */

		var directionsService = new google.maps.DirectionsService();

        var directionsDisplay = new google.maps.DirectionsRenderer({map: map,suppressMarkers: true});

        $(".marker_poi").click(function(e) {

	        e.preventDefault();

			var $me = $(this);
			var _endPointTitle = '<b>' + $me.data("title") + "</b><br />" + $me.data("dis");
			calculateAndDisplayRoute( directionsDisplay, directionsService,  $me.data("dis"), _lat, _lon, $me.data("lat"), $me.data("lon"), _startPointTitle , _endPointTitle );

			$(".closePDFModel").hide();
			$me.parent().find(".closePDFModel").show();

        });

        $(".closePDFModel").click(function(e) {

	        e.preventDefault();
	        var $me = $(this).parent().find(".marker_poi");
	        $(".closePDFModel").hide();

	        for (var i = 0; i < _markers.length; i++) {
	          _markers[i].setMap(null);
	        }

	        directionsDisplay.setDirections({routes: []});

	        putHotel( _lat,_lon, '//static.info-alberghi.com/images/markers/red.png', _testo );

	        var pt = new google.maps.LatLng(_lat, _lon);
			map.setCenter(pt);
			map.setZoom(15);

	        putMarkersPoi($(".marker_poi"), '//static.info-alberghi.com/images/markers/dot-red.png' , false);

	    });

        /*
         Google Place Autocomplete
         */

		//mapInitSearch(center_map, infowindow, item);

	};




	/**
	 * Initializzo il listing
	 */

	window.initializeListing = function (__lat, __lon, __markers_source) {

		/**
	     * Opzioni mappa
	     */

	    var mapOptions = {

			center: new google.maps.LatLng( __lat , __lon ),
			zoom: 13,
			scrollwheel: false,
			gestureHandling:"greedy",
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControlOptions: {
              style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
              position: google.maps.ControlPosition.TOP_CENTER
          },

		};

		mapInit(mapOptions);
		bounds = new google.maps.LatLngBounds();
		putMarkers( __markers_source,  bounds  );


	};





	/**
	 * Initializzo il listing
	 */

	function initializeListingRicerca (__lat, __lon, __clienti_json, __count, __own_poi_json, dizionario, __locale) {

		//clienti_json_obj = jQuery.parseJSON( __clienti_json );
		clienti_json_obj =  __clienti_json;

		var infowindow = new google.maps.InfoWindow();

		var marker, i;


		/**
	     * Opzioni mappa
	     */
	    var mapOptions = {

			center: new google.maps.LatLng( __lat , __lon ),
			zoom: 13,
			scrollwheel: true,
			gestureHandling:"greedy",
			mapTypeId: google.maps.MapTypeId.ROADMAP

		};

		mapInit(mapOptions);

		bounds = new google.maps.LatLngBounds();

		icon = '//static.info-alberghi.com/images/markers/red.png';

		jQuery.each(clienti_json_obj, function (key, data) {
		        var latLng = new google.maps.LatLng(data.mappa_latitudine, data.mappa_longitudine);

		        var marker = new google.maps.Marker({
		            position: latLng,
		            map: map,
		            icon: icon,
		            title: data.nome
		        });

		        _markers.push(marker);

				bounds.extend(marker.getPosition());

		        var details = '<span class="nome_fumetto">' + data.nome + '</span><span class="stelle_fumetto">' + data.stelle.nome + '</span><div class="indirizzo_fumetto">' + data.indirizzo + ", " + data.localita.nome + "</div>";

					details += 	'<div class="immagine_fumetto"><a href="hotel.php?id='+ data.id +'" target="_blank"><img src="'+data.img_mappa_listing+'" style="width:100%;"/></a></div>';

					details += '<div class="prezzi_fumetto">' + dizionario.prezzo_min + ' <b>€ <span class="prezzo">' + data.prezzo_min + '</span></b> - ' + dizionario.p_max + ' <b>€ <span class="prezzo">' + data.prezzo_max + '</span></b></div>';

					if (__locale == 'it') {
						details  += '<div><a style="margin-left:10px;" class="btn btn-primary" href="hotel.php?id='+ data.id +'" target="_blank"> Vai alla scheda hotel </a></div>';
					}

					if (__locale == 'en') {
						details  += '<div><a style="margin-left:10px;" class="btn btn-primary" href="hotel.php?id='+ data.id +'" target="_blank"> Go to the hotel tab </a></div>';
					}

					if (__locale == 'fr') {
						details  += '<div><a style="margin-left:10px;" class="btn btn-primary" href="hotel.php?id='+ data.id +'" target="_blank"> Aller à l\'onglet de l\'hôtel </a></div>';
					}

					if (__locale == 'de') {
						details  += '<div><a style="margin-left:10px;" class="btn btn-primary" href="hotel.php?id='+ data.id +'" target="_blank"> Gehe zum Hotel-Tab </a></div>';
					}


		        // content
		        var _content = createContent(data,__locale);




		        bindInfoWindow(marker, map, infowindow, details, _content);




		});

		if(__count > 1){
			map.fitBounds(bounds);
		}


		addOwnPoi(map, infowindow, __own_poi_json);

	}


	window.resetMarkers = function () {

			for (var i = 0;  i <  _markers.length;  i++) {

			  _markers[i].setMap(null);
			}
			_markers = [];

		};


	window.createMarkers = function (__clienti_json) {

			var infowindow = new google.maps.InfoWindow();

			bounds = new google.maps.LatLngBounds();

			jQuery.each(__clienti_json, function (key, data) {

			        var latLng = new google.maps.LatLng(data.mappa_latitudine, data.mappa_longitudine);

			        var marker = new google.maps.Marker({
			            position: latLng,
			            map: map,
			            // icon: icon,
			            title: data.nome
			        });

			        _markers.push(marker);

					bounds.extend(marker.getPosition());

	        var details = data.nome + '<br/>' + data.indirizzo + ", " + data.categoria_id + ".";

	       	var _content = createContent(data);

	        bindInfoWindow(marker, map, infowindow, details, _content);


			});

			if(_markers.length > 1){
				map.fitBounds(bounds);
			}

		};


		function createContent(data, __locale)
			{
				//console.log('tmp_punti_di_forza_it = '+data.tmp_punti_di_forza_it);
			   var 		_content  = '<div id="mappa-ricerca">';
			         	_content += 	'<a href="hotel.php?id='+ data.id +'" target="_blank"><img src="'+data.img_mappa_listing+'" style="width:100%;"/></a>';

			        	_content += '<div class="boxtesto"><span class="testo">' + dizionario.prezzo_min + ' <b>€ <span class="prezzo">' + data.prezzo_min + '</span></b> - ' + dizionario.p_max + ' <b>€ <span class="prezzo">' + data.prezzo_max + '</span></b></span></div>';
			         	
			         	_content += 	'<div class="infowindow_container_content">';
			         	_content += 		'<h3 style="display:inline-block;"><a href="hotel.php?id='+ data.id +'" target="_blank">' + data.nome + '</a></h3><span class="rating" style="font-size:12px;">'+data.stelle.nome+'</span>';
			        	/* indirizzo */

			        	_content += 		'<address class="item-listing-address "><span class="localita"><i class="icon-location"></i>' + data.localita.nome + '</span> - <span  class="indirizzo">' +  data.indirizzo + '</span></address>';

			        	/* distanze */
			        	_content += 		'<div class="item-listing-distance ">';
			        	_content += 			'<a title="'+data.localita.centro_coordinate_note+'" class="tooltip"><span class="distance"><b>' + data.label_centro + '</b></span></a>';
			        	_content += 			'<span class="distance">'+ dizionario.spiaggia +': <b>m ' + data.distanza_spiaggia + '</b></span>&nbsp;&nbsp;';
			        	_content += 		'</div>';
			        	_content += 		'<div class="item-listing-distance" style="margin-bottom: 10px;">';
			        	_content += 			'<a title="'+data.localita.staz_coordinate_note+'" class="tooltip"><span class="distance">' + dizionario.stazione + ': <b>km ' + data.distanza_staz + '</b></span></a>&nbsp;&nbsp;';
			        	_content += 			'<a title="Fiera di Rimini" class="tooltip"><span class="distance">' + dizionario.fiera + ':	 <b>km '+ data.distanza_fiera +'</b></span></a>&nbsp;&nbsp;';
			        	_content += 		'</div>';


			        	/* punti di forza */
			        	if (__locale == 'it') {
			        		_content  += '<div><a class="btn btn-primary" href="hotel.php?id='+ data.id +'" target="_blank"> Vai alla scheda hotel </a></div>';
			        	}

			        	if (__locale == 'en') {
			        		_content  += '<div><a class="btn btn-primary" href="hotel.php?id='+ data.id +'" target="_blank"> Go to the hotel tab </a></div>';
			        	}

			        	if (__locale == 'fr') {
			        		_content  += '<div><a class="btn btn-primary" href="hotel.php?id='+ data.id +'" target="_blank"> Aller à l\'onglet de l\'hôtel </a></div>';
			        	}

			        	if (__locale == 'de') {
			        		_content  += '<div><a class="btn btn-primary" href="hotel.php?id='+ data.id +'" target="_blank"> Gehe zum Hotel-Tab </a></div>';
			        	}


			        	_content += 	'</div>';
			        	_content += '</div>';

		     return _content;
			}


		function bindInfoWindow(marker, map, infowindow, strDescription, _content) {

		    google.maps.event.addListener(marker, 'mouseover', function () {
		        infowindow.setContent(strDescription);
		        infowindow.open(map, marker);
		    });

		   /* 
			NON SI DEVE CHIUDERE On mouseout
		   	google.maps.event.addListener(marker, 'mouseout', function () {
		        infowindow.close();
		    });
		   */

        	google.maps.event.addListener(marker, 'click', function() {

    			$("#infowindow_container").html(_content).show();
    			$("#closeinfowindow_container").show();

    		});

		}


		function htmlDecode(input)
		{
		  var doc = new DOMParser().parseFromString(input, "text/html");
		  return doc.documentElement.textContent;
		}


		function addOwnPoi(map, infowindow, __own_poi_json) {


				/////////////////////////////////////////
				// AGGIUNGO I MIEI POI SEMPRE PRESENTI //
				/////////////////////////////////////////

			    icon = '//static.info-alberghi.com/images/markers/blue.png';


			    // Loop through our array of markers & place each one on the map
			    jQuery.each(__own_poi_json, function (key, data) {


					//console.log( data );

			    	var position = new google.maps.LatLng(data.lat, data.long);
			    	var title = data.poi_lingua[0].nome;
			    	var colore = data.colore;

			        marker = new google.maps.Marker({
			            position: position,
			            map: map,
			            icon: {
			            	url: icon,
			            	scaledSize: new google.maps.Size(26,30), //37x42
			            	labelOrigin: new google.maps.Point(15,40),
			            },
			            title: title,
			            label:  {
					    		color: colore,
					    		fontSize: '15px',
					    		fontWeight:'bold',
					    		text: title
					    		}
			        });


			     	//var info_titolo = htmlDecode(data.poi_lingua[0].info_titolo);
			        //var info_desc = htmlDecode(data.poi_lingua[0].info_desc);
			     	var info_titolo = data.poi_lingua[0].info_titolo;
			        var info_desc = data.poi_lingua[0].info_desc;

			        var infoWindowContent = '<div class="info_content"><h3>' +
			        						info_titolo +
			        						'</h3><p class="info_desc">' +
			        						info_desc +
			        						'</p></div>';

			        // Allow each marker to have an info window
			       	google.maps.event.addListener(marker, 'mouseover', (function(marker, infoWindowContent) {
			           return function() {
			               infowindow.setContent(infoWindowContent);
			               infowindow.open(map, marker);
			           };
			       	})(marker, infoWindowContent));


			       	google.maps.event.addListener(marker, 'click', function () {
			       	    infowindow.close();
			       	});

			    }); // loop own markers

		}

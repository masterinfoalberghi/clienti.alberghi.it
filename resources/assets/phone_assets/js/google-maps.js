
	// Setto la mappa

	var map;
	var bounds;
	var _markers = [];
		
	window.showMap = function () {
		
		$("body").addClass("openamp");
		$('body').addClass('stop-scrolling');
		$("#map_container").show();
		
	}
	
	window.hideMap = function () {
		
		map_open = false;
		$("body").removeClass("openamp");
		$("body").removeClass("stop-scrolling");
		$("#map_container").hide();
		
	}
	
	window.initialize = function() {
		
		// Mappa GMAPS
		// http://en.marnoto.com/2014/09/5-formas-de-personalizar-infowindow.html
	
		var mapOptions = {
			center: new google.maps.LatLng( $lat , $lon ),
			zoom: $zoom,
			maxZoom: 18,
			scrollwheel: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			//disableDefaultUI: true,
			gestureHandling: "greedy",
			streetViewControl: true,
			mapTypeControl: false,
			zoomControl:false,
			zoomControlOptions: {
	        	position: google.maps.ControlPosition.BOTTOM_RIGHT
	    	},
		};
	
		map = new google.maps.Map(document.getElementById("map"), mapOptions);
		
		var noPoi = [
			{
				featureType: "poi.business",
				stylers: [
					{ visibility: "off" }
				]
			}
		];
	
		map.setOptions({styles: noPoi});
		var infowindow = new google.maps.InfoWindow();
		var _marker;


		bounds = new google.maps.LatLngBounds();


		/*
		 * The google.maps.event.addListener() event waits for
		 * the creation of the infowindow HTML structure 'domready'
		 * and before the opening of the infowindow defined styles
		 * are applied.
		 */
		google.maps.event.addListener(infowindow, 'domready', function() {

		   // Reference to the DIV which receives the contents of the infowindow using jQuery
		   var iwOuter = $('.gm-style-iw');

		   /* The DIV we want to change is above the .gm-style-iw DIV.
		    * So, we use jQuery and create a iwBackground variable,
		    * and took advantage of the existing reference to .gm-style-iw for the previous DIV with .prev().
		    */
		   var iwBackground = iwOuter.prev();

		   // Remove the background shadow DIV
		   iwBackground.children(':nth-child(2)').css({'display' : 'none'});

		   // Remove the white background DIV
		   iwBackground.children(':nth-child(4)').css({'display' : 'none'});


		   // Moves the shadow of the arrow 76px to the left margin 
   			iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: 76px !important;'});

   			// Moves the arrow 76px to the left margin 
   			iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 76px !important;'});


   			// Changes the desired color for the tail outline.
			// The outline of the tail is composed of two descendants of div which contains the tail.
			// The .find('div').children() method refers to all the div which are direct descendants of the previous div. 
			iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(204, 204, 204, 0.6) 0px 1px 6px', 'z-index' : '1'});





		   // Taking advantage of the already established reference to
		   // div .gm-style-iw with iwOuter variable.
		   // You must set a new variable iwCloseBtn.
		   // Using the .next() method of JQuery you reference the following div to .gm-style-iw.
		   // Is this div that groups the close button elements.
		   var iwCloseBtn = iwOuter.next();

		   // Apply the desired effect to the close button
		   iwCloseBtn.css({
		     opacity: '1', // by default the close button has an opacity of 0.7
		     right: '45px', top: '46px', // button repositioning
		     color: '#fff',
		     height: '25px',
		     width:'25px',
		     overflow:'visible',
		     //background-image: 'url("http://appsavvy.net/close.png") !important';
		     });

		   iwCloseBtn.children('img').replaceWith( "<img src='//static.info-alberghi.com/mobile/img/cancel.svg' width='25px' height='25px'>" );


		   var iwCloseShadow = iwCloseBtn.next();

		   iwCloseShadow.css({
			  right: '40px', top: '40px', // button repositioning
			  });		



		   // The API automatically applies 0.7 opacity to the button after the mouseout event.
		   // This function reverses this event to the desired value.
		   iwCloseBtn.mouseout(function(){
		     $(this).css({opacity: '1'});
		   });





		});


		



		
		$(".risultati_ricerca .link_item_slot").each(function () {
			//console.log('NON sono una scheda hotel');
			var me = $(this);
			
			var img = me.find(".img-listing").attr("src");
			
			var nome = me.find("h2").text();
			var stelle = me.find(".rating").text();
			var macrolocalita = $.trim(me.find(".loc-listing").text());
			var categoria = me.data("categoria");
			var prezzo = me.data("prezzo")
			
			if (!prezzo)
				prezzo = 0;
			
			var id  = me.data("id");
			var lat = me.data("lat"); 
			var lon = me.data("lon"); 
			var url = me.data("url");
			
			if (!url && $viewnow) {
				url = $viewnow + id;
			}
			
			if (url !="") {
				
				var image = '//static.info-alberghi.com/images/markers/red.png';
				var image_on_click = '//static.info-alberghi.com/images/markers/blue.png';
				
				var _contentString = '';
				
				/*_contentString = '<a class="infowindow" href="'+url+'">'; 
					_contentString += '<img src="' + img + '" class="alignleft" width="150" height="102" alt="'+nome+'"><br><strong class="nomehotel">'+nome+'</strong> ' + stelle +'<br>' + macrolocalita;
				_contentString += '</a>';*/


				//////////////////////////////////////////////////////////////
				// non posso wrappare con una <a> se dentro c'è già una <a> //
				//////////////////////////////////////////////////////////////
				me.find("h2 a").contents().unwrap();
					
				_contentString = '<article class="mappa-mobile"><a href="'+url+'">' + me.html() + '</a></article>'; 
				
				var _point = new google.maps.LatLng(lat,lon);
				
				var _marker = new google.maps.Marker({
				    position: _point,
				    map: map,
				    icon: image
			    });
				
				_markers.push(_marker);

				google.maps.event.addListener(_marker, 'click', function() {
					
					infowindow.close();
					infowindow.setContent(_contentString);
					for (var j = 0; j < _markers.length; j++) {
			          _markers[j].setIcon(image);
			        }
					_marker.setIcon(image_on_click);
					infowindow.open(map,_marker);
					map.setCenter(_marker.getPosition());
					
				});



				google.maps.event.addListener(infowindow, 'closeclick', function() {  
					for (var j = 0; j < _markers.length; j++) {
			          _markers[j].setIcon(image);
			        }
				});


				
				bounds.extend(_marker.getPosition());
				
				_markers.push(_marker);	
				
			
			} else if (lat != "" && lon != "" && prezzo > 0) {
				
				var _contentString = '';
								_contentString = '<a class="infowindow" href="'+url+'">'; 
									_contentString += '<img src="' + img + '" class="alignleft" width="220" height="150" alt="'+nome+'"><br><strong class="nomehotel">'+nome+'</strong> ' + stelle +'<br>' + macrolocalita + '<br><strong class="vedilink">'+$vedi+'</strong>';
								_contentString += '</a>'; 


				var image = '//static.info-alberghi.com/images/marker/red.png';
				
				var _marker = new google.maps.Marker({
			 		position: new google.maps.LatLng(lat, lon),
			 		animation: google.maps.Animation.DROP,
			 		map: map,
			 		icon: image
			 	});
				
				google.maps.event.addListener(_marker, 'click', function() {
						
					infowindow.close();
					infowindow.setContent(_contentString);
					infowindow.open(map,_marker);
					
				});
				
				bounds.extend(_marker.getPosition());
				_markers.push(_marker);	
				
			}
			
		});
		
		map.fitBounds(bounds);
		
		if (!$(".page-stradario-piazza").length)					
	    window.showMap();
	    
    }; // end initialize
    
// init


    


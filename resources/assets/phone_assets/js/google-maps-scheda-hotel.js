
	// Setto la mappa

	var map;
	var bounds;
	var _markers = [];

	function CustomMarker(latlng, map, args) {
		this.latlng = latlng;	
		this.args = args;	
		this.setMap(map);	
	}
		
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
				
		// Custom MARKER
		CustomMarker.prototype = new google.maps.OverlayView();
		CustomMarker.prototype.draw = function() {
		
			var self = this;
			var div = this.div;
			
			if (!div) {
				
				div = this.div = document.createElement('div');
				
				if (typeof(self.args.categoria) !== 'undefined') {
					var categoria = self.args.categoria;
				}
													
				if (typeof(self.args.id) !== 'undefined') {
					div.dataset.marker_id = self.args.id;
				}
				
				if (typeof(self.args.prezzo) !== 'undefined') {
					div.innerHTML = self.args.prezzo;
				} else {
					div.innerHTML = "50";
					self.args.prezzo = 50;
				}
				
				div.className = 'marker categoria-' + categoria;					

				var panes = this.getPanes();
				panes.overlayImage.appendChild(div);
				
				google.maps.event.addDomListener(div, "click", function(event) {			
					google.maps.event.trigger(self, "click");
					$(div).hide();
				});
			}
			
			var point = this.getProjection().fromLatLngToDivPixel(this.latlng);
	
			if (point) {
				div.style.left = point.x + 'px';
				div.style.top =  point.y + 'px';
			}
		
		}
		CustomMarker.prototype.remove = function() {
			if (this.div) {
				this.div.parentNode.removeChild(this.div);
				this.div = null;
			}	
		};
		CustomMarker.prototype.nascondi = function() {
			if (this.div) {
				$(this.div).hide();
			}
		};
		CustomMarker.prototype.mostra = function() {
			if (this.div) {
				$(this.div).show();
			}
		};
		CustomMarker.prototype.close = function() {
			if (this.div) {
				$(this.div).show();
			}
		};
		CustomMarker.prototype.getPosition = function() {
			return this.latlng;	
		};
	
		/*	 ----   */
		
		// Mappa GMAPS
	
		var mapOptions = {
			center: new google.maps.LatLng( $lat , $lon ),
			zoom: 13,
			maxZoom: 14,
			scrollwheel: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
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
		
		$(".risultati_ricerca .link_item_slot").each(function () {

			var me = $(this);
			var img = $img
			var nome = $nome;
			var stelle = $rating;
			var macrolocalita = $loc;

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
			
		
			var _contentString = '';
							_contentString = '<a class="infowindow">'; 
								_contentString += '<img src="//static.info-alberghi.com/' + img + '" class="alignleft" width="120" height="80" alt="'+nome+'"><br><strong class="nomehotel">'+nome+'</strong> ' + stelle +'<br>' + macrolocalita + '<br>';
							_contentString += '</a>'; 


			var image = '//static.info-alberghi.com/images/marker/red.png';
			
			var _marker = new google.maps.Marker({
		 		position: new google.maps.LatLng(lat, lon),
		 		animation: google.maps.Animation.DROP,
		 		map: map,
		 		icon: image
		 	});
			
			google.maps.event.addListener(_marker, 'click', function() {
				
				infowindow.setContent(_contentString);
				infowindow.open(map,_marker);
				
			});
			infowindow.setContent(_contentString);
			infowindow.open(map,_marker);

				
			google.maps.event.addListener(infowindow,'closeclick',function(){
				
				infowindow.close();
				
			});

			
			
			bounds.extend(_marker.getPosition());
			_markers.push(_marker);	
				
			
			
		});
		
		
		map.fitBounds(bounds);
		
								
	    window.showMap();
	    
    }; // end initialize
    
// init


    


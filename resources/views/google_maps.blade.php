@if(!empty($google_maps))

	<div style="position:fixed; top:-999999; left:-9999999">
		<div id="map"></div>
	</div>

  <script type="text/javascript">

	var map;
	var bounds;
	var _markers = [];
	
	function CustomMarker(latlng, map, args) {
		this.latlng = latlng;	
		this.args = args;	
		this.setMap(map);	
	}
	
	
	function resizeMap() {
		
		$("#map").height($(".fancybox-inner").height());
		$("#map").width($(".fancybox-inner").width());
		
		if (map && bounds) {
			google.maps.event.trigger(map, "resize");
			map.fitBounds(bounds);
		}
		 
	}
		
	function initialize() {
		
		
				
		CustomMarker.prototype = new google.maps.OverlayView();
		CustomMarker.prototype.draw = function() {
		
			var self = this;
			var div = this.div;
			
			if (!div) {
				
				var RegExpString = /([0-9]+)/g;
				var id_categoria = RegExpString.exec(self.args.image)[0];				
				
				div = this.div = document.createElement('div');
				div.className = 'marker categoria-' + id_categoria;					
													
				if (typeof(self.args.marker_id) !== 'undefined') {
					div.dataset.marker_id = self.args.marker_id;
				}
				
				if (typeof(self.args.prezzo_min) !== 'undefined') {
					div.innerHTML = self.args.prezzo_min;
				} else {
					div.innerHTML = "50";
					self.args.prezzo_min = 50;
				}
				
				var panes = this.getPanes();
				panes.overlayImage.appendChild(div);
				
				google.maps.event.addDomListener(div, "click", function(event) {			
					google.maps.event.trigger(self, "click");
					div.style.visibility = "hidden";
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
				this.div.style.visibility = "hidden";
			}
		};
		
		CustomMarker.prototype.mostra = function() {
			if (this.div) {
				this.div.style.visibility = "visible";
			}
		};
		
		CustomMarker.prototype.close = function() {
			if (this.div) {
				this.div.style.visibility = "visible";
			}
		};
		CustomMarker.prototype.getPosition = function() {
			return this.latlng;	
		};
	
		/*	 ----   */
	
		var mapOptions = {
			center: new google.maps.LatLng({{$google_maps["coords"]['lat']}},{{$google_maps["coords"]['long']}}),
			zoom: {{$google_maps["coords"]['zoom']}},
			scrollwheel: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoomControlOptions: {
	        	position: google.maps.ControlPosition.TOP_RIGHT
	    	},
		};
	
		map = new google.maps.Map(document.getElementById("map"), mapOptions);
		
		/*map.addListener('zoom_changed', function() {
			nascondiMarker();
		});*/
		
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
		
		bounds = new google.maps.LatLngBounds();
		
		
		
        @if(!empty($google_maps["hotels"]))
        	
         	@foreach ($google_maps["hotels"] as $cliente)
                
                @if($cliente->prezzo_min >0)
				// se SONO un VOT 
				@if (isset($cliente->pagina_id))
				<?php $cliente = $cliente->offerta->cliente ?>
				@endif
				
				// se è uno slot uso il cliente eagerloadato
				@if (!is_null($cliente->vetrina_id))
				<?php
				$slot = $cliente;
				$cliente = $slot->cliente; 
				?>
				@endif
				
				<?php $img_src = $cliente->getListingImg('220x148', true); ?>
					
					var image_{{$cliente->id}} = '<?= Utility::asset("images/map_icons/$cliente->categoria_id.gif") ?>';
					var contentString_{{$cliente->id}} = '<a href="{{url(Utility::getLocaleUrl($locale).'hotel.php?id='.$cliente->id)}}"><img src=\'{{ Utility::asset("$img_src") }}\' class="alignleft" width="220" height="150" alt="{{{$cliente->nome}}}"><br><strong class="nomehotel">{{{ $cliente->nome }}}</strong> {{{ $cliente->stelle->nome }}} <br> {{{$cliente->indirizzo}}} <br> {{{ $cliente->cap }}} - {{{ $cliente->localita->macrolocalita->nome.' ('. $cliente->localita->prov . ')' }}}<br><strong class="vedilink">{{ trans("listing.vedi_hotel") }}</strong></a>';
					var point = new google.maps.LatLng(<?=$cliente->mappa_latitudine.','.$cliente->mappa_longitudine?>);
					
					//console.log('{{$cliente->nome}}' , '{{$cliente->prezzo_min}}');
					
					marker_{{$cliente->id}} = new CustomMarker(
						point,
						map,
							{
								nome: '{{$cliente->nome}}',
								marker_id: '{{$cliente->id}}',
								prezzo_min: '{{$cliente->stelle->nome}}<br/>{{$cliente->prezzo_min}} &euro;',
								prezzo_marker: '{{$cliente->prezzo_min}}',
								categoria_id: '{{$cliente->categoria_id}}',
								image: '<?= Utility::asset("images/map_icons/$cliente->categoria_id.png") ?>'
							}
					);
					
					google.maps.event.addListener(marker_{{$cliente->id}}, 'click', function() {
						infowindow.close();
						infowindow.setContent(contentString_{{$cliente->id}});
						infowindow.open(map,marker_{{$cliente->id}});
					});
					
					google.maps.event.addListener(infowindow,'closeclick',function(){
						marker_{{$cliente->id}}.close();
					});
					
					bounds.extend(marker_{{$cliente->id}}.getPosition());
					_markers.push(marker_{{$cliente->id}});	
					@endif
                
             @endforeach
            
        @endif
        
        //map.fitBounds(bounds);
        
		var markerCluster = new MarkerClusterer(map, _markers, {
			gridSize:50,
				minimumClusterSize: 3,
				calculator: function(markers, numStyles) {
				return {
					text: markers.length,
					index: numStyles
				};
			}
		});
        
    } // end initialize
    
    /*function nascondiMarker() {
	    
	   var zoom = map.getZoom();
	   var n = 20
	   if (zoom >= 20) 
	   		n = _markers.length;
   	   else if (zoom >= 18 && zoom < 20)
   	   		n = Math.ceil(_markers.length * .75);
   	   else if (zoom >= 16 && zoom < 18)
   	   		n = Math.ceil(_markers.length * .50);
   	   else if (zoom < 16)
   	   		n = Math.ceil(_markers.length * .25);
   	   
   	   console.log(n);
   	   
	    var i = 0;
	    
	    for (i = 0; i < _markers.length; ++i) {
		    
		   
		    
		    if (i<n) {
			   _markers[i].mostra();
			   
		    } else {
			   _markers[i].nascondi();
		    }
		 
	    }
	    
	    
	    
    }*/

  </script>
@endif
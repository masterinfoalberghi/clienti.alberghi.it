@if(!empty($google_maps))
  <script type="text/javascript">

      var map;
	  var bounds;
	  
	  function CustomMarker(latlng, map, args) {
			this.latlng = latlng;	
			this.args = args;	
			this.setMap(map);	
		}
	  
      function initialize() {
        	
        	CustomMarker.prototype = new google.maps.OverlayView();
			CustomMarker.prototype.draw = function() {
			
				var self = this;
				var div = this.div;
				
				if (!div) {
				
					div = this.div = document.createElement('div');
					div.className = 'marker';
										
					div.style.position = 'absolute';
					div.style.cursor = 'pointer';
					div.style.width = '56px';
					div.style.height = '36px';
					div.style.textAlign = 'center';
					div.style.padding= '13px 0 0';
					div.style.color = "#fff";
					div.style.fontWeight = "bold";
					div.style.backgroundImage = 'url('+self.args.image+')';
									
					if (typeof(self.args.marker_id) !== 'undefined') {
						div.dataset.marker_id = self.args.marker_id;
					}
					
					if (typeof(self.args.prezzo_min) !== 'undefined') {
						div.innerHTML = self.args.prezzo_min;
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
					div.style.left = (point.x - 28) + 'px';
					div.style.top = (point.y - 36) + 'px';
				}
			
			};
			
			CustomMarker.prototype.remove = function() {
				if (this.div) {
					this.div.parentNode.removeChild(this.div);
					this.div = null;
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

          var noPoi = [
          {
              featureType: "poi.business",
              stylers: [
                { visibility: "off" }
              ]
            }
          ];

          map.setOptions({styles: noPoi});
          
		  bounds = new google.maps.LatLngBounds();
          var infowindow =  new google.maps.InfoWindow();

          @if(!empty($google_maps["hotels"]))
            @foreach ($google_maps["hotels"] as $cliente)
                

                {{-- se SONO un VOT --}}
                @if (isset($cliente->pagina_id))
                  <?php $cliente = $cliente->offerta->cliente ?>
                @endif

                {{-- se è uno slot uso il cliente eagerloadato--}}
                                
                @if (!is_null($cliente->vetrina_id))
                  <?php
                   $slot = $cliente;
                   $cliente = $slot->cliente; 
                   ?>
                @endif

                <?php $img_src = $cliente->getListingImg('96x65', true); ?>
      
                 var image_{{$cliente->id}} = '<?= Utility::asset("images/map_icons/$cliente->categoria_id.gif") ?>';
					var contentString_{{$cliente->id}} = '<a href="{{url(Utility::getLocaleUrl($locale).'hotel.php?id='.$cliente->id)}}"><img src=\'{{ Utility::asset("$img_src") }}\' class="alignleft" width="220" height="150" alt="{{{$cliente->nome}}}"><br><strong class="nomehotel">{{{ $cliente->nome }}}</strong> {{{ $cliente->stelle->nome }}} <br> {{{$cliente->indirizzo}}} <br> {{{ $cliente->cap }}} - {{{ $cliente->localita->macrolocalita->nome.' ('. $cliente->localita->prov . ')' }}}<br><strong class="vedilink">{{ trans("listing.vedi_hotel") }}</strong></a>';
					 
                    var point = new google.maps.LatLng(<?=$cliente->mappa_latitudine.','.$cliente->mappa_longitudine?>);
					//bounds_point.push(['<?=$cliente->mappa_latitudine ?>','<?= $cliente->mappa_longitudine?>']);
                    
                    
					marker_{{$cliente->id}} = new CustomMarker(
						point,
						map,
						{
							marker_id: '{{$cliente->id}}',
							prezzo_min: '{{$cliente->prezzo_min}} &euro;',
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

            @endforeach
          @endif
		  
		   google.maps.event.addListenerOnce(map, 'idle', function() {
		    map.fitBounds(bounds);
		});
		
		 
		  map.fitBounds(bounds);

      } // end initialize

    

  </script>
@endif
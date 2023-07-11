$(function() {
		
	function tick() {
 
		var sr = $(window).scrollTop();
		var bd = $("body");

		if (sr >= 76 && !bd.hasClass("sticked")) {
			bd.addClass("sticked")

		} else if (sr < 76 && bd.hasClass("sticked")) {
			bd.removeClass("sticked")
		}

	}
	
	$(window).on("scroll", tick);
	
	/**
	 * Mappa
	 */

	 window.initMap = function () {

	 	var styles = {
	 		hide: [
	 		  {
	 			featureType: 'poi.business',
	 			stylers: [{visibility: 'off'}]
	 		  },
	 		  {
	 			featureType: 'transit',
	 			elementType: 'labels.icon',
	 			stylers: [{visibility: 'off'}]
	 		  }
	 		]
	 	  };
	 	
	 	map = new google.maps.Map(document.getElementById('gmap'), {
	 		center: {lat: 44.053458, lng: 12.5396677},
	 		zoom: 13,
	 		styles: styles['hide']
	 	});
	 	
	 	var bounds = new google.maps.LatLngBounds();
	 		
	 	$("table td[data-coords]").each(function () {
	 		
	 		var me = $(this);
			var piante = me.parent().find("td[data-num]");
			var state = me.parent().data("state");
			var year = me.closest("table").data("filter");
			
	 		var year = me.closest("table").data("filter");
	 		var icons = "//static.info-alberghi.com/greenbooking/img/marker_" + year + ".png";
	 		var coords = me.data("coords").split(",");
	 		var latLng = new google.maps.LatLng(coords[0],coords[1]);
	 		
			var contentString = "<h4>" + piante.html() + " Piantumazioni</h4><p style='line-height:22px;'>" + me.html() + "<br />" + state + " (" + year + ")</p>";
			
			var infowindow = new google.maps.InfoWindow({
				content: contentString
		    });
			
			var marker = new google.maps.Marker({position: latLng, map: map, icon: icons});
			marker.addListener('click', function() {
          		infowindow.open(map, marker);
        	});
	 		bounds.extend(latLng);
	 		
	 	});
	 	
	 	google.maps.event.addListener(map, "click", function (event) {
			
	 		var latitude = event.latLng.lat();
	 		var longitude = event.latLng.lng();
	 		console.log( latitude + ', ' + longitude );
			
	 	}); //end addListener
	 	
	 	map.fitBounds(bounds);

	 }
		
	/**
	 * Gallery 
	 */
	
	$('.gallery-items').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 1,
		lazyLoad: 'ondemand',
		centerMode: true,
		variableWidth: true,
		prevArrow: '<button type="button" class="slick-arrow slick-prev"><i class="icon-left-open"></i></button>',
		nextArrow: '<button type="button" class="slick-arrow slick-next"><i class="icon-right-open"></i></button>',
		responsive: [
			
		    {
		      
			  breakpoint: 1200,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1,
				variableWidth: false,
				centerPadding: '0px',
		      }
			  
		  	}
		]
	});

/**
* Menu Mobile
*/

	var apriChiudiMenu = function() {
		var open = $("body").hasClass("menuOpened");
		/* toglie o inserisce menu in base allo stato */
		open ? $("body").removeClass("menuOpened") : $("body").addClass("menuOpened");
		/* cambia icona menu */
		$("#menu-button").find("i")
			.removeClass(open ? "icon-cancel" : "icon-menu" )
			.addClass(open ? "icon-menu" : "icon-cancel" ); 
	}
	/* apre o chiude menu se clicchi sul bottone o sugli item lista */
	$("#menu-button").click(function () { apriChiudiMenu(); }); 
	$("#menu-principale").find("ul").click( function() { apriChiudiMenu(); } ); 

	/**
	 * Filtri
	 */
	
	function filterTestimonials() {
		
		var filtro = $("#testimonials .button-filter .btn.selected").data("filter");
		$("#testimonials table").hide();
		$("#testimonials table[data-filter='"+filtro+"']").show();
				
	}
	
	function filterGallery() {
		
		var filtroA = [];
		var filtro = "";
		
		$("#gallery .button-filter .btn").each(function () {
			
			var me = $(this);
			if (me.hasClass("selected"))
				filtroA.push(me.data("filter"));
				 
		})
		
		console.log(filtroA.join());
		
		if (filtroA.length > 0) {
			filtro = filtroA.join();
		} else {
			filtro = "";
		}
			
		$('#gallery .gallery-items').slick('slickUnfilter');
		
		if (filtro != "") {
			
			$("#gallery figure").each(function () {
				
				var me = $(this);
				var filtri = me.data("filter");
							
				if (filtri.indexOf(filtro)!=-1) {
					me.addClass("visible");
				} else {
					me.removeClass("visible");
				}
				
			});
			
		} else {
			
			$("#gallery figure").addClass("visible");
			
		}	
		
		$('#gallery .gallery-items').slick('slickFilter','.visible');
		 	
	}
	
	$(".button-filter .btn").click(function () {
		
		var me = $(this);
		var filtro = "";
		
		if (me.hasClass("selected")) {
			
			me.removeClass("selected");
			
		} else {
			
			me.parent().find("li").removeClass("selected");
			me.addClass("selected");
						
		}
		
		
		
		if (me.parent().data("referer") == "gallery") {
			filterGallery();
		} else {
			filterTestimonials();
		}
		
	});
	
});
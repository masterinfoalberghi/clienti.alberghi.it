		
	function click_all() {
		
		$(".click_all").unbind("click");
		$(".click_all").bind("click",  function (e) {
						
			var me = $(this);
			var url = me.find("a").eq(0).attr("href"); 
			var hit = $(e.target);
           
            if (hit.prop("tagName") == "I") {
                
                e.preventDefault();
                                
            } else if (hit.closest('a').length == 0 && hit.closest('button').length == 0 && hit.prop("tagName") != "A" && hit.prop("tagName") != "INPUT" && hit.prop("tagName") != "LABEL") {
				 
				e.preventDefault();
				document.location.href = url;
				
			} else {
				
				var dataHref = hit.closest('button').data("href");
				if (dataHref) {
					
					e.preventDefault();
					document.location.href = dataHref;
					
				}
				
			}
			
		});
		
	}

	function select_current() {

		$("#sidebar a").each(function () {

			var a = $(this);

			if (a.attr("href") == window.location.href ) {
				a.closest("li").addClass("current");
			}

		});

	}
	
	function tick() {
 
		var sr = $(window).scrollTop();
		var wd = $(window).width();
		var bd = $("body");

		if (wd >= 992) {

			if (sr >= 76 && !bd.hasClass("stuck_menu")) {
				bd.addClass("stuck_menu")

			} else if (sr < 76 && bd.hasClass("stuck_menu")) {
				bd.removeClass("stuck_menu")
			}

			/**
			 * Per i filtri calcolo anche quando mandarlo via
			 */

			var h = $("#content-listing").height();

			if (sr >= 214 && sr < (h+214) && !bd.hasClass("stuck_filter")) {

				bd.addClass("stuck_filter");
				//$(".filters-container").fadeIn();

			} else if (sr < 214 && bd.hasClass("stuck_filter")) {

				bd.removeClass("stuck_filter");

			} else if (sr > (h+214) && bd.hasClass("stuck_filter")) {
				
				bd.removeClass("stuck_filter");
				//$(".filters-container").fadeOut();

			}

		} else {

			bd.removeClass("stuck_menu").removeClass("stuck_filter");

		}

	}

	$(function() {
		
		/**
		 * Click su tutti i div
		 */
		
		click_all();
		select_current();
		
		/**
		 * Menu lingua e tolltip
		 */
		
		Tipped.create("#changeLanguageButton", $("#changeLanguageLink")[0]);
		Tipped.create(".tooltip");
       

		/**
		 * Sticky orizzontali
		 */

		if ($("body.desktop").length) {

			$(window).on("touchmove", tick);
	      	$(window).on("scroll", tick);
	      	$(window).on("resize", tick);
	      	
	    }

		
		
	});

	

	
	
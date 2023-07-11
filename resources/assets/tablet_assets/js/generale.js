
	var vpwidth = 768;
	var vlwidth = 1024; 

	$(function() {

		function closeMenuRight() {

			$("body").removeClass("menuopen").removeClass("menuopen2");
			$(".menubutton").find("i").attr("class", "icon-menu");

		}

		function click_all() {
		
			$(".click_all").unbind("click");
			$(".click_all").bind("click",  function (e) {
							
				var me = $(this);
				var url = me.find("a").eq(0).attr("href"); 
				var hit = $(e.target);
				
				if (hit.closest('a').length == 0 && hit.closest('button').length == 0 && hit.prop("tagName") != "A" && hit.prop("tagName") != "INPUT" && hit.prop("tagName") != "LABEL") {
					
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

		/**
		 * hack per dimensione pagina
		 */

		$(window).resize( function (e) {
			
			click_all();
			select_current();
			
			$("#page").removeClass("animation");
			$("#page").width( $(window).width() );

			var swidth = $(window).width()
			var sheight = $(window).height()
			var viewport = document.querySelector("meta[name=viewport]");

			$("body")
				.removeClass("portrait")
				.removeClass("landscape");
				
			closeMenuRight();

			if (swidth < sheight && !$("body").hasClass("portrait")) {

				$("body")
					.removeClass("portrait")
					.removeClass("landscape");

				$("body").addClass("portrait");
				viewport.setAttribute('content', 'width=' + vpwidth + ', initial-scale=1, maximum-scale=1.0;');

			} else if (swidth > sheight && !$("body").hasClass("landscape")) {

				$("body")
					.removeClass("portrait")
					.removeClass("landscape");

				$("body").addClass("landscape");
				viewport.setAttribute('content', 'width=' + vlwidth + ', initial-scale=1, maximum-scale=1.0;');

			}

		}).trigger("resize");

		$("ul.menu-macrolocalita").addClass("padding-bottom-2");

		/**
		 * Valido solo per il menu verticale 
		 */

		$("ul.menu-macrolocalita i").click(function () {

			var $ul = $(this).parent().find("ul");
			var $li = $(this).parent();
			var $i = $(this);
			
			if ($ul.hasClass("open")) {
				
				$li.removeClass("hover");
				$ul.slideUp();
				$ul.removeClass("open")
				$i.removeAttr("class").addClass("icon-down-open");
				
			} else {
				
				$li.removeClass("hover");
				$ul.slideDown();
				$ul.addClass("open");
				$i.removeAttr("class").addClass("icon-cancel");	
					
			}
			
		});
		
		/**
		 * Menu
		 */
		 
		$(".tablet #contact-button").click(function (e) {
			
			e.preventDefault();

			$("body").removeClass("sidebar-menuopen");
			$(".sidebar-button").find("i").attr("class", "icon-menu");
			$("#page").addClass("animation");

			if ($("body").hasClass("menuopen") || $("body").hasClass("menuopen2")) {
				
				closeMenuRight();
				
			} else {
				
				$("body").removeClass("menuopen").addClass("menuopen2");
				$('.menubutton').find("i").attr("class", "icon-cancel");
				
			}

		});

		$(".menubutton").click(function (e) {
		  
			e.preventDefault();
			$("body").removeClass("sidebar-menuopen");
			$(".sidebar-button").find("i").attr("class", "icon-menu");
			$("#page").addClass("animation");

			if ($("body").hasClass("menuopen") || $("body").hasClass("menuopen2")) {
				
				closeMenuRight();
				
			} else {
				
				$("body").removeClass("menuopen2").addClass("menuopen");
				$(this).find("i").attr("class", "icon-cancel");
				
			}
			
		});
		
		/**
		 * Se ho una sidebar
		 */

		if ($(".class-cms-pagina-listing #sidebar").length) {

			/**
			 * Controllo i menu nelle varie rotazione del tablet
			 */
		
			breakpointChange.breakpoints = {
				"sm": 0,
				"md": 1023,
				"lg": 1199
			}

			/**
			 * Metto a posto il menu laterale
			 */

			 var html  = '<ul class="menu-macrolocalita">';
			 var macro = $(".menu-macrolocalita li.current>a").html();
			 var link = $(".menu-macrolocalita li.current>a").attr("href");
			 var micro = $(".menu-macrolocalita li.current-micro").html();

			html += '<li class="current"><a href="'+link+'">' + macro +"</a>";

			if (micro) {
				html += '<ul class="menu-microlocalita open "><li class="current-micro" ">' + micro + "</li></ul></li>";
			} else {
				html += '</li>';
			}

			html += '</ul><div class="center padding-bottom"><a id="cambialocalita" class="btn btn-small btn-verde">'+dizionario.cambia_localita+'</a></div>';

			$("#content-link").html( html );

			$("#cambialocalita").click(function (e) {

				e.preventDefault();
				$("#content-link").slideUp(250, function () {
					$("#content-menu #menu-macrolocalita").slideDown();
				});
			});

			$(window).on("breakpointChange", function (e) {
				
				var $sidebar;

				var _old = e.oldBreakpoint;
				var _new = e.newBreakpoint;

				if (_old == undefined) {

					var _cur = breakpointChange.getCurrentBreakpoint();

					if (_cur == "sm") {

						// attivo subito il cambio menu
						_old = "md";
						_new = "sm";
					
					} else {

						// li metto uguali per non fare niente
						_old = "md";
						_new = "md";
						
					}

				}
				
				// Sto passando alla versione portrait
				if (_new == "sm" && _old != "sm") {
					
					// metto il menu verde sotto il menu laterale
					$sidebar = $("#sidebar").detach();
					$sidebar.appendTo("#menu-principale #content-menu");
					$("#content-menu #menu-macrolocalita").hide();
					$("#content-link").show();

				// Sto passando alla versione landscape
				} else if ( _new != "sm" && _old == "sm") {
					
					// metto il menu verde al suo posto
					$sidebar = $("#sidebar").detach();
					$sidebar.appendTo(".sticker-sidebar");
					$("#content-menu #menu-macrolocalita").show();
					$("#content-link").hide();

				}

			}).trigger("breakpointChange");
		}
		
		
			
	});
	
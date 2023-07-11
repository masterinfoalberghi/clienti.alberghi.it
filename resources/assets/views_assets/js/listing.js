
	var console=console?console:{log:function(){}}
    
    /**
	 * Controlla i check nella lista
	 */
	 
	window.whenChecked = function ( $alert ) { 
		
		all_id = [];  
		ids_send_mail_arr = [];
		
		// Ciclo tutti gli slot
		$(".item-listing").each(function () {
			
			var me = $(this);
			all_id.push(me.data("id"));
			
			if ($("#checkbox_" + me.data("id")).is(':checked')) {
				ids_send_mail_arr.push(me.data("id"))
			}
			
		});
		
		$("#ids_send_mail").val(ids_send_mail_arr.toString());
		$("#ids_send_mail_compare").val(ids_send_mail_arr.toString());
		
		if (ids_send_mail_arr.toString() == "") {
			
			$("#CompareSubmit").prop("disabled", "disabled");
			$("#ids_send_mail").val(all_id);

		} 

		if ( $alert !== false ) {
		
			if (ids_send_mail_arr.length > 25) {
				alert('seleziona al massimo 25 strutture !')
				return false;	
			};
		
		}

		checkedCount();
			
	}
	
	window.whenChecked(false);

       /**
     * Attiva i filtri.
     * 
     * @access public
     * @return void
     */
    
    function resetFilter() {
    	
		$(".filter").each(function () {

			var $me = $(this);
			var $data = $(this).attr("data-item");
	   		
	   		$me.removeClass("selected");
	   		
	   		if ($me.hasClass("order")) {
	   			$me.html( $data );
	   		} else {
	   			$me.html( $data + '<i class="icon-down-open"></i>' );
	   		}

	   	})

    }

    function attivaFiltri () {
	   	
        $(".filter").click( function (e) { e.preventDefault(); });
        
        $(".filter.name_order, .filter.no_order").click(function (e) {
         
         e.preventDefault();
         
         var $me = $(this);
         var order = "";
         
         resetFilter();

         $me.addClass("selected");
         order = $(".filter.selected").data("href");
         ajaxOrderListing($(".filter.order.selected").attr("href"));
         
     });
     
    }

    /**
     * carica il listing nell'ordine scelto.
     * 
     * @access public
     * @param mixed order
     * @return void
     */
     
    function ajaxOrderListing (href) { 
		
		$(".loading-ajax").show();
		 
		$.ajax({ 
			
			url: href,
			type: 'GET',
			success: function(msg) {
				
				$(".listing-ajax").html($(msg).find(".listing-ajax").html() );
				attivaCheckbox();
				attivaClickPreferiti();
				click_all();
				Tipped.create(".tooltip");
				$('.lazy').lazy();

				$(window).scrollTop(0);

				$(".alert a").click(function (e) { 	
		    		location.reload();
		    	});
				
			}
		
		});
	
    }

    /**
     * carica il listing filtrato
     * 
     * @access public
     * @param mixed order
     * @return void
     */
     
    function ajaxFilterListing (href) { 
		
		$(".loading-ajax").show();
		 
		$.ajax({ 
			url: href,
			type: 'GET',
			success: function(msg) {
				$(".listing-ajax").html($(msg).find(".listing-ajax").html() );
				attivaCheckbox();
				attivaClickPreferiti();
				click_all();
				Tipped.create(".tooltip");
				$('.lazy').lazy();

				$(window).scrollTop(0);

				$(".alert a").click(function (e) { 
		    		location.reload();
		    	});
				
			}
		
		});
	
    }
    
 
    function category_filter(object) {
	    
	    Tipped.hideAll();
	    
	    var $me = $(object);
		var order = $me.data("href");

	    resetFilter();

		$(".filter.categoria_desc")
			.addClass("selected")
			.html($me.html() + '<i class="icon-down-open"></i>');
				
		ajaxOrderListing($me.attr("href"));
		
    }
     
    function price_filter(object) {
	    
	    Tipped.hideAll();
	    
	    var $me = $(object);
		var order = $me.data("href");
	    
	    resetFilter();
	    
		$(".filter.price_min")
			.addClass("selected")
			.html($me.html() + '<i class="icon-down-open"></i>');
				
		ajaxOrderListing($me.attr("href"));
		
    }

    function discount_filter(object) {
	    
	    Tipped.hideAll();
	    
	    var $me = $(object);
		var order = $me.data("href");
	    
	    resetFilter();
	    
		$(".filter.discount_min")
			.addClass("selected")
			.html($me.html() + '<i class="icon-down-open"></i>');
				
		ajaxOrderListing($me.attr("href"));
		
    }
    
    function apertura_filter(object) {

    	Tipped.hideAll();
		var $me = $(object);
		var order = $me.data("href");

		resetFilter();
		
		$(".filter.filtri_apertura")
			.addClass("selected")
			.html($me.html() + '<i class="icon-down-open"></i>');

		ajaxFilterListing($me.attr("href"));

    }
    
    /**
     * Ritona il numero di slot checcati.
     * 
     * @access public
     * @return void
     */
     
    function checkedCount () {
	
		var n = $(".item-listing-checkbox:checked").length;
		
		if (n==0) {
			
			$("#CompareSubmit").prop("disabled", "disabled");
			$("#WishlistSubmit")
				.prop("disabled", "")
				.removeClass("btn-deep")
				.addClass("btn-arancio")
				.html('<i class="icon-mail-alt"></i> ' + dizionario.writeall )
			
		} else if (n == 1 ) {
			
			$("#CompareSubmit").prop("disabled", "disabled");
			$("#WishlistSubmit").prop("disabled", "disabled");
				// .removeClass("btn-arancio")
				// .addClass("btn-deep")
				// .html('<i class="icon-ok"></i> ' + dizionario.writeselected )

		} else if (n > 1 && n < 4 ) {
		
			$("#CompareSubmit").prop("disabled", "");
			$("#WishlistSubmit")
				.prop("disabled", "")
				.removeClass("btn-arancio")
				.addClass("btn-deep")
				.html('<i class="icon-ok"></i> ' + dizionario.writeselected )

		} else {
			
			$("#CompareSubmit").prop("disabled", "disabled");
			$("#WishlistSubmit")
				.prop("disabled", "")
				.removeClass("btn-arancio")
				.addClass("btn-deep")
				.html('<i class="icon-ok"></i> ' + dizionario.writeselected )

		}
		
		return n;

	}
    
    /**
     * Attiva il checkbox del listing e i bottoni della barra
     * 
     * @access public
     * @return void
     */
     
    function disattivaCheckbox () {
	    $('.item-listing-checkbox').unbind("change");
    }

    /**
     * Attiva il checkbox del listing e i bottoni della barra
     * 
     * @access public
     * @return void
     */
     
    function attivaCheckbox () {
	    
	    disattivaCheckbox();
	    
	    $('.item-listing-checkbox').bind("change", function(e) {
		
			checkedCount();
				
		});
	    
    }

    /**
     * Disattiva il click per i preferiti sullo slot.
     * 
     * @access public
     * @return void
     */
     
    function disattivaClickPreferiti () {
	    
	    $(".item-listing .attiva_preferito").unbind("click");
	    
    }
    
    /**
     * Assegna o Ri assegna il click per i preferiti sullo slot.
     * 
     * @access public
     * @return void
     */
    
    function attivaClickPreferiti () {
		
		disattivaClickPreferiti();
		
		/**
		 * Pulsati singoli
		 */
		 
		$(".item-listing .attiva_preferito").bind("click", function (e) {
			
			e.preventDefault();
			
			var $me = $(this);
			$me
				.find("i")
					.removeClass("icon-heart-empty")
					.removeClass("icon-heart")
					.addClass("icon-spin2")
					.addClass("animate-spin");
			
			var data = {
				id: $me.data("id"),
				_token: $csrf_token,
			}
			
			if ($me.hasClass("disattiva_preferito")) {
				
				$.ajax({
				
					url: "/disattiva_preferito",
					type: 'POST',
					data: data,
					success: function(msg) {
						
		            	$me.find("i").attr("class", "icon-heart-empty");
						$me.removeClass("disattiva_preferito");
						var n =  parseInt($("#linkheader .badge").text()) - 1;
						if (n < 0) {
							n = 0;
							$("#linkheader .badge").removeClass("rosso");
						}
							
						$("#linkheader .badge").text(n);
					}
					
				});	

				
			} else {
			
				$.ajax({
				
					url: "/attiva_preferito",
					type: 'POST',
					data: data,
					success: function(msg) {
						
		            	$me.find("i").attr("class", "icon-heart");
						$me.addClass("disattiva_preferito");
						var n = parseInt($("#linkheader .badge").text()) + 1;
						$("#linkheader .badge").text( n ).addClass("rosso");
					}
				
				});	
			
			}	
		});
		    
    }
       
    /**
	 * Init
	 */
	 
    $(function() {

    	Tipped.create(".tooltip-categories", $("#tooltip-categories")[0] , 	{hideOnClickOutside: true, hideOn:false, position: "bottom", showOn: 'click'});
    	Tipped.create(".tooltip-price", 	 $("#tooltip-price")[0] , 		{hideOnClickOutside: true, hideOn:false, position: "bottom", showOn: 'click'});
    	Tipped.create(".tooltip-discount", 	 $("#tooltip-discount")[0] , 	{hideOnClickOutside: true, hideOn:false, position: "bottom", showOn: 'click'});
		Tipped.create(".tooltip-aperture", 	 $("#tooltip-aperture")[0] , 	{hideOnClickOutside: true, hideOn:false, position: "bottom", showOn: 'click'});
		Tipped.create(".tooltip-rating", dizionario.rating, {maxWidth: 350, showOn: 'click' });
		 
    	// Attivo la fancybox per la mappa
	    $('.venobox').venobox();

	    // Carico le immagini on demand
    	$('.lazy').lazy();

    	// Attivo tutti i comportamenti degli slot
    	attivaClickPreferiti();
    	attivaCheckbox();
    	attivaFiltri();
    	
    	//if ($("body.desktop").length) 
    	$(".sticker-sidebar").stick_in_parent();


		$(".mappa").click(function(e){
			e.preventDefault();
			
			$('form#filter-map input[type=hidden]#cms_pagina_id').val(__cms_pagina_id);
			$('form#filter-map input[type=hidden]#cms_pagina_uri').val(__cms_pagina_uri);

			$('form#filter-map input[type=hidden]#ancora').val(__ancora);

			$('form#filter-map input[type=hidden]#macro_localita_seo').val(__macro_localita_seo);

			$('form#filter-map input[type=hidden]#localita_seo').val(__localita_seo);

			$('form#filter-map input[type=hidden]#lat').val(__lat);
			
			$('form#filter-map input[type=hidden]#long').val(__long);

			if (__page_template == 'localita') {
				$('form#filter-map input[type=hidden]#macrolocalita_id').val(__macrolocalita_id);
				$('form#filter-map input[type=hidden]#localita_id').val(__localita_id);
			}

			$("#filter-map").submit();
		});


		$(".list_periodi").click(function(e){
			e.preventDefault();
			var id = $(this).data('id');
			$("span#"+id).slideToggle("slow");
			return false;
		})

    	    		
    });
	
	$(function() {
		
		/**
		 * Disattiva il click per i preferiti sullo slot.
		 * 
		 * @access public
		 * @return void
		 */
		 
		function disattivaClickPreferiti () {
			
			$(".item-listing-favorites .attiva_preferito").unbind("click");
			
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
			 
			$(".item-listing-favorites .attiva_preferito").bind("click", function (e) {
				
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
							
							/*var n =  parseInt($("#linkheader .badge").text()) - 1;
							if (n < 0) {
								n = 0;
								$("#linkheader .badge").removeClass("rosso");
							}	
							$("#linkheader .badge").text(n);*/
							
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
							 
							/*var n = parseInt($("#linkheader .badge").text()) + 1;
							$("#linkheader .badge").text( n ).addClass("rosso"); */
							
						}
					
					});	
				
				}	
			});
				
		}
		
		attivaClickPreferiti();
		
	});
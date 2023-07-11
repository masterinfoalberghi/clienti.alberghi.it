	
	var i = 0;
	var d = 3000;
	
	$(".readmorescheda").click(function (e) {

		e.preventDefault();
		$(this).parent().hide().slideUp();
		$(this).parent().parent().find("div").eq(1).slideDown();

	});
	
	$(".email").click(function (e) {
		
		e.preventDefault();
		$("#emailmobileforms").submit();
		
	})

	$(".email_2_bottoni").click(function (e) {
		
		e.preventDefault();
		$("#emailmobileforms_2_bottoni").submit();
		
	})


	$(".email_modal_first").click(function (e) {
		
		e.preventDefault();
		$("#emailmobileforms_modal_first").submit();
		
	})
	
	/* -- leggi tutto -- */
	
	$(".readall").click(function (e){
		e.preventDefault();
		$(".testohotel").addClass("all");
		$(this).hide();
	})


	/* CONTACLICK call me*/

	$(".pulsante_chiama_scheda").click(function(e){
		
		var id = $(this).data("id");
		var data = {
			hotel_id: id
		}
		
		$.ajax({ 
			url: '<?=url("/callMe.php") ?>',
			type: 'GET',
			data: data
		});

		return;

	})



	/* CONTACLICK whatsappami */

	$(".pulsante_whatsappa_scheda").click(function(e){
		var id = $(this).data("id");
		var data = {
			hotel_id: id
		}
		
		$.ajax({ 
			url: '<?=url("/whatsappMe.php") ?>',
			type: 'GET',
			data: data,
			success: function(msg) {
				/*$.ajax({ 
					url: '<?=url("/sendMe.php") ?>',
					type: 'GET',
					data: data
				});*/
			}
		});

		return;
	})


	
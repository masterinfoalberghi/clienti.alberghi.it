
	var checkin = [];
	var checkout = [];
	var first = true;
	
	function validatePrivacy(c) 
	{
		if (c.is(":checked")) {
			$(".privacy_checkbox span, .privacy_checkbox span a").removeAttr("style");
			$(".privacy_checkbox").removeAttr("style");
			$(".privacy_checkbox i").removeAttr("style").hide();
			return true
		} else {
			// $(".privacy_checkbox span, .privacy_checkbox span a")
			$(".privacy_checkbox").css({"border": "1px solid #ddd", "margin": "15px 5px", "height":"75px", "background":"#ffcdd2"});
			$(".privacy_checkbox span, .privacy_checkbox span a").css("color", "#F24A46");
			$(".privacy_checkbox i").show().css("color", "#F24A46");
			return false
		} 

	}
	
	function validateHotel(id, email, token) 
	{
		
		var n = str.indexOf(",");	
		if (n != -1) {
			$(".warning.recently").hide();
		} else {
			
			$.post( 
				"/hotel-contact-recently",
				{id: id, email: email, '_token': token }
			).done(function ( data ) {
				if (data != "no") {
					$(".warning.recently").show();
				} else {
					$(".warning.recently").hide();
				}
			});
		
		}

	}
	
	function validateEmail(email) 
	{
	    
	    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    if (email.length == 0) {
		    return 0;
		} else if (re.test(email)) {
			return 10;
		} else {
			return 1;
		}

	}  
	
	function validateName(name) 
	{
		
		if (name.length == 0) {
			return 0;
		} else if (name.length < 3) {
			return 1;
		} else {
			return 10;
		}
		
	}
	
	function validateNumber(number) 
	{
		
		var re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{0,6}$/im
		
		if (number.length == 0) {
			return 0;
		} else if (re.test(number)) {
			return 10;
		} else {
			return 1;
		}
		
	}
	
	function pad(s) { return (s < 10) ? '0' + s : s; }
	
	function convertDate(inputFormat) 
	{
			
		var d = new Date(inputFormat);
		return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
		
	}
	
	function convertDateJs(inputFormat) 
	{
		
		var d = inputFormat.split("/");
		return [d[2], d[1], d[0]].join('-'); 
		
	}
	
	function calcolaGiorni(num) 
	{
		
		var data_inizio = new Date(convertDateJs($("#arrivo_" + num).val()));
		var data_fine 	= new Date(convertDateJs($("#partenza_" + num).val()));
		var data_giorno_inizio = data_inizio.getDay();
		var data_giorno_fine = data_fine.getDay();
		var diff = Math.abs(data_fine - data_inizio) / 1000 / 60 / 60 / 24;
				
		if (!isNaN(diff)) {
			
			var n = night;
			if (diff > 1 )
				n = nights;
				
			diff = diff + " " + n + " (" + name_day[data_giorno_inizio] + " &rarr; " + name_day[data_giorno_fine] + ")"
		
			$("#date_night_" + num).html( diff );
		
		} else {
			
			$("#date_night_" + num).html( "" );
			
		}
		
	}	
	
	function deactive_calendar( num ) 
	{
		
		checkin[num] = undefined;
		checkout[num] = undefined;
		
		$("#arrivo_" + num).unbind ("focus");
		$("#partenza_" + num).unbind ("focus");
		$("#arrivo_button_" + num).unbind("click");
		$("#partenza_button_" + num).unbind("click");
		
	}
	
	function deactive_change() 
	{
		$('.num_bambini').unbind("change");
	}	
	
	function active_calendar( num ) 
	{
		
		deactive_calendar( num );
		
		var nowTemp = new Date(); 

		if ($(".page.closed").length) {
			var now = new Date(nowTemp.getFullYear(), 9, 1, 0, 0, 0, 0);
		} else {
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		}
		
		checkin[num] = $('#arrivo_' + num).datepicker({
			
			format: 'dd/mm/yyyy', 
			weekStart: 1,
			language: locale,
			onRender: function(date) {
				return date.valueOf() < now.valueOf() ? 'disabled' : '';
			}
			
		}).on('changeDate', function(ev) {
			
			var newDate = new Date(ev.date);
			var newDateTomorrow =new Date(ev.date);
			
			if (ev.date.valueOf() > checkout[num].date.valueOf()) {
				
				newDateTomorrow.setDate(newDateTomorrow.getDate() + 1);
				$("#partenza_button_" + num).text( ("0" + newDateTomorrow.getDate()).slice(-2) + "/" + ("0" + (newDateTomorrow.getMonth() + 1)).slice(-2) + "/" + newDateTomorrow.getFullYear()  );
				checkout[num].setValue(newDateTomorrow);
				
			} else {
				
				var dateTomorrow = $("#partenza_" + num).val();
				dateTomorrow = dateTomorrow.substr(3,2) + "/" + dateTomorrow.substr(0,2)  +"/" + dateTomorrow.substr(6,4);
				newDateTomorrow = new Date( dateTomorrow  );
				
			}
						
			$("#arrivo_button_" + num).text( ("0" + newDate.getDate()).slice(-2) + "/" + ("0" + (newDate.getMonth() + 1)).slice(-2) + "/" + newDate.getFullYear()  );
			$("#arrivo_button_" + num).removeClass("selected");
			
			checkin[num].hide(num);
			checkin[num].update();
			checkout[num].update();
			
			$('#partenza_' + num)[0].focus();
			$("#partenza_button_" + num).trigger("click");
			
			calcolaGiorni(num);
			
		}).data('datepicker');	
		
		checkout[num] = $('#partenza_' + num).datepicker({

			format: 'dd/mm/yyyy', 
			weekStart: 1,
			language: locale,
			onRender: function(date) {
				return (date.valueOf() <= checkin[num].date.valueOf() ? 'disabled' : '');
			}
			
		}).on('changeDate', function(ev) {
			
			var newDate = new Date(ev.date);
			$("#partenza_button_" + num).text(("0" + newDate.getDate()).slice(-2) + "/" + ("0" + (newDate.getMonth() + 1)).slice(-2) + "/" + newDate.getFullYear()  );
			checkout[num].hide(num);
			checkin[num].update();
			checkout[num].update();
			
			calcolaGiorni(num);
			
		}).data('datepicker');
		
		calcolaGiorni(num);
		
		$('#arrivo_' + num).bind("focus", function () {
			
			$("#daterange_" + num).addClass("left").removeClass("right");
			$("#partenza_" + num ).closest(".data_picker").addClass("opacity");	
			
		});
			
		$("#partenza_" + num).bind ("focus", function () {
			
			$("#daterange_" + num).addClass("right").removeClass("left");
			$("#arrivo_" + num ).closest(".data_picker").addClass("opacity");
			
		});
		
		
		$("#arrivo_button_" + num).bind("click", function (e) {
			
			e.preventDefault();
			checkin[num].show();
			$(this).addClass("selected");
			$("#date_info_" + num).hide();
			$("#daterange_" + num).addClass("left").removeClass("right");
			$("#partenza_" + num ).closest(".data_picker").addClass("opacity");			
		});
		
		
		
		$("#partenza_button_" + num).bind("click",function (e) {
			
			e.preventDefault();
			checkout[num].show();
			$(this).addClass("selected");
			$("#date_info_" + num).hide();
			$("#daterange_" + num).addClass("right").removeClass("left");
			$("#arrivo_" + num ).closest(".data_picker").addClass("opacity");
			
		});
		
	}	
	
	function active_change() 
	{
		
		deactive_change();
		
		$('.num_bambini').bind("change", function () {
				
			var idRoom = $(this).closest(".room").attr("id");
			
			$("#" + idRoom + " .width_pannello_bambini").hide().find("select").prop("disabled", true);
			var n = parseInt($(this).val());
			
			if (n>0) {
				$(this).closest(".col-form").addClass("active-force");
			} else {
				$(this).closest(".col-form").removeClass("active-force");
			}
			
			for (i = 0; i < 6; i++) {
				
				if (i<n) {
	
					$("#" + idRoom + " .bambini_eta_form_"+i)
						.show()
							.find("select")
								.prop("disabled", false)
	
				} else {
					
					$("#" + idRoom + " .bambini_eta_form_"+i)
						.hide()
							.find("select")
								.val('-1')
								.prop("disabled", false);

						
												
				}
					
			}
			
			if(n > 0){
				
				var txt = $eta_singolo; // "<?php echo trans('listing.eta_singolo') ?>";
				if (n>1) {
					txt = $eta; // "<?php echo trans('listing.eta') ?>";
					txt = txt.replace("%n%" , "<b>"+n+"</b>");
				}
				
				$("#" + idRoom + " label.n_eta_bambini").html( txt );
				$("#" + idRoom + " .width_pannello_bambini").show();
				
			} else {
				
				$("#" + idRoom + " .width_pannello_bambini").hide();
				
			}
	
			first = false;
			
		}).trigger("change");

	}
	
	function activeMealPlan() {

		$(".select_multiline").not(".init").each(function () {

			/** 
			 * Apre e chiude il pannello
			 */

			$es = $(this);
			$es.addClass("init");

			$es.click( function () {
				if ($(this).hasClass("open")) {
					$(this).removeClass("open");
				} else {
					$(this).addClass("open");
				}
			});

			/**
			 * Azioni su click dei checkbox
			 */

			$es.find('input[type="checkbox"]').click(function () {

				

				var values = []; /** Raccoglie i valori */
				var values_labels = []; /** Raccoglie i valori */
				
				var checkboxs = $(this).closest(".options_multiline").find('input[type="checkbox"]'); /** tutti i checkbox */
				var checkboxs_checked = $(this).closest(".options_multiline").find('input[type="checkbox"]:checked'); /** tutti i checkbox selezionati */

				console.log(checkboxs);
				/**
				 * Se ho solo un checkbox selezionato allora lo prendo di default
				 */

				if (checkboxs_checked.length == 1) {	
					checkboxs_checked
						.prop("disabled", "disabled")
						.parent()
							.addClass("disabled")
				} else {
					checkboxs
						.prop("disabled", "")
						.parent()
							.removeClass("disabled");
							
				}

				checkboxs.each(function (i) {
		
					if ($(this).is(":checked")) {
						values.push($(this).val());
						values_labels.push($(this).parent().find("span").text());
					}

				});
				
				$(this).closest(".select_multiline").find(".meal_plan_input").text(values_labels.join(", "));
				$(this).closest(".select_multiline").find(".tipologiaAlloggio").val(values.join(","));
				
			});

		});
	}

	// MULTICAMERE
	
	$("#addCamera").on("click", function (e) 
	{
		
		e.preventDefault();
		
		var n = parseInt($("#numero_camere").val());
		
		if(n == 5)
		{
			window.alert('Hai raggiunto il numero massimo di camere');
			return;
		}

		$("#numero_camere").val(n+1);
		
		var newRoom = $("#rooms-list .room").last().clone();	
		var idOldRoom = newRoom.attr("id");
		
		newRoom.hide();
		newRoom.attr("id", "room_" + n)
		
		$("#rooms-list").append(newRoom);
		
		$("#room_"+n+" .camera_label span").text(n+1);
		$("#room_"+n+" .date_info").attr("id", "date_info_" + n );
		$("#room_"+n+" .date_night").attr("id", "date_night_" + n );
		$("#room_"+n+" .daterange").attr("id", "daterange_" + n );
		$("#room_"+n+" .flex_date").attr("id", "flex_date_" + n );
		
		$("#room_"+n+" .arrivo").attr("id", "arrivo_" + n );
		$("#room_"+n+" .partenza").attr("id", "partenza_" + n );
		$("#room_"+n+" .arrivo_button").attr("id", "arrivo_button_" + n );
		$("#room_"+n+" .partenza_button").attr("id", "partenza_button_" + n );
		// $("#room_"+n+" .tipologiaAlloggio").attr("id", "trattamento_" + n );
		$("#room_"+n+" .select_multiline").removeClass("open").removeClass("init"); 
		
		var n_a = $("#" + idOldRoom + " .num_adulti ").val();
		var n_b = $("#" + idOldRoom + " .num_bambini ").val();
				
		$("#room_"+n+" .num_adulti").val( n_a );
		$("#room_"+n+" .num_bambini").val( n_b );
		
		for (var i = 0; i < n_b; i++) {
			$("#room_"+n+" .bambini_eta_form_" + i + " .eta_select").val( $("#" + idOldRoom +" .bambini_eta_form_" + i + " .eta_select").val());
		}

		/* CAMBIO GLI ID DEGLI ELEMENTI CLONATI */
		$("#room_" + n + " .num_bambini ").attr("id","num_bambini_" + n);
		$("#room_" + n + " .width_pannello_bambini ").attr("id","pannello_bambini_" + n);
		
		$("#room_" + n + " .email-mobile").remove();

		$("#room_" + n + " .eta_select").each(function(i){
			$(this).attr("id","eta_"+n+"_"+i);
		});
		
		$("#daterange_" + n).empty();
		
		deactive_change();
		active_change();
		deactive_calendar(n);
		active_calendar(n);
		activeMealPlan();
		
		newRoom.show("fast");
		$("#delCamera").show("fast");
		$(".camera_label").show(250);

		if (n == 4) {
			$("#addCamera").hide("fast");
		} 
		else {
			$("#addCamera").show("fast");
		}
		
	});
	
	$("#delCamera").on("click", function (e) 
	{
		
		e.preventDefault();
		var n = parseInt($("#numero_camere").val());
		
		$("#rooms-list .room").last().find('.num_bambini').unbind("change");
		deactive_calendar(n);
		
		if (n == 2) {
			$("#delCamera").hide("fast");
			$(".camera_label").hide(250);
		}
		
		$("#rooms-list .room").last().hide("fast" , function () { $(this).remove(); });
		$("#numero_camere").val( n - 1 );

		if (n < 6) {
			$("#addCamera").show("fast");
		} 
		
	});
	
	// CONTROLLI
	
	$("#nome_input").keyup(function () 
	{
		
		var name = $(this).val();
		var val = validateName(name);
		
		if (val == 0) {
		 	$("#nome_input").closest(".col-form").removeClass("error-form").removeClass("success-form");
		 } else if (val == 1) {
			$("#nome_input").closest(".col-form").addClass("error-form").removeClass("success-form");
		} else {
			$("#nome_input").closest(".col-form").addClass("success-form").removeClass("error-form");
		}
			
	}).trigger("keyup");
		
	$("#email_input, #email_coupon").keyup(function () 
	{
		
		var me = $(this);
		var email = me.val();
		var val = validateEmail(email);
		
		if (val == 0) {
			me.closest(".col-form").removeClass("error-form").removeClass("success-form");
		} else if (val == 1) {
			me.closest(".col-form").addClass("error-form").removeClass("success-form");
		} else {
			me.closest(".col-form").addClass("success-form").removeClass("error-form");
		}
			
	}).trigger("keyup");
		
	$("#phone_input").keyup(function () 
	{
		
		var phone = $(this).val();
		var val = validateNumber(phone);
		
		if (val == 0) {
			$("#phone_input").closest(".col-form").removeClass("error-form").removeClass("success-form");
		} else if (val == 1) {
			$("#phone_input").closest(".col-form").addClass("error-form").removeClass("success-form");
		} else {
			$("#phone_input").closest(".col-form").addClass("success-form").removeClass("error-form");
		}
			
	}).trigger("keyup");
	
	$("textarea").each(function () 
	{
		
		var $me = $(this);
		$me.css("height", $me[0].scrollHeight+"px");
		
	});
		
	if ($(".room").length > 1 ) 
	{
		$("#delCamera").show();
	} else {
		$("#delCamera").hide();
	}
	
	$("#numero_camere").val($(".room").length);
		
	$("#form_mail_scheda_mobile, #form_mail_multipla_mobile").submit(function (e) 
	{
		
		var $me = $(this);
		$me.find(".button").hide();
		
		addLoading();
		
		$("#errors").html('');
		$(".col-form")
			.removeClass("active")
			.removeClass("error-form");
		
		var nome = $("#nome_input").val();
		var email = $("#email_input").val();
		var data_arrivo = $(".arrivo");
		var data_partenza = $(".partenza");
		var flex_date = $(".flex_date");
		var newsletter_check = $("#newsletter_check").is(":checked");
		var privacy = $(".privacy_accept");
		
		var errore = false;
		
		if (nome == "") {
			
			errore = true;
			$("#nome_input").closest(".col-form").addClass("error-form");
			
		} 
		
		if (email == "") {
			
			errore = true;
			$("#email_input").closest(".col-form").addClass("error-form");
				
		} 
		
		if (validateEmail(email)<10) {
			errore = true;
			$("#email_input").closest(".col-form").addClass("error-form");
		}
		
		if (!validatePrivacy(privacy)) {
			errore = true;
		}
				
		data_arrivo.each(function () {
			
			var val = $(this).val();
			if (val == $data_default || val == "") {
				errore = true;
				$(this).closest(".col-form").addClass("error-form");
			}
			
		});
		
		data_partenza.each(function () {
			
			var val = $(this).val();
			if (val == $data_default || val == "") {
				errore = true;
				$(this).closest(".col-form").addClass("error-form");
			}
			
		});
		
		var numero_camere_js = $("#numero_camere").val();
		
		if (numero_camere_js > 0 ) {
			
			for (var i = 0; i < numero_camere_js; i++) {

				var $num_bambini_selected = $("#num_bambini_"+i).val();
				
				if ($num_bambini_selected > 0) {

					for (var c = 0; c < $num_bambini_selected; c++) {
						
						var str_id = "eta_"+i+"_"+c;
						var $eta_bambino = $("#"+str_id).val();

						if ($eta_bambino == '-1') {
							
							errore = true;
							$(this).find("#pannello_bambini_"+i).find(".etabambini").addClass("error-form");
							
						}
					}

				}		
			}
		}
		

		if (errore)	{
			
			e.preventDefault();
			$("#errors").html($alertCampi+'<br />');
			$("html, body").animate({ scrollTop: "0px" });
			removeLoading();
			$me.find(".button").show();
			
		} 
		
	});
	
	// INIT
	
	if ( $datepickerdfree != true ) {
		$(".room").each(function (i) {
			active_calendar(i);
		});
	}
	
	$(document).click(function (e) {

		if(!$(e.target).closest('.select_multiline').length) {
			$(".select_multiline").removeClass("open");
		};

	}); 

	active_change();
	activeMealPlan();
	

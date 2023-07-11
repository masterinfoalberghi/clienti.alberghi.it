	
	$(function() {
		
		var deleteOneTime = false;
		var firstKey = false;
		var firstScroll = 0;
		
		/*---------------------------------------------------------------------------------
		* Post
		*---------------------------------------------------------------------------------*/
		
		$(window).scroll(function() {
			if ($(this).scrollTop() > 180 && firstScroll == 0) {
				Tipped.hideAll();
				firstScroll = 1;
			}
		});

		$(window).click(function() {
			if (firstScroll == 0) {
				Tipped.hideAll();
				firstScroll = 1;
			}
		});

		$("#form_preventivo").submit( function (e) {
			
			$("#errors").html( "" ).hide();
			
			var error = false;
			var nome = $("#nome_input").val();
			var email = $("#email_input").val();
			var telefono = $("#phone_input").val();
			var prefisso = $("#prefix_input").val();
			var privacy = $(".privacy_accept");
			var html = "";

			removeErrors();
			
			$("#submit_button").hide();
			$(".loading-ajax").show()
			
			// $("#nome_input")
			//	.parent()
			//	.removeClass("error-form");

			if (_validateName(nome) < 10) {

				error = true;
				html += dizionario.email.nome_ko;
				$("#nome_input")
					.parent()
					.addClass("error-form");

			}

			if (_validateEmail(email) < 10) {
				
				error = true;
				html += dizionario.email.mail_ko;
				$("#email_input")
					.parent()
					.addClass("error-form");
					
			}

			if (_validateNumber(telefono) == 1) {
				
				error = true;
				html += dizionario.email.telefono_ko; 
				$("#phone_input")
					.parent()
					.addClass("error-form");
					
			}
				
			if (!_validatePrivacy(privacy)) {	
				
				error = true;
				html += dizionario.email.privacy_ko;
				
			}

			var numero_camere_js = $("#numero_camere").val();
		
			if (numero_camere_js > 0 ) {
				
				for (var i = 0; i < numero_camere_js; i++) {

					var $num_bambini_selected = $("#room_" + i +" .num_bambini").val();
					
					if ($num_bambini_selected > 0) {

						for (var c = 0; c < $num_bambini_selected; c++) {
							
							var $eta_bambino = $("#room_" + i + " .eta_select").eq(c).val();

							if ($eta_bambino == '-1') {
								
								error = true;
								$("#room_" + i +" .bambini_eta_" +c).removeClass("success-form").addClass("error-form");
								
							}
						}

					}		
				}
			}
			
			/**
			* Prendo tutti i campi flexdate li cambio per essere funzionali
			*/
			
			$(".flex_date").hide();
			$(".flex_date").each(function () {
				
				if ($(this).is(":checked")) {
					$(this).val(1)
				} else {
					$(this).val(0).prop("checked", "checked")
				}
				
			});
			if (error) {
				
				e.preventDefault();
				$("#errors").html( dizionario.email.campi_compilati + html).show();
				$(".loading-ajax").hide();
				$(".flex_date").show();
				$("#submit_button").show();

			}
			
		});
		
		function removeErrors() {
		
			$("#attendi").html( "" ).hide();
			$("#errors").html( "" ).hide();
			$(".privacy_checkbox span, .privacy_checkbox span a").removeAttr("style");
			$(".privacy_checkbox").removeAttr("style");
			$(".privacy_checkbox i").removeAttr("style").hide();

		}

		/*---------------------------------------------------------------------------------
		* Azioni
		*---------------------------------------------------------------------------------*/
		
		/**
		* Correttore inserimento nome e cognome
		*/
	
		$("#nome_input").keyup(function () {
			
			var name = $(this).val();
			var val = _validateName(name);
			
			if (val == 0) {
				
				$("#nome_input")
					.closest(".col-form")
						.removeClass("error-form")
						.removeClass("success-form");

				if (firstKey)
					removeErrors();
				
			} else if (val == 1) {
				
				$("#nome_input")
					.closest(".col-form")
						.addClass("error-form")
						.removeClass("success-form");
						
			} else {
				
				$("#nome_input")
					.closest(".col-form")
						.addClass("success-form")
						.removeClass("error-form");

				if (firstKey)
					removeErrors();
				
			}
				
		}).trigger("keyup");
		
		/**
		* Correttore inserimento email
		*/
		
		$("#email_input").keyup(function () {
			
			var me = $(this);
			var email = me.val();
			var val = _validateEmail(email);
			
			if (val == 0) {
				
				me
					.closest(".col-form")
						.removeClass("error-form")
						.removeClass("success-form");

				if (firstKey)
					removeErrors();
				
			} else if (val == 1) {
				
				me
					.closest(".col-form")
						.addClass("error-form")
						.removeClass("success-form");
				
			} else {
				
				me
					.closest(".col-form")
						.addClass("success-form")
						.removeClass("error-form");

				if (firstKey)
					removeErrors();
				
			}
				
		}).trigger("keyup");
		
		/**
		* Correttore inserimento numero di telefono
		*/
		
		$("#phone_input").keyup(function () {
			
			var phone = $(this).val();
			var val = _validateNumber(phone);
			
			if (val == 0) {
				
				$("#phone_input")
					.closest(".col-form")
						.removeClass("error-form")
						.removeClass("success-form");

				if (firstKey)
					removeErrors();

				$("#whatsapp_check").prop("disabled", "disabled");
				$("#whatsapp_check").closest(".label_checkbox").css("opacity", 0.5);
				
			} else if (val == 1) {
				
				$("#phone_input")
					.closest(".col-form")
						.addClass("error-form")
						.removeClass("success-form");

				$("#whatsapp_check").prop("disabled", "disabled");
				$("#whatsapp_check").closest(".label_checkbox").css("opacity", 0.5);
				
			} else {
				
				$("#phone_input")
					.closest(".col-form")
						.addClass("success-form")
						.removeClass("error-form");

				if (firstKey)
					removeErrors();

				$("#whatsapp_check").prop("disabled", "");
				$("#whatsapp_check").closest(".label_checkbox").css("opacity", 1);
				
			}
				
		}).trigger("keyup");
		
		firstKey = true;

		/*---------------------------------------------------------------------------------
		* Bottoni
		*---------------------------------------------------------------------------------*/	
		
		$("#addCamera").click(function (e) {
			
			Tipped.hideAll();
			e.preventDefault();
			var n = parseInt($("#numero_camere").val());
			if(n == 5) {
				window.alert('Hai raggiunto il numero massimo di camere');
				return;
			}

			$("#numero_camere").val(n+1);
			
			var $oldRoom = $("#rooms-list .room").last();
			var $newRoom = $oldRoom.clone();	
			var idOldRoom = $newRoom.attr("id");
			
			$newRoom.hide();
			$newRoom.attr("id", "room_" + n)
			
			$("#rooms-list").append($newRoom);
			$("#room_"+n+" .camera_label b").text("Camera" + (n+1));
						
			/**
			* Riporto i valori
			*/
			
			// Arrivo e partenza

			$newRoom
				.find(".daterange_select")
				.attr("data-id", n);

			$newRoom
				.find(".dateinfo")
				.attr("id", "dateinfo_" + n);

			$newRoom
				.find(".daterange")
				.removeClass("init")
				.attr("id", "data_picker_" + n)
				.attr("data-id", n);

			$newRoom
				.find(".arrivo_datepicker")
				.empty();

			$newRoom
				.find(".partenza_datepicker")
				.empty();

			$newRoom
				.find(".arrivo")
				.attr("id", "arrivo_" + n);

			$newRoom
				.find(".partenza")
				.attr("id", "partenza_" + n);

			$newRoom
				.find(".lowlimit")
				.attr("id", "lowlimit_" + n);

			$newRoom
				.find(".arrivo_datepicker")
				.attr("id", "arrivo_datepicker_" + n);

			$newRoom
				.find(".partenza_datepicker")
				.attr("id", "partenza_datepicker_" + n);

			$newRoom
				.find(".arrivo_button")
				.attr("id", "arrivo_button_" + n);

			$newRoom
				.find(".partenza_button")
				.attr("id", "partenza_button_" + n);

			// Adulti e bambini
			$newRoom
				.find(".pannello_bambini")
				.attr("id", "pannello_bambini_" + n)
				.hide();
			
			$newRoom
				.find(".num_adulti")
				.val("2");

			$newRoom
				.find(".num_bambini")
				.attr("data-id", n)
				.removeClass("init")
				.val("0");
	
			$newRoom
				.find(".eta_select")
				.val("-1")
				.removeClass("init");

			$newRoom
				.find(".bambini_eta")
				.addClass("error-form")
				.removeClass("success-form");

			// Trattamento
			$newRoom
				.find(".select_multiline")
				.removeClass("init")
				.removeClass("open")

			// Flex date
			$newRoom
				.find(".label_checkbox")
				.attr("for", "flex_date_" + n);

			$newRoom
				.find(".flex_date")
				.attr("id", "flex_date_" + n);

			// Tooltip
			$newRoom
				.find(".tooltip-small")
				.removeClass("init");
			
			$newRoom
				.find(".emailaddress")
				.remove();

			activeDatePicker();
			activeBambini();
			activeMealPlan();
			formTooltip();
			
			$newRoom.show("fast", function () {
				$(document.body).trigger("sticky_kit:recalc");
			});
			
			$("#delCamera").show("fast");
			$(".camera_label").show();

			if (n == 4) {
				$("#addCamera").hide("fast");
			} 
			else {
				$("#addCamera").show("fast");
			}
			
		});
		
		$("#delCamera").click(function (e) {
			
			Tipped.hideAll();
			
			if (!deleteOneTime) {
				deleteOneTime = true;
			
				e.preventDefault();
				var n = parseInt($("#numero_camere").val());
				
				if (n == 2)
					$(this).hide();
				
				$("#rooms-list .room")
					.last()
					.hide("fast" , 
						function () { 
							$(this).remove(); 
							$(document.body).trigger("sticky_kit:recalc"); 
							deleteOneTime = false;
						});
						
				$("#numero_camere").val( n - 1 );

				if (n < 6) {
					$("#addCamera").show("fast");
				} 
			
			}
			
		});
		
		/*---------------------------------------------------------------------------------
		* Tool tip
		*---------------------------------------------------------------------------------*/	

		function formTooltip () {

			$(".tooltip-small").not("init").each( function () {

				if ( $(this).hasClass("tooltip-small-left"))  {

					Tipped.create(this, $(this).data("title"), {position: "left", maxWidth: 200});	

				} else if ($(this).hasClass("tooltip-small-red")) {

					// data-tipped-options="title: 'Small', size: 'small', skin: 'red', close: true"
					// showOn:false, hideOn: false,
					Tipped.create(this, $(this).data("title"), {close: true, title:'<i style="font-size:16px; color:#fff;" class="icon-whatsapp"></i> WhatsApp&reg;', position: "left", maxWidth: 250, skin: "purple"}); 

				} else {

					Tipped.create(this, $(this).data("title"), {hook: "topleft", maxWidth: 200});	

				}

				$(this).addClass("init");

			});

		} 

		formTooltip();

		/*---------------------------------------------------------------------------------
		* Data picker
		*---------------------------------------------------------------------------------*/	
		
		$(document).click(function (e) {


			if(!$(e.target).closest('.daterange_select').length) {

				$(".arrivo_datepicker").hide();
				$(".partenza_datepicker").hide();
				$(".select_icon").removeClass("selected");
				$(".daterange").removeClass("open");

			}
			
			if(!$(e.target).closest('.select_multiline').length) {
				$(".select_multiline").removeClass("open");
			};

		}); 

		function calcoloGiorni(start, end, id) {

			var dateFormat, dayFormat, infoHtml

			dayFormat = "D";
				dayNameFormat = "ddd";
				dateFormat = 'D MM YYYY';
				diff = end.diff(start, 'days');

				infoHtml = ""; 
				
				if (diff == 1){
					infoHtml +=  diff + ' ' + dizionario.email.notte[0];
				}
				else {
					infoHtml += diff + ' ' + dizionario.email.notte[1];
				}

				infoHtml += " (" + start.format(dayNameFormat) + " &rarr; " + end.format(dayNameFormat) + ")";

				if (!isNaN(diff)) {
					$("#dateinfo_" + id).html( infoHtml );
				}

		}

		function activeDatePicker() {

			$(".daterange").not(".init").each(function () {
				
				var $me = $(this);
				var id = $me.parent().data("id"); // order number
				var flex = $me.parent().data("flex");// is flex date ?

				// Date iniziali

				var defaultViewStart =	moment($("#arrivo_" + id).val(), "D/M/Y");
				var defaultViewEnd =	moment($("#partenza_" + id).val(), "D/M/Y");
				var lowlimit =			moment($("#lowlimit_" + id).val(), "D/M/Y").format("D/M/Y");

				$("#arrivo_button_" + id).html('<i class="icon-calendar"></i>&nbsp;' + defaultViewStart.format("D MMM Y") + '<i class="icon-down-open"></i>');
				$("#partenza_button_" + id).html('<i class="icon-calendar"></i>&nbsp;' + defaultViewEnd.format("D MMM Y") + '<i class="icon-down-open"></i>');

				/**
				 * Attivo i Date picker
				 */

				$("#arrivo_datepicker_" + id).datepicker({

					format: 'dd/mm/yyyy', 
					weekStart:1,
					startDate: lowlimit,
					language: "it",
					todayHighlight: true,
						
				}).on("changeDate", function(e) {

					/**
					 * Prendo le date
					 */
					var data_dal = moment($("#arrivo_datepicker_" + id).datepicker("getDate"));
					var data_al  = moment($("#partenza_datepicker_" + id).datepicker("getDate"));
					
					/**
					 * Setto la nuova date negli oggetti
					 */

					$("#arrivo_button_" + id).html('<i class="icon-calendar"></i>&nbsp;' + data_dal.format("D MMM Y")+ '<i class="icon-down-open"></i>');
					$("#arrivo_" + id).val(data_dal.format("D/M/Y"));
					
					/**
					 * Se maggiore della data di partenza risetto la data di partenza
					 */

					if (data_al.isBefore(data_dal)) {

						data_al = data_dal.add(1, 'd'); 
						$("#partenza_" + id).val(data_al.format("D/M/Y"));
						$("#partenza_datepicker_" + id).datepicker("setDate", data_al.format("D/M/Y") );
						$("#partenza_button_" + id).html('<i class="icon-calendar"></i>&nbsp;' + data_al.format("D MMM Y")+ '<i class="icon-down-open"></i>');
							
					}
					
					/**
					 * Setto le nuove date come range
					 */

					$("#arrivo_datepicker_" + id).datepicker("setRange", [ data_dal, data_al]);
					$("#partenza_datepicker_" + id)
						.datepicker("setRange", [ data_dal, data_al])
						.datepicker("setStartDate", moment(e.date).add(1, 'd').format("D/M/Y") )
					
					/**
					 * Nascondo i calendari
					 */

					$(".arrivo_datepicker").hide();
					$(".partenza_datepicker").hide();
					
					/**
					 * mostro il calendario della partenza
					 */

					$("#partenza_datepicker_" + id).show();

					/**
					 * Setto le trasparenze per i calendari
					 */
					
					$(".select_icon").removeClass("selected");
					$(".date_input").removeClass("open");
					$("#partenza_datepicker_" + id).closest(".daterange").addClass("open");

					/**
					 * Setto come selezionato l'input della partenza
					 */

					$("#partenza_button_" + id).addClass("selected");
					$("#partenza_button_" + id).parent().addClass("open");
					
					calcoloGiorni(data_dal, data_al, id);
						
				});

				/**
				 * Partenza
				 */

				$("#partenza_datepicker_" + id).datepicker({

					format: 'dd/mm/yyyy', 
					weekStart:1,
					startDate: lowlimit,
					language: "it",
					todayHighlight: true,

				}).on("changeDate", function(e) {

					/**
					 * Prendo il valore dal datepicker
					 */

					var data_dal = moment($("#arrivo_datepicker_" + id).datepicker("getDate"));
						
					if (isNaN(data_dal)) { data_dal = moment($("#arrivo_" + id).val(), "DD/MM/YYYY");}
					var data_al  = moment($("#partenza_datepicker_" + id).datepicker("getDate"));
					
					/**
					 * Setto i valori negli input
					 */
					$("#partenza_" + id).val(data_al.format("D/M/Y"));
					$("#partenza_button_" + id).html('<i class="icon-calendar"></i>&nbsp;' + data_al.format("D MMM Y")+ '<i class="icon-down-open"></i>');
					$("#arrivo_datepicker_" + id).datepicker("setRange", [ data_dal, data_al]);
					$("#partenza_datepicker_" + id).datepicker("setRange", [ data_dal, data_al]);
					
					/**
					 * Nascondo i date picker
					 */

					$(".arrivo_datepicker").hide();
					$(".partenza_datepicker").hide();

					/**
					 * Rimuovo tutti selezionati
					 */

					$(".select_icon").removeClass("selected");
					$(".daterange").removeClass("open");
					$(".date_input").removeClass("open");

					calcoloGiorni(data_dal, data_al, id);
				
				}); 

				/** 
				 * Assegno i valori di default;
				 */

				$("#arrivo_datepicker_" + id).datepicker("setRange", [ defaultViewStart, defaultViewEnd]);
				$("#partenza_datepicker_" + id).datepicker("setRange", [ defaultViewStart, defaultViewEnd]);

				/**
				 * Calcolo i giorni
				 */
				calcoloGiorni(defaultViewStart, defaultViewEnd, id);

				// Attivatori

				$("#arrivo_button_" + id).click(function (e) {

					e.preventDefault();

					$(".select_icon").removeClass("selected");
					$(".daterange").removeClass("open");
					$(".date_input").removeClass("open");
					
					$(".arrivo_datepicker").hide();
					$(".partenza_datepicker").hide();

					$("#arrivo_datepicker_" + id).show();
					$("#arrivo_datepicker_" + id).closest(".daterange").addClass("open");
					$("#arrivo_button_" + id).parent().addClass("open");
					$(this).addClass("selected");

					$(document.body).trigger("sticky_kit:recalc");

				});

				$("#partenza_button_" + id).click(function (e) {

					e.preventDefault();

					$(".select_icon").removeClass("selected");
					$(".daterange").removeClass("open");
					$(".date_input").removeClass("open");

					$(".arrivo_datepicker").hide();
					$(".partenza_datepicker").hide();

					$("#partenza_datepicker_" + id).show();
					$("#partenza_datepicker_" + id).closest(".daterange").addClass("open");
					$("#partenza_button_" + id).parent().addClass("open");
					$(this).addClass("selected");	

					$(document.body).trigger("sticky_kit:recalc");

				});

				$me.addClass("init");
					
			});
				
		}
		
		activeDatePicker();

		/*---------------------------------------------------------------------------------
		* Bambini
		*---------------------------------------------------------------------------------*/	
		
		function selectBambini ($nb) {
			
			var nb_val = parseInt($nb.find("option:selected").text());
			var $pn = $("#pannello_bambini_" + $nb.data("id"))
						
			for( var t = 0; t < 6; t++ ) {

				var $sl = $pn.find(".bambini_eta_" + t);
				
				if ( t < nb_val ) {
					
					$sl.show()
										
				} else {
					
					$sl.hide()
					$sl.find(">select").val("-1");
					
				} 
				
			}
			
			if (nb_val>0)
				$pn.fadeIn();
			else
				$pn.fadeOut();
			
		}
		
		function activeBambini() {
			
			$(".num_bambini").not(".init").each(function () {
				
				var $nb = $(this);
				$nb.change(function () {
					selectBambini ($nb);
				});
				selectBambini ($nb);
				$nb.addClass("init"); 
				
			});
			
			$(".eta_select").not(".init").each(function() {
				
				$es = $(this);
				$es.change(function () {
					
					var $this = $(this);
					
					$this
						.parent()
						.removeClass("success-form")
						.removeClass("error-form");
					
					if ($this.val() != "-1" )
						$this
							.parent()
							.addClass("success-form");
					
					
				});
				
				$es.addClass("init");
				$es.trigger("change");
				
			});
			
		}
		
		activeBambini();

		/*---------------------------------------------------------------------------------
		* Meal_plan
		*---------------------------------------------------------------------------------*/

		function activeMealPlan() {

			$(".select_multiline").not(".init").each(function () {

				/**
				 * Apre e chiude il pannello
				 */
	
				$es = $(this);
				$es.addClass("init");

				$es.click( function () {
					$(this).addClass("open");
				});

				/**
				 * Azioni su click dei checkbox
				 */

				$es.find('input[type="checkbox"]').click(function () {

					
					var values = []; /** Raccoglie i valori */
					var values_labels = []; /** Raccoglie i valori */
					
					var checkboxs = $(this).closest(".options_multiline").find('input[type="checkbox"]'); /** tutti i checkbox */
					var checkboxs_checked = $(this).closest(".options_multiline").find('input[type="checkbox"]:checked'); /** tutti i checkbox selezionati */

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

		activeMealPlan();

});
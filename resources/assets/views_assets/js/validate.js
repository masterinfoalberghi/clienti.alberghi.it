	
			
		/*---------------------------------------------------------------------------------
		 * Validazione campi form
		 *---------------------------------------------------------------------------------*/
		 
		function _validateEmail(email) {
		    
		    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		    if (email.length == 0) {
			    return 0;
			} else if (re.test(email)) {
				return 10;
			} else {
				return 1;
			}
	
		}  
		
		/**
		 * Validazione privacy
		 */
	
		function _validatePrivacy(c) {
		    if (c.is(":checked")) {
				$(".privacy_checkbox span, .privacy_checkbox span a").removeAttr("style");
				$(".privacy_checkbox").removeAttr("style");
				$(".privacy_checkbox i").removeAttr("style").hide();
		        return true
		    } else {
				// $(".privacy_checkbox span, .privacy_checkbox span a")
				$(".privacy_checkbox").css({"border": "1px solid #ddd", "padding": "5px 5px 10px", "margin-top": "15px", "background":"#ffcdd2"});
				$(".privacy_checkbox span, .privacy_checkbox span a").css("color", "#F24A46");
				$(".privacy_checkbox i").show().css("color", "#F24A46");
		        return false
		    }
		}
	
		/**
		 * Validazione testo
		 */
		
		function _validateName(name) {
			 
			if (name.length == 0) {
				return 0;
			} else if (name.length < 3) {
				return 1;
			} else {
				return 10;
			}
			
		}
		
		/**
		 * Validazione numero telefonico
		 */
		 
		function _validateNumber(number) {
			
			var re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{0,6}$/im
			
			if (number.length == 0) {
				return 0;
			} else if (re.test(number)) {
				return 10;
			} else {
				return 1;
			}
			 
		}

function sendIscrizion() {

	var email = $("#newsletter_email").val();
	var privacy = $("#newsletter_privacy");
	var message = "";
	var error = false;

	if (email == "" || !_validateEmail(email)) {

		error = true;
		message = dizionario.email.mail_ko;

	}

	if (!_validatePrivacy(privacy)) {

		error = true;
		message = dizionario.email.privacy_ko;

	}

	if (error) {

		$(".newsletter_alert").html(message).slideDown();
		$(".newsletter_text").hide();

	} else {

		$(".newsletter_text").hide();
		$(".newsletter_alert").html(dizionario.email.attendi_nl).show();
		$(".newsletter_content").hide();

		$.ajax({

			type: "POST",
			url: dizionario.email.iscrizione_url_nl,

			data: {
				Email: email,
				_token: $csrf_token
			},

			success: function (f) {

				var e = f.Message;
				$(".newsletter_alert").html('<div class="alert alert-ok">' + e + '</div>');

				dataLayer.push({
					'event': 'VirtualPageNewsletter',
					'virtualPageURL': '/iscrizione_newsletter',
					'virtualPageTitle': "Conferma iscrizione alla newsletter"
				});

				dataLayer.push({
					'event': 'VirtualEventNewsletter'
				});

			},

			error: function (g, e, f) {

				$(".newsletter_alert").html(dizionario.email.mail_ko);

			}

		});

	}

}

/**
 * Azioni form newsletter
 */

$(function () {

	$("#newsletter_form").submit(function (e) {

		e.preventDefault();
		$("#message_placeholder").hide();
		sendIscrizion();

	});
});
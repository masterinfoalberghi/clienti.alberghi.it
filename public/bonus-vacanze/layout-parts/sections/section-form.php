<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include($path . "vendor/PHPMailer/src/Exception.php");
include($path . "vendor/PHPMailer/src/PHPMailer.php");
include($path . "vendor/PHPMailer/src/SMTP.php");

?>
<section id="form-domande">
		<style>
			
			.title-form, .form-subtitle {
				font-size: 28px;
			}
			.title-form {
				text-align: center;
				
				margin-bottom: .5em;
			}

			.form-subtitle {
				text-align: center;
				color: #185278;
				font-weight: 300;
				margin-top: .5em;
			}


			#form-domande {
				background-color: #EEEEEE;
			}
			section#form-domande h2 {
				line-height: 33px;
			}

			form#form-di-contatto {
				display: flex;
				flex-direction: column;
				align-content: space-between;
				justify-content: space-between;
				width: 90%;
				align-items: center;
				margin: 0 auto;
			}

			.primo-box, .secondo-box {
				display: flex;
				justify-content: space-between;
				margin-bottom: .5em;
			}
			.area-submit {
				display: flex;
				justify-content: space-between;
				align-items: center;
				min-height: 100px;
			}
			/* BOTTONE INVIA */
			#submit_form.button-primary {
				padding: 1em 2em;
				font-weight: bold;
				background-color: #eab855;
				color: #185278;
			}

			.primo-box, .secondo-box, .area-submit {
				width: 90%;
			}
			.check-buttons-area {
				width: 80%;
			}

			.primo-box label, .secondo-box label  { font-size: 1.1em; }

			.area-testo {
				height: 150px;
				padding: 10px;
			}

			input[name="email"], .area-testo {
				width: 100%;
				border-radius: 5px;
				padding: 10px;
			}


		.check-instance { margin-top: .5em; }
		.check-label, .check-label a { font-size: 0.8rem; }
		.check-label {
			text-align: left;
			margin-left: 1em;
			line-height: 1.4;
			width: 80%;
		}


		@media (max-width: 1024px) {
			form#form-di-contatto-covid-19 {
				width: 100%;
			}
			.primo-box, .secondo-box {
				width: 95%;
			}
			.area-submit {
				width: 94%;
			}
		}

		@media (max-width: 600px) {
			.check-buttons-area, .area-submit {
				flex-direction: column;
				gap: 1.5em;
			}	
		} 

		.grazie-mille {
			text-align: center;
			padding: 20px;
			background-color: green;
			color: whitesmoke;
			border-radius: 25px;
			max-width: 39rem;
			margin: 1em auto;
		}

		</style>

	<?php 

	if(isset($_POST['submit'])) {
		
		$if_is_bot = $_POST['privacy-chkbot'];

		if( $if_is_bot !== '' ) {
			
			echo "<div style='background-color: red;'>Grazie piccola macchina!</div>"; 

			echo "<div class='grazie-mille'>Grazie per il tuo messaggio!</div>";

		} else {

			$email_from = $_POST['email']; // required
			$messaggio_utente = $_POST['comments']; 

			// New Intance of email
			$mail = new PHPMailer(true);
			try {
				//Server settings
				$mail->SMTPDebug = 0;
				$mail->isSMTP();
				$mail->Host       = 'mail08.thirdeye.it';
				$mail->SMTPAuth   = true;
				$mail->Username   = 'master@info-alberghi.com';
				$mail->Password   = '57GQjg)A&[GB&W5K';     // SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->Port       = 587;

				//Recipients
				$mail->setFrom($email_from, 'Visitor /bonus-vacanze');
				$mail->addAddress('info@info-alberghi.com', 'info');
				//$mail->addCc('lmaroncelli@gmail.com');
				$mail->addReplyTo($email_from, "Visitor /bonus-vacanz");

				// Content
				$mail->isHTML(true);         // Set email format to HTML
				$mail->CharSet = 'UTF-8';
				$mail->Subject = 'Domanda proveniente da sezione /bonus-vacanz';
				$mail->Body    = $messaggio_utente;
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				$mail->send();
				echo "<div class='grazie-mille'>Grazie per il tuo messaggio!</div>";
			} catch (Exception $e) {
					echo "Non siamo riusciti a spedire il messaggio, per favore segnala l'errore al gestore del sito: {$mail->ErrorInfo}";
			}
			

			$user_wanted_newsletter = isset($_POST['check-newsletter']) && $_POST['check-newsletter'] == 'on';
			$rispostaNewsletter = $user_wanted_newsletter ? 'sì' : 'no';

			if ($user_wanted_newsletter) {

					/* SUBSCRIBE TO NEWSLETTER VIA CURL REQUEST */
					$url = "http://a4g6g.s21.it/frontend/xmlsubscribe.aspx?email={$_POST['email']}&list=1&group=121&confirm=yes&retCode=0";
					$curl_request = curl_init($url);
					curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
					$server_response = curl_exec($curl_request);
					curl_close($curl_request);
					echo "<script>( () => {
										console.log(`Utente iscritto: {$rispostaNewsletter} - risposta del server: {$server_response}`);
										dataLayer.push({ 'event':'iscrizioneBonus' });
										console.log('push iscrizioneBonus su GTM');
									} )()
								</script>";

			} else {
				echo "<script>( () => { console.log('L\'utente non si è iscritto alla newsletter') } )()</script>";
			}

		} /*end else*/


	} /*if submit POST*/
		?>

		<div class="container">
			<div class="row">
				<div class="col-sm-12">
				<h2 class="title-form">Hai dubbi, vuoi saperne di più sul bonus vacanze?</h3>
				<h3 class="form-subtitle">Scrivici le tue domande</h4>

				
				<form action="/bonus-vacanze/#form-domande" name="form-di-contatto" method="post" id="form-di-contatto">
							
					<div class="primo-box">
						<input type="email" name="email" placeholder="La tua email" required />
					</div>

					<div class="secondo-box">
						<textarea class="area-testo" name="comments" 
							placeholder="Inserisci qui il tuo messaggio"></textarea>
					</div>

					<div style="display:none;">
						<input name="privacy-chkbot" type="text" />
					</div>

					<div class="area-submit">
						<div class="check-buttons-area">

							<div class="check-instance">
								<input name="privacy-gdpr" type="checkbox" id="check-gdpr" required>
								<label for="check-gdpr" class="check-label">Presa visione <a target="_blank" href="____">dell'informativa privacy</a>, autorizzo il trattamento dei dati personali. (Servizio per maggiori di anni 15) </label>

							</div>
							<div class="check-instance">
								<input name="check-newsletter" type="checkbox" id="check-newsltt">
								<label for="check-newsltt" class="check-label"><strong>Accetto di ricevere aggiornamenti tramite newsletter</strong>. (Potrai disiscriverti in qualsiasi momento cliccando sul link presente nei messaggi che ti invieremo)</label>	
							</div>

						</div>

						<button type="submit" class="button-primary" name="submit" value="Submit" id="submit_form">
							Invia
						</button>
							
					</div>
					
				</form>

				</div>
			</div>
		</div>

	</section>

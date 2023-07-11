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
				font-size: 26px;
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

			form#form-di-contatto-covid-19 {
				display: flex;
				flex-direction: column;
				align-content: space-between;
				justify-content: space-between;
				height: 270px;
				width: 90%;
				align-items: center;
				margin: 0 auto;
			}

			.primo-box, .secondo-box {
				display: flex;
				justify-content: space-between;
			}
			.bottone-invia {
				display: flex;
				justify-content: space-between;
			}
			/* BOTTONE INVIA */
			#submit_form.button-primary {
				padding: 1em 2em;
				font-weight: bold;
				background-color: #0099cc;
				color: whitesmoke;
				border-radius: 15px;
			}

			.primo-box, .secondo-box, .bottone-invia {
				width: 90%;
			}

			.primo-box label {
				font-size: 1.1em;
			}

			.secondo-box label {
				font-size: 1.1em;
			}



			.area-testo {
				height: 150px;
				padding: 10px;
			}

			input[name="email"], .area-testo {
				width: 100%;
				border-radius: 5px;
				padding: 10px;
			}

		.check-privacy-area {
			display: flex;
			align-items: center;
		}

		.check-gdpr {
			font-size: 0.7rem;
			text-align: left;
			margin-left: 1em;
			line-height: 1.4;
			width: 80%;
		}
		.check-gdpr a {
			font-size: .7rem;
		}

		@media (max-width: 1024px) {
			form#form-di-contatto-covid-19 {
				width: 100%;
			}
			.primo-box, .secondo-box {
				width: 95%;
			}
			.bottone-invia {
				width: 94%;
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
				$mail->setFrom($email_from, 'Visitor /covid-19');
				$mail->addAddress('info@info-alberghi.com', 'info');
				//$mail->addCc('lmaroncelli@gmail.com');
				$mail->addReplyTo($email_from, "Visitor /covid-19");

				// Content
				$mail->isHTML(true);         // Set email format to HTML
				$mail->CharSet = 'UTF-8';
				$mail->Subject = 'Domanda proveniente da sezione /covid-19';
				$mail->Body    = $messaggio_utente;
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				$mail->send();
				echo "<div class='grazie-mille'>Grazie per il tuo messaggio!</div>";
			} catch (Exception $e) {
					echo "Non siamo riusciti a spedire il messaggio, per favore segnala l'errore al gestore del sito: {$mail->ErrorInfo}";
			}

		} /*end else*/


	} /*if submit POST*/
		?>

		<div class="container">
			<div class="row">
				<div class="col-sm-12">
				<h2 class="title-form">Cosa ti aspetti da hotel e servizi turistici della Riviera Romagnola?</h3>
				<h3 class="form-subtitle"> Vuoi saperne di più? Scrivici le tue domande</h4>

					<form action="/covid-19/#form-domande" name="form-di-contatto" method="post" id="form-di-contatto-covid-19">
							
							<div class="primo-box">
									<input type="email" name="email" placeholder="La tua e-mail" required />
							</div>

							<div class="secondo-box">
								<textarea
									class="area-testo" 
									name="comments" 
									placeholder="Scrivi qui il tuo messaggio"></textarea>
							</div>

							<div style="display:none;">
								<input name="privacy-chkbot" type="text" />
							</div>

							<div class="bottone-invia">
								<div class="check-privacy-area">
									<input name="privacy-gdpr" type="checkbox" id="check-gdpr" required>
									<label for="check-gdpr" class="check-gdpr">Presa visione <a target="_blank" href="https://www.info-alberghi.com/informativa-privacy-gdpr.php">dell'informativa privacy</a>, autorizzo il trattamento dei dati personali. (Servizio per maggiori di anni 15) </label>
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
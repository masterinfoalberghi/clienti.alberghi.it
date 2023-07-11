@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff; width: 100%;  " border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:7px 15px 15px;  font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Ciao {{$user->hotel->nome}},<br />
					abbiamo notato che è da un po' di tempo che non ti colleghi alla tua area riservata sul portale info-alberghi.com.<br /><br />

					Ti ricordiamo che puoi:<br />
					✓ aggiornare le informazioni su servizi e listini prezzi<br />
					✓ inserire nuove offerte, last minute e sconti bimbi gratis.<br /><br />

					Collegati all'indirizzo <a href="https://www.info-alberghi.com/admin" title="Area riservata" target="_blank">https://www.info-alberghi.com/admin</a><br />
					e ottieni il massimo dalla tua presenza sul portale.<br /><br />

					Se non ricordi la tua password <a href="https://www.info-alberghi.com/admin/password/email" title="Recupera password" target="_blank">clicca qui per la procedura di recupero</a>.<br /><br />

					Per qualsiasi necessità, non esitare a contattarci via mail oppure al numero 0541 29187 (dalle ore 9 alle 18).<br /><br />

					A presto,<br />
					staff di info-alberghi.com<br /><br />

				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

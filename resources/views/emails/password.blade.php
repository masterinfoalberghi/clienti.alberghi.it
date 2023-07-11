@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $titolo_email])
<table class="container" align="center" style=" background:#ffffff; width: 100%; "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:7px 15px 15px; font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Gentile {{$nome_cliente}},<br>qualcuno ha richiesto la procedura di <b style="background: #FFF9C4; padding:2px 5px;">"recupero password"</b> per l'account {{$username}}.<br />
					Se non hai richiesto tu il cambio password, puoi semplicemente ignorare questa email.
				</p>
				@include("emails.includes.button", ["title_button" => "Avvia la procedura di recupero","url_button" => url('admin/password/reset/'.$token)])
				<p>
					@include("emails.includes.info")
					@include("emails.includes.firma")
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

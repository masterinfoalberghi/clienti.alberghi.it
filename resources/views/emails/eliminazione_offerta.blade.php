@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff; width: 100%;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:7px 15px 15px; font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Gentile {{$nome_cliente}},<br>ti informiamo che l'offerta <b style="background: #FFF9C4; padding:2px 5px;">"{{$titolo_offerta}}"</b> inserita per la tua struttura <b style="background: #FFF9C4; padding:2px 5px;">&egrave; stata eliminata.</b><br>
				</p>
				<p>
					@include("emails.includes.info")
					@include("emails.includes.firma")
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

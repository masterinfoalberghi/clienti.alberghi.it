@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff; width: 100%; " border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px; padding:7px 15px 15px; font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				
				<p style="margin-top:0">
						@if ($type == 'delete')
							Gentile {{$nome_cliente}},<br>ti informiamo che la dicitura delle <b style="background: #FFF9C4; padding:2px 5px;">"politiche di cancellazione"</b> è <b style="background: #FFF9C4; padding:2px 5px;">stata eliminata o modificata</b>. Al più presto le politiche di cancellazione saranno moderate nuovamente dallo staff di Info Alberghi.
						@else
							Gentile {{$nome_cliente}},<br>ti informiamo che le <b style="background: #FFF9C4; padding:2px 5px;">"politiche di cancellazione"</b> sono state moderate per migliorare la leggibilità da parte degli utenti. <b style="background: #FFF9C4; padding:2px 5px;">"{{$label}}"</b> è la dicitura con cui comparirai nelle varie pagine di listing di info-alberghi.com, fino alla tua prossima modifica.
						@endif	
				
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

@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff; width: 100%;  "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px; padding:7px 15px 15px; font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Gentile {{$nome_cliente}},<br>
					ti informiamo che il testo dell'offerta <b style="background: #FFF9C4; padding:2px 5px;">"{{$titolo_offerta}}"</b> inserita per la tua struttura <b style="background: #FFF9C4; padding:2px 5px;">&egrave; stato parzialmente modificato</b> dal nostro <em>content editor</em> per renderla pi&ugrave; efficace e utile per gli utenti.<br />
				</p>

				@if (!is_null($note) && $note != '')
					<p>
					In particolare, in questo caso: {{$note}}
					</p>
				@else
					@include("emails.includes.moderazione")
				@endif

				@include("emails.includes.traduzioni", ["traduzione" => $traduzione])
				@include("emails.includes.button", ["title_button" => "Controlla le modifiche","url_button" => "https://www.info-alberghi.com/hotel.php?id=".$hotel_id."&amps;".$ancora."=1"])
				<p>
					@include("emails.includes.info")
					@include("emails.includes.firma" )
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

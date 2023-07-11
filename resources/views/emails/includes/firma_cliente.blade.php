<tr>
	<td colspan="2" style="padding:15px; background:#eee;">
		<table class="container" style="background:#eee; width: 100%;" border="0" cellspacing="0" cellpadding="0">
			
			<tr>
				<td style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; font-size:20px; line-height: 22px;">
					<span style="padding-top: 5px;">
						@if (substr($language,0,2) == "it")
							<img src="https://static.info-alberghi.com/images/email/it.gif" />&nbsp;
						@elseif (substr($language,0,2) == "fr")
							<img src="https://static.info-alberghi.com/images/email/fr.gif" />&nbsp;
						@elseif (substr($language,0,2) == "de")
							<img src="https://static.info-alberghi.com/images/email/de.gif" />&nbsp;
						@elseif (substr($language,0,2) == "en")
							<img src="https://static.info-alberghi.com/images/email/en.gif" />&nbsp;
						@endif
						{{$customer}}
						<!--[if mso]>
						@if (substr($language,0,2) == "it")
						(italiano)
						@elseif (substr($language,0,2) == "fr")
						(francese)
						@elseif (substr($language,0,2) == "de")
						(tedesco)
						@elseif (substr($language,0,2) == "en")
						(inglese)
						@endif
						<![endif]-->
						</span><br />
						<span style="font-size:14px">@if($email){{$email}}@endif @if($phone)- {{$phone}}@endif</span>
				</td>
			</tr>
		</table>
	</td>
</tr>

<tr>
	<td align="left" valign="top" style="border-bottom: 15px solid #eee;"></td>
</tr>

@if ($information2 == 1)
<tr>
	<td>
		<table class="container" style="background:#ffffff; border: 1px solid #388C7E; width: 100%;" cellspacing="0" cellpadding="0">
		
			<tr>
				<td valign="top" style="background: #fff; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; font-size:13px; line-height: 22px; padding:30px; margin:0 auto; ">
					<a href="https://wa.me/{{$dati_json["whatsapp"]}}?text={{$dati_json['message_wa']}}" style="font-size:14px; text-align:center; background:#57D365; text-decoration: none; display: block; color:#fff;  padding:15px 10px; border-radius:5px; max-width: 300px; text-align: center; margin: 0 auto;">Rispondi via <b style=" color:#F9FBE7;">&nbsp;&nbsp;<img style="vertical-align:middle" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABV0lEQVQ4jY2SPSjFYRTGn3vrGqxkMFkM3BhkNJCyShZGdEukDDJZjJSBQclnUpYbk1u3XN2yWWwoH90SFiPpSv30cv5679v98NT5v+fjOU/ve/5HQGiNwA5QpBQZoC3kh82zXssRMA1MAhvAp+X3KgmsGGG5zK0iSxknHwqMWyFVpTmybuOuRwIJS6SNMGDxaBWRGeMkXTBhQbMVTyzO1biJw2Zc0pCkV0nPKsWjqiPjep1Ai6QXj3po53kNgUtJDU6gKCnuFQ4k5SRtSWq3XL2kpkCg7ucL7AJfwfviwLW9cwRYMH/N41wAN87ps2J/mUGdBtv4EQxxPgpugbsK0+4C9oEzoNdyaRNIRKQCkP3HEjlbsubhaJE6LTEGzAEPwGCZxlbgyrhukf5WedF74xvw5MX3Nkw3ZId3oMcXjgHH9ltWJeXNT0qaktQhKSapIGnbq/9C0jdCmJgDmM7YkgAAAABJRU5ErkJggg==" /> Whatsapp&reg;</b></a><br />
					{!! __("hotel.wa_appendix") !!}
				</td>
			</tr>

		</table>
	</td>
</tr>
@endif

@include("emails.includes.header")

<table align="center" style="width: 100%; background:#ffffff;" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td bgcolor="222222" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; background:#222222; font-weight:bold; font-size: 30px; padding:7px 15px; border-bottom: 5px solid #00A6DA">
				<span style="color:#ffffff;">INFO</span><span style="margin-left:-2px; color:#00A6DA; text-shadow: -2px 0 #222222">ALBERGHI</span>
			</td>
		</tr>
		<tr>
			<td style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:30px 15px 7px; text-align: left; font-size:16px; color: #00A6DC" align="left">{{$oggetto}}</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid #ddd; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:0px 15px 15px; text-align: left; font-size:20px; " align="left"><b>{{$hotel_name}}</b> <sup style="color:#F9AE58; font-size: 14px;">{{$hotel_rating}}</sup><span style="font-size:14px">&nbsp;&middot;&nbsp;{{$hotel_loc}}</span></td>
		</tr>
	</tbody>
</table>

<table class="container" align="center" style=" background:#ffffff; width: 100%; " border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:30px 15px 15px;  font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Gentile {{$nome_cliente}},<br>qui di seguito Le riportiamo il riepilogo giornaliero delle <b style="background: #FFF9C4; padding:2px 5px;">telefonate (azioni di chiamata)</b> che ha ricevuto in data <b style="background: #FFF9C4; padding:2px 5px;">{{ $ieri }}</b>.
				</p>
				<p>
					{{count($log_arr)}} utenti hanno cliccato da mobile il bottone <b style="background: #FFF9C4; padding:2px 5px;">"Chiama"*</b> presente nulla Sua scheda hotel per mettersi in contatto diretto con la sua struttura:
				</p>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="10%" style="border-bottom: 1px solid #ddd;">#</td>
						<td style="padding:5px 15px 5px 5px; border-bottom: 1px solid #ddd;">Ora</td>
						<td style="padding:5px 15px 5px 5px; border-bottom: 1px solid #ddd;">IP</td>
					</tr>
					@php $count = 1; @endphp
					@foreach ($log_arr as $log)
						<tr>
							<td style="padding:5px;">{{$count}}</td>{!!$log!!}
						</tr>
						@php $count++; @endphp
					@endforeach
				</table>
				<p>
					Questo messaggio ha carattere informativo/statistico: non possiamo conoscere l'esito delle telefonate.
				</p>
				@include("emails.includes.info")
				@include("emails.includes.firma")
				<p>
					* Qui pu&ograve; vedere il bottone "Chiama" presente sulla Sua scheda hotel nella versione per dispositibivi mobili di Info Alberghi<br><br>
					<img src="https://static.info-alberghi.com/images/azione-chiama.jpg" alt="Bottone Chiama" width="335" height="500">
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

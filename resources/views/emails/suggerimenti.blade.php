@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;  padding:7px 15px; margin-bottom: 30px;"  border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Bella Fratella!<br>ti informo che l'hotel "<span style="font-style: italic;">{{$nome_cliente}} (ID = {{$hotel_id}})</span>" ha voluto allegramente suggerire:<br>
				</p>
				<p><i>"{{ $suggerimento }}"</i></p>
				<p>per la {{$ambito}} della {{$sezione}}.</p>
				<p>Se non hai capito una cippa puoi sempre mandarti una email o telefonarti (il numero lo sai).</p>
				<p>
					@include("emails.includes.firma")
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")



@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff;  ; width: 100%; "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:7px 15px 15px;  font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;" >
				<p style="margin-top:0">
					Bella Fratella!,<br>
					ti ricordo che hai inserito un promemoria per l'evidenza <b style="background: #FFF9C4; padding:2px 5px;">"{{$tipo}}"</b>
					@if (empty($tipo))
						dal titolo <b style="background: #FFF9C4; padding:2px 5px;">"{{$titolo_offerta}}"</b>,
					@else
						@if (!empty($titolo_offerta))
							con note aggiuntve <b style="background: #FFF9C4; padding:2px 5px;">"{!!$titolo_offerta!!}"</b>,
						@endif
					@endif
					valida nel periodo:<br /><br />
					{!!$validita_offerta!!}<br /><br />e appartenente a <b style="background: #FFF9C4; padding:2px 5px;">{{$hotel_name}}</b> (ID = {{$hotel_id}}) di {{$localita}}.<br /><br />
					Se non hai capito una cippa puoi sempre mandarti una email o telefonarti (il numero lo sai).
				</p>
				<p>
					@include("emails.includes.firma")
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

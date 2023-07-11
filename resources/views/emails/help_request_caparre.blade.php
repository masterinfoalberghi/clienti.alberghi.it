@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => "Richiesta aiuto compilazione caparre"]) 
<table class="container" align="center" style=" background:#ffffff; width: 100%;  " border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:7px 15px 15px;  font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					<b>{{$hotel->nome}} (#{{$hotel->id}})</b><br />{{$hotel->indirizzo}}, {{$hotel->localita->nome}} ({{$hotel->localita->prov}})</small>
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")
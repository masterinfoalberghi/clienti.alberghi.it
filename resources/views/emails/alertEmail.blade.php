@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => ""])
<table class="container" align="center" style=" background:#ffffff; width: 100%;  " border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:7px 15px 15px;  font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0; padding:20px; ">
					<span style="background:red; color:#fff; padding:5px 10px; margin-bottom:10px; display:inline-block; ">Attenzione!!</span><br>
					le <b style="color: red">email {{$quali}}</b> di ieri <b style="color: red">sono sotto la soglia prevista</b><br /><br />
					Conteggio email: <b>{{$emailIeri->conteggio}}</b><br />
					Conteggio email anno precedente: <b>{{$emailAnnoscorso->conteggio}}</b>
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

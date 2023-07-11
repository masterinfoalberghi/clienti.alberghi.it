@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff; width: 100%;  " border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:7px 15px 15px;  font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Attenzione!!<br>
					Sono state create queste pagine dello stradario <b>NON attive e in italiano.</b><br/><br/>
					<table>
					@foreach($new_pages as $key => $page)
						
						<tr>
							<td width="10%">{{$key + 1}}</td>
							<td width="60%">{{$page['uri']}}</td>
						</tr>
						
					@endforeach
					</table>
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

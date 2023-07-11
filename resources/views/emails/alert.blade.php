@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff; width: 100%;  " border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:7px 15px 15px;  font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Attenzione!!<br>
					Uno dei file importanti di IA <b>è sparito.</b><br/><br/>
					<table>
					@foreach($array_file as $file_item)
						
						<tr>
							<td width="50%">{{$file_item[1]}}</td><td>@if ($file_item[0] == 1) Ok @else Eliminato @endif</td>
						</tr>
						
					@endforeach
					</table>
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

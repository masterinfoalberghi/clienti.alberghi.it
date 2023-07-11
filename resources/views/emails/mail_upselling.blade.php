@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => ""])
<table class="container" align="center" style=" background:#ffffff; width: 100%;  "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px; padding:7px 15px 15px; font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Gentile {{$nome_cliente}},<br>
					grazie per aver utilizzato il servizio del portale info-alberghi.com, sarai contattato al pi&ugrave; presto dalla struttura <b style="background: #FFF9C4; padding:2px 5px;">{{$hotel_name}} di {{$localita}}</b>.
				</p>
				<p>
					Nel frattempo <b style="background: #FFF9C4; padding:2px 5px;">potrebbe interessarti visitare</b>anche:
				</p>
				<table>
					@if ($hotels)
					@foreach($hotels as $hotel)
						<tr>
							<td>
								<a href="{{$hotel[0]}}" target="_blank" >
									<img src="{{$hotel[1]}}" style="width:100%;height:auto" width="600" height="404">
								</a><br />
								<p style="line-height:20px; margin:0; font-size: 20px; margin:15px 0 0 0; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">{{$hotel[2]}} <sup style="color:#F9AE58; font-size: 14px;">{{$hotel[3]}}</sup></p>
								<p style="font-size:12px; line-height:20px;  margin:0 0 45px 0; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">{{$hotel[4]}} - {{$hotel[5]}}</p>
							</td>
						</tr>
					@endforeach
					@endif
				</table>
				<p>
					<span style="font-size:20px; color: #00A6DC; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">Dove sono questi hotel?</span><br />
				</p>
				<img src="https://maps.googleapis.com/maps/api/staticmap?key=<?php echo \Config::get("google.googlekey"); ?>{{$markers}}" width="600" height="400" style="width:100%;height:auto"/>
				<p>
					@include("emails.includes.firma")
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

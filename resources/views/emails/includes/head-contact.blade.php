<table align="center" style="width: 100%; background:#ffffff;" bgcolor="#ffffff"  border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td width="80%" bgcolor="222222" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; background:#222222; font-weight:bold; font-size: 24px; padding:7px 15px; border-bottom: 5px solid #00A6DA">
				<span style="color:#ffffff;">INFO</span><span style="margin-left:-2px; color:#00A6DA; text-shadow: -2px 0 #222222">ALBERGHI</span>
			</td>
			<td width="20%" bgcolor="222222" align="right" valign="middle" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; text-align:right; background:#222222; padding:13px 15px 7px 5px; color:#ffffff; border-bottom: 5px solid #00A6DA; ">
				{{$email_type}}
			</td>
		</tr>

		@if( $email_type == "Diretta" || $email_type == "Wishlist" || $email_type == "Multipla" )
			<tr>
				<td colspan="2" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:15px 15px 0; text-align: left; font-size:20px;  color:#222; " align="left">{{$hotel_name}} <sup style="color:#F9AE58; font-size: 14px;">{{$hotel_rating}}</sup><span style="font-size:14px">&nbsp;&middot;&nbsp;{{$hotel_loc}}</span></td>
			</tr>

			<tr>
				<td colspan="2" align="left" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; text-align:left; padding:5px 15px 15px; font-size: 14px; ">
					<a href="https://www.info-alberghi.com/hotel.php?id={{$hotel_id}}">Vedi scheda {{$hotel_name}}</a> <b style="font-size:18px">&rarr;</b>
				</td>
			</tr>

		@if (!is_null($caparre))
			<tr>
				<td colspan="2" align="left" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; text-align:left; padding:5px 15px 2px 15px; font-size: 15px; font-weight: bold;">Politiche di modifica/cancellazione prenotazione
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; text-align:left; padding:5px 15px 15px; font-size: 13px; ">
					@foreach ($caparre as $key => $caparra)
							{{$caparra}}
							@if ($key+1 < count($caparre))
								<hr>
							@endif
					@endforeach
				</td>
			</tr>
		@endif

		@elseif( $email_type == "Multipla")
			<tr>
				<td colspan="2" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:15px; text-align: left; font-size:20px;  color:#222; " align="left">Richiesta preventivo</td>
			</tr>
		@else
			<tr>
				<td colspan="2" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:15px; text-align: left; font-size:20px;  color:#222; " align="left">Richiesta preventivo</td>
			</tr>
		@endif
	</tbody>
</table>

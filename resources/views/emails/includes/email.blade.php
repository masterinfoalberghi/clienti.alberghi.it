<table align="center" style=" background:#ffffff; width: 100%;"  border="0" cellspacing="0" cellpadding="0">
	<tbody>

		<tr>
			<td align="left" valign="top" style="border-bottom: 15px solid #eee;"></td>
		</tr>

		@php $count = 1; @endphp
		@foreach ($dati_json["rooms"] as $room)
			<tr>
				<td colspan="2">
					<!-- camera -->
					<table style=" background:#ffffff;  width: 100%;" border="0" cellspacing="0" cellpadding="0">

						<tr>
							<td align="left" valign="top" bgcolor="00A6DC" style="padding:7px 15px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; font-size:16px;  background: #00A6DC; text-align:left; color:#fff; border-bottom:1px solid #00A6DC;">
								@if(count($dati_json["rooms"]) > 1)Camera {{$count}} -@endif Richiesta per {{$room["nights"]}} notti
							</td>
						</tr>
						
						<tr>
							<td align="left" valign="top" style="padding:15px">

								<table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 0 0 7px 0; width:70px; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;">Check-in</span></td>
										<td align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 0 0 7px 0; text-align:left; font-size: 16px; border-bottom:1px solid #ddd;">
											@php \Carbon\Carbon::setLocale("it"); @endphp
											{!!\Carbon\Carbon::createFromFormat('d/m/Y',$room["checkin"])->formatLocalized("%a, <b>%d %B</b> %Y ")!!} @if($room["flex_date"])<br /><em style="font-size: 12px; color: #aaa">{{trans("labels.date_flessibili_email")}}</em>@endif
										</td>
									</tr>
									<tr>
										<td valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 7px 0;  width:70px; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;">Check-out</span></td>
										<td align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 7px 0; text-align:left; font-size: 16px; border-bottom:1px solid #ddd;">
											{!!\Carbon\Carbon::createFromFormat('d/m/Y',$room["checkout"])->formatLocalized("%a, <b>%d %B</b> %Y")!!} @if($room["flex_date"])<br /><em style="font-size: 12px; color: #aaa">{{trans("labels.date_flessibili_email")}}</em>@endif
										</td>
									</tr>
									<tr>
										<td valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:  7px 0;  width:70px; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;">Pax</span></td>
										<td align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 7px 0;  text-align:left; font-size: 16px; border-bottom:1px solid #ddd;">
											<b>{{$room["adult"]}} Ad</b>
											@if (!empty($room["children"]))
											 + <b>{{count($room["children"])}} Ba</b> <span style="font-size:16px;">({{implode(",", $room["children"])}} anni)</span>
											@endif
										</td>
									</tr>
									<tr>
										<td valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; width:70px; padding:  7px 0; @if ($dati_json["information"] != "" && count($dati_json["rooms"]) == 1)border-bottom:1px solid #dddddd;@endif"><span style="font-size:12px; color: #888888;" >Tratt.</span></td>
										<td align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;  padding:  7px 0; text-align:left; font-size: 16px; @if ($dati_json["information"] != "" && count($dati_json["rooms"]) == 1)border-bottom:1px solid #dddddd; @endif">
											<b>{!!\Utility::getTrattamentoOfferte(strtolower($room["meal_plan"])) !!}</b>
										</td>
									</tr>
									@if ($dati_json["information"] != "" && count($dati_json["rooms"]) == 1)
									<tr>
										<td valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; width:70px; padding:  7px 0;"><span style="font-size:12px; color: #888;">Note</span></td>
										<td align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 7px 0; text-align:left; font-size:16px; line-height: 24px; ">
											{{$dati_json["information"]}}
										</td>
									</tr>	
									@endif
									@if ( isset($room["caparre"]) && count($room["caparre"]) )
										<tr><td colspan="2" style="padding-bottom: 10px;">&nbsp;</td></tr>
										<tr>
											<td colspan="2">
												<table style="border-collapse: collapse; background: #ecf3e5;">
													<tr>
														<td colspan="2" align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;  padding:  7px 0 7px 7px; text-align:left; font-size: 15px; border-top: 1px solid #8CC152; border-left: 1px solid #8CC152; border-right: 1px solid #8CC152;">
															<b>{{$dati_json['politiche_canc']}}</b>
														</td>
													</tr>
													<tr>
														<td colspan="2" align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica, 	 Arial, sans-serif; padding:7px; text-align:left; font-size: 13px; border-bottom: 1px solid #8CC152; border-left: 1px solid #8CC152; border-right: 1px solid #8CC152;">
															@foreach ($room["caparre"] as $key => $caparra)
																{!!$caparra!!}
																@if ($key+1 < count($room["caparre"]))
																	<br/><br/>
																@endif
															@endforeach
														</td>
													</tr>
												</table>
											</td>
										</tr>
									@endif
								</table>

							</td>
						</tr>
						@if (count($dati_json["rooms"]) == 1)
						<tr>
							<td align="left" valign="top" style="border-bottom: 5px solid #00A6DC;"></td>
						</tr>
						@endif

					</table>
					<!-- camera -->
				</td>
			</tr>
		@php $count++; @endphp
		@endforeach

		@if (count($dati_json["rooms"]) > 1)
			<tr>
				<td colspan="2">
					<!-- Richiesta -->
					<table style="background:#ffffff;  width: 100%" border="0" cellspacing="0" cellpadding="0">
						@if ($dati_json["information"] != "")
							<tr>
								<td align="left" valign="top" bgcolor="00A6DC" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; font-size:16px;  background: #00A6DC; color:#fff; text-align:left; padding:7px 15px; border-bottom:1px solid #00A6DC;">
									Richiesta
								</td>
							</tr>
							<tr>
								<td valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; font-size:16px; line-height: 28px; padding:15px; ">
									{{$dati_json["information"]}}
								</td>
							</tr>
							
							
						@endif					
					</table>
					<!-- Richiesta -->
				</td>
			</tr>
		@endif

		@include("emails.includes.firma_cliente", ["information2" => $dati_json["information2"], "email" => $dati_json["email"], "customer" => $dati_json["customer"], "phone" => $dati_json["phone"] , "language" => $dati_json["language"]])

	</tbody>
</table>

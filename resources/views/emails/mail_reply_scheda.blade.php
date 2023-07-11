<html>
	<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<!--[if mso]>
			<meta content="IE=edge" http-equiv="X-UA-Compatible">
		<![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title></title>
		<!--[if (gte mso 9)|(IE)]>
			<style type="text/css">
				table { border-collapse:collapse; }
			</style>
		<![endif]-->
		<style type="text/css">
			a:hover {text-decoration:none !important}
		</style>
	</head>
	<body>
		<table align="center" bgcolor="eeeeee" style="width: 100%; background: #eeeeee; color:#222; " border="0" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<td align="center" >
						<!--[if mso]>
							<table style="width:600px; text-align:center;" width="600" align="center">
								<tr>
									<td>
						<![endif]-->
						<!--[if !mso]><!-->
							<table style=" background:#ffffff; max-width:600px; width: 100%; padding:0px;" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td>
						<!--<![endif]-->
										{{-- CORPO DELLA MAIL --}}
										<table align="center" style="width: 100%; background:#ffffff;" bgcolor="#ffffff"  border="0" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td colspan="2" bgcolor="222222" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; background:#222222; font-weight:bold; font-size: 24px; padding:7px 15px; border-bottom: 5px solid #00A6DA">
														<span style="color:#ffffff;">INFO</span><span style="margin-left:-2px; color:#00A6DA; text-shadow: -2px 0 #222222">ALBERGHI</span>
													</td>
												</tr>

												<tr>
													<td colspan="2" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:15px 15px 0; text-align: left; font-size:20px;  color:#222; " align="left">
														<span style="font-size:16px;">Hai inviato una richiesta preventivo a:</span><br/>
														<b>{{$dati_mail['hotel_name']}}</b><sup style="color:#F9AE58; font-size: 14px;">{{$dati_mail['hotel_rating']}}</sup><br/>
														<span style="font-size:14px">{{$dati_mail['hotel_indirizzo']}}</span>
													</td>
												</tr>
												@php
													$hotel_id = $dati_mail['hotel_id'];
												@endphp
												<tr>
													<td colspan="2" align="left" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; text-align:left; padding:5px 15px 15px; font-size: 14px; ">
														<a href="https://www.info-alberghi.com/reply-count-hotel/{{$hotel_id}}">Ricontrolla le informazioni di {{$dati_mail['hotel_name']}}</a> <b style="font-size:18px">&rarr;</b><br/>
														<a href="tel:{{explode(',',$dati_mail['hotel_telefono'])[0]}}">Chiama {{$dati_mail['hotel_name']}} {{explode(',',$dati_mail['hotel_telefono'])[0]}}</a><b style="font-size:18px">&rarr;</b>
													</td>
												</tr>
												{{-- loop sui periodi e le relative policy di un hotel --}}
												@php $count = 1; @endphp
												@foreach ($dati_json["rooms"] as $room)
													<tr>
														<td colspan="2">
															<!-- camera -->
															<table style=" background:#ffffff;  width: 100%;" border="0" cellspacing="0" cellpadding="0">

																<tr>
																	<td align="left" valign="top" bgcolor="00A6DC" style="padding:7px 15px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; font-size:16px;  background: #00A6DC; text-align:left; color:#fff; border-bottom:1px solid #00A6DC;">
																		Riepilogo del preventivo richiesto @if(count($dati_json["rooms"]) > 1) - Camera {{$count}}@endif 
																	</td>
																</tr>
																
																<tr>
																	<td align="left" valign="top" style="padding:15px">

																		<table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
																			<tr>
																				<td valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 0 0 7px 0; width:70px; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;">Check-in</span></td>
																				<td align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 0 0 7px 0; text-align:left; font-size: 16px; border-bottom:1px solid #ddd;">
																					@php \Carbon\Carbon::setLocale("it"); @endphp
																					{!!\Carbon\Carbon::createFromFormat('d/m/Y',$room["checkin"])->formatLocalized("%a, <b>%d %B</b> %Y ")!!}
																				</td>
																			</tr>
																			<tr>
																				<td valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 7px 0;  width:70px; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;">Check-out</span></td>
																				<td align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding: 7px 0; text-align:left; font-size: 16px; border-bottom:1px solid #ddd;">
																					{!!\Carbon\Carbon::createFromFormat('d/m/Y',$room["checkout"])->formatLocalized("%a, <b>%d %B</b> %Y")!!}
																				</td>
																			</tr>
																			<tr>
																				<td valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:  7px 0;  width:70px; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;">Persone</span></td>
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
																					<b>{!!\Utility::getTrattamentoOfferte(strtolower($room["meal_plan"]), false) !!}</b>
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
																								<td colspan="2" align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;  padding:  7px 0 7px 7px; text-align:left; font-size: 16px; border-top: 1px solid #8CC152; border-left: 1px solid #8CC152; border-right: 1px solid #8CC152;">
																									<b>{{$dati_json['politiche_canc']}}</b>
																								</td>
																							</tr>
																							<tr>
																								<td colspan="2" align="left" valign="top" style="font-family: 'Helvetica Neue', Helvetica, 	 Arial, sans-serif; padding:7px; text-align:left; font-size: 15px; border-bottom: 1px solid #8CC152; border-left: 1px solid #8CC152; border-right: 1px solid #8CC152;">
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
											</tbody>
										</table>
										{{-- END - CORPO DELLA MAIL --}}
										
										<table style="background:#ffffff; width: 100%;" border="0" bgcolor="#ffffff" cellspacing="0" cellpadding="0">
											<tr><td style="background: #eeeeee;" bgcolor="#eeeeee">&nbsp;</td></tr>
											<tr>
												<td style="font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; font-size:14px; line-height: 28px; padding:7px 15px; ">
													&copy; {{date("Y")}} - Info Alberghi Srl.
												</td>
											</tr>
										</table>

									</td>
								</tr>
							</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>

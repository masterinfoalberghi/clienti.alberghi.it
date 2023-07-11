
<!DOCTYPE html>
<html>
<head>
	<title>Richiesta Preventivo MULTIPLA</title>
		<meta http-equiv="X-Render-Mode" content="html" />

<style>
		
		body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;margin-top:5px; }
		a { text-decoration: none !important; }
				
	</style>
	
</head>
<body>

<table width="100%" border="0" cellspacing="0" style="padding:0px 50px 150px 50px;text-align:center;" bgcolor="#dddddd">	<tr>
		<td>
		
			<table bgcolor="#ffffff" width="700" border="0" cellspacing="0" cellpadding="0" style="text-align:left; margin:0 auto;">
			
				<tr>
					<td bgcolor="#222222" width="700" border="0" style="padding:10px; color:#fff;text-transform: uppercase;">
						<a href="https://www.info-alberghi.com" style="color:#fff; text-decoration: none; font-size: 30px; font-weight: bold;">
							<span style="color:#ffffff">Info</span><span style="margin-left: -5px;color:#38A6E9; font-size: 30px; font-weight: bold; text-shadow: -2px 0 #222">alberghi</span>
						</a>
					</td>
				</tr>
				
				<tr>
					
					<td>
						
						<table bgcolor="#ffffff" width="700" border="0" cellspacing="0" cellpadding="0" style="margin:10px 0;">
							<tr>
								<td style="width:20%;font-size: 12px; background: #8CC152; padding:4px 8px; color:#fff; vertical-align: middle; text-align: center; " class="badge">WISHLIST</td>
								<td style="width:80%;padding-left: 20px;"><big style="font-size: 20px;padding:6px 0; display: block;">Richiesta preventivo</big></td>
							</tr>
						</table>
						
						
						<table bgcolor="#f5f5f5" width="700" style="font-size: 16px; font-family: Helvatica, sans-serif; line-height: 20px; ">
							<tr>
								<td style="padding:10px;">  			
									
									<?php echo $dati ?><br />
									
									<?php if ($in_lingua != '') { ?>
									<br />
																		
									<small><b>Nota</b>: L'utente ha spedito questa mail navigando il sito in <?php echo strtoupper($in_lingua) ?><br />
									Tieni in considerazione questa informazione quando rispondi</small>
									
									<?php } ?>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
				
				
				<tr>
					<td style=" font-size: 11px; color:#666; width:100%; padding:10px ; display: block;">
						&copy; <?php echo date("Y") ?> <b>Info Alberghi S.r.l</b>
					</td>
				</tr>
				
			</table>
			
			
		</td>
	</tr>
</table>

</body>
</html>




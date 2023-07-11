<table class="container" style="background:#eeeeee; width: 100%;" border="0" bgcolor="#eeeeee" cellspacing="0" cellpadding="0">
	<tbody>
		
		@if ($actual_link != $referer && $referer != "")
			@php
				if ( strpos($referer , ", " ) === false) {
					$uri = \Utility::urlToPage($referer);
					if($uri[1] != "#"):
						$link = '<a href="'. $uri[1] .'">'.$uri[0].'</a>';
					else:
						$link = "";
					endif;
				} else {
					$link = $referer;
				}
			@endphp

			@if ($link != "")
			<tr>
				<td style="font-size:14px; line-height: 22px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:15px 15px 7px;">
					<b style="color: #333">Pagina di provenienza:</b><br />
					<span style="color: #666666; ">{!!$link!!}</span>
				</td>
			</tr>
			@endif

		@endif
		 <tr>
			<td style="font-size:14px; line-height: 22px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:7px 15px 15px;">
				@if (isset($hotels_contact) && $hotels_contact != "")<b style="color: #333; ">Hotel contattati: {{$hotels_contact}}</b><br />@endif
				<span style="color: #666666;">Email spedita in data {{$date_created_at}} @if($hour_created_at) alle ore {{$hour_created_at}} @endif da un dispositivo {{$device}} @if($ip!="") con IP {{Utility::maskIP($ip)}} @endif. </span>
			</td>
		</tr>

		@if($sign_email != "")
		<tr>
			<td style="font-size:14px; line-height: 22px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif; padding:7px 15px 15px;">
				<b style="color: #333">Firma elettronica</b><br />
				<div style="font-size:12px; line-height: 20px; color:#666; word-break: break-all;" id="email-code">{{$sign_email}}</div>
			</td>
		</tr>
		@endif
		
	</tbody>
</table>

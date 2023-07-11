@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff; width: 100%; " border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px; padding:7px 15px 15px; font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Gentile {{$nome_cliente}},<br>ti informiamo che i <b style="background: #FFF9C4; padding:2px 5px;">"trattamenti"</b> relativi alla struttura <b style="background: #FFF9C4; padding:2px 5px;">"{{$hotel_name}}"</b> sono <b style="background: #FFF9C4; padding:2px 5px;">stati moderati dallo staff di Info Alberghi</b>.
				</p>
				{{-- @include("emails.includes.moderazione") --}}
				@include("emails.includes.button", ["title_button" => "Controlla le modifiche","url_button" => "https://www.info-alberghi.com/hotel.php?id=".$hotel_id])
				<p>
					@include("emails.includes.info")
					@include("emails.includes.firma")
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

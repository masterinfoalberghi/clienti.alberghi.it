@include("emails_test.includes.header")
@include("emails_test.includes.head-contact", ["nome" => "", "hotel_id" =>"" , "rating" =>"", "localita" => $localita, "img" => $img])
 
	<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;  padding:0px; "  border="0" cellspacing="0" cellpadding="0">
    	<tbody>

    		@php $count = 1; @endphp
    		@foreach ($camere as $camera)

    		
	        <tr>
	        	<td colspan="2" style="padding: 0 0 7px 0;">

	        		<!-- camera -->

	        		<table class="container" style=" background:#ffffff; max-width: 600px; width: 100%" border="0" cellspacing="0" cellpadding="0">

	        			<tr>

	        				<td align="left" valign="top" bgcolor="00A6DC" style="font-size:16px;  background: #00A6DC; text-align:left; color:#fff; padding:7px 15px; border-bottom:1px solid #00A6DC;">
	        					@if(count($camere) > 1)Camera {{$count}} -@endif Richiesta per {{$camera[0]}} notti
	        				</td>

	        			</tr>

	        			<tr>
	        				<td align="left" valign="top">

	        					<table style="width:100%; padding:15px;" border="0" cellspacing="0" cellpadding="0">
				        			<tr >

				        				<td valign="top" style="padding: 0 0 7px 0; width:70px; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;">Check-in</span></td>
				        				<td align="left" valign="top" style="padding: 0 0 7px 0; text-align:left; font-size: 16px; border-bottom:1px solid #ddd;">
				        					{!!$camera[1]!!} <em style="font-size: 14px; color: #D90B34">flex</em>
				        				</td>
				        			</tr>

				        			<tr >
				        				<td valign="top" style="padding: 7px 0;  width:70px; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;">Check-out</span></td>
				        				<td align="left" valign="top" style="padding: 7px 0; text-align:left; font-size: 16px; border-bottom:1px solid #ddd;">
				        					{!!$camera[2]!!} <em style="font-size: 14px; color: #D90B34">flex</em>
				        				</td>
				        			</tr>

				        			<tr >
				        				<td valign="top" style="padding:  7px 0;  width:70px; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;">Pax</span></td>
				        				<td align="left" valign="top" style="padding: 7px 0;  text-align:left; font-size: 16px; border-bottom:1px solid #ddd;">
				        					<b>{{$camera[3]}} Ad</b> + <b>{{count($camera[4])}} Ba</b> <span style="font-size:16px;">({{implode(",", $camera[4])}} anni)</span>
				        				</td>
				        			</tr>

				        			<tr>
				        				<td valign="top" style="width:70px; padding:  7px 0; border-bottom:1px solid #ddd;"><span style="font-size:12px; color: #888;" >Tratt.</span></td>
				        				<td align="left" valign="top" style=" padding:  7px 0; text-align:left; font-size: 16px; border-bottom:1px solid #ddd;">
				        					<b>{{$camera[5]}}</b>
				        				</td>

				        			</tr>

				        			@if ($richiesta != "" && count($camere) == 1)
				        			<tr>
				        				<td valign="top" style="width:70px; padding:  7px 0 0 0;"><span style="font-size:12px; color: #888;">Note</span></td>
				        				<td align="left" valign="top" style=" padding: 5px 0 0 0 ; text-align:left; font-size:16px; line-height: 24px; ">
				        					{{$richiesta}}
				        				</td>
				        			</tr>
				        			@endif
				        			

				        		</table>

			        		</td>
			        	</tr>

			        	@if (count($camere) == 1)
			        	<tr>

	        				<td align="left" valign="top" bgcolor="00A6DC" style="font-size:16px;  background: #00A6DC; text-align:left; color:#fff; padding:7px 15px; border-bottom:1px solid #00A6DC;">
	        					&nbsp;
	        				</td>

	        			</tr>
	        			@endif
	        			
	        		</table>

	        		<!-- camera -->

	        	</td>
	        </tr>

	       @php $count++; @endphp
	       @endforeach

	       @if ($richiesta != "" && count($camere) > 1)
	       <tr>
	        	<td colspan="2" style="padding: 0 0 7px 0;">

	        		<!-- Richiesta -->

	        		<table class="container" style="background:#ffffff; max-width: 600px; width: 100%" border="0" cellspacing="0" cellpadding="0">
	        			<tr>
	        				<td align="left" valign="top" bgcolor="00A6DC" style="font-size:16px;  background: #00A6DC; color:#fff; text-align:left; padding:7px 15px; border-bottom:1px solid #00A6DC;">
	        					Richiesta
	        				</td>
	        			</tr>
	        			<tr>
	        				<td valign="top" style="font-size:16px; line-height: 28px; padding:15px 15px 0; ">
				        		{{$richiesta}}
	        				</td>
	        			</tr>

	        			<tr>

	        				<td align="left" valign="top" bgcolor="00A6DC" style="font-size:16px;  background: #00A6DC; text-align:left; color:#fff; padding:7px 15px; border-bottom:1px solid #00A6DC;">
	        					&nbsp;
	        				</td>

	        			</tr>

	        		</table>

	        		<!-- Richiesta -->

	        	</td>
	        </tr>
	        @endif

	       @include("emails_test.includes.firma_cliente", ["email_cliente" => $email_cliente, "nome_cliente" => $nome_cliente, "lingua" => $in_lingua, "telefono_cliente" => $telefono_cliente])
	        

    </tbody>
</table>

@include("emails_test.includes.foot-contact", ["hotel_contattati" => $hotel_contattati, "ip" => $ip, "data" => $data, "ora" => $ora ])
@include("emails_test.includes.footer")
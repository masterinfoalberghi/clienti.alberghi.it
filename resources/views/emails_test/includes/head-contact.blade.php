<table class="container" align="center" style="max-width: 600px; width: 100%; background:#ffffff;" bgcolor="#ffffff"  border="0" cellspacing="0" cellpadding="0">
    <tbody>

        <tr>

            <td width="80%" bgcolor="222222" style="background:#222222; font-weight:bold; font-size: 24px; padding:7px 15px; border-bottom: 5px solid #00A6DA"">
                <span style="color:#ffffff;">INFO</span><span style="margin-left:-5px; color:#00A6DA; text-shadow: -5px 0 #222222">ALBERGHI</span>
            </td>

            <td width="20%" bgcolor="222222" align="right" valign="middle" style="text-align:right; background:#222222; padding:13px 15px 7px 5px; color:#ffffff; border-bottom: 5px solid #00A6DA; ">
                {{$img}}
            </td>

        </tr>

     
        @if($img == "Diretta")
            
            <tr>
                <td colspan="2" style="padding:15px 15px 0; text-align: left; font-size:20px;  color:#222; " align="left">{{$nome}} <sup style="color:#F9AE58; font-size: 14px;">{{$rating}}</sup> - {{$localita}}</td>
            </tr>
            
            <tr>
                <td colspan="2" align="left" style="text-align:left; padding:5px 15px 15px; font-size: 14px; ">www.info-alberghi.com/hotel.php?id={{$hotel_id}}</td>
            </tr>

        @elseif( $img == "Multi")
            
            <tr>
                <td colspan="2" style="padding:15px; text-align: left; font-size:20px;  color:#222; " align="left">Preventivo per la localita <b>{{$localita}}<b></td>
            </tr>

        @else
            
            <tr>
               <td colspan="2" style="padding:15px; text-align: left; font-size:20px;  color:#222; " align="left">Richiesta preventivo</td>
            </tr>
        
        @endif

        
    </tbody>
</table>
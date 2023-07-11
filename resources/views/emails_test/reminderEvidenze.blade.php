@include("emails_test.includes.header")
@include("emails_test.includes.head-title", ["title" => $titolo_email])

<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;   padding:7px 15px; "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
        <tr>
        	<td style="line-height: 28px;  font-size: 16px;">

        		<p style="margin-top:0">
        			Bella Fratella!,<br>
        			ti ricordo che hai inserito un promemoria per l'evidenza <i>"{{$tipo}}"</i> 
        			@if (empty($tipo))
						dal titolo <i>"{{$titolo_offerta}}"</i>, 
					@else
						@if (!empty($titolo_offerta))
							con note aggiuntve <i>"{!!$titolo_offerta!!}"</i>, 
						@endif
					@endif
					valida nel periodo <i>"{{$validita_offerta}}"</i> e appartenente a <b>{{$hotel_name}}</b> (ID = {{$hotel_id}}) di {{$localita}}.<br /><br />
					Se non hai capito una cippa puoi sempre mandarti una email o telefonarti (il numero lo sai).
        		</p>
        		
        		<p>
					@include("emails_test.includes.firma")
        		</p>
        	</td>
        </tr>
    </tbody>
</table>

@include("emails_test.includes.footer")
@include("emails.includes.header")
@include("emails.includes.head-title", ["title" => $oggetto])
<table class="container" align="center" style=" background:#ffffff; width: 100%;  " border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="line-height: 28px;  padding:7px 15px 15px;  font-size: 16px; font-family: 'Helvetica Neue', Helvetica,  Arial, sans-serif;">
				<p style="margin-top:0">
					Gentile Cliente,<br>
					ti informiamo che abbiamo creato con successo le tue credenziali per accedere al portale info-alberghi.com.
				</p>
				
                <p>
                    <strong>User:</strong> {{$user}} 
                </p> 
                <p>
                    <strong>Password:</strong> <span style="background: #eeeeee ">{{$password}}</span>
                  
                </p>

                <p>
                    Può accedere alla sua area riservata all'indirizzo clienti.info-alberghi.com inserendo le credenziali sopra riportate.
                </p>
				
				<p>
					@include("emails.includes.info")
					@include("emails.includes.firma")
				</p>
			</td>
		</tr>
	</tbody>
</table>
@include("emails.includes.footer")

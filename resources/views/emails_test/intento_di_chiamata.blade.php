@include("emails_test.includes.header")
@include("emails_test.includes.head-title", ["title" => $titolo_email])

<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;  padding:7px 15px; "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
        <tr>
        	<td style="line-height: 28px;  font-size: 16px;">

        		<p style="margin-top:0">
        			Gentile {{$nome_cliente}},<br>La informiamo che il {{ $ts->format('d/m/Y') }} alle ore {{ $ts->toTimeString() }} un utente (che utilizza il sistema operativo {{$os}} ed identificato dall'IP {{$ip}}) ha cliccato il bottone "Chiama" della Sua scheda hotel per mettersi in contatto con la Sua struttura.
        		</p>

                <p>
                    Questo messaggio ha il solo scopo di informarLa ritenendo Le telefonate uno strumento molto efficace per concludere le prenotazioni.
                </p>
        		
        		<p>
        			@include("emails_test.includes.info")
					@include("emails_test.includes.firma")
        		</p>

        </tr>
    </tbody>
</table>

@include("emails_test.includes.footer")



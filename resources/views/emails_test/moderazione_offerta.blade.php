@include("emails_test.includes.header")
@include("emails_test.includes.head-title", ["title" => $titolo_email])

<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;   padding:7px 15px; "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
        <tr>
        	<td style="line-height: 28px;  font-size: 16px;">

        		<p style="margin-top:0">
        			Gentile {{$nome_cliente}},<br>
                    La informiamo che il testo dell'offerta <i>"{{$titolo_offerta}}"</i> da Lei inserito &egrave; stato parzialmente modificato dal nostro <b>content editor</b> al fine di ottimizzarne l'efficacia e l'utilit&agrave; per gli utenti.<br />
                </p>

                <p>
                    La moderazione pu&ograve; essere avvenuta per errori di forma (errori di battitura, ortografia o grammatica) o contenuto, qualora esso o parti di esso siano stati ritenuti non idonei ai nostri standard qualitativi.<br />
        		</p>
        		
                @if (isset($traduzione) && $traduzione)
                <p>
                    La informiamo inoltre che il sistema ha tradotto automaticamente il nuovo testo dall'italiano e <b>sovrascritto</b> le traduzioni già presenti.
                </p>
                @endif

                @include("emails_test.includes.button", ["title_button" => "Controlla le modifiche","url_button" => "https://www.info-alberghi.com/hotel.php?id=".$hotel_id."&".$ancora])
                
        		<p>
					@include("emails_test.includes.info")
                    @include("emails_test.includes.firma")
        		</p>
									
        </tr>
    </tbody>
</table>

@include("emails_test.includes.footer")



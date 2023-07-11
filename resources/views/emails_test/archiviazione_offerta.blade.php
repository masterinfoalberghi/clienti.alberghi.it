@include("emails_test.includes.header")
@include("emails_test.includes.head-title", ["title" => $titolo_email])

<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;   padding:7px 15px; "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
        <tr>
        	<td style="line-height: 28px;  font-size: 16px;">

        		<p style="margin-top:0">
        			Gentile {{$nome_cliente}},<br>
                    La informiamo che l'offerta <i>"{{$titolo_offerta}}"</i> da Lei inserita &egrave; stata moderata e archiviata (<b>quindi non visibile online</b>) in quanto non ritenuta idonea agli standard qualitativi adottati dal nostro team.
                </p>

                <p>
                    La moderazione pu&ograve; essere avvenuta per errori di forma (errori di battitura, ortografia o grammatica) o contenuto.<br />
        		</p>
        		
        		<p>
					@include("emails_test.includes.info")
                    @include("emails_test.includes.firma")
        		</p>
									
        </tr>
    </tbody>
</table>

@include("emails_test.includes.footer")





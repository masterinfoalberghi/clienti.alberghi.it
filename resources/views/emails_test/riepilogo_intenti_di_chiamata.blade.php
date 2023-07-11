@include("emails_test.includes.header")
@include("emails_test.includes.head-title", ["title" => $titolo_email])

<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;   padding:7px 15px; "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
        <tr>
        	<td style="line-height: 28px;  font-size: 16px;">

                <p style="margin-top:0">
                    Gentile {{$nome_cliente}},<br>qui di seguito Le riportiamo il riepilogo giornaliero delle telefonate (azioni di chiamata) che ha ricevuto in data {{ $ieri }}.
                </p>

                <p>
                    {{count($log_arr)}} utenti hanno cliccato da mobile il bottone <i>"Chiama"*</i> presente nulla Sua scheda hotel per mettersi in contatto diretto con la Sua struttura:
                </p>

                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr >
                        <td width="10%" style="border-bottom: 1px solid #ddd;">#</td>
                        <td style="padding:0 15px 0 0; border-bottom: 1px solid #ddd;">Ora</td>
                        <td style="padding:0 15px 0 0; border-bottom: 1px solid #ddd;">OS</td>
                        <td style="padding:0 15px 0 0; border-bottom: 1px solid #ddd;">IP</td>
                    </tr>

                    @php $count = 1; @endphp

                    @foreach ($log_arr as $log)

                        <tr>
                            <td>{{$count}}</td>{!!$log!!}
                        </tr>

                        @php $count++; @endphp

                    @endforeach
                
                </table>

                <p>
                    Questo messaggio ha carattere informativo/statistico: non possiamo conoscere l'esito delle telefonate.
                </p>

                @include("emails_test.includes.info")
                @include("emails_test.includes.firma")

                <p>
                    * Qui pu&ograve; vedere il bottone "Chiama" presente sulla Sua scheda hotel nella versione per dispositibivi mobili di Info Alberghi<br><br>
                    <img src="//static.info-alberghi.com/images/azione-chiama.jpg" alt="Bottone Chiama" width="335" height="500">
        		</p>
									
        </tr>
    </tbody>
</table>

@include("emails_test.includes.footer")



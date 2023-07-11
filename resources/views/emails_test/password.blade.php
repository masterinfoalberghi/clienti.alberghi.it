@include("emails_test.includes.header")
@include("emails_test.includes.head-title", ["title" => $titolo_email])

<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;  padding:7px 15px; "  border="0" cellspacing="0" cellpadding="0">
	<tbody>
        <tr>
        	<td style="line-height: 28px;  font-size: 16px;">
                
        		<p style="margin-top:0">
        			Gentile {{$nome_cliente}},<br>qualcuno ha richiesto la procedura di <i>"recupero password"</i> per l'account <strong>{{ $username }}</strong>.<br />
        			Se non ha richiesto Lei il cambio password, semplicemente ignori questa email.
        		</p>

                @include("emails_test.includes.button", ["title_button" => "Avvia la procedura di recupero","url_button" => url('admin/password/reset/'.$token)])

        		<p>
        			@include("emails_test.includes.info")
                    @include("emails_test.includes.firma")
        		</p>
        	</td>
        </tr>
    </tbody>
</table>



					
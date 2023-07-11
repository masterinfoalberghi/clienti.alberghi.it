@include("emails_test.includes.header")
@include("emails_test.includes.head-title", ["title" => $titolo_email])

<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;  padding:7px 15px; "  border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td style="line-height: 28px;  font-size: 16px;">

                <p style="margin-top:0">
                    Gentile {{$nome_cliente}},<br>La informiamo che alcuni titoli della <i>"galleria fotografica"</i> relativi alla struttura denominata <b>"{{$hotel_name}}"</b> sono stati moderati dallo staff di Info Alberghi.
                </p>
                
                @include("emails_test.includes.button", ["title_button" => "Controlla le modifiche","url_button" => "https://www.info-alberghi.com/hotel.php?id=".$hotel_id])

                <p>
                    @include("emails_test.includes.info")
                    @include("emails_test.includes.firma")
                </p>
                                    
        </tr>
    </tbody>
</table>

@include("emails_test.includes.footer")



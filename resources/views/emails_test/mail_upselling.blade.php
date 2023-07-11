@include("emails_test.includes.header")
@include("emails_test.includes.head-title", ["title" => ""])

<table class="container" align="center" style=" background:#ffffff;  max-width: 600px; width: 100%;   padding:7px 15px;"  border="0" cellspacing="0" cellpadding="0">
	<tbody>
        <tr>
        	<td style="line-height: 28px;  font-size: 16px;">

        		<p style="margin-top:0">
        			Gentile {{$nome_cliente}},<br>
                    grazie per aver utilizzato il servizio di Info Alberghi, sarai contattato al pi&ugrave; presto dalla struttura {{$hotel_name}} di {{$localita}}.
        		</p>

                <p>
                    Nel frattempo potrebbe interessarti anche visitare: 
                </p>

                <table>

                    @foreach($hotels as $hotelrow)
                        @foreach($hotelrow as $hotel)
                        <tr>

                            <td>
                                
                                <a href="{{$hotel[0]}}" target="_blank" >
                                    <img src="{{$hotel[1]}}" style="width:100%;height:auto">
                                </a><br />

                                <p style="line-height:20px; margin:0;">{{$hotel[2]}} <sup style="color:#F9AE58; font-size: 14px;">{{$hotel[3]}}</sup></p>
                                <p style="font-size:12px; line-height:20px;  margin:0 0 15px 0;">{{$hotel[4]}} - {{$hotel[5]}}</p>

                            </td>
                                                  
                        </tr>
                        @endforeach
                    @endforeach

                </table>

                <p>
                    <span style="font-size:20px; color: #00A6DC">Dove sono questio hotel?</span><br />
                </p>

                <img src="https://www.info-alberghi.com/emails_test/mappa.jpg" style="width:100%;height:auto"/>
                
                <?php /* <span style="display: block; text-align: center; padding:10px;">
                    <a href="https://www.info-alberghi.com/mappa-ricerca">Prova la nostra ricerca per mappa</a>
                </span> */ ?>

        		<p>
					@include("emails_test.includes.firma")
        		</p>
									
        </tr>
    </tbody>
</table>

@include("emails_test.includes.footer")
@extends('templates.admin')

@section('title')
Invio da whatsapp desktop a numeri non in rubrica
@endsection

@section('content')
<div class="wrapper_content_whatsapp">
  <span  data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Clicca per gestire i template dei messaggi WhastApp da inviare ai clienti" data-original-title="Template">
    <span class="badge badge-info add_template"  data-toggle="modal" data-target="#template_wa"><i class="entypo-plus"></i></span>
  </span>

<p style="text-align:center;">Requisiti per il corretto funzionamento: windows 10 o Mac, <strong>APP installata e loggati nell'APP</strong> con il proprio numero di whatsapp</p>
<hr />
<div class="row">
  <div class="form-group col-md-2 col-centered">
    <select id="prefix" class="form-control">
      <optgroup label="Scelte rapide">
        <option value="39">+39 Italia</option>  
        <option value="49">+49 Germania</option>  
        <option value="41">+41 Svizzera</option>  
        <option value="33">+33 Francia</option>   
        <option value="1">+1 Stati Uniti d&#039;America/Canada</option>  
        <option value="32">+32 Belgio</option>  
        <option value="44">+44 Regno Unito</option>   
        <option value="43">+43 Austria</option>   
        <option value="40">+40 Romania</option>   
        <option value="7">+7 Russia</option>   
        <option value="48">+48 Polonia</option>   
      </optgroup>
      <optgroup label="Tutti i prefissi">
        <option value="93">+93 Afghanistan</option>
        <option value="355">+355 Albania</option>
        <option value="213">+213 Algeria</option>
        <option value="376">+376 Andorra</option>
        <option value="244">+244 Angola</option>
        <option value="672">+672 Isola Norfolk</option>
        <option value="54">+54 Argentina</option>
        <option value="374">+374 Armenia</option>
        <option value="297">+297 Aruba</option>
        <option value="247">+247 Ascensione</option>
        <option value="61">+61 Isole Cocos-Keeling</option>
        <option value="43">+43 Austria</option>
        <option value="994">+994 Azerbaijan</option>
        <option value="973">+973 Bahrain</option>
        <option value="880">+880 Bangladesh</option>
        <option value="375">+375 Bielorussia</option>
        <option value="32">+32 Belgio</option>
        <option value="501">+501 Belize</option>
        <option value="229">+229 Benin</option>
        <option value="975">+975 Bhutan</option>
        <option value="591">+591 Bolivia</option>
        <option value="387">+387 Bosnia ed Erzegovina</option>
        <option value="267">+267 Botswana</option>
        <option value="55">+55 Brasile</option>
        <option value="673">+673 Brunei Darussalam</option>
        <option value="359">+359 Bulgaria</option>
        <option value="226">+226 Burkina Faso</option>
        <option value="257">+257 Burundi</option>
        <option value="855">+855 Cambogia</option>
        <option value="237">+237 Camerun</option>
        <option value="238">+238 Isole di Capo Verde</option>
        <option value="236">+236 Repubblica Centrafricana</option>
        <option value="235">+235 Chad</option>
        <option value="64">+64 Nuova Zelanda</option>
        <option value="56">+56 Isola di Pasqua</option>
        <option value="86">+86 Cina (RPC)</option>
        <option value="61-8">+61-8 Isola di Natale</option>
        <option value="57">+57 Colombia</option>
        <option value="269">+269 Isola di Mayotte</option>
        <option value="242">+242 Congo</option>
        <option value="243">+243 Repubblica Democratica del Congo</option>
        <option value="682">+682 Isole Cook</option>
        <option value="506">+506 Costa Rica</option>
        <option value="225">+225 Costa d&#039;Avorio (Costa d&#039;Avorio)</option>
        <option value="385">+385 Croazia</option>
        <option value="53">+53 Cuba</option>
        <option value="5399">+5399 Baia di Guantanamo</option>
        <option value="599">+599 Antille Olandesi</option>
        <option value="357">+357 Cipro</option>
        <option value="420">+420 Repubblica Ceca</option>
        <option value="45">+45 Danimarca</option>
        <option value="246">+246 Diego Garcia</option>
        <option value="253">+253 Gibuti</option>
        <option value="670">+670 Timor Est</option>
        <option value="593">+593 Ecuador</option>
        <option value="20">+20 Egitto</option>
        <option value="503">+503 El Salvador</option>
        <option value="240">+240 Guinea Equatoriale</option>
        <option value="291">+291 Eritrea</option>
        <option value="372">+372 Estonia</option>
        <option value="251">+251 Etiopia</option>
        <option value="500">+500 Isole Falkland (Malvinas)</option>
        <option value="298">+298 Isole Faroe</option>
        <option value="679">+679 Isole Figi</option>
        <option value="358">+358 Finlandia</option>
        <option value="33">+33 Francia</option>
        <option value="596">+596 Martinique</option>
        <option value="594">+594 Guiana francese</option>
        <option value="689">+689 Polinesia francese</option>
        <option value="241">+241 Repubblica Gabonese</option>
        <option value="220">+220 Gambia</option>
        <option value="995">+995 Georgia</option>
        <option value="49">+49 Germania</option>
        <option value="233">+233 Ghana</option>
        <option value="350">+350 Gibilterra</option>
        <option value="30">+30 Grecia</option>
        <option value="299">+299 Groenlandia</option>
        <option value="590">+590 Guadeloupe</option>
        <option value="502">+502 Guatemala</option>
        <option value="245">+245 Guinea-Bissau</option>
        <option value="224">+224 Guinea</option>
        <option value="592">+592 Guyana</option>
        <option value="509">+509 Haiti</option>
        <option value="504">+504 Honduras</option>
        <option value="852">+852 Hong Kong</option>
        <option value="36">+36 Ungheria</option>
        <option value="354">+354 Islanda</option>
        <option value="91">+91 India</option>
        <option value="62">+62 Indonesia</option>
        <option value="98">+98 Iran</option>
        <option value="964">+964 Iraq</option>
        <option value="353">+353 Irlanda</option>
        <option value="972">+972 Israele</option>
        <option value="39">+39 Italia</option>
        <option value="81">+81 Giappone</option>
        <option value="962">+962 Giordania</option>
        <option value="7">+7 Russia</option>
        <option value="254">+254 Kenia</option>
        <option value="686">+686 Kiribati</option>
        <option value="850">+850 Corea del Nord</option>
        <option value="82">+82 Corea del Sud</option>
        <option value="965">+965 Kuwait</option>
        <option value="996">+996 Repubblica del Kirghizistan</option>
        <option value="856">+856 Laos</option>
        <option value="371">+371 Lettonia</option>
        <option value="961">+961 Libano</option>
        <option value="266">+266 Lesoto</option>
        <option value="231">+231 Liberia</option>
        <option value="218">+218 Libia</option>
        <option value="423">+423 Liechtenstein</option>
        <option value="370">+370 Lituania</option>
        <option value="352">+352 Lussemburgo</option>
        <option value="853">+853 Macao</option>
        <option value="389">+389 Macedonia</option>
        <option value="261">+261 Madagascar</option>
        <option value="265">+265 Malawi</option>
        <option value="60">+60 Malaysia</option>
        <option value="960">+960 Maldive</option>
        <option value="223">+223 Repubblica Mali</option>
        <option value="356">+356 Malta</option>
        <option value="692">+692 Isole Marshall</option>
        <option value="222">+222 Mauritania</option>
        <option value="230">+230 Mauritius</option>
        <option value="52">+52 Messico</option>
        <option value="691">+691 Micronesia</option>
        <option value="373">+373 Moldova</option>
        <option value="377">+377 Monaco</option>
        <option value="976">+976 Mongolia</option>
        <option value="382">+382 Montenegro</option>
        <option value="212">+212 Marocco</option>
        <option value="258">+258 Mozambico</option>
        <option value="95">+95 Myanmar</option>
        <option value="264">+264 Namibia</option>
        <option value="674">+674 Nauru</option>
        <option value="977">+977 Nepal</option>
        <option value="31">+31 Olanda</option>
        <option value="687">+687 Nuova Caledonia</option>
        <option value="505">+505 Nicaragua</option>
        <option value="227">+227 Niger</option>
        <option value="234">+234 Nigeria</option>
        <option value="683">+683 Niue</option>
        <option value="47">+47 Norvegia</option>
        <option value="968">+968 Oman</option>
        <option value="92">+92 Pakistan</option>
        <option value="680">+680 Palau</option>
        <option value="970">+970 Insediamenti palestinesi</option>
        <option value="507">+507 Panama</option>
        <option value="675">+675 Papua Nuova Guinea</option>
        <option value="595">+595 Paraguay</option>
        <option value="51">+51 Perù</option>
        <option value="63">+63 Filippine</option>
        <option value="48">+48 Polonia</option>
        <option value="351">+351 Portogallo</option>
        <option value="974">+974 Qatar</option>
        <option value="262">+262 Union Island</option>
        <option value="40">+40 Romania</option>
        <option value="250">+250 Ruanda Repubblica</option>
        <option value="290">+290 Sant&#039;Elena</option>
        <option value="508">+508 St. Pierre e Miquelon</option>
        <option value="685">+685 Samoa</option>
        <option value="378">+378 San Marino</option>
        <option value="239">+239 São Tomé e Príncipe</option>
        <option value="966">+966 Arabia Saudita</option>
        <option value="221">+221 Senegal</option>
        <option value="381">+381 Serbia</option>
        <option value="248">+248 Repubblica delle Seychelles</option>
        <option value="232">+232 Sierra Leone</option>
        <option value="65">+65 Singapore</option>
        <option value="421">+421 Repubblica Slovacca</option>
        <option value="386">+386 Slovenia</option>
        <option value="677">+677 Isole Salomone</option>
        <option value="252">+252 Repubblica Democratica Somala</option>
        <option value="27">+27 Sud Africa</option>
        <option value="34">+34 Spagna</option>
        <option value="94">+94 Sri Lanka</option>
        <option value="249">+249 Sudan</option>
        <option value="597">+597 Suriname</option>
        <option value="268">+268 Swaziland</option>
        <option value="46">+46 Svezia</option>
        <option value="41">+41 Svizzera</option>
        <option value="963">+963 Siria</option>
        <option value="886">+886 Taiwan</option>
        <option value="992">+992 Tajikistan</option>
        <option value="255">+255 Zanzibar</option>
        <option value="66">+66 Tailandia</option>
        <option value="228">+228 Repubblica del Togo</option>
        <option value="690">+690 Tokelau</option>
        <option value="676">+676 Isole Tonga</option>
        <option value="216">+216 Tunisia</option>
        <option value="90">+90 Turchia</option>
        <option value="993">+993 Turkmenistan</option>
        <option value="688">+688 Tuvalu</option>
        <option value="256">+256 Uganda</option>
        <option value="380">+380 Ucraina</option>
        <option value="971">+971 Emirati Arabi Uniti</option>
        <option value="44">+44 Regno Unito</option>
        <option value="1" >+1 Stati Uniti d&#039;America/Canada</option>
        <option value="878">+878 Universal Personal Telecommunications (UPT)</option>
        <option value="598">+598 Uruguay</option>
        <option value="998">+998 Uzbekistan</option>
        <option value="678">+678 Vanuatu</option>
        <option value="58">+58 Venezuela</option>
        <option value="84">+84 Vietnam</option>
        <option value="808">+808 Wake Island</option>
        <option value="681">+681 Isole Wallis e Futuna</option>
        <option value="967">+967 Yemen</option>
        <option value="260">+260 Zambia</option>
        <option value="263">+263 Zimbabwe</option>
      </optgroup>
    </select>
  </div>
</div>
<div class="row">
  <div class="form-group col-sm-2 col-centered">
    <input class="form-control" placeholder="numero whatsapp destinatario" value="" name="tel" type="tel" id="tel">
  </div>
</div>
<div class="row">
  <div class="form-group col-sm-5 col-centered">
      <textarea class="form-control" placeholder="messaggio"  rows="8" id="msg"></textarea>
  </div>
</div>
@if ($wa_templates->count())
  <div class="row form-group">
      <div class="form-group col-sm-offset-3 col-sm-7">
          <p>Clicca su uno dei pulsanti per incollare un testo già pronto da inviare. Per creare altri testi predefiniti clicca sul pulsante rosso in alto a sinistra</p>
          @foreach ($wa_templates as $template)
              <div id="{{$template->id}}" class="label">
                {{-- <button class="btn btn-default popover-default" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{Str::words($template->testo, 15, ' .....')}}" data-original-title="{{$template->titolo}}">{{$template->titolo}}
                </button> --}}
                <button class="btn btn-info">
                  {{$template->titolo}}
                </button>
              </div>
              <div id="testo_{{$template->id}}" style="display: none">
                {{$template->testo}}
              </div>
          @endforeach
      </div>
  </div>
@endif
<hr>
<div class="row">
  <div class="form-group col-sm-5 col-centered" style="text-align: center;">
       <button type="button" class="btn btn-green btn-icon icon-left" id="submit_form" onClick="sendWa()">
          Invia con WhatsApp
          <i class="icon-whatsapp"></i>
        </button>
  </div>
</div>
<div class="row">
  <div class="form-group col-sm-5">
    <p style="font-size:15px"><a href="https://www.info-alberghi.com/note/whatsapp-per-hotel/" target="_blank"><strong><u>Approfondisci perchè usare whatsapp in hotel</u></strong></a> (by Lucio Bonini)</p>
    <p style="font-size:15px">Hai una domanda? <a href="mailto:mail@info-alberghi.com"><strong><u>scrivici una mail</u></strong></a></p>
  </div>
</div>

  <!-- Modal Elenco -->
  <div class="modal fade" id="template_wa">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">
            @if (isset($param) && $param == 'form')
              Template
            @else
              Elenco template
            @endif
          </h4>
        </div>
        <div class="modal-body">
        @if (isset($param) && ($param == 'index' || $param == 'lista'))
          @include('admin.wa-template_index')
        @endif
        @if (isset($param) && $param == 'form')
          @include('admin.wa-template_form')
        @endif 
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@section('onbodyclose')

  <script>
    jQuery(document).ready(function() {


    @if(isset($param) && $param != 'index')
      jQuery("#template_wa").modal({
          backdrop:'true',
          keyboard:'false'
          });
    @endif

    jQuery('#template_wa').on('hide.bs.modal', function (e) {
      document.location.href= "{{ route('whatsapp-non-rubirca') }}";
    })



    });
  </script>

  <script type="text/javascript">


    function sendWa()
      {
      var action = "https://api.whatsapp.com/send/?phone=";

      var tel = document.getElementById("tel").value;

      if(tel == '') {
        alert('Inserire il numero WhatsApp');
        return false;
        }

      var select = document.getElementById("prefix");

      var prefix = select.options[select.selectedIndex].value;

      var msg = document.getElementById("msg").value;


      var new_action = action + prefix + tel + '&text=' + encodeURI(msg);

      //console.log('new_action = '+new_action);
      //document.location.href=new_action;

      window.open(new_action, '_blank');


      } 

    function creaUrlWhatsApp($=jQuery) {

        var action = "https://api.whatsapp.com/send/?phone=";
        var prefix = $("#prefix").val();
        var tel = $("#tel").val();

        var msg = $("#msg").val();
        // console.log('msg '+msg);
        var new_action = action + prefix + tel + '?text=' + encodeURI(msg);
        // console.log('new_action '+new_action);
        return new_action;

    }

    function submitForm($=jQuery) {

        var tel = $("#tel").val();

        if(tel == '') {

            alert('Inserire il numero WhatsApp');
            return false;

        } else {

            // regex of \D, which is a shorthand character class that matches all non-digits:
            tel = tel.replace(/\D/g,'');
            $("#tel").val(tel);

            new_action = creaUrlWhatsApp();

            window.open(new_action, '_blank');

        }

    }

   jQuery(function($=jQuery){
      $(".label").click(function(e){
        e.preventDefault();

        var id = $(this).attr('id');
        var txtToAdd = $.trim($("#testo_"+id).text());

        var $txt = $("#msg");
        var caretPos = $txt[0].selectionStart;
        var textAreaTxt = $txt.val();
        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );

      });
   });


   </script>

@endsection
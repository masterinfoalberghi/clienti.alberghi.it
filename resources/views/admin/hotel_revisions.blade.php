@extends('templates.admin')

@section('title')
    Revisioni Hotel ( id: {{$data["record"]->id}} )<br/>
    <small>Versione APLHA</small>
@endsection

@section('onheadclose')

    <script type="text/javascript">

        jQuery(document).ready(function($) {

            var currentRevision = 0;
            $("ul#rev li").eq(0).addClass("current");
            // var currentData = JSON.parse("{{$data['record']}}");

        });

    </script>

    <style>
        ul#rev, ul#rev li { padding:0; margin:0; list-style:none;}
        ul#rev li { padding:2px;}
        ul#rev li:hover,
        ul#rev li.current { background: #990000; color:#fff; border-radius: 3px; margin:1px 0; cursor: pointer;}
    </style>

@endsection

@section('content')
    
    @php
        $current = $data["record"];
        $revisions = $data["record"]->revisions;
        $commerciali = [0 => "Nussun commerciale"] + $data["commerciali"];
        $tipologie = [0 => "Nussun tipologia"] + $data["tipologie"];
        $categorie = [0 => "Nussuna categoria"] + $data["categorie"];
    @endphp

    <div class="row">
    <div class="form-group">
        
        <div  class="col-sm-9">
            <div id="records" style="font-size:14px; padding:15px;">

                <table class="table table-hover table-bordered table-responsive datatable dataTable">

                    <!-- parametro, testo, suffisso, revisione_corrent, tutte_le_revisioni -->
                    {!!Utility::revisionData("nome", "Nome struttura", "", $current, $revisions)!!}
                    {!!Utility::revisionData("commerciale_id", "Commerciale id", $commerciali , $current, $revisions)!!}
                    {!!Utility::revisionData("attivo", "Attivo", [0 => "disattivo", 1 => "attivo"], $current, $revisions)!!}
                    {!!Utility::revisionData("chiuso_temp", "Chiudo Temporaneamente", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("hide_price_list", "Listini disattivati", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("prezzo_min", "Prezzo Minimo", "", $current, $revisions)!!}
                    {!!Utility::revisionData("prezzo_max", "Prezzo Massimo", "", $current, $revisions)!!}
                    {!!Utility::revisionData("cir", "Cir", "", $current, $revisions)!!}

                </table><br />

                <h2>Struttura</h2>
                <table class="table table-hover table-bordered table-responsive datatable dataTable">

                    {!!Utility::revisionData("tipologia_id", "Tipologia", $tipologie , $current, $revisions)!!}
                    {!!Utility::revisionData("categoria_id", "Categoria", $categorie, $current, $revisions)!!}

                </table><br />

                <h2>Informazioni geografiche</h2>
                <table class="table table-hover table-bordered table-responsive datatable dataTable">

                    {!!Utility::revisionData("localita_id", "Localita", "", $current, $revisions)!!}
                    {!!Utility::revisionData("cap", "Cap", "", $current, $revisions)!!}
                    {!!Utility::revisionData("indirizzo", "Indirizzo", "", $current, $revisions)!!}
                    {!!Utility::revisionData("mappa_latitudine", "Latitudine", "", $current, $revisions)!!}
                    {!!Utility::revisionData("mappa_longitudine", "Longitudine", "", $current, $revisions)!!}
                    <tr>
                        <th colspan="2"><br /><b>Distanze</b></th>
                    </tr>
                    {!!Utility::revisionData("distanza_centro", "Centro", "", $current, $revisions)!!}
                    {!!Utility::revisionData("distanza_staz", "Stazione", "", $current, $revisions)!!}
                    {!!Utility::revisionData("distanza_spiaggia", "Spiaggia", "", $current, $revisions)!!}
                    {!!Utility::revisionData("distanza_fiera", "Fiera", "", $current, $revisions)!!}
                    {!!Utility::revisionData("distanza_casello", "Casello", "", $current, $revisions)!!}
                    {!!Utility::revisionData("distanza_casello_label", "Desccrizione casello",  $current->distanza_casello_label ,$current, $revisions)!!}
                   
                    {{-- <tr>
                        <th colspan="2"><br /><b>Punti di interesse</b></th>
                    </tr>
                    @foreach ($current->poi as $key => $poi)
                        {!!Utility::revisionData($poi->pivot->distanza, $poi->poi_lingua[0]->nome, "", $current, $revisions)!!}
                    @endforeach --}}
                    

                </table><br />

                <h2>Slug</h2>
                <table class="table table-hover table-bordered table-responsive datatable dataTable">

                    {!!Utility::revisionData("slug_it", "IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("slug_en", "EN", "",  $current, $revisions)!!}
                    {!!Utility::revisionData("slug_fr", "FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("slug_de", "DE", "", $current, $revisions)!!}

                </table>

                <h2>Recapiti</h2>
                <table class="table table-hover table-bordered table-responsive datatable dataTable">

                    {!!Utility::revisionData("telefono", "Telefono", "", $current, $revisions)!!}
                    {{-- {!!Utility::revisionData("fax", "Fax", "", $current, $revisions)!!} --}}
                    {!!Utility::revisionData("cell", "Cellulare", "", $current, $revisions)!!}
                    {{-- {!!Utility::revisionData("telefoni_mobile_call", "Telefoni mobile (call)", "", $current, $revisions)!!} --}}
                    {!!Utility::revisionData("whatsapp", "Whatsapp", "", $current, $revisions)!!}
                    {!!Utility::revisionData("notewa_it", "Note IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("notewa_en", "Note EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("notewa_fr", "Note FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("notewa_de", "Note DE", "", $current, $revisions)!!}

                    <tr>
                        <th colspan="2"><br /><b>Email</b></th>
                    </tr>
                    {!!Utility::revisionData("email", "Principale", "", $current, $revisions)!!}
                    {!!Utility::revisionData("email_multipla", "Multipla", "", $current, $revisions)!!}
                    {!!Utility::revisionData("email_risponditori", "Risponditori", "", $current, $revisions)!!}
                    {!!Utility::revisionData("email_secondaria", "Secondaria", "", $current, $revisions)!!}

                    <tr>
                        <th colspan="2"><br /><b>Sito</b></th>
                    </tr>
                    {!!Utility::revisionData("nascondi_url", "Nascosto", [0 => "Visibile", 1 => "Nascosto"], $current, $revisions)!!}
                    {!!Utility::revisionData("testo_link", "Sito", "", $current, $revisions)!!}
                    {!!Utility::revisionData("link", "Url", "", $current, $revisions)!!}

                    <tr>
                        <th colspan="2"><br /><b>Organizzazione eventi</b></th>
                    </tr>
                    {!!Utility::revisionData("organizzazione_comunioni", "Comunioni", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("organizzazione_cresime",   "Cresime",   [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("organizzazione_matrimoni", "Matrimoni", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("note_organizzazione_matrimoni", "Note", "", $current, $revisions)!!}
                    
                    <tr>
                        <th colspan="2"><br /><b>Date di apertura</b></th>
                    </tr>
                    {!!Utility::revisionData("aperto_dal", "Aperto dal", "", $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_al",  "Aperto al",   "", $current, $revisions)!!}

                    <tr>
                        <th colspan="2"><br /><b>Periodi di apertura</b></th>
                    </tr>
                    {!!Utility::revisionData("annuale", "Annuale", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_eventi_e_fiere", "Eventi e fiere", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_pasqua", "Pasqua", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_25_aprile", "25 Aprile", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_1_maggio", "1 Maggio", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_capodanno", "Capodanno", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("organizzazione_comunioni", "Altro", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_altro_it", "Note IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_altro_en", "Note EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_altro_fr", "Note FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("aperto_altro_de", "Note DE", "", $current, $revisions)!!}
                    
                    <tr>
                        <th colspan="2"><br /><b>Camere e posti letto</b></th>
                    </tr>
                    {!!Utility::revisionData("n_camere", "N camere", "", $current, $revisions)!!}
                    {!!Utility::revisionData("n_posti_letto", "N posti letto", "", $current, $revisions)!!}
                    {!!Utility::revisionData("n_appartamenti", "N Appartamenti", "", $current, $revisions)!!}
                    {!!Utility::revisionData("n_suite", "N suite", "", $current, $revisions)!!}

                    <tr>
                        <th colspan="2"><br /><b>Orari dei pasti</b></th>
                    </tr>
                    {!!Utility::revisionData("colazione_da", "Colazione da", "", $current, $revisions)!!}
                    {!!Utility::revisionData("colazione_a", "Colazione a", "", $current, $revisions)!!}
                    {!!Utility::revisionData("pranzo_da", "Pranzo da", "", $current, $revisions)!!}
                    {!!Utility::revisionData("pranzo_a", "Pranzo a", "", $current, $revisions)!!}
                    {!!Utility::revisionData("cena_da", "Cena da", "", $current, $revisions)!!}
                    {!!Utility::revisionData("cena_a", "Cena a", "", $current, $revisions)!!}
                    
                    <tr>
                        <th colspan="2"><br /><b>Trattamenti principali</b></th>
                    </tr>
                    {!!Utility::revisionData("trattamento_ai", "All inclusive", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("trattamento_pc", "Pensione completa", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("trattamento_mp", "Mezza pensione", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("trattamento_mp_spiaggia", "Mezza pensione + spiaggia", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("trattamento_bb", "B&B", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("trattamento_bb_spiaggia", "B&B + spiaggia", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("trattamento_sd", "Solo dormire", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("trattamento_sd_spiaggia", "Solo dormire + spiaggia", [0 => "No", 1 => "Si"], $current, $revisions)!!}

                    <tr>
                        <th colspan="2"><br /><b>Pagamenti accettati</b></th>
                    </tr>
                    {!!Utility::revisionData("contanti", "Contanti", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("note_contanti_it", "Note IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_contanti_en", "Note EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_contanti_fr", "Note FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_contanti_de", "Note DE", "", $current, $revisions)!!}

                    {!!Utility::revisionData("assegno", "Assegno", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("note_assegno_it", "Note IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_assegno_en", "Note EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_assegno_fr", "Note FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_assegno_de", "Note DE", "", $current, $revisions)!!}

                    {!!Utility::revisionData("carta_credito", "Carta Credito", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("note_carta_credito_it", "Note IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_carta_credito_en", "Note EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_carta_credito_fr", "Note FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_carta_credito_de", "Note DE", "", $current, $revisions)!!}

                    {!!Utility::revisionData("bonifico", "Bonifico", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("note_bonifico_it", "Note IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_bonifico_en", "Note EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_bonifico_fr", "Note FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_bonifico_de", "Note DE", "", $current, $revisions)!!}

                    {!!Utility::revisionData("paypal", "Paypal", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("note_paypal_it", "Note IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_paypal_en", "Note EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_paypal_fr", "Note FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_paypal_de", "Note DE", "", $current, $revisions)!!}

                    {!!Utility::revisionData("bancomat", "Bancomat", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("note_bancomat_it", "Note IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_bancomat_en", "Note EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_bancomat_fr", "Note FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_bancomat_de", "Note DE", "", $current, $revisions)!!}
                    
                    {!!Utility::revisionData("altro_pagamento", "Altro", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("note_altro_pagamento_it", "Note IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_altro_pagamento_en", "Note EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_altro_pagamento_fr", "Note FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("note_altro_pagamento_de", "Note DE", "", $current, $revisions)!!}
                
                    <tr>
                        <th colspan="2"><br /><b>Lingue parlate</b></th>
                    </tr>
                    {!!Utility::revisionData("inglese", "Inglese", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("francese", "Francese", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("tedesco", "Tedesco", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("spagnolo", "Spagnolo", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("russo", "Russo", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("altra_lingua_it", "Altro IT", "", $current, $revisions)!!}
                    {!!Utility::revisionData("altra_lingua_en", "Altro EN", "", $current, $revisions)!!}
                    {!!Utility::revisionData("altra_lingua_fr", "Altro FR", "", $current, $revisions)!!}
                    {!!Utility::revisionData("altra_lingua_de", "Altro DE", "", $current, $revisions)!!}

                    <tr>
                        <th colspan="2"><br /><b>Recensioni</b></th>
                    </tr>
                    {!!Utility::revisionData("enabled_rating_ia", "Abilito", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("rating_ia", "Rating", "", $current, $revisions)!!}
                    {!!Utility::revisionData("n_rating_ia", "Reviews", "", $current, $revisions)!!}
                    {!!Utility::revisionData("n_source_rating_ia", "Sorgenti", "", $current, $revisions)!!}

                    <tr>
                        <th colspan="2"><br /><b>Altro</b></th>
                    </tr>
                    {!!Utility::revisionData("certificazione_aci", "Certificazione AIC", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("green_booking", "Green Booking", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("design_hotel", "Design Hotel", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("family_hotel", "Hotel family", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    {!!Utility::revisionData("eco_sostenibile", "Eco sostenibile", [0 => "No", 1 => "Si"], $current, $revisions)!!}
                    
                </table> 

            </div>
        </div>

       

    </div>
    </div>

    

@endsection
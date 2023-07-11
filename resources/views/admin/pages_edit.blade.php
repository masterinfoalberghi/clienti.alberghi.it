@extends('templates.admin')

@section('title')
  @if ($data["record"]->exists)
    Modifica Pagina
  @else
    Nuova Pagina
  @endif
@endsection

@section('content')

    @if ($data["record"]->exists)

        <a target="blank" style="margin-bottom:20px; display:block; font-size:16px;" href="{{ Utility::getPublicUri($data["record"]->uri) }}" >Apri su sito <i class="entypo-popup"></i></a>
        <div style="clear:both"></div>

        {!! Form::open(['id' => 'record_delete', 'url' => 'admin/pages/delete', 'method' => 'POST']) !!}
            <input type="hidden" name="id" value="<?=$data["record"]->id ?>">
        {!! Form::close() !!}

    @endif

    {!! Form::model($data["record"], ['role' => 'form', 'url' => 'admin/pages/store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

        <input type="hidden" name="id" value="<?=($data["record"]->exists ? $data["record"]->id : 0)?>">
        <input type="hidden" name="menu_auto_annuale" value="0">
        <input type="hidden" name="menu_evidenza" value="0">

        <input type="hidden" name="template" value="statica">
        <input type="hidden" name="menu_macrolocalita_id" value="0">
        <input type="hidden" name="menu_localita_id" value="0">
        <input type="hidden" name="vetrina_id" value="0">
        <input type="hidden" name="evidenza_vetrina_id" value="0">
        <input type="hidden" name="banner_vetrina_id" value="0">
        <input type="hidden" name="menu_macrolocalita_count" value="0">
        <input type="hidden" name="listing_attivo" value="0">
        <input type="hidden" name="listing_count" value="0">
        <input type="hidden" name="listing_macrolocalita_id" value="0">
        <input type="hidden" name="listing_localita_id" value="0">
        <input type="hidden" name="indirizzo_stradario" value="">
        <input type="hidden" name="punto_di_forza" value="">
        <input type="hidden" name="listing_parolaChiave_id" value="0">
        <input type="hidden" name="listing_offerta" value="">
        <input type="hidden" name="listing_offerta_prenota_prima" value="">
        <input type="hidden" name="listing_gruppo_servizi_id" value="0">
        <input type="hidden" name="listing_categorie" value="">
        <input type="hidden" name="listing_tipologie" value="">
        <input type="hidden" name="listing_trattamento" value="">
        <input type="hidden" name="listing_coupon" value="0">
        <input type="hidden" name="listing_bambini_gratis" value="0">
        <input type="hidden" name="listing_whatsapp" value="0">
        <input type="hidden" name="listing_green_booking" value="0">
        <input type="hidden" name="listing_eco_sostenibile" value="0">
        <input type="hidden" name="listing_annuali" value="0">
        <input type="hidden" name="n_offerte" value="0">
        <input type="hidden" name="prezzo_minimo" value="0">
        <input type="hidden" name="prezzo_massimo" value="0">
        <input type="hidden" name="listing_preferiti" value="0">
        <input type="hidden" name="tipo_evidenza_crm" value="">
        <input type="hidden" name="listing_bonus_vacanza_2020" value="0">
        
        <!-- Bottoni -->
        <div class="form-group">
            <div class="col-sm-12 con_bottoni_in_alto">
                @include('templates.admin_inc_record_buttons')
            </div>
        </div>

        <!-- Attiva -->
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('attiva', "1", null, ["class" => "apple-switch"]) !!} <span style="display:inline-block; margin:8px 12px; ">Attiva pagina</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Lingua -->
        <div class="form-group">
            {!! Form::label('lang_id', 'Lingua', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::select('lang_id', $data["langs"], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Clona -->
        @if ($data["record"]->exists)
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <a href="{{ url("admin/pages/".$data["record"]->id."/clona") }}">Clona questa pagina</a>. Alla pagina clone verrà assegnato una URI temporaneo che dovrai personalizzare.
                </div>
            </div>
        @endif
        
        <!-- Menu panel -->
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">Menu</div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('menu_dal', 'Visibile nel menu dal', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        <input id="reportrange-start" class="form-control" type="text" name="menu_dal" value="{{ (isset($data["record"]) && Utility::getLocalDate($data["record"]->menu_dal,'%d/%m/%y') != "" ) ? $data["record"]->menu_dal->format('d/m/Y') : ''}}" style="width:150px; display:inline-block"> al
                        <input id="reportrange-end"   class="form-control" type="text" name="menu_al"  value="{{ (isset($data["record"]) && Utility::getLocalDate($data["record"]->menu_al,'%d/%m/%y')  != "" ) ? $data["record"]->menu_al->format('d/m/Y')  : ''}}" style="width:150px; display:inline-block">
                        @if(isset($data["record"]->menu_dal) && Utility::isValidMenu($data["record"]->menu_dal->format('Y-m-d'), $data["record"]->menu_al->format('Y-m-d'), null, 1))
                            <span style="display:inline-block; margin-left: 30px; "><b style="color: green;">Menu attivo per la data di oggi</b></span>
                        @endif
                    </div>
                    <div class="col-sm-10 col-sm-offset-2 ">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('menu_auto_annuale', "1", null, ["id"=>'menu_auto_annuale']) !!} Rinnovo annuale automatico
                            </label><br >
                            <label>
                                {!! Form::checkbox('menu_evidenza', "1", null, ["id"=>'menu_evidenza']) !!} Metti in evidenza nel menu tematico
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campi SEO panel -->
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">Campi SEO</div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('uri', 'URI', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('uri', null, ['placeholder' => 'URI', 'class' => 'form-control']) !!}
                        <p class="help-block">
                            Alcune URI particolari hanno al loro interno il placeholder <em>{CURRENT_YEAR}</em>.
                            <strong>Non è un errore!</strong> serve a gestire le pagine tipo <em>/estate-2016/cattolica.php</em> e i vari redirect 301 dagli anni precedente all'anno attuale.
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('alternate', 'Pagine in lingua', ['class' => 'col-sm-2 control-label']) !!}
                    @php $class=""; @endphp
                    @foreach($data["langs"] as $lang)
                            @if ($lang != $data["record"]->lang_id)
                            <div style="margin-bottom:5px; position:relative; " class="col-sm-10 {{$class}}">
                                {!! Form::text('alternate_' . $lang , $data["alternate_uri"][$lang], ['placeholder' => 'Link alternate', 'class' => 'form-control', "style" => "padding-left:40px;"]) !!}
                                <div class="langTag">{{$lang}}</div>
                            </div>
                            @php $class="col-sm-offset-2"; @endphp
                        @endif
                    @endforeach
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        Link associati alla pagina (bilaterale).
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('ancora', 'Ancora', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('ancora', null, ['placeholder' => 'Ancora', 'class' => 'form-control']) !!}
                        <p class="help-block">compare nei link che puntano a questa pagina</p>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('seo_title', 'Title', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::text('seo_title', null, ['placeholder' => 'Title', 'class' => 'form-control cont_caratteri_input']) !!}
                        <span class="cont_caratteri">0</span>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('seo_description', 'Description', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::textarea('seo_description', null, ['placeholder' => 'Description', 'class' => 'form-control cont_caratteri_input']) !!}
                        <span class="cont_caratteri">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Homepage Spot panel -->
        <div class="panel panel-default config-visibilita" @if ($data["spot_attivo"] == 0) data-collapsed="1" @else data-collapsed="0" @endif>
            <div class="panel-heading">
                <div class="panel-title form-group" style="float:none; margin-bottom: 0;">
                    <div class="col-sm-2">Mostra in homepage</div>
                    <div class="col-sm-10">
                        <label>{!! Form::radio('spot_attivo', "0", $data["spot_attivo"] == 0 ? true: false, ["class" => "spot_attivo", "id"=>'spot_attivo1']) !!} da nessuna parte</label>&nbsp;&nbsp;&nbsp;
                        <label>{!! Form::radio('spot_attivo', "1", $data["spot_attivo"] == 1 ? true: false, ["class" => "spot_attivo", "id"=>'spot_attivo2']) !!} nelle <b>Evidenze</b></label>&nbsp;&nbsp;&nbsp;
                        <label>{!! Form::radio('spot_attivo', "2", $data["spot_attivo"] == 2 ? true: false, ["class" => "spot_attivo", "id"=>'spot_attivo3']) !!} nelle <b>Offerte</b></label>&nbsp;&nbsp;&nbsp;
                        <label>{!! Form::radio('spot_attivo', "3", $data["spot_attivo"] == 3 ? true: false, ["class" => "spot_attivo", "id"=>'spot_attivo4']) !!} nei <b>Link secondari</b></label>&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
            </div>

            <div class="panel-body">

                <div class="form-group">
                    {!! Form::label('spot_ordine', 'Ordine', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-1">
                        {!! Form::text('spot_ordine', $data["spot_ordine"], ['placeholder' => '10', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('spot_h1', 'Titolo', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::text('spot_h1', $data["spot_h1"], ['placeholder' => 'Titolo', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('spot_h2', 'Sottotitolo', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::text('spot_h2', $data["spot_h2"], ['placeholder' => 'es: 1-3 Giugno, 2 Luglio {CURRENT_YEAR}, Tutto quello ch vuoi ...', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('spot_colore', 'Colore tematico', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::text('spot_colore', $data["spot_colore"], ['placeholder' => 'es: #ff0000', 'class' => 'form-control','style' => "display:inline-block; width:30%;"]) !!}
                    </div>
                </div>

                <div class="form-group">

                    {!! Form::label('spot_immagine', 'Immagine', ['class' => 'col-sm-2 control-label']) !!}
                    {!! Form::hidden('spot_immagine', $data["spot_immagine"], ['class' => 'form-control dropzoneFileUpload', 'id' => 'spot_image']) !!}

                    <div class="col-sm-10">
                        <div class="dropzone dropzone_singola" id="dropzoneFileUpload" data-path="immagini-spot" data-service="uploadImage" style="border:2px dashed #ddd; display: inline-block;
                            @if( $data["spot_immagine"] != "")
                                background: #f5f5f5 url('{{config('app.cdn_online') . '/images/romagna/spothome/600x290/'.$data['spot_immagine']}}') top center no-repeat;
                            @else
                                background: #f5f5f5 url('/images/admin/photo.png') center center no-repeat
                            @endif
                            ">
                        </div>
                        <div class="dz-default dz-message">
                            <strong>Clicca o trascina un file sul riquadro per caricarlo</strong> - <a href="#" data-id="dropzoneFileUpload" class="deleteImage">Elimina immagine</a><br>
                            <span>Note: Partire da una immagine di dimensioni 1200x580px </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('spot_descrizione', 'Descrizione', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::textarea('spot_descrizione', $data["spot_descrizione"], ['placeholder' => 'Descrizione', 'class' => 'form-control ckeditor-small']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('menu_dal', 'Visibile dal', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        <input id="reportrange_spot_visibilita-start" class="form-control" type="text" name="spot_visibile_dal" value="{{ (isset($data["spot_visibile_dal"]) && Utility::getLocalDate($data["spot_visibile_dal"],'%d/%m/%y') != "" ) ? $data["spot_visibile_dal"]->format('d/m/Y') : ''}}" style="width:150px; display:inline-block"> al
                        <input id="reportrange_spot_visibilita-end"   class="form-control" type="text" name="spot_visibile_al"  value="{{ (isset($data["spot_visibile_al"]) && Utility::getLocalDate($data["spot_visibile_al"],'%d/%m/%y')  != "" ) ? $data["spot_visibile_al"]->format('d/m/Y')  : ''}}" style="width:150px; display:inline-block">
                        @if(isset($data["spot_visibile_dal"]->menu_dal) &&  Utility::isValidMenu($data["spot_visibile_dal"]->format('Y-m-d'), $data["spot_visibile_al"]->format('Y-m-d'), null, $data["spot_visibile_ricorsivo"]))
                            <span style="display:inline-block; margin-left: 30px; "><b style="color: green;">Spot attivo per la data di oggi</b></span>
                        @endif
                    </div>
                    <div class="col-sm-10 col-sm-offset-2">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('spot_visibile_ricorsivo', "1", $data["spot_visibile_ricorsivo"], ["id"=>'spot_visibile_ricorsivo']) !!} Rinnovo annuale automatico
                            </label>
                        </div>
                    </div>

                </div>

            </div>
            
        </div> 

        <!-- Footer Spot panel -->
        <div class="panel panel-default config-visibilita-footer" @if ($data["spot_attivo_footer"] == 0) data-collapsed="1" @else data-collapsed="0" @endif>
            <div class="panel-heading">
                <div class="panel-title form-group" style="float:none; margin-bottom: 0;">
                    <div class="col-sm-2">Mostra nel Footer</div>
                    <div class="col-sm-10">
                        <label>{!! Form::radio('spot_attivo_footer', "0", $data["spot_attivo_footer"] == 0 ? true: false, ["class" => "spot_attivo_footer", "id"=>'spot_attivo_footer1']) !!} da nessuna parte</label>&nbsp;&nbsp;&nbsp;
                        <label>{!! Form::radio('spot_attivo_footer', "1", $data["spot_attivo_footer"] == 1 ? true: false, ["class" => "spot_attivo_footer", "id"=>'spot_attivo_footer2']) !!} {{trans("title.informazioni_footer")}}</label>&nbsp;&nbsp;&nbsp;
                        <label>{!! Form::radio('spot_attivo_footer', "2", $data["spot_attivo_footer"] == 2 ? true: false, ["class" => "spot_attivo_footer", "id"=>'spot_attivo_footer3']) !!} {{trans("title.link_utili_footer")}}</label>&nbsp;&nbsp;&nbsp;
                        <label>{!! Form::radio('spot_attivo_footer', "3", $data["spot_attivo_footer"] == 3 ? true: false, ["class" => "spot_attivo_footer", "id"=>'spot_attivo_footer4']) !!} {{trans("title.eventi_fiere_footer")}}</label>&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('spot_ordine_footer', 'Ordine', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-1">
                        {!! Form::text('spot_ordine_footer', $data["spot_ordine_footer"], ['placeholder' => '10', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Header panel -->
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading"><div class="panel-title">Header</div></div>
            <div class="panel-body">

                <div class="form-group">
                    {!! Form::label('h1', 'Titolo header (h1)', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10 cont_caratteri">
                        {!! Form::text('h1', null, ['placeholder' => 'Titolo pagina', 'class' => 'form-control cont_caratteri_input']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('immagine', 'Immagine', ['class' => 'col-sm-2 control-label']) !!}
                    {!! Form::hidden('immagine', null , ['class' => 'form-control dropzoneFileUploadEvidenza', 'id' => 'immagine']) !!}
                    <div class="col-sm-10">
                        <div class="dropzone dropzone_singola" id="dropzoneFileUploadEvidenza" data-path="immagini-evidenza" data-service="uploadImage" style="border:2px dashed #ddd; display: inline-block;
                            @if( $data["record"]->immagine != "")
                                background: transparent url('{{Utility::assetsLoaded('/romagna/pagine/600x290/'. $data['record']->immagine)}}') center center no-repeat;
                            @endif 
                            ">
                        </div>
                        <div class="dz-default dz-message">
                            <strong>Clicca o trascina un file sul riquadro per caricarlo</strong> - <a href="#" data-id="dropzoneFileUploadEvidenza" class="deleteImage">Elimina immagine</a><br>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('descrizione_1', 'Testo header', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::textarea('descrizione_1', null, ['id'=>'descrizione_1','placeholder' => 'Testo header', 'class' => 'form-control ckeditor']) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenuti panel -->
        <?php

        $indice = 1;
        if ( $data["record"]->descrizione_2 != "") {
            $contenuti = json_decode($data["record"]->descrizione_2);
            foreach($contenuti as $contenuto):
                switch ($contenuto->tipocontenuto) {

                    case "text": ?>

                        <div id="pannello-h2-<?php echo $indice ?>" class="pannello-h2-<?php echo $indice ?> panel panel-default"  data-collapsed="0">

                            <input type="hidden" value = "text" name="tipocontenuti[]"/>

                            <div class="panel-heading">
                                <div class="panel-title">Contenuto testuale #<?php echo $indice ?></div>
                            </div>

                            <div class="panel-body">

                                <div class="form-group">
                                    <label for="layout" class="col-sm-2 control-label">Immagine</label>
                                    <div class="col-sm-10">
                                        <img class="image-layout <?php if ($contenuto->layout == '1col') echo 'selected'; ?>" src="/images/admin/1col.jpg" data-layout="1col" />
                                        <img class="image-layout <?php if ($contenuto->layout == 'normal') echo 'selected'; ?>" src="/images/admin/normal.jpg" data-layout="normal" />
                                        <img class="image-layout <?php if ($contenuto->layout == 'left') echo 'selected'; ?>" 	src="/images/admin/left.jpg" data-layout="left" />
                                        <img class="image-layout <?php if ($contenuto->layout == 'right') echo 'selected'; ?>"	src="/images/admin/right.jpg" data-layout="right" />
                                        <img class="image-layout <?php if ($contenuto->layout == 'over') echo 'selected'; ?>" 	src="/images/admin/over.jpg" data-layout="over" />
                                        <input type="hidden" value = "<?php echo $contenuto->layout ?>" name="layout[]"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="immagine_secondaria" class="col-sm-2 control-label">Immagine</label>
                                    <input type="hidden" name="immagine_secondaria[]" value="<?php echo $contenuto->immagine ?>" class="form-control dropzoneFileUploadSecondario_<?php echo $indice ?>" id="immagine_secondaria_<?php echo $indice ?>" />
                                    <div class="col-sm-10">

                                        <div class="dropzone dropzone_singola" 
                                            id="dropzoneFileUploadSecondario_<?php echo $indice ?>" 
                                            data-indice="<?php echo $indice ?>"  
                                            data-path="immagini-evidenza" 
                                            data-service="uploadImage" 
                                            style="border:2px dashed #ddd; display: inline-block;  @if($contenuto->immagine) background: transparent url({{Utility::assetsLoaded('/romagna/pagine/600x290/'. $contenuto->immagine)}}) top center no-repeat; @endif"></div>

                                        <div class="dz-default dz-message">
                                            <strong>Clicca o trascina un file sul riquadro per caricarlo</strong> - <a href="#" data-id="dropzoneFileUploadSecondario_<?php echo $indice ?>" class="deleteImage">Elimina immagine</a><br>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="h2" class="col-sm-2 control-label">Titolo</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="h2[]" placeholder="Titolo" value="<?php echo $contenuto->h2 ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="h3" class="col-sm-2 control-label">Sottotitolo</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="h3[]" placeholder="Sottotitolo" value="<?php echo $contenuto->h3 ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="descrizione_2" class="col-sm-2 control-label">Testo</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control ckeditor ckeditor-add-<?php echo $indice ?>" placeholder="Testo" name="descrizione_2[]"><?php echo $contenuto->descrizione_2 ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <button class="delContentButton btn btn-danger" data-index="<?php echo $indice; ?>" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>
                                    </div>
                                </div>
                            </div>

                        </div><?php

                    break;

                    case "gallery": ?>

                        <div id="pannello-h2-<?php echo $indice ?>" class="pannello-h2-<?php echo $indice ?> panel panel-default" data-collapsed="0">

                            <input type="hidden" value="gallery" name="tipocontenuti[]"/>

                            <div class="panel-heading">
                                <div class="panel-title">Galleria fotografica #<?php echo $indice ?></div>
                            </div>

                            <div class="panel-body">

                                <div class="form-group">

                                    <label for="immagine_secondaria" class="col-sm-2 control-label">Immagini</label>

                                    <div class="col-sm-10">
                                        <div class="dropzone dropzone_gallery" id="dropzoneFileUploadSecondarioGallery_<?php echo $indice ?>" data-indice="<?php echo $indice ?>"  data-path="pagine" data-service="uploadImage" style="border:2px dashed #ddd; display: inline-block; background: #eee url('/images/admin/photo.png') center center no-repeat"></div>
                                        <div class="dz-default dz-message">
                                            <strong>Clicca o trascina un file sul riquadro per caricarlo</strong><br>
                                        </div>
                                    </div>

                                </div>

                                <div id="dropzoneFileUploadSecondarioGallery_<?php echo $indice; ?>_list" class="form-group sortable">

                                    @if(count($contenuto->galleria))
                                        @for( $i=0; $i<count($contenuto->galleria->immagini); $i++)
                                            <div class="row" style="margin-bottom: 5px;">

                                                <div class="col-sm-2"></div>

                                                <div class="col-sm-2">
                                                    <input type="hidden" name="immagine_gallery[<?php echo $indice-1 ?>][]" value="{{$contenuto->galleria->immagini[$i]}}" />
                                                    <img src="{{Utility::assetsLoaded("romagna/pagine/300x200/" . $contenuto->galleria->immagini[$i] )}}" width="150" height="100" />
                                                </div>

                                                <div class="col-sm-6">
                                                    <textarea class="form-control" name="testo_gallery[<?php echo $indice-1 ?>][]">{{$contenuto->galleria->testo[$i] }}</textarea>
                                                </div>

                                                <div class="col-sm-2">
                                                    <a href="#" class="btn btn-danger" onclick="delThisPhoto(this); return false;">Elimina</a>
                                                </div>

                                            </div>
                                        @endfor
                                    @endif

                                </div>

                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <button class="delGallerytButton btn btn-danger" data-index="<?php echo $indice; ?>" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>
                                    </div>
                                </div>

                            </div>

                        </div><?php

                    break;

                    case "map": ?>

                        <div id="pannello-h2-<?php echo $indice ?>" class="pannello-h2-<?php echo $indice ?> panel panel-default" data-collapsed="0">

                            <input type="hidden" value="map" name="tipocontenuti[]"/>

                            <div class="panel-heading">
                                <div class="panel-title">Mappa e punti di interese #<?php echo $indice ?></div>
                            </div>

                            <div class="panel-body">

                                <div class="form-group" >
                                    <label for="layout" class="col-sm-2 control-label">Latitudine / Longitudine</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="map_lat_lon[]" value="{{$contenuto->map_lat_lon}}" placeholder="es: 44.0535197,12.5395819"/><br />
                                        <input name="map_source[]" type="hidden" value="0" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <button class="delMapButton btn btn-danger" data-index="<?php echo $indice; ?>" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>
                                    </div>
                                </div>

                            </div>
                        </div><?php

                    break;
                }

                $indice++;
            endforeach;
        } ?>

        <!-- Bottoni -->
        <div class="aggiungiContenuto" style="text-align: center; ">
            <div  style="padding:50px; ">
                <button class="addContentButton btn btn-info" data-index='<?php echo $indice ?>' type="button" onclick="addContentSecondary(this);">Aggiungi blocco testo</button>
                {{-- <button class="addGalleryButton btn btn-info" data-index='<?php echo $indice ?>' type="button" onclick="addGalleryButton(this);">Aggiungi una gallery fotografica</button> --}}
                {{-- <button class="addMapButton btn btn-info" data-index='<?php echo $indice ?>' type="button" onclick="addMapButton(this);">Aggiungi una mappa</button> --}}
            </div>
        </div>

        <!-- Bottoni -->
        <div class="form-group">
            <div class=" col-sm-12">
                @include('templates.admin_inc_record_buttons')
            </div>
        </div>

{!! Form::close() !!}

@endsection

@section('onheadclose')

    <link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/daterangepicker/daterangepicker.min.css', true)}}" />
    <link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/palettecolor/palette-color-picker.min.css', true)}}" />
    <link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/neon/js/dropzone/dropzone.css')}}" />
    
    <script type="text/javascript" src="{{Utility::assets('/vendor/moment/moment.min.js', true)}}"></script>
    <script type="text/javascript" src="{{Utility::assets('/vendor/daterangepicker/daterangepicker.min.js', true)}}"></script>
    <script type="text/javascript" src="{{Utility::assets('/vendor/palettecolor/palette-color-picker.min.js', true)}}"></script>
    <script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/dropzone/dropzone.js')}}"></script>

    <style>

        .tag { font-size:10px; padding:3px 6px; background: #f0f0f0; display: inline-block; border:1px solid #ddd; margin-top: 5px; margin-right:2px;}
        .cont_caratteri { position:relative; }
        span.cont_caratteri { position:absolute; top:0; right:15px; padding:3px; background: #666; color:#fff; font-size: 10px;  }

        .dropzone { min-height: 290px; width:600px; padding:0;  }
        .dropzone img { width:100%; height:auto; }
        .dropzone .dz-default.dz-message { background: none; }

        .aggiungiContenuto { width:100%;  margin-bottom: 30px;  }

        .image-layout { border:2px solid #fff; opacity: 0.5}
        .image-layout:hover {opacity: 1}
        .image-layout.selected { border:2px solid #788B9C; opacity: 1;}

        .palette-color-picker-button { margin:0 0 0 8px; vertical-align: middle; }
        .langTag {

            position:absolute; top:1px; left:16px;
            width:30px;
            height:29px;
            background: #f5f5f5;
            line-height: 29px;
            text-align: center;
            -webkit-border-radius: 2px 0 0 2px;
            -moz-border-radius: 2px 0 0 2px;
            border-radius: 2px 0 0 2px;
            border-right:1px solid #ddd;

        }

    </style>

    @include("admin.widget.page_js_header")

@endsection

@section('onbodyclose')

    @include("admin.widget.page_js_footer")

@endsection

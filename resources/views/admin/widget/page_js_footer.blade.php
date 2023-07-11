<script type="text/javascript">

    var dropZoneArray = [];

    function cambiaData () {
        var start 	= moment(jQuery('#reportrange-start').val() , "DD-MM-YYYY");
        var end 	= moment(jQuery('#reportrange-end').val() 	, "DD-MM-YYYY");
        if(end.isBefore(start)) {
            end = start.add(1,"d");
            jQuery('#reportrange-end')
                .val( end.format("DD/MM/YYYY") )
                .data('daterangepicker')
                    .setStartDate(start);
            jQuery('#reportrange-end')
                .data('daterangepicker')
                .setMinDate(start);
        }

    }

    function cambiaData2() {
        var start 	= moment(jQuery('#reportrange_spot_visibilita-start').val() , "DD-MM-YYYY");
        var end 	= moment(jQuery('#reportrange_spot_visibilita-end').val() 	, "DD-MM-YYYY");
        if(end.isBefore(start)) {
            end = start.add(1,"d");
            jQuery('#reportrange_spot_visibilita-end')
                .val( end.format("DD/MM/YYYY") )
                .data('daterangepicker')
                    .setStartDate(start);
            jQuery('#reportrange_spot_visibilita-end')
                .data('daterangepicker')
                .setMinDate(start);
        }
    }

    jQuery(document).ready(function(){

        /**
         * Apro e chiudo tutti i panel in partenza
         */ 

        jQuery("#listing_attivo").change(function () {
            var io = jQuery(this);
            if (io.is(":checked"))
                jQuery(".config-listing").attr("data-collapsed" , 0);
            else
                jQuery(".config-listing").attr("data-collapsed" , 1);
        }).trigger("change");

        jQuery(".spot_attivo").change(function () {
            var io = jQuery(this);
            if (io.val() != 0)
                jQuery(".config-visibilita").attr("data-collapsed" , 0);
            else
                jQuery(".config-visibilita").attr("data-collapsed" , 1);

        });

        jQuery(".spot_attivo_footer").change(function () {
            var io = jQuery(this);
            if (io.val() != 0)
                jQuery(".config-visibilita-footer").attr("data-collapsed" , 0);
            else
                jQuery(".config-visibilita-footer").attr("data-collapsed" , 1);
        });

        /**
         * Color palette
         */

        jQuery('#spot_colore').paletteColorPicker({
            colors: ["#ffffff", "#222222", "#8CC152", "#26A69A", "#1FB0E1", "#1A5C87", "#F24A46" , "#EC407A", "#F1B000", "#FF9326", "#8D6E63" ],
            insert: 'after', // default -> 'before'
            clear_btn: 'last', // null -> without clear button, default -> 'first'
        });

        /**
         * Date picker
         */

        moment.locale('it');

        @if( isset($data["record"]) && Utility::getLocalDate($data["record"]->menu_dal,'%d/%m/%y') != "" )
            var start = moment("{{$data["record"]->menu_dal->format('Y-m-d')}}");
            var end = moment("{{$data["record"]->menu_al->format('Y-m-d')}}");
        @else
            var start = moment();
            var end = moment().add(1, 'year');
            jQuery("#menu_auto_annuale").prop("checked", "checked" );
        @endif

        jQuery('#reportrange-start').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: start,
            opens: "right",
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        jQuery('#reportrange-end').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: end,
            opens: "right",
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        jQuery('#reportrange-start, #reportrange-end').change( cambiaData );
        cambiaData();

        /**
         * Visibilità spot
         */

        @if( isset($data["spot_visibile_dal"]) && Utility::getLocalDate($data["spot_visibile_dal"],'%d/%m/%y') != "" )
            var start_spot_visibilita = moment("{{$data["spot_visibile_dal"]->format('Y-m-d')}}");
            var end_spot_visibilita = moment("{{$data["spot_visibile_al"]->format('Y-m-d')}}");
        @else
            var start_spot_visibilita = moment();
            var end_spot_visibilita = moment().add(1, 'year');
        @endif

        jQuery('#reportrange_spot_visibilita-start').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: start_spot_visibilita,
            opens: "right",
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        jQuery('#reportrange_spot_visibilita-end').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: end_spot_visibilita,
            opens: "right",
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        jQuery('#reportrange_spot_visibilita-start, #reportrange_spot_visibilita-end').change( cambiaData2 );
        cambiaData2();

    });

    /**
     * Dropzone
     */

    var baseUrl = "{{ url('/') }}";
    var token = "{{ Session::token() }}";

    Dropzone.autoDiscover = false;

    function initDropZone (tipo ) {

        jQuery(".dropzone_" + tipo).not(".init").each(function (i) {

            var dz = jQuery(this);
            var id = dz.attr("id");
            var path = dz.data("path");
            var service = dz.data("service");
            var indice = dz.data("indice");

            dz.addClass("init");

            var options = {

                maxFilesize: 5, // MB
                createImageThumbnails: false,
                url: baseUrl+"/admin/"+path+"/" + service,
                params: { _token: token }

            }

            // console.log(options);

            if (tipo == "gallery") {
                // options.uploadMultiple = true;
                previewTemplate: '<img data-dz-thumbnail id="src'+id+i+'" />';
            } else {
                previewTemplate: '<img data-dz-thumbnail id="src'+id+i+'" style="display:none"/>';
            }

            dropZoneArray[i] = new Dropzone("div#" + id, options);

            if (tipo == "singola") {

                dropZoneArray[i].on("success", function(file, response) {
                   
                    jQuery("div#" + id).css("background", '#eee url("'+response[1]+'") center center no-repeat');
                    jQuery("." + id).val(response[0]);
                });

                dropZoneArray[i].on("error", function(file, error) {
                    alert(error);
                    jQuery("." + id).val("");
                });

            } else {

                dropZoneArray[i].on("success", function(file, response) {

                    jQuery("div#" + id).css("background", 'none');

                    var html = '<div class="row" style="margin-bottom: 5px;">' +
                                    '<div class="col-sm-2"></div>' +
                                    '<div class="col-sm-2">' +
                                        '<input type="hidden" name="immagine_gallery['+(indice-1)+'][]" value="'+response[0]+'">' +
                                        '<img src="{{config("app.cdn_online") . "/images/romagna/pagine/300x200/"}}/' + response[0] +'" width="150" height="100">' +
                                    '</div>' +
                                    '<div class="col-sm-6">' +
                                        '<textarea class="form-control" name="testo_gallery['+(indice-1)+'][]"></textarea>' +
                                    '</div>' +
                                    '<div class="col-sm-2">' +
                                        '<a href="#" class="btn btn-danger" onclick="delThisPhoto(this); return false;">Elimina</a>' +
                                    '</div>' +
                                '</div>';

                    jQuery("div#" + id + "_list").append(html);

                });

                dropZoneArray[i].on("error", function(file, error) {
                    alert(error);
                });

            }

        });

        jQuery(".deleteImage").unbind("click");
        jQuery(".deleteImage").bind("click", function (e) {
            e.preventDefault();
            var id = jQuery(this).data("id");
            jQuery("." + id).val("");
        });


    }

    initDropZone("singola");
    initDropZone("gallery");

    /**
     * Layout
     */

    function initLayout() {

        jQuery(".image-layout").unbind("click");
        jQuery(".image-layout").bind("click" , function () {

            var me = jQuery(this);
            me.parent().find(".image-layout").removeClass("selected");
            me.addClass("selected");
            me.parent().find("input").val(me.data("layout"));

        });

    }

    initLayout();

    /**
     * Ordinamenti
     */

    function initOrder() {

        jQuery(".sortable").not("init").sortable({

            placeholder: "ui-state-highlight",
            forcePlaceholderSize: true,
            opacity: 0.6

        });

        jQuery(".sortable").addClass("init");

    }

    initOrder();

    /**
     * Tiny
     */

    function initTiny ( _selector, _height , _toolbar = null ) {

        if (!_toolbar)
            _toolbar = "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link anchor image | code";

        tinymce.init({

            selector: _selector,
            height: _height,

            menubar:false,
            forced_root_block : false,
            extended_valid_elements : 'span[itemscope|itemtype|itemprop|content],div[itemscope|itemtype|itemprop],strong[itemprop],p[itemprop]',

            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste imagetools jbimages code filemanager"
            ],

            image_advtab: true,

            toolbar: _toolbar,
            relative_urls: false

        });

    }

    initTiny(".ckeditor", 300);
    initTiny(".ckeditor-small", 200, "bold italic | code");

    /**
     * Cancella un contenuto
     */

    function delContentSecondary(object) {

        var indice = jQuery(object).attr("data-index");
        jQuery("#pannello-h2-"+indice).remove();

    }

    /**
     * Cancella una foto
     */

    function delThisPhoto(object) {

        jQuery(object).parent().parent().remove();
        return false;

    }

    /**
     * Add gallery
     */

    function addGalleryButton(object) {

        var tipocontenuti = "gallery";
        var indice = jQuery(object).data("index");

        var html = '<div id="pannello-h2-'+indice+'" class="pannello-h2-'+indice+' panel panel-default"  data-collapsed="0">' +
                        '<input type="hidden" value="gallery" name="tipocontenuti[]"/>' +
                        '<div class="panel-heading">' +
                            '<div class="panel-title">Galleria fotografica #'+indice+'</div>' +
                        '</div>' +

                        '<div class="panel-body">' +

                            '<div class="form-group">' +
                                '<label for="immagine_secondaria" class="col-sm-2 control-label">Immagini</label>' +
                                '<div class="col-sm-10">' +
                                    '<div class="dropzone dropzone_gallery" id="dropzoneFileUploadSecondarioGallery_'+indice+'" data-indice="'+indice+'" data-path="pagine" data-service="uploadImage" style="border:2px dashed #ddd; display: inline-block; "></div>' +
                                    '<div class="dz-default dz-message">' +
                                        '<strong>Clicca o trascina un file sul riquadro per caricarlo</strong><br>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div id="dropzoneFileUploadSecondarioGallery_'+indice+'_list" class="form-group sortable">' +
                            '</div>' +


                            '<div class="form-group">'+
                                '<div class="col-sm-10 col-sm-offset-2">' +
                                    '<button class="delGallerytButton btn btn-danger" data-index="'+indice+'" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>' +
                                '</div>' +
                            '</div>' +

                        '</div>' +

                    '</div>';

        jQuery(".aggiungiContenuto").before(html);
        initDropZone("gallery");
        indice++;
        jQuery(".aggiungiContenuto .btn").data("index", indice);
        initOrder();

    }

    /**
     * Aggiungo una mappa
     */

    function addMapButton(object) {

        var tipocontenuti = "map";
        var indice = jQuery(object).data("index");

        var html = '<div id="pannello-h2-'+indice+'" class="pannello-h2-'+indice+' panel panel-default"  data-collapsed="">' +
                    '<input type="hidden" value = "'+tipocontenuti+'" name="tipocontenuti[]"/>' +
                    '<div class="panel-heading">' +
                        '<div class="panel-title">Mappa e punti di interese #'+indice+'</div>' +
                    '</div>' +

                    '<div class="panel-body">' +

                            '<div class="form-group" >' +
                                '<label for="layout" class="col-sm-2 control-label">Latitudine / Longitudine</label>' +
                                '<div class="col-sm-10">' +
                                    '<input class="form-control" name="map_lat_lon[]" value="" placeholder="es: 44.0535197,12.5395819"/>' +
                                    '<br /><label>' +
                                        '<input name="map_source[]" type="checkbox" value="1"> includi il listing attivo' +
                                    '</label>' +
                                '</div>' +
                            '</div>' +

                    '</div>' +


                        '<div class="form-group">'+
                            '<div class="col-sm-10 col-sm-offset-2">' +
                                '<button class="delGallerytButton btn btn-danger" data-index="'+indice+'" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>' +
                            '</div>' +
                        '</div>' +

                    '</div>';

        indice++;
        jQuery(".aggiungiContenuto .btn").data("index", indice);
        jQuery(".aggiungiContenuto").before(html);

    }

    /**
     * Add contenuto
     */

    function addContentSecondary(object) {

        var tipocontenuti = "text";
        var indice = jQuery(object).data("index");
        var titolo = "";
        var sottotitolo = "";
        var testo = "";
        var immagine = "";
        var layout = "normal";

        var html = '<div id="pannello-h2-'+indice+'" class="pannello-h2-'+indice+' panel panel-default"  data-collapsed="">' +
                    '<input type="hidden" value = "'+tipocontenuti+'" name="tipocontenuti[]"/>' +
                    '<div class="panel-heading">' +
                            '<div class="panel-title">Contenuti secondario BODY COPY #'+indice+'</div>' +
                    '</div>' +

                    '<div class="panel-body">' +

                            '<div class="form-group">' +
                                '<label for="layout" class="col-sm-2 control-label">Immagine</label>'+
                                '<div class="col-sm-10">' +
                                    '<img class="image-layout " src="/images/admin/1col.jpg" data-layout="1col" />' +
                                    '<img class="image-layout selected" src="/images/admin/normal.jpg" data-layout="normal" />' +
                                    '<img class="image-layout" src="/images/admin/left.jpg" data-layout="left" />' +
                                    '<img class="image-layout" src="/images/admin/right.jpg" data-layout="right" />' +
                                    '<img class="image-layout" src="/images/admin/over.jpg" data-layout="over" />' +
                                    '<input type="hidden" value = "'+layout+'" name="layout[]"/>' +
                                '</div>' +
                            '</div>' +

                            '<div class="form-group">' +
                                '<label for="immagine_secondaria" class="col-sm-2 control-label">Immagine</label>'+
                                '<input type="hidden" name="immagine_secondaria[]" value="'+immagine+'" class=form-control dropzoneFileUploadSecondario_'+indice+' id="immagine_secondaria_'+indice+'" />' +
                                '<div class="col-sm-10">' +
                                    '<div class="dropzone dropzone_singola" id="dropzoneFileUploadSecondario_'+indice+'" data-path="immagini-evidenza" data-service="uploadImage" data-indice="'+indice+'" style="border:2px dashed #ddd; display: inline-block; "></div>' +
                                    '<div class="dz-default dz-message">' +
                                        '<strong>Clicca o trascina un file sul riquadro per caricarlo</strong> - <a href="#" data-id="dropzoneFileUploadSecondario_'+indice+'" class="deleteImage">Elimina immagine</a><br>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="form-group">' +
                            '<label for="h2" class="col-sm-2 control-label">Titolo contenuto</label>'+
                            '<div class="col-sm-10">' +
                                '<input class="form-control" name="h2[]" placeholder="Titolo" value="'+titolo+'" />' +
                            '</div>' +
                            '</div>'+
                        '<div class="form-group">' +
                            '<label for="h3" class="col-sm-2 control-label">Sottotitolo</label>'+
                            '<div class="col-sm-10">' +
                                '<input class="form-control" name="h3[]" placeholder="Sottotitolo" value="'+ sottotitolo+'" />' +
                            '</div>' +
                            '</div>'+
                            '<div class="form-group">'+
                            '<label for="descrizione_2" class="col-sm-2 control-label">Testo contenuto</label>' +
                            '<div class="col-sm-10">' +
                                    '<textarea class="form-control ckeditor-add-'+indice+'" placeholder="Testo contenuto" name="descrizione_2[]">'+testo+'</textarea>' +
                            '</div>' +
                        '</div>' +

                            '<div class="form-group">'+
                                '<div class="col-sm-10 col-sm-offset-2">' +
                                    '<button class="delContentButton btn btn-danger" data-index="'+indice+'" type="button" onclick="delContentSecondary(this);">Elimina questo contenuto</button>' +
                                '</div>' +
                            '</div>' +

                        '</div>' +

                '</div>';

        jQuery(".aggiungiContenuto").before(html);

        initTiny(".ckeditor-add-"+indice, 300);
        initDropZone("singola");
        initLayout();
        initOrder();
        indice++;
        jQuery(".aggiungiContenuto .btn").data("index", indice);

    }

</script>
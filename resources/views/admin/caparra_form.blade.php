@extends('templates.admin')

@section('title')
    Nuova politica di modifica/cancellazione prenotazione
@endsection

@section('content')
    @php
        if (isset($politica->from)) $from = Carbon\Carbon::parse( $politica->from)->format("d/m/Y");  else $from = "";
        if (isset($politica->to))   $to   = Carbon\Carbon::parse( $politica->to)->format("d/m/Y");    else $to = "";
    @endphp

    <div id="alert-success" class="alert alert-success alert-dismissable" style="display:none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <i class="fa fa-info-circle"></i> Modifiche salvate con successo.      
    </div>

    <div class="row">

        <div class="col-sm-12">
            <p>Le informazioni richieste verranno mostrate agli utenti in più parti del sito (scheda hotel, listing, mail).<br />E' importante in un periodo di incertezza come questo agevolare la volontà dell'utente con flessibilità e senza richiesta di caparra. <br/>Specificare di non richiedere caparra facilita in questo momento particolare il contatto fra utente ed hotel.</p><br />
        </div>

        <div class="form-group">
            
            <div class=" col-md-3">
                <label style="display:block;">A partire da questa data</label>
                <input id="dpd1" type="text" value="{{$from}}" name="from_date" class="date-period form-control dateicon" autocomplete="off">
            </div>

            <div class=" col-md-3">
                <label style="display:block;">Fino a questa data</label>
                <input id="dpd2" type="text" value="{{$to}}" name="to_date" class="date-period form-control dateicon" autocomplete="off">
            </div>

            <div id="alert1" class="col-sm-12 error alert" style="display:none"></div>
        
        </div>

        <div class="clearfix"></div>

        <div class="form-group">

            <div class="col-sm-12 green evidence" style="margin-top: 30px;">
                <input id="option1" type="radio" value="1" name="option" <?php if ($politica->option == 1) echo "checked"; ?>/> <label for="option1">{!! $options[1] !!} @if (!$is_hotel) <span class="option">option 1</span><span class="short_label">{{__("labels.option_1_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 green evidence">
                <input id="option2" type="radio" value="2" name="option" <?php if ($politica->option == 2) echo "checked"; ?>/> <label for="option2">{!! $options[2] !!}@if (!$is_hotel) <span class="option">option 2</span><span class="short_label">{{__("labels.option_2_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 green evidence">
                <input id="option7" type="radio" value="7" name="option" <?php if ($politica->option == 7) echo "checked"; ?>/> <label for="option7">{!! $options[7] !!}@if (!$is_hotel) <span class="option">option 7</span><span class="short_label">{{__("labels.option_7_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 evidence orange">
                <input id="option3" type="radio" value="3" name="option" <?php if ($politica->option == 3) echo "checked"; ?>/> <label for="option3">{!! $options[3] !!}@if (!$is_hotel) <span class="option">option 3</span> <span class="short_label">{{__("labels.option_3_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 evidence orange">
                <input id="option4" type="radio" value="4" name="option" <?php if ($politica->option == 4) echo "checked"; ?>/> <label for="option4">{!! $options[4] !!}@if (!$is_hotel) <span class="option">option 4</span><span class="short_label">{{__("labels.option_4_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 evidence orange">
                <input id="option9" type="radio" value="9" name="option" <?php if ($politica->option == 9) echo "checked"; ?>/> <label for="option9">{!! $options[9] !!}@if (!$is_hotel) <span class="option">option 9</span><span class="short_label">{{__("labels.option_9_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 evidence red">
                <input id="option5" type="radio" value="5" name="option" <?php if ($politica->option == 5) echo "checked"; ?>/> <label for="option5">{!! $options[5] !!}@if (!$is_hotel) <span class="option">option 5</span><span class="short_label">{{__("labels.option_5_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 evidence red">
                <input id="option6" type="radio" value="6" name="option" <?php if ($politica->option == 6) echo "checked"; ?>/> <label for="option6">{!! $options[6] !!}@if (!$is_hotel) <span class="option">option 6</span><span class="short_label">{{__("labels.option_6_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 evidence red">
                <input id="option11" type="radio" value="11" name="option" <?php if ($politica->option == 11) echo "checked"; ?>/> <label for="option11">{!! $options[11] !!}@if (!$is_hotel) <span class="option">option 11</span><span class="short_label">{{__("labels.option_11_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 evidence red">
                <input id="option8" type="radio" value="8" name="option" <?php if ($politica->option == 8) echo "checked"; ?>/> <label for="option8">{!! $options[8] !!}@if (!$is_hotel) <span class="option">option 8</span><span class="short_label">{{__("labels.option_8_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 evidence red">
                <input id="option10" type="radio" value="10" name="option" <?php if ($politica->option == 10) echo "checked"; ?>/> <label for="option10">{!! $options[10] !!}@if (!$is_hotel) <span class="option">option 10</span><span class="short_label">{{__("labels.option_10_short")}}</span>@endif</label>
            </div>

            <div class="col-sm-12 evidence red">
                <input id="option12" type="radio" value="12" name="option" <?php if ($politica->option == 12) echo "checked"; ?>/> <label for="option12">{!! $options[12] !!}@if (!$is_hotel) <span class="option">option 12</span><span class="short_label">{{__("labels.option_12_short")}}</span>@endif</label>
            </div>
            <div class="col-sm-12 evidence red">
                <input id="option13" type="radio" value="13" name="option" <?php if ($politica->option == 13) echo "checked"; ?>/> <label for="option13">{!! $options[13] !!}@if (!$is_hotel) <span class="option">option 13</span><span class="short_label">{{__("labels.option_13_short")}}</span>@endif</label>
            </div>

            

            <div id="alert2" class="col-sm-12 error" style="display:none"></div>

        </div>

        <div class="col-sm-12">
            <button type="button" class="btn btn-primary" onclick="store()"><i class="glyphicon glyphicon-ok"></i> Salva</button>
        </div>
    
    </div>

@endsection

@section('onheadclose')

    <style>
        .evidence { position: relative; padding:3px 3px 3px 40px; margin-bottom:3px; color: #222;  border-radius:4px;}
        .evidence input[type="radio"] { position: absolute; top:2px; left:15px}
        .evidence.green { background: #DCEDC8; }
        .evidence.orange { background: #FFE0B2; }
        .evidence.red { background: #ffcdd2; }
        .evidence label { position: relative; top:2px}
        input.error {border-color: #e53935 !important}
        .error {color: #e53935 !important}
        .alert {display:block; clear: both;}

    </style>

    <script type="text/javascript">
        
        function validatePeriod() 
        {
            var error = false;

            if (jQuery('#dpd1').val() == "" || jQuery('#dpd2').val() == "") {
                error = true;
                jQuery(".date-period").addClass("error");
                jQuery("#alert1").text("Le date non sono compilate completamente.").show();
            }

            var option_value = jQuery("input[name='option']:checked").val();

            if (!option_value) {

                error = true;
                jQuery("#alert2").text("Selezionare almeno una opzione.").show();

            } else {

                if (option_value == 4) {

                    if (jQuery("input[name='day_before_1']").val() == "") {
                        error = true;
                        jQuery("input[name='day_before_1']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if (jQuery("input[name='perc']").val() == "") {
                        error = true;
                        jQuery("input[name='perc']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                } else if (option_value == 6) {

                    if (jQuery("input[name='day_before_2']").val() == "") {
                        error = true;
                        jQuery("input[name='day_before_2']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if (jQuery("input[name='month_after']").val() == "") {
                        error = true;
                        jQuery("input[name='month_after']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }
                
                } else if (option_value == 7) {

                    if (jQuery("input[name='day_before_3']").val() == "") {
                        error = true;
                        jQuery("input[name='day_before_3']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                } else if (option_value == 8) {
                    
                    if (jQuery("input[name='day_before_4']").val() == "") {
                        error = true;
                        jQuery("input[name='day_before_4']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if (jQuery("input[name='perc_2']").val() == "") {
                        error = true;
                        jQuery("input[name='perc_2']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                } else if (option_value == 9) {
                    
                    if (jQuery("input[name='day_before_5']").val() == "") {
                        error = true;
                        jQuery("input[name='day_before_5']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if (jQuery("input[name='month_after_2']").val() == "") {
                        error = true;
                        jQuery("input[name='month_after_2']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                } else if (option_value == 10) {
                    
                    if (jQuery("input[name='perc_3']").val() == "") {
                        error = true;
                        jQuery("input[name='perc_3']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                } else if (option_value == 11) {
                    
                    if (jQuery("input[name='perc_11']").val() == "") {
                        error = true;
                        jQuery("input[name='perc_11']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if (jQuery("input[name='month_after_11']").val() == "") {
                        error = true;
                        jQuery("input[name='month_after_11']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if (jQuery("input[name='day_before_11']").val() == "") {
                        error = true;
                        jQuery("input[name='day_before_11']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                }  else if (option_value == 12) {
                    if (jQuery("input[name='day_before_12']").val() == "") {
                        error = true;
                        jQuery("input[name='day_before_12']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if (jQuery("input[name='perc_4']").val() == "") {
                        error = true;
                        jQuery("input[name='perc_4']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }
                }  else if (option_value == 13) {
                    
                    if (jQuery("input[name='day_before_13']").val() == "") {
                        error = true;
                        jQuery("input[name='day_before_13']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if (jQuery("input[name='perc_13']").val() == "") {
                        error = true;
                        jQuery("input[name='perc_13']").addClass("error");
                        jQuery("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                }
            }

            return error;
        }

        function store ()
        {

            jQuery("#alert1").hide();
            jQuery("#alert2").hide();
            jQuery(".date-period").removeClass("error");
            jQuery("input[name='day_before_1']").removeClass("error");
            jQuery("input[name='day_before_2']").removeClass("error");
            jQuery("input[name='day_before_3']").removeClass("error");
            jQuery("input[name='day_before_4']").removeClass("error");
            jQuery("input[name='day_before_5']").removeClass("error");
            jQuery("input[name='day_before_11']").removeClass("error");
            jQuery("input[name='day_before_12']").removeClass("error");
            jQuery("input[name='day_before_13']").removeClass("error");
            jQuery("input[name='perc_1']").removeClass("error");
            jQuery("input[name='perc_2']").removeClass("error");
            jQuery("input[name='perc_3']").removeClass("error");
            jQuery("input[name='perc_4']").removeClass("error");
            jQuery("input[name='perc_11']").removeClass("error");
            jQuery("input[name='perc_13']").removeClass("error");
            jQuery("input[name='month_after_1']").removeClass("error");
            jQuery("input[name='month_after_2']").removeClass("error");
            jQuery("input[name='month_after_11']").removeClass("error");

            if (!validatePeriod()) {

                data_options = {
                    "_token": '{{ csrf_token() }}',
                    "id": {{$id}}, 
                    "option":   jQuery("input[name='option']:checked").val(),
                    "from":  jQuery("#dpd1").val(),
                    "to":  jQuery("#dpd2").val(),
                    "day_before": "",
                    "perc": "",
                    "month_after": ""
                }

                if (data_options.option == 4) {
                    data_options.day_before = jQuery("input[name='day_before_1']").val();
                    data_options.perc = jQuery("input[name='perc_1']").val();
                }

                if (data_options.option == 6) {
                    data_options.day_before = jQuery("input[name='day_before_2']").val();
                    data_options.month_after = jQuery("input[name='month_after_1']").val();
                }

                if (data_options.option == 7) {
                    data_options.day_before = jQuery("input[name='day_before_3']").val();
                }

                if (data_options.option == 8) {
                    data_options.day_before = jQuery('input[name="day_before_4"]').val();
                    data_options.perc = jQuery('input[name="perc_2"]').val();
                }

                if (data_options.option == 9) {
                    data_options.day_before = jQuery('input[name="day_before_5"]').val();
                    data_options.month_after = jQuery('input[name="month_after_2"]').val();
                }

                if (data_options.option == 10) {
                    data_options.perc = jQuery('input[name="perc_3"]').val();
                }

                if (data_options.option == 11) {
                    data_options.day_before = jQuery('input[name="day_before_11"]').val();
                    data_options.month_after = jQuery('input[name="month_after_11"]').val();
                    data_options.perc = jQuery('input[name="perc_11"]').val();
                }

                if (data_options.option == 12) {
                    data_options.day_before = jQuery('input[name="day_before_12"]').val();
                    data_options.perc = jQuery('input[name="perc_4"]').val();
                }

                if (data_options.option == 13) {
                    data_options.day_before = jQuery('input[name="day_before_13"]').val();
                    data_options.perc = jQuery('input[name="perc_13"]').val();
                }

                jQuery.ajax({

                    url: '/admin/politiche-cancellazione/store',
                    type: "post",
                    async: false,
                    data: data_options,

                    success: function(response) {
                        
                        jQuery("#alert-success").show();
                        jQuery("#dpd1").val("");
                        jQuery("#dpd2").val("");
                        jQuery('#dpd1').datepicker('clearDates');
                        jQuery('#dpd2').datepicker('clearDates');
                        jQuery("input[name='option']").prop("checked", "");
                        jQuery("input[name='day_before_1']").val("");
                        jQuery("input[name='day_before_2']").val("");
                        jQuery("input[name='day_before_3']").val("");
                        jQuery("input[name='day_before_4']").val("");
                        jQuery("input[name='day_before_5']").val("");
                        jQuery("input[name='day_before_11']").val("");
                        jQuery("input[name='day_before_12']").val("");
                        jQuery("input[name='day_before_13']").val("");
                        jQuery("input[name='perc_1']").val("");
                        jQuery("input[name='perc_2']").val("");
                        jQuery("input[name='perc_3']").val("");
                        jQuery("input[name='perc_4']").val("");
                        jQuery("input[name='perc_11']").val("");
                        jQuery("input[name='perc_13']").val("");
                        jQuery("input[name='month_after_1']").val("");
                        jQuery("input[name='month_after_2']").val("");
                        jQuery("input[name='month_after_11']").val("");
                        
                    }

                });
            }

        }

    </script>

@endsection

@section('onbodyclose')

    <link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/datepicker3/datepicker.min.css', true)}}" />
    <script type="text/javascript"          src="{{Utility::assets('/vendor/moment/moment.min.js', true)}}"></script>
    <script type="text/javascript"          src="{{Utility::assets('/vendor/datepicker3/datepicker.min.js', true)}}"></script>
    <script type="text/javascript"          src="{{Utility::assets('/vendor/datepicker3/locales/bootstrap-datepicker.it.min.js')}}"></script>
    <script type="text/javascript">

        jQuery(document).ready(function($) {

            jQuery("#dpd1").datepicker({ 

                format: 'dd/mm/yyyy', 
                weekStart:1,
                startDate: moment().format("D/M/Y"),
                language: "it",
                orientation: "bottom left",
                todayHighlight: true,
                autoclose: true

            }).on("changeDate", function(e) {
            
                var data_dal = moment(jQuery("#dpd1").datepicker("getDate"));
                var data_al = moment(jQuery("#dpd2").datepicker("getDate"));

                if (data_al.isBefore(data_dal)) {

                    data_al = data_dal.add(1, 'd');
                    jQuery("#dpd2").datepicker("setDate", data_al.format("D/M/Y") );

                }

                jQuery("#dpd2").datepicker("setStartDate", moment(e.date).format("D/M/Y") );
                jQuery("#dpd1").datepicker("setRange", [ data_dal, data_al.add("1","d")]);
                jQuery("#dpd2").datepicker("setRange", [ data_dal, data_al]);
                jQuery("#dpd2").datepicker("show");

            });

            jQuery("#dpd2").datepicker({ 
                
                format: 'dd/mm/yyyy', 
                weekStart:1,
                startDate: moment().format("D/M/Y"),
                language: "it",
                orientation: "bottom left",
                todayHighlight: true,
                autoclose: true

            }).on("changeDate", function(e) {

                var data_dal = moment(jQuery("#dpd1").datepicker("getDate"));
                var data_al = moment(jQuery("#dpd2").datepicker("getDate"));

                jQuery("#valido_dal").datepicker("setRange", [ data_dal, data_al.add("1","d")]);
                jQuery("#dpd2").datepicker("setRange", [ data_dal, data_al]);

            });
        });
        
    </script>

@endsection

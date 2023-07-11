<!DOCTYPE html>

<html lang="it_IT">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <title>Configurazione politiche di modifica/cancellazione prenotazione</title>

    <style>

        @include('vendor.fontello.css.fontello') 
        @include('desktop.css.above') 
        @include('desktop.css.deposit')
        @include('caparra.head')

    </style>

    <link href="{{Utility::asset('/vendor/air-datepicker/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{Utility::asset('/vendor/air-datepicker/datepicker.min.js')}}"></script>

    <script>
        var today = new Date();
        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);

        $(function() {

            var first = true
            $(".link").click(function(e) {

              e.preventDefault();
              $("#formHelpRequest").submit();

            });

            var options = {};
            options.language = {
                days: ['Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'],
                daysShort: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
                daysMin: ['Do', 'Lu', 'Ma', 'Me', 'Gi', 'Ve', 'Sa'],
                months: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
                monthsShort: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
                today: 'Oggi',
                clear: 'Annulla',
                dateFormat: 'dd/mm/yyyy',
                timeFormat: 'hh:ii aa',
                firstDay: 0
            };

            options.autoClose = true;
            options.minDate = tomorrow;

            $('#dpd2').datepicker(options);
            $('#dpd2').data('datepicker').selectDate(new Date(2020,8,30));

            options.minDate = today;
            options.onSelect = function onSelect(fd, date) {

                if (!first) {
                    var datepicker = $('#dpd2').data('datepicker');
                    datepicker.clear();
                    var tomorrow = new Date(date);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    datepicker.update('minDate', tomorrow);
                } else {
                    first = false;
                }
            }
            
            $('#dpd1').datepicker(options);
            $('#dpd1').data('datepicker').selectDate(new Date(2020,5,1));
            

        })

        function validatePeriod() {

            var error = false;

            if ($('#dpd1').val() == "" || $('#dpd2').val() == "") {
                error = true;
                $(".date-period").addClass("error");
                $("#alert1").text("Le date non sono compilate completamente.").show();
            }

            var option_value = $("input[name='option']:checked").val();

            if (!option_value) {

                error = true;
                $("#alert2").text("Selezionare almeno una opzione.").show();

            } else {

                if (option_value == 4) {

                    if ($("input[name='day_before_1']").val() == "") {
                        error = true;
                        $("input[name='day_before_1']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if ($("input[name='perc_1']").val() == "") {
                        error = true;
                        $("input[name='perc_1']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                } else if (option_value == 6) {

                    if ($("input[name='day_before_2']").val() == "") {
                        error = true;
                        $("input[name='day_before_2']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if ($("input[name='month_after_1']").val() == "") {
                        error = true;
                        $("input[name='month_after_1']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                } else if (option_value == 7) {
                    
                    if ($("input[name='day_before_3']").val() == "") {
                        error = true;
                        $("input[name='day_before_3']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                } else if (option_value == 8) {
                    
                    if ($("input[name='day_before_4']").val() == "") {
                        error = true;
                        $("input[name='day_before_4']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if ($("input[name='perc_2']").val() == "") {
                        error = true;
                        $("input[name='perc_2']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                } else if (option_value == 9) {
                    
                    if ($("input[name='day_before_5']").val() == "") {
                        error = true;
                        $("input[name='day_before_5']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                    if ($("input[name='month_after_2']").val() == "") {
                        error = true;
                        $("input[name='month_after_2']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                }  else if (option_value == 10) {
                    
                    if ($("input[name='perc_3']").val() == "") {
                        error = true;
                        $("input[name='perc_3']").addClass("error");
                        $("#alert2").text("L'opzione selezionata richiede la compilazione di campi aggiuntivi.").show();
                    }

                }
            }

            return error;

        }

        function addPeriod(callback = false) {

            $("#alert1").hide();
            $("#alert2").hide();
            $(".date-period").removeClass("error");
            $("input[name='day_before_1']").removeClass("error");
            $("input[name='day_before_2']").removeClass("error");
            $("input[name='day_before_3']").removeClass("error");
            $("input[name='day_before_4']").removeClass("error");
            $("input[name='day_before_5']").removeClass("error");
            $("input[name='perc_1']").removeClass("error");
            $("input[name='perc_2']").removeClass("error");
            $("input[name='perc_3']").removeClass("error");
            $("input[name='month_after_1']").removeClass("error");
            $("input[name='month_after_2']").removeClass("error");

            if (!validatePeriod()) {

                var option_value = $("input[name='option']:checked").val();
                var data_from = $('#dpd1').val();
                var data_to = $('#dpd2').val();
                var day_before= "";
                var perc = "";
                var month_after = "";

                if (option_value == 4) {
                    var day_before = $('input[name="day_before_1"]').val();
                    var perc = $('input[name="perc_1"]').val();
                }

                if (option_value == 6) {
                    var day_before = $('input[name="day_before_2"]').val();
                    var month_after = $('input[name="month_after_1"]').val();
                }

                if (option_value == 7) {
                    var day_before = $('input[name="day_before_3"]').val();
                }

                if (option_value == 8) {
                    var day_before = $('input[name="day_before_4"]').val();
                    var perc = $('input[name="perc_2"]').val();
                }

                if (option_value == 9) {
                    var day_before = $('input[name="day_before_5"]').val();
                    var month_after = $('input[name="month_after_2"]').val();
                }

                if (option_value == 10) {
                    var perc = $('input[name="perc_3"]').val();
                }

                var html = "<div class='items-line container' data-option='" + option_value + "' data-from='" + data_from + "' data-to='" + data_to + "' data-day-before='" + day_before + "' data-perc='" + perc + "' data-month-after='" + month_after + "'>";
                html += "<div class='items row'>";
                html += '<div class="col-sm-3">';
                html += "Dal <b>" + data_from + "</b> al <b>" + data_to + "</b> ";
                html += '</div>';
                html += '<div class="col-sm-8">';

                if ($("input[name='option']:checked").val() == 1) {
                    html += "<b>{{__('labels.option_1_short')}}</b><br />{{__('labels.option_1')}}";
                }

                if ($("input[name='option']:checked").val() == 2) {
                    html += "<b>{{__('labels.option_2_short')}}</b><br />{{__('labels.option_2')}}";
                }

                if ($("input[name='option']:checked").val() == 3) {
                    html += "<b>{{__('labels.option_3_short')}}</b><br />{{__('labels.option_3')}}";
                }

                if ($("input[name='option']:checked").val() == 4) {
                    var rtext = "{{__('labels.option_4_short')}}";
                    rtext = rtext.replace("$1", day_before);
                    rtext = rtext.replace("$2", perc);

                    var ltext = "{{__('labels.option_4')}}";
                    ltext = ltext.replace("$1", day_before);
                    ltext = ltext.replace("$2", perc);
                    html += "<b>" + rtext + "</b><br />" + ltext;
                }

                if ($("input[name='option']:checked").val() == 5) {
                }

                if ($("input[name='option']:checked").val() == 6) {
                    var rtext = "{{__('labels.option_6_short')}}";
                    rtext = rtext.replace("$1", day_before);
                    rtext = rtext.replace("$2", month_after);
                    
                    var ltext = "{{__('labels.option_6')}}";
                    ltext = ltext.replace("$1", day_before);
                    ltext = ltext.replace("$2", month_after);
                    html += "<b>" + rtext + "</b><br />" + ltext;
                }

                if ($("input[name='option']:checked").val() == 7) {
                    var rtext = "{{__('labels.option_7_short')}}";
                    rtext = rtext.replace("$1", day_before);
                    
                    var ltext = "{{__('labels.option_7')}}";
                    ltext = ltext.replace("$1", day_before);
                    html += "<b>" + rtext + "</b><br />" + ltext;
                }

                if ($("input[name='option']:checked").val() == 8) {
                    var rtext = "{{__('labels.option_8_short')}}";
                    rtext = rtext.replace("$1", day_before);
                    rtext = rtext.replace("$2", perc);

                    var ltext = "{{__('labels.option_8')}}";
                    ltext = ltext.replace("$1", day_before);
                    ltext = ltext.replace("$2", month_after);
                    html += "<b>" + rtext + "</b><br />" + ltext;
                }

                if ($("input[name='option']:checked").val() == 9) {
                    var rtext = "{{__('labels.option_9_short')}}";
                    rtext = rtext.replace("$1", day_before);
                    rtext = rtext.replace("$2", month_after);
                    
                    var ltext = "{{__('labels.option_9')}}";
                    ltext = ltext.replace("$1", day_before);
                    ltext = ltext.replace("$2", month_after);
                    html += "<b>" + rtext + "</b><br />" + ltext;
                }

                if ($("input[name='option']:checked").val() == 10) {
                    var rtext = "{{__('labels.option_10_short')}}";
                    rtext = rtext.replace("$1", perc);
                    
                    var ltext = "{{__('labels.option_10')}}";
                    ltext = ltext.replace("$1", perc);
                    html += "<b>" + rtext + "</b><br />" + ltext;
                }

                html += '</div><div class="col-sm-1" style="text-align:right;"><button onClick="removePeriod(this)" class="btn btn-cyano btn-small">Elimina</button></div></div></div>';

                $("#period-items .p-items").append(html);
                $("#period-items").show();

                if (callback)
                    saveOptions(); 

            }

            $("html, body").stop().animate({scrollTop:0}, 500, "swing");

        }

        function saveOptions() {

            if ( $(".items-line").length == 0) {
                addPeriod(true);
            } else {

                var hotel_id = $("#hotel_id").val();
                $("#period-items").hide();
                $("#add-periods").hide();
                $("#loading").show();

                var data_options = [];

                $(".items-line").each(function(i) {

                    data_options[i] = {};
                    data_options[i].option = $(this).attr("data-option");
                    data_options[i].from = $(this).attr("data-from");
                    data_options[i].to = $(this).attr("data-to");
                    data_options[i].day_before = $(this).attr("data-day-before")
                    data_options[i].perc = $(this).attr("data-perc");
                    data_options[i].month_after = $(this).attr("data-month-after");

                });

                $.ajax({
                    url: '/politiche-cancellazione/' + hotel_id,
                    type: "post",
                    async: false,
                    data: {
                        'hotel_id': hotel_id,
                        '_token': '{{ csrf_token() }}',
                        'options': JSON.stringify(data_options)
                    },
                    success: function(response) {
                        if (response.success == "success") {
                            document.location.href = "/politiche-cancellazione/" + hotel_id + "/confirm";
                        } else {
                            document.location.href = "/error";
                        }
                    }
                });
            }

        }

        function removePeriod(obj) {

            $(obj).closest(".items-line").remove();
            if ($(".items-line").length == 0) {
                $("#period-items").hide();
            }

        }


    </script>

</head>

<body>

    @include("caparra.header")

    <main>
        <section class="container">
            <div class="row">

            
                <article class="col-sm-12 col-md-6">
                    <h3 style="padding-bottom:0;">{{$hotel->nome}}</h3>
                    <small>{{$hotel->indirizzo}}, {{$hotel->localita->nome}} ({{$hotel->localita->prov}})</small><br /><br />
                
                    <form action="{{ route('help_request',$hotel->id) }}" method="POST" id="formHelpRequest">
                        @csrf
                    </form>

                </article>

                <section id="period-items" class="col-sm-12" style="display:none;">

                    <input type="hidden" name="hotel_id" id="hotel_id" value="{{$hotel->id}}" />
                    <div class="p-items">
                    </div>
                    <h3 style="display: block; padding-bottom:0px;">Aggiungi un perido</h3>

                </section>

               
                <section id="add-periods" class="col-sm-12" style="margin-bottom: 100px;">

                    <div id="alert1" class="col-sm-12 error" style="display:none;margin-bottom: 15px;"></div>

                    <div class="col-sm-12 col-md-3">
                        <label style="display:block;">A partire da questa data</label>
                        <input id="dpd1" type="text" value="01/06/2020" name="from_date" class="date-period" autocomplete="off">
                    </div>

                    <div class="col-sm-12 col-md-3" style="margin-bottom: 30px;">
                        <label style="display:block;">Fino a questa data</label>
                        <input id="dpd2" type="text" value="30/09/2020" name="to_date" class="date-period" autocomplete="off">
                    </div>
                    
                    <div id="alert2" class="col-sm-12 error" style="display:none;margin-bottom: 15px;"></div>

                    <div class="col-sm-12 green evidence" >
                        <input id="option1" type="radio" value="1" name="option" /> <label for="option1">{{__("labels.option_1")}}</label>
                    </div>

                    <div class="col-sm-12 green evidence">
                        <input id="option2" type="radio" value="2" name="option" /> <label for="option2">{{__("labels.option_2")}}</label>
                    </div>

                    <div class="col-sm-12 green evidence">
                        <input id="option7" type="radio" value="7" name="option" /> <label for="option7">{!! str_replace("$1" , '<input type="number" min="0" name="day_before_3" style="width:50px!important" value="" class="input-small" />', __("labels.option_7")) !!}</label>
                    </div>

                    <div class="col-sm-12 evidence green">
                        <input id="option3" type="radio" value="3" name="option" /> <label for="option3">{{__("labels.option_3")}}</label>
                    </div>

                    <div class="col-sm-12 evidence orange">
                        <input id="option4" type="radio" value="4" name="option" /> <label for="option4">{!! str_replace(["$1", "$2"] , ['<input type="number" min="0" name="day_before_1" style="width:50px!important" value="" class="input-small" />','<input min="0" type="number" name="perc_1" class="input-small" style="width:50px !important" value="" />'], __("labels.option_4")) !!}</label>
                    </div>

                    <div class="col-sm-12 evidence orange">
                        <input id="option9" type="radio" value="9" name="option" /> <label for="option9">{!! str_replace(["$1", "$2"] , ['<input type="number" min="0" name="day_before_5" style="width:50px!important" value="" class="input-small" />','<input min="0" type="number" name="month_after_2" class="input-small" style="width:50px !important" value="" />'], __("labels.option_9")) !!}</label>
                    </div>

                    <div class="col-sm-12 evidence red">
                        <input id="option5" type="radio" value="5" name="option" /> <label for="option5">{{__("labels.option_5")}}</label>
                    </div>

                    <div class="col-sm-12 evidence red">
                        <input id="option6" type="radio" value="6" name="option" /> <label for="option6">{!! str_replace(["$1", "$2"] , ['<input type="number" min="0" name="day_before_2" style="width:50px!important" value="" class="input-small" />','<input min="0" type="number" name="month_after_1" class="input-small" style="width:50px !important" value="" />'], __("labels.option_6")) !!}</label>
                    </div>

                    <div class="col-sm-12 evidence red">
                        <input id="option8" type="radio" value="8" name="option" /> <label for="option8">{!! str_replace(["$1", "$2"] , ['<input type="number" min="0" name="day_before_4" style="width:50px!important" value="" class="input-small" />','<input min="0" type="number" name="perc_2" class="input-small" style="width:50px !important" value="" />'], __("labels.option_8")) !!}</label>
                    </div>

                    <div class="col-sm-12 evidence red">
                        <input id="option10" type="radio" value="10" name="option" /> <label for="option10">{!! str_replace(["$1"] , ['<input min="0" type="number" name="perc_3" class="input-small" style="width:50px !important" value="" />'], __("labels.option_10")) !!}</label>
                    </div>

                   

                    <div class="col-sm-12 buttons" style="margin-top:30px;">
                        <button class="btn btn-cyano" onClick="addPeriod()" style="margin-right:5px;">Aggiungi periodo</button>  
                        <button class="btn btn-primary" onclick="saveOptions()">Avanti</button>
                        <br /> <br />
                        <small><a href="#" class="link">Ho bisogno di aiuto. Chiedo di essere ricontattato!</a></small>
                    </div>

                </section>

                <br />  
                <section id="loading" style="display:none">
                    Salvataggio in corso ...
                </section>

            </div>
        </section>
    </main>

</body>

</html>
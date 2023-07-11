<!DOCTYPE html>

<html lang="it_IT">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Configurazione politiche di modifica/cancellazione prenotazione</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <style>
        @include('vendor.fontello.css.fontello') @include('desktop.css.above') @include('desktop.css.deposit')
        @include('caparra.head')
    </style>

    <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>

    <script>

        function validate() {

            var error = false;
            if ($("#confirm-data").val().trim() == "") {

                error = true;
                $("#alert").text("Compilare il campo per confermare i dati.").show();
                $("#confirm-data").addClass("error");

            }

            return error;
        } 

        function convalidateIA() 
        {
            $("#alert").hide();
            $("#confirm").hide();
            $("#confirm-result-ia").show();
            $("#confirm-data").removeClass("error");
        }

        function convalidate () {

            $("#alert").hide();
            $("#confirm-data").removeClass("error");

            if (!validate()) {

                $("#loading").show();
                $("#confirm").hide();

                var hotel_id = $("#hotel_id").val();
                var data_options = {};
                data_options.hotel_id = $("#hotel_id").val();
                data_options.method = $("#confirm-method").val();
                data_options.data = $("#confirm-data").val();

                $.ajax({
                    url: '/politiche-cancellazione/' + hotel_id + "/confirm",
                    type: "post",
                    async: false,
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'data': JSON.stringify(data_options)
                    },

                    success: function(response) {

                        if (response.success == "success") {
                            
                            $("#loading").hide();
                            $("#confirm-result").show();

                        } else {
                            
                            $("#alert").text("I dati inseriti non coincidono con quelli in nostro possesso.").show();
                            $("#confirm-data").addClass("error");
                            $("#loading").hide();
                            $("#confirm").show();

                        }
                    }
                });
            }

        }
    </script>

</head>

<body>

    @include("caparra.header")

    <main>
        <section class="container">
            <div class="row">

                <div class="col-sm-12">
                    <h2>Conferma credenziali</h2>
                </div>

                <section id="confirm">

                    <p>Per completare l'inserimento delle tue politiche ti chiediamo di completare 
                        @if ($method == "email") <b>l'email</b> mascherata di seguito: @endif
                        @if ($method == "phone")  <b>il numero di telefono</b> mascherato di seguito: @endif
                        @if ($method == "fax")  <b>il numero di fax</b> mascherato di seguito: @endif
                        @if ($method == "mobile")  <b>il numero di cellulare</b> mascherato di seguito: @endif
                        @if ($method == "whatsapp")  <b>il numero di cellulare/whatsapp</b> mascherato di seguito: @endif
                    </p>
                    <b>{{$verify_method[$method]}}</b><br /><br />
                    <input id="confirm-method" value="{{$method}}" type="hidden" />
                    <input id="hotel_id" value="{{$hotel->id}}" type="hidden" />
                    <input id="confirm-data" value="" placeholder="{{__("labels.confirminput")}}" type="text"/>
                    <div id="alert" class="error" style="display:none"></div>
                    <br /><br />
                    <button class="btn btn-primary" onclick="convalidate()" style="margin-right:30px">Conferma</button><br><br />
                    <a href="#" onclick="convalidateIA()">{{__("labels.dontknow")}}</a>
                    
                </section>
                
                <section id="loading" style="display:none">
                    Convalida in corso ...
                </section>
                
                <div class="clearfix"></div>
                <section id="confirm-result" style="display:none">
                    <div class="evidence green" style="padding:10px;">
                        <b>Tutti i dati sono stati salvati e visibili online</b>.
                    </div>
                </section>

                <section id="confirm-result-ia" style="display:none">
                    <div class="evidence orange" style="padding:10px;">
                        <b>Tutti i dati sono stati salvati.</b><br /> Un operatore di Info Alberghi abiliterà le politiche di cancellazione valutandone l'attendibilità.
                    </div>
                </section>

            </div>
        </section>
    </main>

</body>

</html>
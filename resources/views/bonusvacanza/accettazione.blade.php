<!DOCTYPE html>

<html lang="it_IT">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <title>Configurazione bonus vacanze 2020</title>

    <style>

        @include('vendor.fontello.css.fontello') 
        @include('desktop.css.above') 
        @include('desktop.css.deposit')
        @include('bonusvacanza.head')
        .search-text-item { display:inline!important; }

    </style>
    
    <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>

    <script>
        
        function saveOptions(opt)
        {
            var hotel_id = $("#hotel_id").val();
            $.ajax({
                url: '/politiche-bonus-vacanza-2020/' + hotel_id,
                type: "post",
                async: false,
                data: {
                    'hotel_id': hotel_id,
                    '_token': '{{ csrf_token() }}',
                    'option': opt
                },
                success: function(response) {
                    if (response.success == "success") {
                        document.location.href = "/politiche-bonus-vacanza-2020/" + hotel_id + "/confirm";
                    } else {
                        document.location.href = "/politiche-bonus-vacanza-2020/error";
                    }
                }
            });
        }

    </script>
</head>

<body>

    @include("bonusvacanza.header")

    <main>
        <section class="container">
            <div class="row">
                <div class="col-sm-3"></div>

                <article class="col-sm-6 col-xs-12" style="text-align:center;">
                    <input type="hidden" name="hotel_id" id="hotel_id" value="{{$hotel->id}}" />
                    <p>I clienti della tua struttura potranno usufruire del bonus vacanze?</p>
                <button class="btn btn-primary" id="accetto" style="margin-bottom:10px;" onClick="saveOptions(2)">Si, lo accetto</button><br /><button class="btn btn-danger" id="nonaccetto" onclick="saveOptions(-2)">No, non lo accetto</button><br /><br /><br />
                <a href="https://www.bonusvacanze.net/" target="_blank">Non conosco il bonus vacanze, di cosa si tratta?</a>
                </article>
            </div>
        </section>
    </main>

</body>
</html>
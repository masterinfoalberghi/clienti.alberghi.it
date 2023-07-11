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
        $(function() {
            var first = true
            $(".link").click(function(e) {

              e.preventDefault();
              $("#formHelpRequest").submit();

            });
        });
    </script>

</head>

<body>

    @include("caparra.header")
    <form action="{{ route('help_request',$hotel->id) }}" method="POST" id="formHelpRequest">
        @csrf
    </form>
    <main>
        <section class="container">
            <div class="row">

                <div class="col-sm-12">
                    <h1>Attenzione!</h1>
                    <p>L'hotel scelto non risulta attivo sul nostro portale.</p>
                    <a href="/admin" class="btn btn-primary">Effettua il login</a>
                    <a href="/politiche-cancellazione" class="btn btn-cyano">Cambia struttura</a>
                    <br /> <br />
                    <small><a href="#" class="link">Ho bisogno di aiuto. Chiedo di essere ricontattato!</a></small>
                </div>

            </div>
        </section>
    </main>

</body>
</html>
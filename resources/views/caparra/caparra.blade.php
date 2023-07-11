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
        .search-text-item { display:inline!important; }

    </style>
    
    <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>

    <script>

        function searchHotel () {

            var searchText = $(".search-hotel").val().toLowerCase();

            $(".search-text-item").each(function () {
                var nameHotel = $(this).text().toLowerCase();
                if (nameHotel.indexOf(searchText) > -1) {
                    $(this).closest("tr").show();
                } else { 
                    $(this).closest("tr").hide();
                }
            });

        }

    </script>

</head>

<body>
    
    @include("caparra.header")

    <main>
        <div id="search-hotel" class="container">
            <div class="row">

                <article class="col-sm-12">

                    <p>INDICA LA TUA STRUTTURA</p>

                    <div class="search">

                        <input class="search-hotel" value="" placeholder="Scrivere il nome dell'hotel" type="text" onKeyUp="searchHotel()" />
                        <div class="hotel-list">
                            <table>
                            @foreach($hotel as $h)
                                <tr id="hotel-{{$h->id}}" class="hotel-item" style="display:none;">

                                    <td class="name">
                                        <span class="search-text-item"><b>{{$h->nome}}</b><br /><small>(#{{$h->id}})</small></span><small> - <span>{{$h->indirizzo}},</span> {{$h->localita->nome}} ({{$h->localita->prov}})</small>
                                    </td>

                                    <td class="button" style="text-align:right">
                                        <a class="link-hotel btn btn-primary" href="/politiche-cancellazione/{{$h->id}}" onClick="selecthotelitem({{$h->id}});">Seleziona</a>
                                    </td>

                                </tr>
                            @endforeach
                            </table>
                        </div>

                    </div>

                    <br /><br /><br />
                    <footer>
                        <ol>
                            <li>Cercare la propria struttura tramite il campo di ricerca;</li>
                            <li>Inserire le politiche di modifica/cancellazione prenotazione secondo le istruzioni:</li>
                            <li>Alla fine della procedura di inserimento verrà richiesto la conferma del numero di telefono o dell'email principale;</li>
                            <li>Se non si possiede questo dato un operatore di Info Alberghi vi contatterà successimente per verificare e confermare le politiche;</li>
                            <li>Se si vuole aggiungere dei periodi dopo il primo inserimento lo si potrà fare solo <a href="/admin"><u>effettuando il login all'amministrazione</u></a>;</li>
                        </ol>
                    </footer>
                </article>
            </div>
        </div>
        
    </main>

</body>
</html>
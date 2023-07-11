<!DOCTYPE html>
<html lang="{{$locale}}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="noindex">
        <title>{{__("title.filter")}}</title>
        <meta name="description" content="">
        
        {!! "<style>" !!}
        
            @include('vendor.fontello.css.animation')
            @include('vendor.fontello.css.fontello')
            @include('desktop.css.above')
            @include('desktop.css.filter')
        
        {!! "</style>" !!}

    </head>

    <body class="filter-listing " style="padding-bottom:100px;">
        
        <header class="row">
            <h2 class="col-sm-12"><span class="count_hotel">{{$count}}</span> Hotel trovati </h2>
        </header>

        <div class="row">

            <div class="col-sm-3 hide">
                <div style="margin-right:10px">
                    <label>{{__("labels.localita")}}</label><br />
                    <div class="list-macrolocalita">
                        @foreach($macrolocalita as $macro)
                            <div data-id="{{$macro->id}}" data-group="filter-button-0" class="filter-button filter-button-0 @if($macro->id == 11) filter-default @endif @if(in_array($macro->id , $macrolocalita_ids)) selected @endif">
                                {{$macro->nome}} 
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div style="margin-right:10px">
                    <label>{{__("labels.category")}}</label><br />
                    <div class="list-categorie">
                        @foreach($categorie as $key => $cat)
                            <div  data-id="{{$key}}" data-group="filter-button-2" class="filter-button filter-button-2 @if($key == "*") filter-default @endif @if(in_array($key, $categorie_keys)) selected @endif">
                                @if ($cat == "labels.filter_category")
                                    {!!__($cat)!!} 
                                @else
                                    {!!$cat!!} 
                                @endif
                                
                            </div>
                        @endforeach
                    </div>
           
                    <br /><label>{{__("labels.popolari")}}</label><br />
                    <div class="list-categorie">
                        {{-- <div  data-id="1" data-group="filter-button-3" class="filter-button filter-button-3 @if($bonus_vacanza_keys == 1) selected @endif">
                            {{__("labels.filter_bonus")}}
                        </div> --}}
                        <div  data-id="1" data-group="filter-button-6" class="filter-button filter-button-6 @if($cancellazione_gratuita_keys == 1) selected @endif">
                            {{__("labels.filter_policy")}}
                        </div>      
                        <div  data-id="1" data-group="filter-button-4" class="filter-button filter-button-4 @if($annuale_keys == 1) selected @endif">
                            {{__("labels.filter_opening")}}
                        </div>                  
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div style="margin-right:10px">
                    <label>{{__("labels.trattamenti")}}</label><br />
                    <div class="list-trattamenti">
                        @foreach($trattamenti as $key => $trat)
                            <div data-id="{{$key}}" data-group="filter-button-1" class="filter-button filter-button-1 @if($key == "*") filter-default @endif @if(in_array($key, $trattamenti_keys)) selected @endif">
                                {!!$trat!!} 
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-sm-3">
                <div style="margin-right:10px">
                    <label>{{__("labels.servizi")}}</label><br />
                    <div class="list-servizi">
                        @foreach($gruppo_servizi as $key => $serv)
                            <div data-id="{{$key}}" data-group="filter-button-5" class="filter-button filter-button-5 @if($key == "*") filter-default @endif @if(in_array($key, $gruppo_servizi_keys)) selected @endif">
                                {!!$serv!!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div style="margin-right:10px">
                    <label>{{__("labels.filter_rating")}}</label><br />
                    <div class="list-rating">
                        <div data-id="9" data-group="filter-button-7" class="filter-button filter-button-7 @if(in_array("9", $rating_keys)) selected @endif">
                            {{-- {{__("listing.top")}} --}}{{__("listing.da")}} 9 {{__("labels.a")}} 10
                        </div>
                        <div data-id="8" data-group="filter-button-7" class="filter-button filter-button-7 @if(in_array("8", $rating_keys)) selected @endif">
                            {{-- {{__("listing.eccezionale")}} --}}{{__("listing.da")}} 8 {{__("labels.a")}} 9
                        </div>
                        <div data-id="7" data-group="filter-button-7" class="filter-button filter-button-7 @if(in_array("7", $rating_keys)) selected @endif">
                            {{-- {{__("listing.ottimo")}} --}}{{__("listing.da")}} 7 {{__("labels.a")}} 8
                        </div>
                        <div data-id="6" data-group="filter-button-7" class="filter-button filter-button-7 @if(in_array("6", $rating_keys)) selected @endif">
                            {{-- {{__("listing.buono")}} --}}{{__("listing.da")}} 6 {{__("labels.a")}} 7
                        </div>
                        <div data-id="*" data-group="filter-button-7" class="filter-button filter-button-7 filter-default @if(in_array("*", $rating_keys)) selected @endif">
                            {{__("labels.filter_all_rating")}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="buttons">
            <button id="button-filter" class="btn btn-verde">{{__("labels.filtra")}} (<span class="count_hotel">{{$count}}</span>)</button>
        </div>

        @include("widget.loading")

        <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script> 

        <script>

            function retriveData () {

                var macrolocalita_ids = [];
                var trattamenti_keys = [];
                var categorie_keys = [];
                var bonus_vacanza_keys = "";
                var annuale_keys = "";
                var cancellazione_gratuita_keys = "";
                var gruppo_servizi_keys = [];
                var rating_keys = [];

                $(".filter-button-0.selected").each(function () {
                    macrolocalita_ids.push($(this).data("id"));
                });

                $(".filter-button-1.selected").each(function () {
                    trattamenti_keys.push($(this).data("id"));
                });

                $(".filter-button-2.selected").each(function () {
                    categorie_keys.push($(this).data("id"));
                });

                $(".filter-button-3.selected").each(function () {
                    bonus_vacanza_keys = $(this).data("id");
                });

                $(".filter-button-4.selected").each(function () {
                    annuale_keys = $(this).data("id");
                });

                $(".filter-button-6.selected").each(function () {
                    cancellazione_gratuita_keys = $(this).data("id");
                });

                $(".filter-button-5.selected").each(function () {
                    gruppo_servizi_keys.push($(this).data("id"));
                });

                $(".filter-button-7.selected").each(function () {
                    rating_keys.push($(this).data("id"));
                });

                /**
                 * $macrolocalita_id = [11], 
                 * $trattamenti_keys = ["*"], 
                 * $categorie_keys = ["*"], 
                 * $bonus_vacanza_keys = 0, 
                 * $annuale_keys = "*", 
                 * $gruppo_servizi_keys = ["*"], 
                 * $cancellazione_gratuita_keys = "*" 
                 * $rating_keys = ["*"]
                 */

                if (bonus_vacanza_keys == "")           bonus_vacanza_keys = 0;
                if (annuale_keys == "")                 annuale_keys = "*";
                if (cancellazione_gratuita_keys == "")  cancellazione_gratuita_keys = "*";
                
                return [
                    macrolocalita_ids.join("+"), 
                    trattamenti_keys.join("+"),  
                    categorie_keys.join("+"), 
                    bonus_vacanza_keys, 
                    annuale_keys, 
                    gruppo_servizi_keys.join("+"), 
                    cancellazione_gratuita_keys,
                    rating_keys.join("+")
                ];

            }

            $(function () {

                $(".filter-button").click(function (e) {

                    $(".loading-ajax").show();
                    e.preventDefault();
                    var group = $(this).data("group");

                    /** Se sono il tasto di default allora spengo tutto mi attimo */
                    if ($(this).hasClass("filter-default")) {
                        
                        $("." + group).removeClass("selected");
                        $(this).addClass("selected");

                    /** Altrimenti vado al conteggio */
                    } else {

                        /** Se sono attivo mi tolgo dal conteggio */
                        if ($(this).hasClass("selected")) {
                        
                            $(this).removeClass("selected");

                            /** Se non ho più una selezione seleziono quella di default */
                            if ($("." + group + ".selected").length == 0) {
                                $("." + group + ".filter-default").addClass("selected");
                            }
                        
                        /** Se non sono attivo mi illumino */
                        } else {
                            
                            $(this).addClass("selected");

                            /** Toldo la selezione al bottone di default */
                            if ($("." + group + ".selected").length > 0) {
                                $("." + group + ".filter-default").removeClass("selected");
                            }
                            
                        }

                    }
    
                    /**
                     * Recupero dei dati 
                     */

                    var data = retriveData();

                    console.log(data);

                    var macrolocalita_ids = data[0];
                    var trattamenti_keys = data[1];
                    var categorie_keys = data[2];
                    var bonus_vacanza_keys = data[3];
                    var annuale_keys = data[4];
                    var gruppo_servizi_keys = data[5];
                    var cancellazione_gratuita_keys = data[6];
                    var rating_keys = data[7];

                    $.ajax({
                        
                        url: "/filter/count",
                        method: "POST",
                        data: { 

                            "_token": "{{ csrf_token() }}",
                            "macrolocalita_ids": macrolocalita_ids,
                            "trattamenti_keys": trattamenti_keys,
                            "categorie_keys": categorie_keys,
                            "bonus_vacanza_keys": bonus_vacanza_keys,
                            "annuale_keys": annuale_keys,
                            "cancellazione_gratuita_keys": cancellazione_gratuita_keys,
                            "gruppo_servizi_keys": gruppo_servizi_keys,
                            "rating_keys": rating_keys

                        }

                    }).done(function(data) {

                        $(".count_hotel").text(data["count"]);
                        if (data["count"] == 0) {
                            $("#button-filter").addClass("disabled").prop("disabled", true);
                        } else {
                            $("#button-filter").removeClass("disabled").prop("disabled", false);
                        }

                        $(".loading-ajax").hide();
                        
                    });

                });

                $("#button-filter").click(function (e){

                    e.preventDefault();
                    var data = retriveData();
                    var macrolocalita_ids = data[0];
                    var trattamenti_keys = data[1];
                    var categorie_keys = data[2];
                    var bonus_vacanza_keys = data[3];
                    var annuale_keys = data[4];
                    var gruppo_servizi_keys = data[5];
                    var cancellazione_gratuita_keys = data[6];
                    var rating_keys = data[7];
                    
                    var uri = "/filter-listing/" + macrolocalita_ids + "/" + trattamenti_keys + "/" + categorie_keys + "/" + bonus_vacanza_keys + "/" + annuale_keys + "/" + gruppo_servizi_keys + "/" + cancellazione_gratuita_keys + "/" + rating_keys;
                    window.top.location.href = uri;

                });

            });

        </script>
    </body>
</html>
<!DOCTYPE html>
<html lang="{{$locale}}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="noindex">
    
    <title>{{__("title.filter")}}</title>
    <meta name="description" content="">
    
    {!!"<style>"!!}

        @include("vendor.flags.flags")
        @include("mobile.css.css-above.above-bootstrap")
        @include('mobile.css.css-above.above-generale-mobile')
        @include('mobile.css.css-above.above-covid')
        @include('mobile.css.css-above.above-listing-filter-mobile')
        label { font-size: 18px !important; }
        
    {!!"</style>"!!}
    
    <script src="{{ Utility::asset('/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Utility::asset('/mobile/js/generale.min.js') }}"></script> 

    @include("header")
    @include("gtm")
	 
  </head>

  <body class="filter-listing ">

    @include("gtm-noscript")
  	@include('cookielaw')
  	
  	<header class="hidden" role="banner">
		<h1>{{__("title.filter")}}</h1>
    </header>

    <div class="fixed">

        <div id="tab-bar">
            <a href="{{Utility::getUrlWithLang($locale, '/')}}" class="logomobile">
                <img src="{{ Utility::asset('/mobile/img/logo-ia.svg') }}" alt="Info Alberghi srl" title="Info Alberghi srl">
            </a>
        </div>

        <a class="menubutton open" href="#" onclick="closeMenuFilter(); return false;">
            <img src="//static.info-alberghi.com/images/menu_close.png">
        </a>

    </div>

    <article class="page" style="padding-bottom:100px;">
        <div class="container">
            <div class="row">

                <div class="hide">
                    <label>{{__("labels.localita")}}</label><br />
                    <div class="col-xs-12">
                        <div class="list-macrolocalita">
                            @foreach($macrolocalita as $macro)
                                <div data-id="{{$macro->id}}" data-group="filter-button-0" class="filter-button filter-button-0 @if($macro->id == 11) filter-default @endif @if(in_array($macro->id , $macrolocalita_ids)) selected @else hide @endif">
                                    {{$macro->nome}} 
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div style="text-align:right; margin:5px 5px 0 0; ">
                        <button id="more_macro" class="button small cyan" style="margin:0 auto;">{{__("labels.more_macro")}}</button>
                    </div>
                    <div class="clearfix"></div><br />
                </div>

                <label>{{__("labels.filter_rating")}}</label><br />
                <div class="list-rating">
                    <div data-id="9" data-group="filter-button-7" class="filter-button filter-button-7 @if(in_array("9", $rating_keys)) selected @endif">
                        {{-- {{__("listing.top")}} --}}{{__("listing.da")}} 9 {{__("labels.a")}} 10
                    </div>
                    <div data-id="8" data-group="filter-button-7" class="filter-button filter-button-7 @if(in_array("8", $rating_keys)) selected @endif">
                        {{-- {{__("listing.eccezionale")}} --}}{{__("listing.da")}} 8 {{__("labels.a")}} 9
                    </div>
                    <div data-id="7" data-group="filter-button-7" class="filter-button filter-button-7 @if(in_array("7", $rating_keys)) selected @endif">
                        {{-- {{__("listing.ottimo")}} --}}{{__("listing.da")}} 7 {{__("listing.a")}} 8
                    </div>
                    <div data-id="6" data-group="filter-button-7" class="filter-button filter-button-7 @if(in_array("6", $rating_keys)) selected @endif">
                        {{-- {{__("listing.buono")}} --}}{{__("listing.da")}} 6 {{__("listing.a")}} 7
                    </div>
                    <div data-id="*" data-group="filter-button-7" class="filter-button filter-button-7 filter-default @if(in_array("*", $rating_keys)) selected @endif">
                        {{__("labels.filter_all_rating")}}
                    </div>
                </div>

                <br /><label>{{__("labels.popolari")}}</label><br />

                <div class="col-xs-12">
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

                <div class="clearfix"></div>
                <br /><label>{{__("labels.category")}}</label><br />

                <div class="col-xs-12">
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
                </div>
                
                <div class="clearfix"></div>
                <br /><label>{{__("labels.trattamenti")}}</label><br />
                
                <div class="col-xs-12">
                    <div class="list-trattamenti">

                        @foreach($trattamenti as $key => $trat)
                            <div data-id="{{$key}}" data-group="filter-button-1" class="filter-button filter-button-1 @if($key == "*") filter-default @endif @if(in_array($key, $trattamenti_keys)) selected @endif">
                                {!!$trat!!} 
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="clearfix"></div>
                <br /><label>{{__("labels.servizi")}}</label><br />
                
                <div class="col-xs-12">
                    <div class="list-servizi">

                        @foreach($gruppo_servizi as $key => $serv)
                            <div data-id="{{$key}}" data-group="filter-button-5" class="filter-button filter-button-5 @if($key == "*") filter-default @endif @if(in_array($key, $gruppo_servizi_keys)) selected @endif">
                                {!!$serv!!}
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="clear"></div>

            </div>
        </div>

        <footer class="button-filter" style="padding:0 15px; ">
            <button id="button-filter" class="button big green" style="width:100%; ">{{__("labels.filtra")}} (<span class="count_hotel">{{$count}}</span>)</button>
            <div class="clearfix"></div>
        </footer>

    </article>

    @include("widget.loading")

    <link rel="stylesheet" href="{{ Utility::asset('/mobile/css/menu.min.css') }}" />
    <link rel="stylesheet" href="{{ Utility::asset('/mobile/css/app.min.css') }}" />

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

            if (bonus_vacanza_keys == "") bonus_vacanza_keys = 0;
            if (annuale_keys == "") annuale_keys = "*";
            if (cancellazione_gratuita_keys == "") cancellazione_gratuita_keys = "*";
            
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

            $("#more_macro").click(function (e) {
                e.preventDefault();
                $(".filter-button-0").removeClass("hide");
                $(this).hide();
            });

            $(".filter-button").click(function (e) {

                addLoading();

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
                    removeLoading();
                    
                });

            })

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
                
                var uri = "/filter-listing/" + macrolocalita_ids + "/" + trattamenti_keys + "/" + categorie_keys + "/" + bonus_vacanza_keys + "/" + annuale_keys + "/" + gruppo_servizi_keys + "/" + cancellazione_gratuita_keys  + "/" + rating_keys;
                document.location.href = uri;

            })
        });

    </script>
    </body>
</html>
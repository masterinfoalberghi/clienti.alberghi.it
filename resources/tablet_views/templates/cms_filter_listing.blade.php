<!DOCTYPE html>
<html lang="{{$locale}}">

      <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>@yield('seo_title')</title>
        <meta name="description" content="@yield('seo_description')">
        <link rel="canonical" href="{{url('/filter-listing')}}">

        @include('header')

        {!! "<style>" !!}

			@include('desktop.css.listing')
			@include('desktop.css.content')
	        @include('tablet.css.above')
	        @include('tablet.css.header')
	        @include('tablet.css.listing')

        {!! "</style>" !!}

        @include("gtm")

    </head>

    <body class="tablet class-cms-pagina-filter ">

        @include("gtm-noscript")
        @include('cookielaw')
        @include('menu.header_menu')

        <div id="page">
        <main id="main-content">

            <nav id="briciole">
                <div class="container">
                    <div class="row">
                        <ul class="breadcrumb">
        
                            <li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
                                <a href="https://www.info-alberghi.xxx" class="home">
                                    <i class="icon-home" itemprop="name"><span style="display:none;">Home</span></i>
                                </a>
                                <meta itemprop="position" content="1">
                            </li>
                            <li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
                                <a href="/italia/hotel_riviera_romagnola.html"><span itemprop="name">Hotel Riviera Romagnola</span></a>
                                <meta itemprop="position" content="2">
                            </li>
                            <li>
                                <a href="#"><span itemprop="name">{{$titolo}}</span></a>
                            </li>
                                                                
                        </ul>
                    </div>
                </div>
                
            </nav>
            @include('widget.testa',["filter" => 1])

            <div class="main-content-list-item container" >

                <div class="row">

                    @include('widget.filtri')

                    <div id="content-listing">

                    @include("widget.sidebar")

                            <div class="content-list-item col-md-9 col-sm-12" >

                                @yield('content')

                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>

                </div>

            </div>
x
        </main>

        @include('menu.sx_newsletter')
        @include('composer.footer')

        </div>

        <script src="{{Utility::asset('/vendor/jquery/jquery.min.js')}}"></script>

        <script type="text/javascript">

            var console=console?console:{log:function(){}}
            var $csrf_token = 	'<?php echo csrf_token(); ?>';

            //  Dizionario javascript
            var dizionario		= {};
            dizionario.cambia_localita = '{{trans("labels.cambia_localita")}}';
            dizionario.writeall = '{{trans("listing.scrivi_tutti_short")}}';
            dizionario.writeselected = '{{trans("listing.scrivi_email")}}';
            @include("lang.desktop.cookielaw")

        </script>

        <link rel="stylesheet" type="text/css" media="screen"  href="{{Utility::asset('/vendor/venobox/venobox.min.css')}}" />
        <script src="{{Utility::asset('/vendor/sticky/jquery.sticky.min.js')}}"></script>
        <script src="{{Utility::asset('/vendor/lazyloading/jquery.lazy.min.js')}}"></script>
        <script src="{{Utility::asset('/desktop/js/listing.min.js')}}"></script>
        <script src="{{Utility::asset('/vendor/venobox/venobox.min.js')}}"></script>

        <script type="text/javascript">

            $(function() {

                // Attivo la fancybox
                $('.venobox').venobox();

                // Attivo tutti i comportamenti degli slot
                attivaClickPreferiti();
                attivaCheckbox();
                attivaFiltri();

                // Carico le immagini on demand
                $('.lazy').lazy();
                $(".sticker-sidebar").stick_in_parent({offset_top: 124 });

                @if (Utility::isValidIP() )

                    $(".tag.click").each(function (i) {
                        $(this).html((i+1) + " - " + $(this).html());
                    })

                @endif

                @include("lang.desktop.email")

            });

        </script>

        @include('footer')

    </body>
</html>
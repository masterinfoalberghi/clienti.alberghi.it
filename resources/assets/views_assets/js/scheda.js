var console = console ? console : {
    log: function () {}
}

function showAllThumbs(obj) {
    $(obj).hide();
    $(".thumbs").show();
}

/**
 * Init
 */

$(function () {

    Tipped.create(".tooltip-rating", dizionario.rating, {maxWidth: 350, showOn: 'click' });

    $(".google-place").change(function (e) {
        var center_map = new google.maps.LatLng(__lat, __lon);
        var infowindow = new google.maps.InfoWindow();

        clearMarkers();

        $(".google-place:checked").each(function () {
            e.preventDefault();
            var item = $(this).data("item");

            $.each(item.split("|"), function (index, value) {
                mapInitSearch(center_map, infowindow, value);
            });
        });
    });

    function attachSticky() {
        $(".sticker-sidebar").stick_in_parent({
            offset_top: _sticker_sidebar
        });
    }

    attachSticky();

    /**
     * Scroll Button
     */

    function scrollToSection(_scroll) {

        if ($("a#" + _scroll + "-scroll").length) {
            var top = $("a#" + _scroll + "-scroll").offset().top - 60;
            $("html, body").animate({
                scrollTop: top + "px"
            });
        }

    }

    $(".btn-scroll").click(function (e) {

        e.preventDefault();
        var _scroll = $(this).data("scroll");
        scrollToSection(_scroll);

    });

    /**
     * Controllo che non ci siano URL ancore
     */

    var url = document.URL;
    var res = url.split("&");

    if (res[1] !== undefined) {
        setTimeout(function () {
            scrollToSection(res[1]);
        }, 500)
    }


    /**
     * Preferiti
     */

    $(".preferito").click(function (e) {

        e.preventDefault();

        var $me = $(this);
        $me
            .find("i")
            .removeClass("icon-heart-empty")
            .removeClass("icon-heart")
            .addClass("icon-spin2")
            .addClass("animate-spin");

        var data = {
            id: $me.data("id"),
            _token: $csrf_token,
        }

        if ($me.hasClass("disattiva_preferito")) {

            $.ajax({

                url: "/disattiva_preferito",
                type: 'POST',
                data: data,
                success: function (msg) {

                    $me.find("i").attr("class", "icon-heart-empty");
                    $me.removeClass("disattiva_preferito");
                    $me.find("span").text($me.data("add"));

                    var n = parseInt($("#linkheader .badge").text()) - 1;
                    if (n < 0) {
                        n = 0;
                        $("#linkheader .badge").removeClass("rosso");
                    }

                    $("#linkheader .badge").text(n);
                }

            });


        } else {

            $.ajax({

                url: "/attiva_preferito",
                type: 'POST',
                data: data,
                success: function (msg) {

                    $me.find("i").attr("class", "icon-heart");
                    $me.addClass("disattiva_preferito");
                    $me.find("span").text($me.data("added"));

                    var n = parseInt($("#linkheader .badge").text()) + 1;
                    $("#linkheader .badge").text(n).addClass("rosso");
                }

            });

        }

    });

    /**
     * Tooltip
     */

    Tipped.create(".tipped");

    $(".tipped-html").each(function () {
        var selector = '#' + $(this).data('tooltip-id');
        Tipped.create(this, $(selector)[0]);
    });

    /**
     * Gallery fotografica
     */

    var options = {
        infinite: false,
        centerMode: true,
        centerPadding: '0px',
        prevArrow: '<button type="button" class="slick-prev"><i class="icon-left-open"></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="icon-right-open"></button>',
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        speed: 10
    }

    // if ( $(".thumbs").length < 11) {
    // 	options.centerMode = false;
    // 	options.infinite = false;
    // }

    $('.slider-for').slick(options)
        .on('afterChange', function (event, slick, currentSlide) {

            $(".thumbs").removeClass("current");
            $(".thumbs").eq(currentSlide).addClass("current");

        });

    $(".thumbs").click(function () {

        $(".thumbs").removeClass("current");
        $(".thumbs").eq($(this).index()).addClass("current");
        $('.slider-for').slick("slickGoTo", $(this).index(), true);

    });

    /**
     * Testo offerte
     */

    $(".box-offers-article-content-small a").click(function (e) {

        e.preventDefault();
        $(this).parent().slideUp();
        $(this).parent().parent().find(".box-offers-article-content-full").slideDown();

    });

    $(".box-offers-article-content-full a").click(function (e) {

        e.preventDefault();
        $(this).parent().slideUp();
        $(this).parent().parent().find(".box-offers-article-content-small").slideDown();

    });

    /**
     * Listini custom
     */

    $(".listiniCustom").each(function () {

        var $me = $(this).find("tr");
        var $titolo = "";
        var colonne = 1;
        var first = true;

        $me.each(function () {

            var $row = $(this);

            if (first) {
                $titolo = $row.find(">th");
            } else {
                colonne = $row.children().length;
            }

            if (!first) {

                var colonne_rest_width = 100 / colonne;

                $row.children().each(function () {

                    var $io = $(this);
                    $io.attr("width", colonne_rest_width + "%");

                    if ($io.text() == "" || $io.text() == "/")
                        $io.addClass("empty").text("")

                });
            }

            first = false;

        });

        $titolo.attr("colspan", colonne).removeAttr("width");

    });

    $('.venobox').fancybox({
        toolbar: true,
        smallBtn: false,
        iframe: {
            preload: false
        }
    });

    // 	$(".image_gallery").click(function () {

    //   // verifico se c'è il video
    //   if($('div.t-video').length) {
    //     var to_skip = 1;
    //   } else {
    //     var to_skip = 2;
    //   }
    // 		var elements = [];
    // 		var indexFoto = $(this).data("index");
    // 		var count = 1;
    // 		$(".image_gallery").each(function() {

    //     // salto i ultimi 2 (se c'è il video ne devo saltare 1 SOLA)
    //     if(count > to_skip){

    //       var item = {};
    //       if ($(this).attr("src")) {
    //         item.src = $(this).attr("src").toString();
    //       } else {
    //         item.src = $(this).data("lazy").toString();
    //       }
    //       item.opts = {};
    //       item.opts.caption = $(this).parent().find("figcaption>span").html();
    //       item.opts.thumb = item.src.replace("800x538", "113x99");
    //       elements.push(item);

    //     }
    //     count++;
    //   });

    //   // tolgo gli ultimi 2
    //   elements.splice(-2,2);


    // 		console.log('indexFoto = '+indexFoto);


    // 		/*elements.forEach(function (element) {
    // 		    console.log(element.src);
    // 		});*/

    // 		$.fancybox.open(
    // 			elements,
    // 			{
    //       'loop' : false,
    //       arrows: true,
    // 				buttons: [
    // 					"close"
    // 				],
    // 				thumbs : {
    // 					autoStart   : true,                  // Display thumbnails on opening
    // 					hideOnClose : true,                   // Hide thumbnail grid when closing animation starts
    // 					parentEl    : '.fancybox-container',  // Container is injected into this element
    // 					axis        : 'x'                     // Vertical (y) or horizontal (x) scrolling
    // 				}
    // 			}).jumpTo(indexFoto-1);
    // 	});

    $(".image_gallery").click(function () {

        var elements = [];
        var indexFoto = $(this).data("index");

        $(".image_gallery").each(function () {

            var item = {};
            if ($(this).attr("src")) {
                item.src = $(this).attr("src").toString();
            } else {
                item.src = $(this).data("lazy").toString();
            }

            item.opts = {};
            item.opts.caption = $(this).parent().find("figcaption>span").html();
            item.opts.thumb = item.src.replace("800x538", "113x99");
            elements.push(item);

        });

        $.fancybox.open(

            elements,

            {
                'loop': false,
                arrows: true,
                buttons: [
                    "close"
                ],
                thumbs: {
                    autoStart: true, // Display thumbnails on opening
                    hideOnClose: true, // Hide thumbnail grid when closing animation starts
                    parentEl: '.fancybox-container', // Container is injected into this element
                    axis: 'x' // Vertical (y) or horizontal (x) scrolling
                }
            }).jumpTo(indexFoto);

    });

    // 	$(".image_gallery-thumb").click(function () {

    //    // verifico se c'è il video
    //   if($('div.t-video').length) {
    //     var to_skip = 1;
    //     var sub_jump = 0;
    //   } else {
    //     var to_skip = 2;
    //     var sub_jump = 1;
    //   }
    // 		var elements = [];
    // 		var indexFotoThumb = $(this).data("index-thumb");
    // 		var count = 1;
    // 		$(".image_gallery").each(function() {
    // 			if(count > to_skip){

    //       var item = {};
    //       if ($(this).attr("src")) {
    //         item.src = $(this).attr("src").toString();
    //       } else {
    //         item.src = $(this).data("lazy").toString();
    //       }
    //       item.opts = {};
    //       item.opts.caption = $(this).parent().find("figcaption>span").html();
    //       item.opts.thumb = item.src.replace("800x538", "113x99");
    //       elements.push(item);

    //     }
    //     count++;

    //   });

    //   // tolgo gli ultimi 2
    //   elements.splice(-2,2);

    // 		$.fancybox.open(
    // 			elements,
    // 			{
    // 				 'loop' : false,
    //       arrows: true,
    // 				buttons: [
    // 					"close"
    // 				],
    // 				thumbs : {
    // 					autoStart   : true,                  // Display thumbnails on opening
    // 					hideOnClose : true,                   // Hide thumbnail grid when closing animation starts
    // 					parentEl    : '.fancybox-container',  // Container is injected into this element
    // 					axis        : 'x'                     // Vertical (y) or horizontal (x) scrolling
    // 				}
    // 			}).jumpTo(indexFotoThumb-sub_jump);
    // 	});

    $(".image_gallery-thumb").click(function () {

        var elements = [];
        var indexFotoThumb = $(this).data("index-thumb");

        $(".image_gallery").each(function () {

            var item = {};
            if ($(this).attr("src")) {
                item.src = $(this).attr("src").toString();
            } else {
                item.src = $(this).data("lazy").toString();
            }
            item.opts = {};
            item.opts.caption = $(this).parent().find("figcaption>span").html();
            item.opts.thumb = item.src.replace("800x538", "113x99");
            elements.push(item);

        });

        $.fancybox.open(

            elements, {
                'loop': false,
                'arrows': true,
                buttons: [
                    "close"
                ],
                thumbs: {
                    autoStart: true, // Display thumbnails on opening
                    hideOnClose: true, // Hide thumbnail grid when closing animation starts
                    parentEl: '.fancybox-container', // Container is injected into this element
                    axis: 'x' // Vertical (y) or horizontal (x) scrolling
                }
            }).jumpTo(indexFotoThumb);
    });

    $(".more-photo").trigger('click');

});
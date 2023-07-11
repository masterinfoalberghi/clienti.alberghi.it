var open_menu = false;

function addLoading() {
	$(".scuro").show();
}

function removeLoading() {
	$(".scuro").hide();
}

function menuISOpen () {
	return open_menu
}

function closeMenu() {
	
	$(".backbutton").unbind("click");
	$(".menubutton img").attr("src", "//static.info-alberghi.com//images/menu.png");

	if (open_menu) {
		open_menu = false;
		$("body").removeClass("menuopen").removeClass("menuopen2");
	}
	
}

function openMenu() {
	
	if (window.hideOrder) window.hideOrder();
	if (window.hideMap) window.hideMap();
	
	if (!open_menu) {
		open_menu = true;
		$("body").removeClass("menuopen2").addClass("menuopen");
	} 
	
	$(".menubutton img").attr("src", "//static.info-alberghi.com/images/menu_close.png");
	
	$(".backbutton").bind("click", function (e) {
		e.preventDefault();
		closeMenu();
	})

}

function closeMenuFilter() {

    document.cookie = "lastpagefilter= ; expires = Thu, 01 Jan 1970 00:00:00 GMT"
    window.history.back();

}

function getLastPageFilter() {

    var lastpagefilter = document.cookie;

    if (lastpagefilter != "") {
        lastpagefilter = document.cookie.split('; ').find(row => row.startsWith('lastpagefilter'));
    } 

    if (lastpagefilter == undefined || lastpagefilter.indexOf("/filter/") === true) {

        lastpagefilter = "/italia/hotel_riviera_romagnola.html";

    } else {

        lastpagefilter = lastpagefilter.substring(lastpagefilter.indexOf('=') + 1);

    }

    return lastpagefilter;

}

function openMenuFilter() {

    var url = $(".select-filter.filter").data("uri");  

    /** Cerco un vecchio cookie */
    var lastpagefilter = getLastPageFilter();
    
    if (lastpagefilter != "") {
        document.cookie = "lastpagefilter= ; expires = Thu, 01 Jan 1970 00:00:00 GMT"
    }
    
    /** Setto il nuovo cookie */
    document.cookie = "lastpagefilter=" + document.location.href
    document.location.href=url;

}

function removeFilter() {

    var lastpagefilter = getLastPageFilter();
    document.cookie = "lastpagefilter= ; expires = Thu, 01 Jan 1970 00:00:00 GMT"
    document.location.href= lastpagefilter;

}

function share(url, codice) {
	
	$.ajax({ 
		url: '/shareMe.php',
		type: 'GET',
		data: {uri: url, codice: codice}
	});
	
	ga('send', 'event', {eventCategory: 'Condivisione',eventAction: 'Whatsapp',eventLabel: url, eventValue: codice});
	
}

function doOnOrientationChange()
{
    switch(window.orientation) 
    {  
        case -90:
        case 90:
            $("body").addClass("landscape");
            break; 
        default:
            $("body").removeClass("landscape");
            break; 
    }
}

window.addEventListener('orientationchange', doOnOrientationChange);
doOnOrientationChange();

$(function () {

    $(".alert a").click(function (e) {
        
        e.preventDefault();
        $(".alert").fadeOut();
        
    })

    $(".footer_link h4").click(function (e) {
        
        e.preventDefault();
        $(this).parent().find("ul").slideToggle(250);
        
    });

    var _scroll		= -1;
    var verso 		= ""; 
    var d = 0;

    /**
     * Gestione barra top su mobile
     */

    $(window).scroll(function(){
        
        var $me = $(this);
        var _scrollTop = $me.scrollTop();
        
        /**
         * Preparo l'header
         */
        
        if (_scrollTop >= 52) {
            $("body").addClass("header-pre-fix");
        } else {
            clearTimeout(d);
            $("body").removeClass("header-pre-fix");
        }
        
        if (_scrollTop >= 92) {
            $("body").addClass("header-fix");
        } else {
            clearTimeout(d);
            $("body").removeClass("header-fix");
        }
        
        /**
         * Fa apparire o sparire il menu durante lo scroll
         */
        
        if (_scrollTop > _scroll) {

            clearTimeout(d);	
            verso = "G";
            if ($("body").hasClass("open"))
                $("body").removeClass("open");
            
        } else {
            
            //clearTimeout(d);	
            if (verso != "S") {
                clearTimeout(d);
                d=setTimeout(function () {
                    $("body").addClass("open");
                    verso = "S";
                }, 50);	
            }
        
        }
        
        if ($(".gallery-hotel").length) {

            var h = $(".gallery-hotel").height() + $(".gallery-hotel").offset().top + 41;
            if (_scrollTop > h) {
                $("body").addClass("fixed-pulsantiera");
            } else if (_scrollTop <= (h-50)) {
                $("body").removeClass("fixed-pulsantiera");
            }

        }
        
        _scroll = _scrollTop

    });

});
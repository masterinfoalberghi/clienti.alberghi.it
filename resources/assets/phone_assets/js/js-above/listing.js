var order_open = false;
var map_open = false;
var google_api = false;

function _setCookieLaw() 
{
    if (typeof setCookieLaw == 'function') {
        setCookieLaw();
        setCookieLaw = undefined;
    }
}

function _closeOrderPanel()
{
    order_open = false;
    $(".order_select").hide();
}

function _openMapPanel()
{
    map_open = true;
    if (!google_api) {

        google_api = true;
        addLoading();

        $script(['//static.info-alberghi.com/mobile/js/markerclusterer.min.js', '//maps.googleapis.com/maps/api/js?key=AIzaSyCAyCUJ63a6dtvWfdAaqCmLxrWqOombjM8&language=it'], function () {
            window.initialize();
            removeLoading();
        });

    } else {

        map_open = true;
        window.showMap();

    }
}

function _closeMapPanel()
{
    map_open = false;
    window.hideMap();
}

window.hideOrder = function () { _closeOrderPanel(); }
window.hideMap = function ()   { _closeMapPanel(); }

function getMap() 
{

    _setCookieLaw();
    _closeOrderPanel();

    /** Se la mappa non è aperta */
    if (!map_open) {
        _openMapPanel();
    } else {
        _closeMapPanel();
    }

}

function assegnaComportamenti() 
{

    /** Assegno i selected */
    $(".link_item_slot").each(function (i) {
        var me = $(this);
        if (ids_send_mail_arr.indexOf(me.data("id")) > -1) {
            me.addClass("selected")
        }
    });

    /** Assegno i click */
    $(".link_item_slot").click(function (e) {

        var hit = $(e.target);
        var me = $(this);

        /** Non sono un link */
        if (hit.closest('a').length == 0 && hit.prop("tagName") != "A") {
            if (hit.closest(".link_item_slot_count").length == 0) {
                /** Altrimenti rimando alla scheda */
                document.location.href = me.data("url");
            }
        } else {
            /** Sono un link */
            if (hit.closest(".link_item_slot_count").length > 0) {

                e.preventDefault();
                var $hit = hit.prop('href');
                if (hit.prop("tagName") != "A") {
                    $hit = hit.closest('a').prop("href");
                }
                document.location.href = $hit;
            }
        }

    });

}

$(function () {

    $(".list_periodi").click(function(e){
        e.preventDefault();
        var id = $(this).data('id');
        $("span#"+id).slideToggle("slow");
        return false;
    })

    /**
     * Mappa 
     */

    if (!$map_disabled) 
    {

        $(".filter.mappa").click(function (e) {

            e.preventDefault();
            _closeOrderPanel();
            _setCookieLaw();          

            if (!map_open) {
                _openMapPanel()
            } else {
                _closeMapPanel()
            }

        });

        $(".fancybox-close").click(function (e) {
            e.preventDefault();
            getMap();
        });

        $(".mobile-close").click(function (e) {
            e.preventDefault();
            getMap();
        });

    }

    /**
     * Filtro ordinamento
     */

    $(".filter.ordine").click(function (e) 
    {

        e.preventDefault();
        _closeMapPanel();
        _setCookieLaw();

        if (!order_open) {

            order_open = true;
            $("body").addClass("openamp");
            $(".order_select").show();

        } else {

            order_open = false;
            $("body").removeClass("openamp");
            $(".order_select").hide();

        }

    });

    /**
     * Scrivi a tutti 
     */

    $(".filter.email").click(function (e) {

        _closeOrderPanel();
        _closeMapPanel();
        _setCookieLaw();
        
        e.preventDefault();
        $("#emailmultiplamobileforms").submit();
            
    });

    /**
     * Filtri 
     */

    $(".filter.select-filter").click(function (e) {

        e.preventDefault();
        _closeOrderPanel();
        _closeMapPanel();
        _setCookieLaw();
        openMenuFilter();

    })

    assegnaComportamenti();

});
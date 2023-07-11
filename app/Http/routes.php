<?php

use App\CmsPagina;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;



/*
 |--------------------------------------------------------------------------
 | Application Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register all of the routes for an application.
 | It's a breeze. Simply tell Laravel the URIs it should respond to
 | and give it the controller to call when that URI is +ed.
 |
 */



global $deviceType;
$router->pattern('locale', 'ing|ted|fr');
$router->pattern('stradario', 'stradario|addresses');
$router->pattern('id', '[0-9]+');
$router->pattern('hotel_id', '[0-9]+');
$router->pattern('localita_id', '[0-9]+');
$router->pattern('slot_id', '[0-9]+');
$router->pattern('vetrina_id', '[0-9]+');
$router->pattern('vot_id', '[0-9]+');
$router->pattern('vtt_id', '[0-9]+');
$router->pattern('vst_id', '[0-9]+');
$router->pattern('localita', '[a-z\_\-]+');
$detect = new \Detection\MobileDetect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'desktop');


// ------------------------------------------------------------------------ //
// INDICE
//
// 1. 301
// 2. 404
// 3. FUNCTION DI SERVIZIO
// 4. CRON
// 5. CAPARRA E BOUNS
// 6. STATS
// 7. CRM
// 8. TRADUZIONE TAG
// 9. PARCHEGGIO
// 10. HOMEPAGE
// 11. HOTEL
// 12. CONTACLICK
// 13. CERCA HOTEL
// 14 .RICERCA AVANZATA 
// 15. COMPARA HOTEL
// 16. LINK PARTICOLARI
// 17. EMAIL
// 18. NEWSLETTER
// 19. ALTRO
// 20. ADMIN
//    20.1 ADMIN / COMMERCIALI
//    20.2 ADMIN / AMMINISTRATORI
//    20.3 ADMIN / AMMINISTRATORI, OPERATORI
//    20.4 ADMIN / AMMINISTRATORI, OPERATORI, HOTEL
//    20.5 ADMIN / AMMINISTRATORI, OPERATORI, COMMECIALI
//    20.6 ADMIN / TUTTI 
// 21. STRADARIO
// 22. MAPPE
// 23. FILTRI
// 24. TUTTO IL RESTO
// ------------------------------------------------------------------------ //


// ------------------------------------------------------------------------ //
// 1. 404
// ------------------------------------------------------------------------ //

// Route::get('mail_listing.php', ['middleware' => 'checkDispositivo:desktop', function () {
//     abort('404');
// }]);

// Route::get('{locale}/mail_listing.php', ['middleware' => ['lang', 'checkDispositivo:desktop'], function () {
//     abort('404');
// }]);

// ------------------------------------------------------------------------ //
// 2 .301
// ------------------------------------------------------------------------ //

Route::get('/', function () {
    return Redirect::to('/admin', 301);
});

Route::singularResourceParameters(false);

Route::get('admin/caparra', function () {
    return Redirect::to('admin/politiche-cancellazione', 301);
});

Route::get('admin/caparra/create', function () {
    return Redirect::to('admin/politiche-cancellazione/create', 301);
});

Route::get('stream/live.php', function () {
    abort("404");
});

Route::get('phpinfo.php', function () {
    abort("404");
});



// Route::get('/caparra', function () {
//     return Redirect::to('/politiche-cancellazione', 301);
// });

// Route::get('prenotazione-hotel/gabicce-mare.php', function () {
//     return Redirect::to('/prenotazione-hotel/gabicce.php', 301);
// });

// Route::get('ricerca_avanzata_completa.php', function () {
//     return Redirect::to('/ricerca_avanzata.php', 301);
// });

/* Altri 301 */

/**
 * @2021-02-15 09:47:10 
 * @Lucio, @Elena, @Sacco
 * Su gabicce non esistono 3 stelle superiori 
 * In linea di massima su tutte le marche i 3 stelle sup non ci sono, controllare in caso di apertura di nuove zone
 * 
 */

/*Route::get('hotel-3-tre-stelle-superiore/gabicce-mare.php', function () {
    return Redirect::to('hotel-3-tre-stelle/gabicce-mare.php', 301);
});

Route::get('halloween-parchi-divertimento-discoteche.php', function () {
    return Redirect::to('/offerte-halloween/riviera-romagnola.php', 301);
});

Route::get('aeroporti', function () {
    return Redirect::to('/aeroporti.php', 301);
});

Route::get('provapdf/guida_unica/riviera_romagnola.pdf', function () {
    return Redirect::to('/guida-riviera-romagnola.php', 301);
});

Route::get('capodanno/rimini-eventi.php', function () {
    return Redirect::to('/capodanno/rimini.php', 301);
});

Route::get('capodanno/riviera-romagnola-disco.php', function () {
    return Redirect::to('/capodanno/riviera-romagnola.php', 301);
});

Route::get('capodanno/riviera-romagnola-piazza.php', function () {
    return Redirect::to('/capodanno/riviera-romagnola.php', 301);
});

Route::get('ing/capodanno/riviera-romagnola.php', function () {
    return Redirect::to('ing/new-year-eve/riviera-romagnola.php', 301);
});

Route::get('ted/capodanno/riviera-romagnola.php', function () {
    return Redirect::to('ted/neujahrs/riviera-romagnola.php', 301);
});

Route::get('fr/capodanno/riviera-romagnola.php', function () {
    return Redirect::to('fr/nouvel-an/riviera-romagnola.php', 301);
});

Route::get('acquafan-riccione/acquafan.php', function () {
    return Redirect::to('/aquafan.php', 301);
});

Route::get('acquario/parco-le-navi-cattolica.php', function () {
    return Redirect::to('/acquario-cattolica.php', 301);
});

Route::get('italia-in-miniatura-rimini/italia-in-miniatura.php', function () {
    return Redirect::to('/italia-in-miniatura.php', 301);
});

Route::get('parco-delfinario-oltremare-riccione/parco-delfinario-oltremare.php', function () {
    return Redirect::to('/oltremare-riccione.php', 301);
});

Route::get('delfinario/rimini.php', function () {
    return Redirect::to('/delfinario-rimini.php', 301);
});

Route::get('fiabilandia/rimini.php', function () {
    return Redirect::to('/fiabilandia-rimini.php', 301);
});

Route::get('hotel-mirabilandia/riviera-romagnola.php', function () {
    return Redirect::to('/mirabilandia-ravenna.php', 301);
});

Route::get('green-booking.php', function () {
    return Redirect::to('/greenbooking/', 301);
});

Route::get('ebook/guida-primavera-2014.pdf', function () {
    return Redirect::to('/pasqua-riviera-romagnola/pasqua.php', 301);
});

Route::get('guida-riviera-romagnola.php', function () {
    return Redirect::to('/', 301);
});

Route::get('ebook/Natale-Capodanno.pdf', function () {
    return Redirect::to('italia/hotel_riviera_romagnola/offerte_speciali.html', 301);
});

Route::get('fr/pensione-completa/riviera-romagnola.php', function () {
    return Redirect::to('/fr/pension-complete/riviera-romagnola.php', 301);
});

Route::get('ing/pensione-completa/riviera-romagnola.php', function () {
    return Redirect::to('/ing/full-board-hotels/riviera-romagnola.php', 301);
});

Route::get('ted/pensione-completa/riviera-romagnola.php', function () {
    return Redirect::to('/ted/vollpension/riviera-romagnola.php', 301);
});

Route::get('ing/solo-dormire/riviera-romagnola.php', function () {
    return Redirect::to('/ing/only-room/riviera-romagnola.php', 301);
});

Route::get('fr/solo-dormire/riviera-romagnola.php', function () {
    return Redirect::to('/fr/chambre-seule/riviera-romagnola.php', 301);
});

Route::get('ted/solo-dormire/riviera-romagnola.php', function () {
    return Redirect::to('/ted/nur-zimmer/riviera-romagnola.php', 301);
});

Route::get('ing/mezza-pensione/riviera-romagnola.php', function () {
    return Redirect::to('/ing/half-board-hotels/riviera-romagnola.php', 301);
});

Route::get('fr/mezza-pensione/riviera-romagnola.php', function () {
    return Redirect::to('/fr/demi-pension/riviera-romagnola.php', 301);
});

Route::get('ted/mezza-pensione/riviera-romagnola.php', function () {
    return Redirect::to('/ted/halbpension/riviera-romagnola.php', 301);
});

Route::get('offerte-25-aprile/gabicce.php', function () {
    return Redirect::to('/offerte-25-aprile/gabicce-mare.php', 301);
});

Route::get('guida-natale-romagna/', function () {
    return Redirect::to('/capodanno/riviera-romagnola.php', 301);
});

Route::get('guida-pasqua-romagna/', function () {
    return Redirect::to('/pasqua-riviera-romagnola/pasqua.php', 301);
});

Route::get('rassegna/{file}', function ($file) {
    return Redirect::to('/doc/rassegna/' . $file, 301);
});

Route::get('coupon-buoni-sconto/cervia.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/cervia/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/gabicce-mare.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/gabicce-mare/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/milano-marittima.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/milano-marittima/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/misano-adriatico.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/misano-adriatico/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/cattolica.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/cattolica/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/cesenatico.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/cesenatico/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/bellaria.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/bellaria/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/igea-marina.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/igea-marina/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/riccione.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/riccione/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/rimini.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/rimini/offerte_speciali.php', 301);
});

Route::get('coupon-buoni-sconto/lidi-ravennati.php', function () {
    return Redirect::to('/hotel_riviera_romagnola/lidi-ravennati/offerte_speciali.php', 301);
});

Route::get('offerte-coupon-riviera-romagnola.php', function () {
    return Redirect::to('/italia/hotel_riviera_romagnola/offerte_speciali.html', 301);
});

Route::get('hotel-vista-mare/{leaf}', function ($leaf) {
    return Redirect::to("/hotel-vicino-spiaggia/$leaf", 301);
});

Route::get('/cesenatico/san-mauro-mare/via-marina', function () {
    return Redirect::to('/san-mauro-mare/via-marina', 301);
});

Route::get(' /cervia/pinarella-di-cervia/viale-titano', function () {
    return Redirect::to('/pinarella-di-cervia/viale-titano', 301);
});

Route::get('hotel-disabili/pesaro.php', function () {
    return Redirect::to('marche/pesaro/disabili', 301);
});

Route::get('ing/hotel-disabili/pesaro.php', function () {
    return Redirect::to('ing/marche/pesaro/disabili', 301);
});

Route::get('ted/hotel-disabili/pesaro.php', function () {
    return Redirect::to('ted/marche/pesaro/disabili', 301);
});

Route::get('fr/hotel-disabili/pesaro.php', function () {
    return Redirect::to('fr/marche/pesaro/disabili', 301);
});
*/



// ------------------------------------------------------------------------ //
// 3. FUNCTION DI SERVIZIO
// ------------------------------------------------------------------------ //
/*
if (!function_exists('checkPageUrl')){
function checkPageUrl ($uri)
    {   
        return \App\CmsPagina::where('uri', $uri)->attiva()->first();
    }
}
if (!function_exists('checkHotelsParams')){
function checkHotelsParams($demo = 0)
{

    global $deviceType;

    $params = Request::all();

    if (!$demo) {
        $id = $params["id"];
    } else {

        $demo = App\Hotel::where('attivo', -1)->get()->first();

        if (is_null($demo)) {
            abort(404);
        } else {
            $id = $demo->id;
        }

        $params["id"] = $id;

    }

    $ref = "";

    if (isset($params["ref"])) {
        $ref = $params["ref"];
    }

    $query = array_keys($params);
    $query = array_pop($query);
    $query_orig = $query;

    $mobile = "_mobile";

    if ($deviceType != "phone") {
        $query = "id";
        $mobile = "";
    }

    $arraylistAction = array(
        "id" => "index",
        "map" => "map",
        "gallery" => "gallery",
        "lastminute" => "lastminute",
        "offers" => "offerte",
        "children-offers" => "bambinigratis",
        "price-list" => "listino",
        "coupon" => "coupon",
        "contact" => "mailSchedaMobile",
        "contact_2_bottoni" => "mailSchedaMobile_2_bottoni",
        "contact_modal_first" => "mailSchedaMobile_modal_first",
        "prenotaprima" => "prenotaprima",
        "whatsappshare" => "index",
    );

    $arraylistController = array(
        "id" => "\HotelController",
        "map" => "\HotelController",
        "gallery" => "\HotelController",
        "lastminute" => "\HotelController",
        "offers" => "\HotelController",
        "prenotaprima" => "\HotelController",
        "children-offers" => "\HotelController",
        "price-list" => "\HotelController",
        "coupon" => "\HotelController",
        "contact" => "\MailSchedaController",
        "contact_2_bottoni" => "\MailSchedaController",
        "contact_modal_first" => "\MailSchedaController",
        "whatsappshare" => "\HotelController",
    );

    if ($ref == "directmail") {

        $_action = "index" . $mobile;

        return [

            $id, // id hotel
            $_action, // metodo da chiamare
            '\HotelController',
            $query_orig,

        ];

    }

    // Se sono un parametro non concordato restituisco 404
    if ($query != "id" && !array_key_exists($query, $arraylistAction)) {

        return false;

    } else {

        if ($arraylistAction[$query] == "mailSchedaMobile" && $deviceType == "phone") {
            $mobile = "";
        }

        return [

            $id, // id hotel
            $arraylistAction[$query] . $mobile, // metodo da chiamare
            $arraylistController[$query],
            $query_orig,

        ];

    }

}
}
if (!function_exists('checkEmail')){
function checkEmail($type)
{

    global $deviceType;

    $return = "";

    if ($deviceType == "phone" && $type == "m") {
        $return = "mailMultiplaMobile";
    }

    if ($deviceType != "phone" && $type == "m") {
        $return = "mailMultipla";
    }

    return $return;

}
}
if (!function_exists('checkEmailRichiesta')){
function checkEmailRichiesta($type)
{

    global $deviceType;

    $return = "";

    if ($deviceType == "phone" && $type == "m") {
        $return = "richiestaMailMultiplaMobile";
    }

    if ($deviceType != "phone" && $type == "m") {
        $return = "richiestaMailMultipla";
    }

    if ($deviceType == "phone" && $type == "s") {
        $return = "richiestaMailSchedaMobile";
    }

    if ($deviceType != "phone" && $type == "s") {
        $return = "richiestaPreventivo";
    }

    return $return;

}
}

*/

// ------------------------------------------------------------------------ //
// 4. CRON
// ------------------------------------------------------------------------ //

/*
Route::get('/deleteReset', 'TaskController@deleteReset');
Route::get('/deleteAcceptance', 'TaskController@deleteAcceptance');
Route::get('/punti', ['uses' => 'HotelController@cronCreatePuntiDiForzaTemp'])->middleware(['limitIP']); /** Crea i punti di forza temp * /
Route::get('/zero', ['uses' => 'HotelController@cron']); /* Azzera i click a gli hotel * /;
/** NON USATA Route::get('/bu_doc', ['uses' => 'TaskController@BackUpDocFoldler'])->middleware(['limitIP']); * /
Route::get('/scadenzeVot', ['uses' => 'TaskController@cronScadenzeVot'])->middleware(['limitIP']);
Route::get('/scadenzeVtt', ['uses' => 'TaskController@cronScadenzeVtt'])->middleware(['limitIP']);
Route::get('/cron', ['uses' => 'MailUpsellingController@cron'])->middleware(['limitIP']);
/*Route::get('/validate_email/{mail}', array('uses' => 'TestController@validate_email'))->middleware(['limitIP']);* /
*/ 
// ------------------------------------------------------------------------ //
// 5. CAPARRA E BOUNS
//    DEPRECATI
// ------------------------------------------------------------------------ //

// Route::get('/politiche-cancellazione', 'CaparraController@index');
// Route::get('/politiche-cancellazione/{id}', 'CaparraController@periods');
// Route::post('/politiche-cancellazione/{id}', 'CaparraController@store');
// Route::get('/politiche-cancellazione/{id}/confirm', 'CaparraController@confirm');
// Route::post('/politiche-cancellazione/{id}/confirm', 'CaparraController@confirmStore');
// Route::post('/politiche-cancellazione/{id}/helpRequest', 'CaparraController@helpRequest')->name('help_request');
// Route::get('/politiche-cancellazione/helpRequestThanks', 'CaparraController@helpRequestThanks')->name('help_request_thanks');

// Route::get('/politiche-bonus-vacanza-2020', 'BonusController@index');
// Route::get('/politiche-bonus-vacanza-2020/{id}', 'BonusController@bonus');
// Route::post('/politiche-bonus-vacanza-2020/{id}', 'BonusController@store');
// Route::post('/politiche-bonus-vacanza-2020/{id}/confirm', 'BonusController@confirmStore');
// Route::get('/politiche-bonus-vacanza-2020/{id}/confirm', 'BonusController@confirm');
// Route::post('/politiche-bonus-vacanza-2020/{id}/helpRequest', 'BonusController@helpRequest')->name('help_request');
// Route::get('/politiche-bonus-vacanza-2020/helpRequestThanks', 'BonusController@helpRequestThanks')->name('help_request_thanks');

// ------------------------------------------------------------------------ //
// 6. STATS 
//    DEPRECATE
// ------------------------------------------------------------------------ //

/* DEPRECATE Route::get('/ms/{hotel_id}/{da?}/{a?}', ['uses' => 'TaskController@getMailSchedaDaA'])->middleware(['limitIP']); // http://www.info-alberghi.com/ms/17/01042017/15042017 */
/* DEPRECATE Route::get('/mm/{hotel_id}/{da?}/{a?}', ['uses' => 'TaskController@getMailMultiplaDaA'])->middleware(['limitIP']); // http://www.info-alberghi.com/mm/17/01042017/15042017 */
/* DEPRECATE Route::get('/mail_utenti', ['uses' => 'TaskController@getMailUtenti'])->middleware(['limitIP']); */
/* DEPRECATE Route::get('/mail_clienti', ['uses' => 'TaskController@getMailClienti'])->middleware(['limitIP']); */
/* DEPRECATE Route::get('/mail_green', ['uses' => 'TaskController@getMailClientiGreen'])->middleware(['limitIP']); */
/* DEPRECATE Route::get('/key', ['uses' => 'TaskController@getKeywordRicerca']);->middleware(['limitIP']); */
/* DEPRECATE Route::get('/infoPiscina', ['uses' => 'TaskController@getInfoPiscinaHotels']);//->middleware(['limitIP']); */

// ------------------------------------------------------------------------ //
// 7. CRM
// ------------------------------------------------------------------------ //

/*
Route::get('/serviziHotelCrm/{hotel_id}', ['uses' => 'TaskController@getServiziHotelForCrm'])->middleware(['limitIP']);
Route::get('/serviziAggiuntiviHotelCrm/{hotel_id}', ['uses' => 'TaskController@getServiziAggiuntiviHotelForCrm'])->middleware(['limitIP']);
Route::get('/infoPiscinaHotelCrm/{hotel_id}', ['uses' => 'TaskController@getInfoPiscinaHotelForCrm'])->middleware(['limitIP']);
Route::get('/infoBenessereHotelCrm/{hotel_id}', ['uses' => 'TaskController@getInfoBenessereHotelForCrm'])->middleware(['limitIP']);
*/

// ------------------------------------------------------------------------ //
// 8. TRADUZIONE TAG
// ------------------------------------------------------------------------ //

/** NON USATA Route::get('/photo_tag_translate', [ 'uses' => 'TaskController@photoTagTranslate'])->middleware(['limitIP']); */
// Route::get('/translate', ['uses' => 'TranslateController@index'])->middleware(['limitIP']);

// ------------------------------------------------------------------------ //
// 9. PARCHEGGIO
// ------------------------------------------------------------------------ //

/* NON USATE Route::get('/parcheggio', [ 'uses' => 'TaskController@setParcheggio'])->middleware(['limitIP']); */
/* NON USATE Route::get('/parcheggioGratis', [ 'uses' => 'TaskController@setParcheggioGratis'])->middleware(['limitIP']); */

// ------------------------------------------------------------------------ //
// 10. HOMEPAGE
// ------------------------------------------------------------------------ //

/*Route::get('/', ['middleware' => 'lang', 'uses' => 'HomeController@index']);
Route::get('/{locale}', ['middleware' => 'lang', 'uses' => 'HomeController@index']);*/

// ------------------------------------------------------------------------ //
// 11. HOTEL
// ------------------------------------------------------------------------ //

/*
Route::any('/hotel.demo', function () {

    $params = checkHotelsParams($demo = 1);
    if (!$params)
        abort(404);

    $controller = \App()->make('App\Http\Controllers' . $params[2]);
    return $controller->callAction($params[1], $parameters = array('id' => $params[0], 'pag' => $params[3]));

});

Route::any('{locale}/hotel.demo', ['middleware' => ['lang:locale'], function () {

    $params = checkHotelsParams($demo = 1);
    if (!$params)
        abort(404);

    $controller = \App()->make('App\Http\Controllers' . $params[2]);
    return $controller->callAction($params[1], $parameters = array('id' => $params[0], 'pag' => $params[3]));

}]);

Route::any('/hotel.php', ['middleware' => 'checkDisattivato', function () {

    $params = checkHotelsParams();
    if (!$params) 
        abort(404);

    if ($params[0] == 470)
        return Redirect::to('/hotel.demo', 301);

        $controller = \App()->make('App\Http\Controllers' . $params[2]);
    return $controller->callAction($params[1], $parameters = array('id' => $params[0], 'pag' => $params[3]));

}]);

*/


//? Nuove ruotes per gli hotel di Pesaro => slug_url = marche/pesaro/scheda/1899-hotel-due-pavoni
//! ATTENZIONE https://info-alberghi.ssl/marche/pesaro/hotel-due-pavoni-id=1899 visualizza la scheda ID = 1899
// ! MA https://info-alberghi.ssl/marche/pesaro/hotel-due-pavoni-id=17 visualizza la scheda ID = 17 
//! BISOGNA scegliere ID oppure slug


// Route::get('marche/pesaro/scheda/{slug_url}', function($slug_url) {
//     $id = explode('-', $slug_url)[0];
//     $params = explode('&', $slug_url);
//     if(isset($params[1])) {
//         $request = Request::create('/hotel.php?id='.$id.'&'.$params[1], 'GET');
//     } else {
//         $request = Request::create('/hotel.php?id=' . $id , 'GET');
//     }

//     $response = app()->handle($request);
    


//     return $response;
// });


// Route::get('marche/pesaro/{slug_url}', function($slug_url) {
//     $id = substr($slug_url, strrpos($slug_url,'=')+1);
//     $request = Request::create('/hotel.php?id='.$id, 'GET');
//     $response = app()->handle($request);
//     return $response;
// })->where('slug_url', '(.*)(\-)id=[0-9]+');


//Route::any('marche/pesaro/{slug_url}', 'HotelController@index_slug_url')->where('slug_url', '(.*)(\-)id=[0-9]+');


// Route::get('marche/pesaro/{slug_url}', function ($slug_url) {
    
//     dd($slug_url);
// });

/*
Route::any('{locale}/hotel.php', ['middleware' => ['lang:locale', 'checkDisattivato'], function () {

    $params = checkHotelsParams();

    if (!$params)
        abort(404);

    if ($params[0] == 470) {
        $locale = App::getLocale();
        return Redirect::to(App\Utility::getLocaleUrl($locale) . 'hotel.demo', 301);
    }

    $controller = \App()->make('App\Http\Controllers' . $params[2]);
    return $controller->callAction($params[1], $parameters = array('id' => $params[0], 'pag' => $params[3]));

}]);

Route::get('away/{hotel_id}', 'HotelController@outboundLink');

Route::any('preferiti_listing', ['as' => 'preferiti_listing', 'uses' => 'CmsPagineController@preferiti_listing']);
Route::post('attiva_preferito', ['uses' => 'HotelController@attivaPreferitoAjax']);
Route::post('disattiva_preferito', ['uses' => 'HotelController@disattivaPreferitoAjax']);
*/
// ------------------------------------------------------------------------ //
// 12. CONTACLICK
// ------------------------------------------------------------------------ //

/* CONTACLICK vetrine * /
Route::get('/vetrina/{hotel_id}/{slot_id}/{vetrina_id}', array('uses' => 'VetrinaController@contaClick'));
Route::get('{locale}/vetrina/{hotel_id}/{slot_id}/{vetrina_id}', array('middleware' => 'lang', 'uses' => 'VetrinaController@contaClick'));

/* CONTACLICK Vetrine Offerte Top  * /
Route::get('/vot/{hotel_id}/{vot_id}/{offer_id?}', array('uses' => 'VetrinaOfferteTopLinguaController@contaClick'));
Route::get('{locale}/vot/{hotel_id}/{vot_id}/{offer_id?}', array('middleware' => 'lang', 'uses' => 'VetrinaOfferteTopLinguaController@contaClick'));

/* CONTACLICK Vetrine Bambini Gratis Top  * /
Route::get('/vaat/{hotel_id}/{vot_id}/{offer_id?}', array('uses' => 'VetrinaBambiniGratisTopLinguaController@contaClick'));
Route::get('{locale}/vaat/{hotel_id}/{vot_id}/{offer_id?}', array('middleware' => 'lang', 'uses' => 'VetrinaBambiniGratisTopLinguaController@contaClick'));

/* CONTACLICK Vetrine Trattamenti Top * /
Route::get('/vtt/{hotel_id}/{vtt_id}', array('uses' => 'VetrinaTrattamentoTopLinguaController@contaClick'));
Route::get('{locale}/vtt/{hotel_id}/{vtt_id}', array('middleware' => 'lang', 'uses' => 'VetrinaTrattamentoTopLinguaController@contaClick'));

/* CONTACLICK Vetrine Servizi Top * /
Route::get('/vst/{hotel_id}/{vst_id}', array('uses' => 'VetrinaServiziTopLinguaController@contaClick'));
Route::get('{locale}/vst/{hotel_id}/{vst_id}', array('middleware' => 'lang', 'uses' => 'VetrinaServiziTopLinguaController@contaClick'));

/* CONTACLICK Call me scheda hotel mobile * /
Route::get('callMe.php', [function () {

    $hotel_id = Request::get('hotel_id');
    $controller = \App()->make('App\Http\Controllers\HotelController');
    return $controller->callAction('contaClickCallMeAjax', $parameters = array('hotel_id' => $hotel_id, "call"));

}]);

/* CONTACLICK whatsappMe me scheda hotel mobile * /
Route::get('whatsappMe.php', [function () {

    $hotel_id = Request::get('hotel_id');
    $controller = \App()->make('App\Http\Controllers\HotelController');
    return $controller->callAction('contaClickWhatsappMeAjax', $parameters = array('hotel_id' => $hotel_id, "call"));

}]);

/* CONTACLICK Call me scheda hotel mobile * /
Route::get('shareMe.php', [function () {

    $url = Request::get('uri');
    $codice = Request::get('codice');
    $controller = \App()->make('App\Http\Controllers\AjaxController');
    return $controller->callAction('contaClickShare', array('url' => $url, 'codice' => $codice));

}]);

// ------------------------------------------------------------------------ //
// 13. CERCA HOTEL
// ------------------------------------------------------------------------ //

Route::post('cerca', ['as' => 'cerca', 'uses' => 'CmsListingController@cerca']);
Route::post('trova_hotel.php', ['middleware' => ['lang', 'checkDispositivo:desktop'], 'uses' => 'SearchController@byName']);
Route::post('{locale}/trova_hotel.php', ['middleware' => ['lang', 'checkDispositivo:desktop'], 'uses' => 'SearchController@byName']);
Route::get('trova_hotel.php', ['middleware' => ['lang', 'checkDispositivo:desktop'], 'uses' => 'SearchController@notFoundByName']);
Route::get('{locale}/trova_hotel.php', ['middleware' => ['lang', 'checkDispositivo:desktop'], 'uses' => 'SearchController@notFoundByName']);

// ------------------------------------------------------------------------ //
// 14. RICERCA AVANZATA 
// ------------------------------------------------------------------------ //

Route::get('/ricerca_avanzata.php', array('middleware' => 'checkDispositivo:desktop', 'as' => 'ricerca_avanzata', 'uses' => 'SearchController@ricerca_avanzata'));
Route::get('{locale}/ricerca_avanzata.php', array('middleware' => ['lang', 'checkDispositivo:desktop'], 'as' => 'ricerca_avanzata', 'uses' => 'SearchController@ricerca_avanzata'));
Route::post('/richiesta_ricerca_avanzata.php', array('middleware' => ['checkDispositivo:desktop'], 'as' => 'richiesta_ricerca_avanzata', 'uses' => 'SearchController@richiesta_ricerca_avanzata'));
Route::post('{locale}/richiesta_ricerca_avanzata.php', array('middleware' => ['lang', 'checkDispositivo:desktop'], 'as' => 'richiesta_ricerca_avanzata', 'uses' => 'SearchController@richiesta_ricerca_avanzata'));

// ------------------------------------------------------------------------ //
// 15. COMPARA HOTEL
// ------------------------------------------------------------------------ //

Route::get('/compare', array('middleware' => 'checkDispositivo:desktop', 'as' => 'compare', 'uses' => 'HotelController@compare'));
Route::get('{locale}/compare', array('middleware' => ['lang', 'checkDispositivo:desktop'], 'as' => 'compare', 'uses' => 'HotelController@compare'));

// ------------------------------------------------------------------------ //
// 16. LINK PARTICOLARI
// ------------------------------------------------------------------------ //

Route::get('estate-{year}/{leaf}', function ($year, $leaf) {

    $current_year = date("Y");

    /*
    * Se la url richiesta riporta un anno precedente rispetto all'attuale
    * facciamo un redirect 301 verso la stessa url dell'anno attuale
    * /
    if ($year >= 2009 && $year < $current_year) {
        $uri = "/estate-$current_year/$leaf";

        return Redirect::to("/estate-$current_year/$leaf", 301);
    }

    /*
    * Se la url richiesta riporta l'anno attuale, svolgiamolo
    * / !!!WARNING!!! / non-laravelish / dirty / hackish /
    * capendo come funziona il passaggio Route » Controller, lo ricostruisco manualmente
    * /
    elseif ($year == $current_year) {

        /*
        * Per queste url particolari, la pagina sul db è salvata con una URI contentente il placeholder dell'anno
        * purtroppo questa cosa è venuta vuori il giorno prima della pubblicazione e non sono riuscito a fare di meglio
        * /
        $uri = "estate-{CURRENT_YEAR}/$leaf";

        /* devo estrarmi un oggetto request perchè il metodo index di CmsPagineController se lo aspetta come parametro */
        /* @var $request Illuminate\Http\Request * /
        $request = Request::capture();

        /*
        * a manazza in modo non-laravelish instazio il controller che mi interessa
        * e ne eseguo il metodo
        * /
        $controller = new App\Http\Controllers\CmsPagineController;

        return $controller->index($uri, $request);
    }

    /*
    * Se la url richiesta riporta un anno nel futuro
    * o più vecchio del 2009: 404
    * /
    else {
        abort(404);
    }

});

// ------------------------------------------------------------------------ //
// 17. EMAIL
// ------------------------------------------------------------------------ //

Route::post('/wishlist', array('middleware' => 'checkDispositivo:desktop|tablet', 'as' => 'wishlist', 'uses' => 'MailMultiplaController@wishlist'));
Route::post('{locale}/wishlist', array('middleware' => ['lang', 'checkDispositivo:desktop|tablet'], 'as' => 'wishlist', 'uses' => 'MailMultiplaController@wishlist'));
Route::post('/richiesta-wishlist', array('middleware' => 'checkDispositivo:desktop|tablet', 'as' => 'richiesta-wishlist', 'uses' => 'MailMultiplaController@richiestaWishlist'));
Route::post('{locale}/richiesta-wishlist', array('middleware' => ['lang', 'checkDispositivo:desktop|tablet'], 'as' => 'richiesta-wishlist', 'uses' => 'MailMultiplaController@richiestaWishlist'));
Route::post('/richiesta_mail_listing.php', array('as' => 'richiesta_mail_listing', 'uses' => 'MailSchedaController@richiesta_mailListing'));
Route::post('{locale}/richiesta_mail_listing.php', array('middleware' => ['lang'], 'as' => 'richiesta_mail_listing', 'uses' => 'MailSchedaController@richiesta_mailListing'));

$action = checkEmail("m");
Route::any('/mail-multipla', array('as' => 'mail-multipla', 'uses' => 'MailMultiplaController@' . $action));
Route::any('{locale}/mail-multipla', array('middleware' => ['lang'], 'as' => 'mail-multipla', 'uses' => 'MailMultiplaController@' . $action));

$action = checkEmailRichiesta("m");
Route::any('/richiesta-mail-multipla', array('as' => 'richiesta-mail-multipla', 'uses' => 'MailMultiplaController@' . $action));
Route::any('{locale}/richiesta-mail-multipla', array('as' => 'richiesta-mail-multipla', 'middleware' => ['lang'], 'uses' => 'MailMultiplaController@' . $action));

$action = checkEmailRichiesta("s");
Route::post('/richiesta-mail-scheda', array('as' => 'richiesta-mail-scheda', 'uses' => 'MailSchedaController@' . $action));
Route::post('{locale}/richiesta-mail-scheda', array('middleware' => ['lang'], 'as' => 'richiesta-mail-scheda', 'uses' => 'MailSchedaController@' . $action));

Route::get('/thankyou', array('as' => 'thankyou', 'uses' => 'MailSchedaController@thanks'));
Route::get('{locale}/thankyou', array('middleware' => ['lang'], 'as' => 'thankyou', 'uses' => 'MailSchedaController@thanks'));
Route::get('/thankyou_multipla', array('as' => 'thankyou_multipla', 'uses' => 'MailMultiplaController@thanks'));
Route::get('{locale}/thankyou_multipla', array('middleware' => ['lang'], 'as' => 'thankyou_multipla', 'uses' => 'MailMultiplaController@thanks'));

Route::get('/thankyou-more', function () {abort('404');});
Route::get('{locale}/thankyou-more', function () {abort('404');});

Route::get('/error_send', array('as' => 'error_send', 'uses' => 'MailSchedaController@error'));
Route::get('{locale}/error_send', array('middleware' => ['lang'], 'as' => 'error_send', 'uses' => 'MailSchedaController@error'));

Route::get('/reply-count-hotel/{hotel_id}', array('uses' => 'MailSchedaController@replyCountHotel'));
Route::get('/upselling/hotel/{hotel_to}/from/{queue_id}', array('uses' => 'MailUpsellingController@index'));


// ------------------------------------------------------------------------ //
// 18. NEWSLETTER
// ------------------------------------------------------------------------ //

Route::post('iscrizione_newsletter', ['middleware' => 'checkDispositivo:desktop', 'as' => 'iscrizione_newsletter', 'uses' => 'CmsPagineController@iscrizione_newsletter']);


// ------------------------------------------------------------------------ //
// 19. ALTRO
// ------------------------------------------------------------------------ //

Route::get('/cache', 'HomeController@cache');
Route::get('/parse_ipernet/{hotel_id?}', array('uses' => 'ParsingController@index'));
Route::get('/old-browser-update', array('uses' => 'CmsPagineController@oldBrowser'));
Route::get('{locale}/old-browser-update', array('middleware' => 'lang', 'uses' => 'CmsPagineController@oldBrowser'));
Route::post('/hotel-contact-recently', array('as' => 'hotel-contact-recently', 'uses' => 'HotelController@checkIfWriteRecently'));

Route::get('/back/{id}', array('uses' => 'CmsPagineController@redirectTo'));
Route::get('{locale}/back/{id}', array('middleware' => 'lang', 'uses' => 'CmsPagineController@redirectTo'));

Route::get('/back_hotel/{id}', array('uses' => 'HotelController@redirectTo'));
Route::get('{locale}/back_hotel/{id}', array('middleware' => 'lang', 'uses' => 'HotelController@redirectTo'));

/* NON USATA    Route::get('/checkfile', ['uses' => 'TaskController@CheckFile'])->middleware(['limitIP']); */ 
/* NON ESISTE   Route::get('/mappa', ['uses' => 'ProvaController@mappa'])->middleware(['limitIP']); */
/* DEPRECATA    Route::get('/stats_marzio/{anno?}', ['uses' => 'TaskController@StatsMarzio']); //->middleware(['limitIP']); */
/* NON ESISTE   Route::get("/prefill", array('uses' => 'HomeController@prefill')); */
/* NON USATA    Route::get('/allinea_slots', ['uses' => 'TaskController@allineaSlots'])->middleware(['limitIP']); */
/* NON USATA    Route::get('/export_rr', ['uses' => 'ExportRRController@export'])->middleware(['limitIP']); */

// ------------------------------------------------------------------------ //
// 20. ADMIN
// ------------------------------------------------------------------------ //

Route::post('admin/simpleLogin/{from_caparra?}', ['as' => 'simple.login', 'uses' => 'Auth\SimpleLoginController@login']);
Route::group(['namespace' => 'Auth'], function () {
    Route::any('admin/auth/login_manutenzione', "LoginController@manutenzione");
    Route::get('admin/auth/login', "LoginController@showLoginForm");
    Route::post('admin/auth/login', "LoginController@login");
    Route::get('admin/auth/logout', "LoginController@logout");
    Route::get('admin/password/email', 'ForgotPasswordController@showLinkRequestForm');
    Route::post('admin/password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::get('admin/password/reset/{token}', 'ResetPasswordController@showResetForm');
    Route::post('admin/password/reset', 'ResetPasswordController@reset');
});

Route::group(["namespace" => "Admin"], function () {
    setlocale(LC_ALL, "it_IT.utf8");
    Route::group(['middleware' => ['auth', 'roles']], function () {
        
        // ------------------------------------------------------------------------ //
        // 20.1 ADMIN / COMMERCIALI
        // ------------------------------------------------------------------------ //

        Route::group(['roles' => ['commerciale']], function () {

            // home
            Route::get('admin/commerciale', 'AdminController@index');

            // action impersonificazione hotel
            Route::post('admin/commerciale-seleziona-hotel', 'AdminController@commercialeSelezionaHotel');

            // pag delle foto
            Route::get('admin/commerciale-immagini-gallery-titoli/{locale}', array('middleware' => 'lang', 'uses' => 'ImmaginiGalleryController@commercialeModificaCaptionHotel'));
            Route::get('admin/commerciale-immagini-gallery-titoli', 'ImmaginiGalleryController@commercialeModificaCaptionHotel');

        });

        // ------------------------------------------------------------------------ //
        // 20.3 ADMIN / AMMINISTRATORI, OPERATORI
        // ------------------------------------------------------------------------ //

        Route::group(['roles' => ['admin', 'operatore']], function () {

            Route::get('admin/home', 'AdminController@index');
            Route::post('admin/cache', 'AdminController@cache');
            Route::any('admin/seleziona-hotel', 'AdminController@selezionaHotel');
            Route::post('admin/seleziona-hotel-da-id-ajax/{id_offerta?}', 'AdminController@selezionaHotelDaIdAjax');
            Route::post('admin/save-hotel-tag-modificati', 'AdminController@saveHotelTagModificatiAjax');
            Route::get("admin/utenti", "UserController@index");
            Route::get("admin/utenti/{id}/edit", "UserController@edit");
            Route::get("admin/utenti/create", "UserController@create");
            Route::post("admin/utenti/store", "UserController@store");
            Route::post("admin/utenti/delete", "UserController@destroy");
            Route::get("admin/hotels", "HotelController@index");

            Route::get("admin/rating/exportRating", "RatingController@exportRating");
            Route::get("admin/rating/exportNewRating", "RatingController@exportNewRating");
            Route::get("admin/rating/importRating", "RatingController@importRating");
            Route::post("admin/rating/uploadCsv", "RatingController@uploadCsv");
            Route::get("admin/rating/calcola", "RatingController@calcola");

            Route::get("admin/hotels/{id}/edit", "HotelController@edit")->name('hotel.edit');
            Route::get("admin/hotels/{id}/revisions/{revision}", "HotelController@revisions")->name('hotel.revisions');
            Route::post('admin/hotels/generate-psw/{id}', "HotelController@generate_password")->name('hotel.generate-password');
            Route::post('admin/hotels/newsletter/{id}', "HotelController@newsletter")->name('hotel.newsletter');
            //Route::get("admin/hotels/create", "HotelController@create");

            Route::get("admin/hotels/create/romagna", "HotelController@createRomagna");
            Route::get("admin/hotels/create/italia", "HotelController@createItalia");

            Route::post("admin/hotels/store", "HotelController@store");
            Route::post("admin/hotels/delete", "HotelController@destroy");
            Route::post("admin/hotels/remove-image", "HotelController@removeImage");
            Route::post('admin/hotels/agg_nota_pagamento_ajax', ['uses' => 'HotelController@aggNotaPagamentoAjax']);
            Route::post('admin/hotel-cache/{id}', ['uses' => 'HotelController@clearCacheHotel']);

            Route::resource('admin/gruppo-hotels', 'GruppoHotelController');
            Route::get("admin/menus", "MenusController@index");
            Route::get("admin/menus/{id}/edit", "MenusController@edit");
            Route::post("admin/menus/add", "MenusController@add");
            Route::post("admin/menus/saveOrder", "MenusController@saveOrder");
            Route::get("admin/menus/{id}/{id_macro}/delete", "MenusController@delete");

            Route::get("admin/stats/stats-IA", "StatsController@statsIA") /**/;
            Route::post("admin/stats/stats-IA", "StatsController@statsIAResult");

            Route::get("admin/pages", "PagesController@index");
			Route::get("admin/pages/{id}/edit", "PagesController@edit");
			Route::get("admin/pages/{id}/clona", "PagesController@clona");
			Route::get("admin/pages/create", "PagesController@create");
			Route::post("admin/pages/store", "PagesController@store");
			Route::post("admin/pages/delete", "PagesController@destroy");
			Route::post("admin/pages/massive", "PagesController@massiveEdit");
			Route::post("admin/pages/massive/edit", "PagesController@massiveEditStore");

            Route::get("admin/listing", "ListingController@index");
            Route::get("admin/listing/{id}/edit", "ListingController@edit");
            Route::get("admin/listing/{id}/clona", "ListingController@clona");
            
			Route::get("admin/listing/create", "ListingController@create");

            Route::post("admin/listing/store", "ListingController@store");
            Route::post("admin/listing/delete", "ListingController@destroy");
            Route::post("admin/listing/massive", "ListingController@massiveEdit");
            Route::post("admin/listing/massive/edit", "ListingController@massiveEditStore");
            
            Route::get("admin/listing/stradario", "ListingController@index_stradario");

            // Politiche di cancellazione
            // Route::get("admin/politiche-cancellazione/moderazione", "CaparraController@moderazione");
            // Route::post("admin/politiche-cancellazione/moderazione", "CaparraController@storeModeratore");

            // Politiche bonus vacanza
            // Route::get("admin/politiche-bonus/moderazione", "BonusController@moderazione");
            // Route::post("admin/politiche-bonus/moderazione", "BonusController@storeModeratore");

            Route::get("admin/stats/mail_scheda", "StatsMailSchedaController@schede");
            Route::post("admin/stats/mail_scheda", "StatsMailSchedaController@schedeResult") /**/;
            Route::get("admin/parole-chiave", "ParoleChiaveController@index");
            Route::get("admin/parole-chiave/{id}/edit", "ParoleChiaveController@index");
            Route::post("admin/parole-chiave/save", "ParoleChiaveController@save");
            Route::post("admin/parole-chiave/delete", "ParoleChiaveController@delete");

            /* PUNTI DI FORZA ESPANSI */
            Route::get("admin/punti-forza-chiave", "PuntiForzaChiaveController@index");
            Route::get("admin/punti-forza-chiave/{id}/edit", "PuntiForzaChiaveController@index");
            Route::post("admin/punti-forza-chiave/save", "PuntiForzaChiaveController@save");
            Route::post("admin/punti-forza-chiave/delete", "PuntiForzaChiaveController@delete");
            /*Route::get("admin/tasse-soggiorno", "TassaSoggiornoController@index");
            Route::post("admin/tasse-soggiorno", "TassaSoggiornoController@store");*/
            Route::get("admin/note-listino", "NoteListinoController@index");
            Route::post("admin/note-listino", "NoteListinoController@save");
            Route::any('admin/featured', 'AdminController@featured');
            Route::any('admin/hotfix', 'AdminController@hotfix');

            /* VERTRINE E SLOT */
            Route::get('admin/vetrine-principali', 'VetrineController@index_principali');
            Route::get('admin/vetrine-limitrofe', 'VetrineController@index_limitrofe');
            Route::resource('admin/vetrine', 'VetrineController');
            Route::post("admin/remove-image-slot", "SlotsVetrinaController@removeImage");
            Route::resource('admin/vetrine.slots', 'SlotsVetrinaController');

            /* IMMAGINI-GALLERY HOTEL */
            /* richiesta AJAX per ordinamento immagine gallery */
            Route::post('admin/immagini-gallery/order_ajax', ['as' => 'admin/immagini-gallery/order_ajax', 'uses' => 'ImmaginiGalleryController@orderAjax']);
            Route::post('admin/immagini-gallery/set_gruppo_immagine_ajax', ['as' => 'admin/immagini-gallery/set_gruppo_immagine_ajax', 'uses' => 'ImmaginiGalleryController@SetGruppoImmagineAjax']);
            Route::post('admin/immagini-gallery/delete-all', ['as' => 'admin/immagini-gallery/delete-all', 'uses' => 'ImmaginiGalleryController@deleteAll']);
            Route::post('admin/immagini-gallery/uploadImage', ['as' => 'admin/immagini-gallery/uploadImage', 'uses' => 'ImmaginiGalleryController@uploadImage']);
            Route::post('admin/immagini-spot/uploadImage', ['as' => 'admin/immagini-spot/uploadImage', 'uses' => 'PagesController@uploadImage']);
            Route::post('admin/immagini-evidenza/uploadImage', ['as' => 'admin/immagini-evidenza/uploadImage', 'uses' => 'PagesController@uploadImageEvidenza']);
            Route::post('admin/pagine/uploadImage', ['as' => 'admin/pagine/uploadImage', 'uses' => 'PagesController@uploadImagePagine']);
            Route::post('admin/immagini-gallery/create_img_listing/{id_foto}', ['uses' => 'ImmaginiGalleryController@createImgListing']);
            Route::post('admin/immagini-gallery/approve_titles', ['as' => 'admin/immagini-gallery/approve_titles', 'uses' => 'ImmaginiGalleryController@approveTitles']);
            Route::post('admin/immagini-gallery/notify_approved_titles', ['as' => 'admin/immagini-gallery/notify_approved_titles', 'uses' => 'ImmaginiGalleryController@notifyApprovedTitles']);

            Route::post('admin/immagini-gallery/del_caption_ajax', ['uses' => 'ImmaginiGalleryController@DelCaptionAjax']);
            Route::get('admin/immagini-gallery/{locale}', array('middleware' => 'lang', 'uses' => 'ImmaginiGalleryController@index'));
            Route::post('admin/immagini-gallery/del_checked_ajax', ['uses' => 'ImmaginiGalleryController@DelCheckedAjax']);

            Route::resource('admin/immagini-gallery', 'ImmaginiGalleryController');

            /* CREAZIONE SERVIZI HOTEL */
            Route::post("admin/servizi/updateLingua", "ServiziController@updateLingua") /**/;

            /*
            * ATTENZIONE:
            * Supplementing Resource Controllers
            *
            * If it becomes necessary to add additional routes to a resource controller beyond the default resource routes, you should define those routes before your call to Route::resource; otherwise, the routes defined by the resource method may unintentionally take precedence over your supplemental routes:
            * Route::get('photos/popular', 'PhotoController@method');
            * Route::resource('photos', 'PhotoController');
            * QUINDI il Route::resource('admin/servizi', 'ServiziController'); è definito SOTTO nell'altro gruppo
            */

            /* TESTO SCHEDA HOTEL */
            Route::post("admin/scheda-hotel/updateLingua", "SchedaHotelController@updateLingua");
            Route::post("admin/scheda-hotel/duplicate", "SchedaHotelController@duplicate");
            Route::post("admin/scheda-hotel/online", "SchedaHotelController@online");
            Route::resource('admin/scheda-hotel', 'SchedaHotelController');
            Route::get('admin/modera-foto', 'HomeController@moderaFoto');
            Route::get('admin/offerte_da_approvare', array('uses' => 'OfferteDaAprrovareController@index'));
            Route::post('admin/approva-offerta-ajax/{id_offerta}', array('uses' => 'OfferteDaAprrovareController@approvaOffertaAjax'));
            Route::post('admin/approva-offerta_pp-ajax/{id_offerta}', array('uses' => 'OfferteDaAprrovareController@approvaOffertaPPAjax'));
            Route::post('admin/approva-offerta_bb-ajax/{id_offerta}', array('uses' => 'OfferteDaAprrovareController@approvaOffertaBBAjax'));

            // PUNTI DI INTERESSE //
            Route::get('admin/poi/hotels-localita/{localita_id}', array('uses' => 'PoiController@hotelsLocalita'));
            Route::get('admin/poi/hotels-macrolocalita/{macrolocalita_id}', array('uses' => 'PoiController@hotelsMacroLocalita'));
            Route::post("admin/poi/updateLingua", "PoiController@updateLingua") /**/;
            Route::resource('admin/poi', 'PoiController');

            // PUNTI DI INTERESSE DI GOOLGE //
            Route::get('admin/google-poi', ['uses' => 'GooglePoiController@index'])->name('google-poi');
            Route::post('admin/google-poi/delete/{id}', ['uses' => 'GooglePoiController@delete'])->name('delete-google-poi');

            // MAPPA RICERCA PUNTI DI INTERESSE //
            Route::resource('admin/mappa-ricerca-poi', 'MappaRicercaPoiController');

            // INFO AGGIUNTIVE HOTEL       //
            Route::post("admin/azzera-info-benessere/{ajax_call?}", "InfoBenessereController@reset") /**/;
            Route::resource('admin/info-benessere', 'InfoBenessereController');

            // INFO AGGIUNTIVE HOTEL       //
            Route::post("admin/azzera-info-piscina/{ajax_call?}", "InfoPiscinaController@reset");
            Route::resource('admin/info-piscina', 'InfoPiscinaController');

            // ASSOCIAZIONE SERVIZI HOTEL //
            Route::get("admin/servizi/associa-servizi/{locale?}", array('middleware' => 'lang', 'uses' => 'ServiziController@viewServiziHotel'));
            Route::post("admin/servizi/associa-servizi/{locale?}", array('middleware' => 'lang', 'uses' => 'ServiziController@associaServiziHotel'));

            Route::post('admin/servizi/associa_nota_servizio_ajax/{locale?}', ['middleware' => 'lang', 'uses' => 'ServiziController@AssociaNotaServizioAjax']);
            Route::post('admin/servizi/associa_nota_servizio_listing_ajax/{locale?}', ['middleware' => 'lang', 'uses' => 'ServiziController@AssociaNotaServizioListingAjax']);
            Route::post('admin/servizi/crea_servizio_privato_ajax/{locale?}', ['middleware' => 'lang', 'uses' => 'ServiziController@CreaServizioPrivatoAjax']);
            Route::post('admin/servizi/aggiorna_servizio_privato_ajax/{locale?}', ['middleware' => 'lang', 'uses' => 'ServiziController@AggiornaServizioPrivatoAjax']);
            //Route::post('admin/servizi/del_servizio_privato_ajax', ['uses' => 'ServiziController@DelServizioPrivatoAjax']);

            Route::post('admin/servizi/del_servizio_privato_ajax/{locale?}', ['middleware' => 'lang', 'uses' => 'ServiziController@DelServizioPrivatoAjax']);
            Route::post('admin/servizi/del_servizio_privato_all_ajax/{locale?}', ['middleware' => 'lang', 'uses' => 'ServiziController@DelServizioPrivatoAllAjax']);
            Route::post('admin/servizi/order_ajax', ['uses' => 'ServiziController@OrderServizioCategoriaAjax']);

            // DISSOCIA TUTTO
            Route::post("admin/servizi/dissocia-servizi/", array('middleware' => 'lang', 'uses' => 'ServiziController@dissociaServiziHotel'));
            Route::resource('admin/servizi', 'ServiziController');
            Route::resource('admin/newsletterLink', 'NewsLetterController');

            Route::get('admin/sendgrid', 'SendgridController@dashboard');
            Route::get('admin/sendgrid-blocks', 'SendgridController@blocks')->name('sendgrid-blocks');
            Route::get('admin/sendgrid-bounces', 'SendgridController@bounces')->name('sendgrid-bounces');

            Route::resource('admin/motivazioni', 'MotivazioniController');



            Route::post("admin/politiche-cancellazione/create-label", "CaparraController@create_label")->name('cancellazione-create-label');
            Route::post("admin/politiche-cancellazione/label-not-labelable", "CaparraController@labelNotLabelable")->name('label-not-labelable');

            

            Route::post("admin/politiche-cancellazione/update-label/{label_id}", "CaparraController@update_label")->name('cancellazione-update-label');

            Route::post("admin/politiche-cancellazione/delete-label/{label_id}", "CaparraController@delete_label")->name('cancellazione-delete-label');

            Route::get("admin/elenco-politiche-cancellazione/{hotel_id}", "CaparraController@elencoPoliticheCancellazione")->name('elenco-politiche-cancellazione');


        });

        // ------------------------------------------------------------------------ //
        // 20.4 ADMIN / AMMINISTRATORI, OPERATORI, HOTEL
        // ------------------------------------------------------------------------ //

        Route::group(['roles' => ['admin', 'operatore', 'hotel']], function () {

            Route::get('admin', 'HomeController@index');
            Route::post("admin/hotel/acceptPolicyGallery", "HotelController@acceptPolicyGallery");
            Route::get('admin/punti-forza', 'PuntiForzaController@edit');
            Route::post('admin/punti-forza', 'PuntiForzaController@store');

            Route::post('admin/bambini-gratis/{id}/archivia', ['uses' => 'BambiniGratisController@archivia']);
            Route::get('admin/bambini-gratis/archiviati', ['uses' => 'BambiniGratisController@archiviati']);
            Route::get("admin/bambini-gratis", "BambiniGratisController@index");
            Route::get("admin/bambini-gratis/{id}/edit", "BambiniGratisController@edit");
            Route::get("admin/bambini-gratis/create", "BambiniGratisController@create");
            Route::post("admin/bambini-gratis/store", "BambiniGratisController@store");
            Route::post("admin/bambini-gratis/delete", "BambiniGratisController@destroy");

            Route::get("admin/listini-custom", "ListiniCustomController@index");
            Route::get("admin/listini-custom/order", "ListiniCustomController@order");
            Route::get("admin/listini-custom/{id}/edit", "ListiniCustomController@edit");
            Route::get("admin/listini-custom/create", "ListiniCustomController@create");
            Route::post("admin/listini-custom/store", "ListiniCustomController@store");
            Route::post("admin/listini-custom/saveOrder", "ListiniCustomController@saveOrder");
            Route::post("admin/listini-custom/delete", "ListiniCustomController@destroy");
            Route::post("admin/listini-custom/translate_data_ajax", "ListiniCustomController@translateDataAjax");

            // LISTINI STANDARD //
            Route::post('admin/listini-standard/salva_prezzo_ajax', ['as' => 'salva_prezzo_ajax', 'uses' => 'ListiniStandardController@SalvaPrezzoAjax']);
            Route::post('admin/listini-standard/salva_data_ajax', ['as' => 'salva_data_ajax', 'uses' => 'ListiniStandardController@SalvaDataAjax']);
            Route::post('admin/listini-standard/modifica_stato', ['uses' => 'ListiniStandardController@ModificaStato']);
            Route::post('admin/listini-standard/delete_selected', ['uses' => 'ListiniStandardController@DeleteSelected']);
            Route::resource('admin/listini-standard', 'ListiniStandardController');

            // LISTINI MIN/MAX  //
            Route::post('admin/listini-minmax/salva_prezzo_ajax', ['as' => 'salva_prezzo_ajax', 'uses' => 'ListiniMinMaxController@SalvaPrezzoAjax']);
            Route::post('admin/listini-minmax/salva_data_ajax', ['as' => 'salva_data_ajax', 'uses' => 'ListiniMinMaxController@SalvaDataAjax']);
            Route::post('admin/listini-minmax/modifica_stato', ['uses' => 'ListiniMinMaxController@ModificaStato']);

            Route::resource('admin/listini-minmax', 'ListiniMinMaxController');
            /*Route::get("admin/tasse-soggiorno", "TassaSoggiornoController@index");
            Route::post("admin/tasse-soggiorno", "TassaSoggiornoController@store");*/
            Route::get("admin/note-listino", "NoteListinoController@index");
            Route::post("admin/note-listino", "NoteListinoController@save");

            /*
            * AL MOMENTO "OFFERTE" E "LAST" SONO 2 RISORSE QUASI UGUALI (condividono la stessa tabella con gli stessi campi e c'è un enum per differenziarne il tipo), QUINDI SI POTREBBERO UNIFICARE ANCHE COME CODICE
            * MA SI PREFERISCE TENERLE SEPARATE IN PREVISIONE DI POSSIBILI (E PROBABILI ?) MODIFICHE DA APPORTARE SOLO AD UN TIPO DI RISORSA !!
            */

            // OFFERTE //
            Route::post('admin/offerte/{id}/archivia', ['uses' => 'OfferteController@archivia']);
            Route::post('admin/offerte/{id}/ripristina', ['uses' => 'OfferteController@ripristina']);
            Route::get('admin/offerte/archiviate', ['uses' => 'OfferteController@archiviate']);
            Route::resource('admin/offerte', 'OfferteController');

            // LAST //
            Route::post('admin/last/{id}/archivia', ['uses' => 'LastController@archivia']);
            Route::post('admin/last/{id}/ripristina', ['uses' => 'LastController@ripristina']);
            Route::get('admin/last/archiviati', ['uses' => 'LastController@archiviati']);
            Route::resource('admin/last', 'LastController');

            // PRENOTA PRIMA //
            Route::post('admin/prenota-prima/{id}/archivia', ['uses' => 'OffertePrenotaPrimaController@archivia']);
            Route::post('admin/prenota-prima/{id}/ripristina', ['uses' => 'OffertePrenotaPrimaController@ripristina']);
            Route::get('admin/prenota-prima/archiviati', ['uses' => 'OffertePrenotaPrimaController@archiviate']);
            Route::resource('admin/prenota-prima', 'OffertePrenotaPrimaController');

            // VETRINE OFFERTE TOP //
            Route::post('admin/vetrine-offerte-top/{id}/archivia', ['uses' => 'VetrinaOfferteTopController@archivia']);
            Route::post('admin/vetrine-offerte-top/{id}/ripristina', ['uses' => 'VetrinaOfferteTopController@ripristina']);
            Route::post('admin/vetrine-offerte-top/{id}/clona', ['uses' => 'VetrinaOfferteTopController@clona']);
            Route::get('admin/vetrine-offerte-top/archiviati', ['uses' => 'VetrinaOfferteTopController@archiviate']);
            Route::resource('admin/vetrine-offerte-top', 'VetrinaOfferteTopController');
            Route::get('admin/elenco_scadenze_vot', ['uses' => 'VetrinaOfferteTopController@elenco_scadenze']);

            // VETRINE BAMBINI GRATIS TOP //
            Route::post('admin/vetrine-bg-top/{id}/archivia', ['uses' => 'VetrinaBambiniGratisTopController@archivia']);
            Route::post('admin/vetrine-bg-top/{id}/ripristina', ['uses' => 'VetrinaBambiniGratisTopController@ripristina']);
            Route::get('admin/vetrine-bg-top/archiviati', ['uses' => 'VetrinaBambiniGratisTopController@archiviate']);

            Route::resource('admin/vetrine-bg-top', 'VetrinaBambiniGratisTopController');
            Route::get('admin/elenco_scadenze_vtt', ['uses' => 'VetrinaBambiniGratisTopController@elenco_scadenze']);
            Route::get('admin/elenco_hotel_tag_modificati', ['uses' => 'HomeController@elencoHotelTagModificati']);
            Route::post('admin/remove_hotel_tag_modificati', ['uses' => 'HomeController@removeHotelTagModificati']);
            Route::get('admin/elenco_scadenze_vtt', ['uses' => 'VetrinaBambiniGratisTopController@elenco_scadenze']);
            Route::get('admin/elenco_hotel_tag_modificati', ['uses' => 'HomeController@elencoHotelTagModificati']);
            Route::post('admin/remove_hotel_tag_modificati', ['uses' => 'HomeController@removeHotelTagModificati']);

            Route::get('admin/elenco_hotel_trattamenti_modificati', ['uses' => 'HomeController@elencoHotelTrattamentiModificati']);
            Route::get('admin/elenco_hotel_politiche_cancellazione', ['uses' => 'HomeController@elencoHotelPoliticheCancellazione'])->name('elenco_hotel_politiche_cancellazione');

            // TRATTAMENTO TOP //
            Route::post('admin/vetrine-trattamento-top/{id}/archivia', ['uses' => 'VetrinaTrattamentoTopController@archivia']);
            Route::post('admin/vetrine-trattamento-top/{id}/ripristina', ['uses' => 'VetrinaTrattamentoTopController@ripristina']);
            Route::get('admin/vetrine-trattamento-top/archiviati', ['uses' => 'VetrinaTrattamentoTopController@archiviate']);
            Route::resource('admin/vetrine-trattamento-top', 'VetrinaTrattamentoTopController');

            // SERVIZI TOP //
            Route::post('admin/vetrine-servizi-top/{id}/archivia', ['uses' => 'VetrinaServiziTopController@archivia']);
            Route::post('admin/vetrine-servizi-top/{id}/ripristina', ['uses' => 'VetrinaServiziTopController@ripristina']);
            Route::get('admin/vetrine-servizi-top/archiviati', ['uses' => 'VetrinaServiziTopController@archiviate']);
            Route::resource('admin/vetrine-servizi-top', 'VetrinaServiziTopController');

            // COUPON //
            Route::post('admin/coupon/{id}/archivia', ['uses' => 'CouponScontoController@archivia']);
            Route::get('admin/coupon/{id}/generati', ['uses' => 'CouponScontoController@generati']);
            Route::resource('admin/coupon', 'CouponScontoController');

            // CAPTION FOTO //
            Route::get('admin/immagini-gallery-titoli/{locale}', array('middleware' => 'lang', 'uses' => 'ImmaginiGalleryController@modificaCaptionHotel'));
            Route::get('admin/immagini-gallery-titoli', 'ImmaginiGalleryController@modificaCaptionHotel');
            Route::post('admin/immagini-gallery/del_caption_ajax', ['uses' => 'ImmaginiGalleryController@DelCaptionAjax']);

            // INFO AGGIUNTIVE HOTEL       //
            /*Route::resource('admin/info-piscina', 'InfoPiscinaController');*/
            Route::get('admin/stats/hotel-like', 'StatsHotelController@like');
            Route::get("admin/stats/whatsapp", "StatsHotelController@whatsapp");
            Route::post("admin/stats/whatsapp", "StatsHotelController@whatsappResult");

            // ASSOCIAZIONE SERVIZI SOLO READ ONLY //
            Route::get("admin/servizi-hotel/vedi-servizi", array('uses' => 'ServiziController@viewServiziHotelReadOnly'))->name('vedi-servizi');

            ////////////////////////////////////
            // PAGINE ASSOCIATE ALLE EVIDENZE //
            ////////////////////////////////////
            Route::get("admin/vetrine-offerte-top-hotel", "VetrinaOfferteTopController@listPage");
            Route::get("admin/vetrine-bg-top-hotel", "VetrinaBambiniGratisTopController@listPage");
            Route::get("admin/vetrine-trattamento-top-hotel", "VetrinaTrattamentoTopController@listPage");
            Route::get("admin/vetrine-servizi-top-hotel", "VetrinaServiziTopController@listPage");

            Route::get("admin/trattamenti/{hotel_id?}", "TrattamentiController@index")->name('elenco-trattamenti');
            Route::post("admin/trattamenti", "TrattamentiController@store")->name('salva-trattamenti');


            //////////////////////////////////////////
            // INVIO WHATSAPP NUMERI NON IN RUBRICA //
            //////////////////////////////////////////
            Route::get("admin/whatsapp-non-rubirca", "WhastAppController@index")->name('whatsapp-non-rubirca');
            Route::get("admin/whatsapp-non-rubirca/lista", "WhastAppController@lista")->name('whatsapp-non-rubirca-lista');

             Route::resource('admin/wa-template', 'WhastAppTemplateController');


            Route::get("admin/servizi-hotel/servizi-covid", array('uses' => 'ServiziCovidController@index'))->name('servizi-covid');
            Route::post("admin/servizi-hotel/servizi-covid/store", array('uses' => 'ServiziCovidController@store'))->name('servizi-covid.store');


            ///////////////////////////////////////////
            // SERVIZI ARRIVO E PARTENZA            //
            //////////////////////////////////////////
            Route::get("admin/servizi-hotel/servizi-in-out", array('uses' => 'ServiziInOutController@index'))->name('servizi-in-out');
            Route::post("admin/servizi-hotel/servizi-in-out/store", array('uses' => 'ServiziInOutController@store'))->name('servizi-in-out.store');


            ///////////////////////////////////////////
            // SERVIZI GREEN        //
            //////////////////////////////////////////
            Route::get("admin/servizi-hotel/servizi-green", array('uses' => 'ServiziGreenController@index'))->name('servizi-green');
            Route::post("admin/servizi-hotel/servizi-green/store", array('uses' => 'ServiziGreenController@store'))->name('servizi-green.store');





        });

        // ------------------------------------------------------------------------ //
        // 20.5 ADMIN / AMMINISTRATORI, OPERATORI, COMMECIALI
        // ------------------------------------------------------------------------ //

        Route::group(['roles' => ['admin', 'operatore', 'commerciale']], function () {

            // home
            Route::get('admin/statsv2/offerte/generale', 'v2_StatsOfferteController@offerteGenerale');
            Route::post('admin/statsv2/offerte/generale', 'v2_StatsOfferteController@offerteGenerale') /**/;
            Route::post('admin/statsv2/offerte/cambia_tipo_vetrina_ajax', 'v2_StatsOfferteController@cambiaTipoVetrinaAjax');
        
            // Statistiche Hotel
            Route::get("admin/stats/index", "StatsController@index"); // Indice statistiche
            Route::get("admin/stats/hotels", "StatsHotelController@generali");
            Route::post("admin/stats/hotels", "StatsHotelController@generali");
            Route::get("admin/stats/hotels/rating", "StatsHotelController@rating");
            Route::post("admin/stats/hotels/rating", "StatsHotelController@ratingResult");

            Route::get("admin/stats/whatsapp", "StatsHotelController@whatsapp");
            Route::post("admin/stats/whatsapp", "StatsHotelController@whatsappResult");
            //Route::get("admin/stats/hotel", "StatsHotelController@dettaglio");
            //Route::post("admin/stats/hotel", "StatsHotelController@dettaglio");
            Route::get("admin/stats/mail_scheda", "StatsMailSchedaController@schede") /**/;
            Route::post("admin/stats/mail_scheda", "StatsMailSchedaController@schedeResult") /**/;
            Route::get("admin/stats/mail_scheda_giornaliere", "StatsMailSchedaController@giornaliere");
            Route::get("admin/stats/mail_scheda_localitazone", "StatsMailSchedaController@localitazone");
            Route::post("admin/stats/mail_scheda_localitazone", "StatsMailSchedaController@localitazoneResult");
            Route::get("admin/stats/mail_multiple", "StatsMailMultiplaController@multipla") /**/;
            Route::post("admin/stats/mail_multiple", "StatsMailMultiplaController@multiplaResult") /**/;
            Route::get("admin/stats/mail_multiple_giornaliere", "StatsMailMultiplaController@giornaliere") /**/;
            Route::post("admin/stats/mail_multiple_giornaliere", "StatsMailMultiplaController@giornaliere") /**/;
            // Route::get("admin/stats/mail_upselling", "StatsMailUpsellingController@strutture");
            // Route::post("admin/stats/mail_upselling", "StatsMailUpsellingController@strutture");
            // Route::get("admin/stats/outbound-links", "StatsHotelOutboundLinksController@form");
            // Route::post("admin/stats/outbound-links", "StatsHotelOutboundLinksController@results");
            Route::any("admin/stats/share", "StatsController@share") /**/;

            // statistiche chiamate all'hotel
            Route::get("admin/stats/hotelCalls", "StatsHotelController@calls") /**/;
            Route::post("admin/stats/hotelCalls", "StatsHotelController@callsResult") /**/;
            /* Route::get("admin/stats/vetrine/pre-laravel-era", "StatsVetrineController@preLaravelEraForm");
            Route::post("admin/stats/vetrine/pre-laravel-era", "StatsVetrineController@preLaravelEraResults");*/
            Route::get("admin/stats/vetrine/laravel-era", "StatsVetrineController@laravelEraForm");
            Route::post("admin/stats/vetrine/laravel-era", "StatsVetrineController@laravelEraResults");
            Route::get("admin/stats/vetrine/vaat", "StatsVetrineController@vetrinePagineForm");
            Route::post("admin/stats/vetrine/vaat", "StatsVetrineController@vaatResults");
            Route::get("admin/stats/vetrine/vaatSimple", "StatsVetrineController@vetrinePagineFormSimple");
            Route::post("admin/stats/vetrine/vaatSimple", "StatsVetrineController@vaatResultsSimple");
            Route::get("admin/stats/vetrine/vaatPage", "StatsVetrineController@vaatVetrinePagineFormPage");
            Route::post("admin/stats/vetrine/vaatPage", "StatsVetrineController@vaatResultsPage");
            Route::get("admin/stats/vetrine/vot", "StatsVetrineController@vetrinePagineForm");
            Route::post("admin/stats/vetrine/vot", "StatsVetrineController@votResults");
            Route::get("admin/stats/vetrine/votSimple", "StatsVetrineController@vetrinePagineFormSimple");
            Route::post("admin/stats/vetrine/votSimple", "StatsVetrineController@votResultsSimple");
            Route::get("admin/stats/vetrine/votPage", "StatsVetrineController@votVetrinePagineFormPage");
            Route::post("admin/stats/vetrine/votPage", "StatsVetrineController@votResultsPage");
            Route::get("admin/stats/vetrine/vst", "StatsVetrineController@vetrinePagineForm");
            Route::post("admin/stats/vetrine/vst", "StatsVetrineController@vstResults");
            Route::get("admin/stats/vetrine/vstSimple", "StatsVetrineController@vetrinePagineFormSimple");
            Route::post("admin/stats/vetrine/vstSimple", "StatsVetrineController@vstResultsSimple");
            Route::get("admin/stats/vetrine/vstPage", "StatsVetrineController@vstVetrinePagineFormPage");
            Route::post("admin/stats/vetrine/vstPage", "StatsVetrineController@vstResultsPage");
            Route::get("admin/stats/vetrine/vtt", "StatsVetrineController@vetrinePagineForm");
            Route::post("admin/stats/vetrine/vtt", "StatsVetrineController@vttResults");
            Route::get("admin/stats/vetrine/vttSimple", "StatsVetrineController@vetrinePagineFormSimple");
            Route::post("admin/stats/vetrine/vttSimple", "StatsVetrineController@vttResultsSimple");
            Route::get("admin/stats/vetrine/vttPage", "StatsVetrineController@vttVetrinePagineFormPage");
            Route::post("admin/stats/vetrine/vttPage", "StatsVetrineController@vttResultsPage");

            Route::get("admin/stats/rapporto-mail", "StatsController@rapportoMail") /**/;
            Route::post("admin/stats/rapporto-mail", "StatsController@rapportoMailResult") /**/;

            Route::get("admin/stats/export_rapporto-mail-diretta", "StatsController@exportRapportoMailDiretta")->name('export_rapporto-mail-diretta');
            Route::get("admin/stats/export_rapporto-mail-multipla", "StatsController@exportRapportoMailMultipla")->name('export_rapporto-mail-multipla');

        });

        // ------------------------------------------------------------------------ //
        // 20.6 ADMIN / TUTTI
        // ------------------------------------------------------------------------ //

        Route::group(['roles' => ['admin', 'operatore', 'hotel', 'commerciale']], function () {

            Route::get('admin', 'HomeController@index');
            Route::post('admin/immagini-gallery/crea_caption_ajax', ['uses' => 'ImmaginiGalleryController@CreaCaptionAjax']);
            Route::get("admin/stats/hotel", "StatsHotelController@dettaglio");
            Route::post("admin/stats/hotel", "StatsHotelController@dettaglioResult");
            Route::get("admin/stats/whatsapp", "StatsHotelController@whatsapp");
            Route::post("admin/stats/whatsapp", "StatsHotelController@whatsappResult");
            Route::get("admin/stats/outbound-links", "StatsHotelOutboundLinksController@form");
            Route::post("admin/stats/outbound-links", "StatsHotelOutboundLinksController@results");

            Route::get("admin/politiche-cancellazione", "CaparraController@index");
            Route::post("admin/politiche-cancellazione", "CaparraController@storeModeratore");
            Route::post("admin/politiche-cancellazione/store", "CaparraController@store");
            Route::get("admin/politiche-cancellazione/create", "CaparraController@create");
            Route::get("admin/politiche-cancellazione/{id}/edit", "CaparraController@edit");
            // Route::get("admin/politiche-bonus", "BonusController@index");
            // Route::post("admin/politiche-bonus", "BonusController@store");

            Route::get("admin/recensioni", "RatingController@index");
            Route::post("admin/recensioni", "RatingController@store");

        });

    });

});

// ------------------------------------------------------------------------ //
// 21. STRADARIO
// ------------------------------------------------------------------------ //

// Route::any("stradario/{uri}", ['uses' => 'CmsListingController@stradario']);

// ------------------------------------------------------------------------ //
// 22. MAPPE
// ------------------------------------------------------------------------ //

// Route::any("mappa/{uri}", ['uses' => 'CmsListingController@mappa']);
// Route::any("{locale}/mappa/{uri}", ['uses' => 'CmsListingController@mappa']);
// Route::any("mappa-hotel/{hotel_id}", ['uses' => 'CmsListingController@mappaHotel']);
// Route::any("{locale}/mappa-hotel/{hotel_id}", ['middleware' => 'lang', 'uses' => 'CmsListingController@mappaHotel']);
// Route::any("mappa-ricerca", ['uses' => 'CmsListingController@mappaRicerca']);
// Route::any("{locale}/mappa-ricerca", ['middleware' => 'lang', 'uses' => 'CmsListingController@mappaRicerca']);

// ------------------------------------------------------------------------ //
// 23. FILTRI
// ------------------------------------------------------------------------ //

// Route::post("filter/count", ['uses' => 'FilterController@filterCount']);

/**
 * es https://www.info-alberghi.xxx/filter/ 
 *    macrolocalita_id (11) / 
 *    trattamenti_keys (*) / 
 *    categorie_keys (*) / 
 *    bonus_vacanza_keys (0) / 
 *    annuale_keys (*) / 
 *    gruppo_servizi_keys (*) / 
 *    cancellazione_gratuita_keys (*)
 */

// Route::get("filter" , function () {

//     $request = Request::capture();
//     $controller = \App()->make('App\Http\Controllers\FilterController');

//     return $controller->callAction("index", 
//         [
//             "request" => $request, 
//             'macrolocalita_ids' => 11, 
//             'trattamenti_keys' => "*", 
//             'categorie_keys' => "*", 
//             'bonus_vacanza_keys' =>0, 
//             "annuale_keys" => "*", 
//             "gruppo_servizi_keys" => "*", 
//             "cancellazione_gratuita_keys" => "*",
//             "rating_keys" => "*"
//         ]
//     );

// });

// Route::get("filter/{macrolocalita_ids}/{trattamenti_keys}/{categorie_keys}/{bonus_vacanza_keys}/{annuale_keys}/{gruppo_servizi_keys}/{cancellazione_gratuita_keys}/{rating_keys}",  

//     function (
//         $macrolocalita_ids = 11, 
//         $trattamenti_keys = "*", 
//         $categorie_keys = "*", 
//         $bonus_vacanza_keys = 0, 
//         $annuale_keys = "*", 
//         $gruppo_servizi_keys = "*", 
//         $cancellazione_gratuita_keys = "*",
//         $rating_keys = "*"
//     ) {

//     $request = Request::capture();
//     $controller = \App()->make('App\Http\Controllers\FilterController');

//     return $controller->callAction("index", 
//         [
//             "request" => $request, 
//             'macrolocalita_ids' => $macrolocalita_ids, 
//             'trattamenti_keys' => $trattamenti_keys, 
//             'categorie_keys' => $categorie_keys, 
//             'bonus_vacanza_keys' => $bonus_vacanza_keys, 
//             "annuale_keys" => $annuale_keys, 
//             "gruppo_servizi_keys" => $gruppo_servizi_keys, 
//             "cancellazione_gratuita_keys" => $cancellazione_gratuita_keys,
//             "rating_keys" => $rating_keys
//         ]
//     );

// });

// Route::get("filter-listing", function() {
    
//     $request = Request::capture();
//     $controller = \App()->make('App\Http\Controllers\FilterController');
    
//     return $controller->callAction("listing", 
//         [
//             "request" => $request, 
//             'macrolocalita_ids' => 11, 
//             'trattamenti_keys' => "*", 
//             'categorie_keys' => "*", 
//             'bonus_vacanza_keys' => 0, 
//             "annuale_keys" => "*", 
//             "gruppo_servizi_keys" => "*", 
//             "cancellazione_gratuita_keys" => "*",
//             "rating_keys" => "*"
//         ]
//     );
// });

// Route::get("filter-listing/{macrolocalita_ids}/{trattamenti_keys}/{categorie_keys}/{bonus_vacanza_keys}/{annuale_keys}/{gruppo_servizi_keys}/{cancellazione_gratuita_keys}/{rating_keys}", 

//     function(
//         $macrolocalita_ids = 11, 
//         $trattamenti_keys = "*", 
//         $categorie_keys = "*", 
//         $bonus_vacanza_keys = 0, 
//         $annuale_keys = "*", 
//         $gruppo_servizi_keys = "*", 
//         $cancellazione_gratuita_keys = "*",
//         $rating_keys = "*"
//     ) {

//     $request = Request::capture();
//     $controller = \App()->make('App\Http\Controllers\FilterController');
    
//     return $controller->callAction("listing", 
//         [
//             "request" => $request, 
//             'macrolocalita_ids' => $macrolocalita_ids, 
//             'trattamenti_keys' => $trattamenti_keys, 
//             'categorie_keys' => $categorie_keys, 
//             'bonus_vacanza_keys' => $bonus_vacanza_keys, 
//             "annuale_keys" => $annuale_keys, 
//             "gruppo_servizi_keys" => $gruppo_servizi_keys, 
//             "cancellazione_gratuita_keys" => $cancellazione_gratuita_keys,
//             "rating_keys" => $rating_keys
//         ]
//     );

// }); 

// ------------------------------------------------------------------------ //
// 24. TUTTO IL RESTO
// ------------------------------------------------------------------------ //

/*
Route::any('{uri}', function($uri)
{

    $params = checkPageUrl($uri);
    
    switch(true):
        
        case is_null($params):
            abort("404");
        break;

        case $params->template == "statica":
            $controller = \App()->make('App\Http\Controllers\CmsPagineController');
            return $controller->callAction("index", array('uri' => $uri));
        break;

        case $params->template == "localita":
            $request = Request::capture();
            $controller = \App()->make('App\Http\Controllers\CmsListingController');
            return $controller->callAction("localita", array('uri' => $uri, "request" => $request));
        break;

        case $params->template == "listing":
            $request = Request::capture();
            $controller = \App()->make('App\Http\Controllers\CmsListingController');
            return $controller->callAction("listing", array('uri' => $uri, "request" => $request));
        break;

    endswitch;

})->where('uri', '([A-z\d\-\/\_\.]+)?');
*/

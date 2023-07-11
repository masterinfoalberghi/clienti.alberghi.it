<div id="green">
    <span id="title-aside">{{ trans('labels.menu_ricerca') }}:</span>
    <h3>{{ trans('labels.menu_cat') }}</h3>
    <ul>
        <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-5-cinque-stelle/riviera-romagnola.php")}}" >{{ trans('labels.rr_cat6') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-4-quattro-stelle/riviera-romagnola.php")}}" >{{ trans('labels.rr_cat5') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-3-tre-stelle-superiore/riviera-romagnola.php")}}" >{{ trans('labels.rr_cat4') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-3-tre-stelle/riviera-romagnola.php")}}" >{{ trans('labels.rr_cat3') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-2-due-stelle/riviera-romagnola.php")}}" >{{ trans('labels.rr_cat2') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-1-una-stella/riviera-romagnola.php")}}" >{{ trans('labels.rr_cat1') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/residence/riviera-romagnola.php")}}" >{{ trans('labels.rr_cat_res') }}</a></li>
    </ul>
    <h3>{{ trans('labels.menu_serv') }}</h3>
    <ul>
        <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-piscina/riviera-romagnola-hotel-piscina.php")}}" >{{ trans('labels.rr_pisc') }}</a></li>
		<li><a href="{{Utility::getUrlWithLang($locale,"/benessere/riviera-romagnola.php")}}" >{{ trans('labels.rr_bene') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/internet-wireless-wifi/riviera-romagnola.php")}}" >{{ trans('labels.rr_wifi') }}</a></li>
        @if ($locale == 'it')
            <li><a href="https://hotelperdisabili.info-alberghi.com" >Hotel per Disabili</a></li>
        @endif
        <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-celiaci/riviera-romagnola.php")}}" >{{ trans('labels.rr_celi') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/animali-ammessi/riviera-romagnola.php")}}" >{{ trans('labels.rr_pet') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/bike-hotel/riviera-romagnola.php")}}">{{ trans('labels.rr_bike') }}</a></li>
    </ul>
    
    @if ($locale == 'it')
    <h3>{{ trans('labels.menu_off') }}</h3>
    <ul>
		<li><a href="{{Utility::getUrlWithLang($locale,"/early-booking/riviera-romagnola.php")}}">Prenota Adesso e Risparmia</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/week-end/riviera-romagnola-week-end.php")}}">Week End</a></li>
		<li><a href="{{Utility::getUrlWithLang($locale,"/offerte-last-minute-settembre/riviera-romagnola.php")}}" >Offerte Settembre</a></li>
		<li><a href="{{Utility::getUrlWithLang($locale,"/offerte-halloween/riviera-romagnola.php")}}" >Offerte Halloween</a></li>
		<li><a href="{{Utility::getUrlWithLang($locale,"/capodanno/riviera-romagnola.php")}}" >Offerte Capodanno</a></li>
		<li><a href="{{Utility::getUrlWithLang($locale,"/offerte-terme/riviera-romagnola.php")}}" >Terme di Rimini</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/offerte-coupon-riviera-romagnola.php")}}" >Coupon Sconto</a></li>            
        <li><a href="https://fierarimini.info-alberghi.com/">Offerte Fiera di Rimini</a></li>
    </ul>
	@endif
	

    <h3>{{ trans('labels.menu_fam') }}</h3>
    <ul>
     @if ($locale == 'it')
    <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-bambini/riviera-romagnola-hotel-bambini.php")}}">Bambini Gratis</a></li>
    @endif
     <li><a href="{{Utility::getUrlWithLang($locale,"/offerte-piano-famiglia/riviera-romagnola.php")}}">{{ trans('labels.rr_off_fam') }}</a></li>
    </ul>
    
    
    <h3>{{ trans('labels.menu_trat') }}</h3>
            <ul>
        <li><a href="{{Utility::getUrlWithLang($locale,"/hotel-all-inclusive/riviera-romagnola.php")}}" >{{ trans('labels.rr_ai') }}</a></li>
        @if ($locale == 'it')
            <li><a href="{{Utility::getUrlWithLang($locale,"/pensione-completa/riviera-romagnola.php")}}" >Pensione Completa</a></li>
            <li><a href="{{Utility::getUrlWithLang($locale,"/mezza-pensione/riviera-romagnola.php")}}" >Mezza Pensione</a></li>
        @endif
        <li><a href="{{Utility::getUrlWithLang($locale,"/bed-and-breakfast/riviera-romagnola.php")}}" >{{ trans('labels.rr_bb') }}</a></li>
        @if ($locale == 'it')
            <li><a href="{{Utility::getUrlWithLang($locale,"/solo-dormire/riviera-romagnola.php")}}">Solo Dormire</a></li>
        @endif
    </ul>

        <h3>{{ trans('labels.menu_par') }}</h3>
    <ul>
        <li><a href="{{Utility::getUrlWithLang($locale,"/aquafan.php")}}">{{ trans('labels.rr_aqua_ricc') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/acquario-cattolica.php")}}">{{ trans('labels.rr_aqua_cat') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/delfinario-rimini.php")}}" >{{ trans('labels.rr_delf') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/mirabilandia-ravenna.php")}}">{{ trans('labels.rr_mira') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/fiabilandia-rimini.php")}}">{{ trans('labels.rr_fiabi') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/italia-in-miniatura.php")}}">{{ trans('labels.rr_ita') }}</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/oltremare-riccione.php")}}">{{ trans('labels.rr_oltre') }}</a></li>
    </ul>
    
    <a class="ico lastMinute" href="{{Utility::getUrlWithLang($locale,"/italia/hotel_riviera_romagnola/last_minute.html")}}">{{ trans('labels.menu_last') }}</a>
    <a class="ico offerteSpeciali" href="{{Utility::getUrlWithLang($locale,"/italia/hotel_riviera_romagnola/offerte_speciali.html")}}">{{ trans('labels.menu_offer') }}</a>
    @include('menu.sx_mail_multipla')
    @include('menu.sx_ricerca_avanzata')

</div>


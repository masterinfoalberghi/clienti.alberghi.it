
    <ul class="menu-tematico">
    
        <li><span>{{ trans('labels.menu_cat') }}</span></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-5-cinque-stelle/riviera-romagnola.php')}}" >{{ trans('labels.rr_cat6') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-4-quattro-stelle/riviera-romagnola.php')}}" >{{ trans('labels.rr_cat5') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-3-tre-stelle-superiore/riviera-romagnola.php')}}" >{{ trans('labels.rr_cat4') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-3-tre-stelle/riviera-romagnola.php')}}" >{{ trans('labels.rr_cat3') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-2-due-stelle/riviera-romagnola.php')}}" >{{ trans('labels.rr_cat2') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-1-una-stella/riviera-romagnola.php')}}" >{{ trans('labels.rr_cat1') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'residence/riviera-romagnola.php')}}" >{{ trans('labels.rr_cat_res') }}</a></li>

        <li><span>{{ trans('labels.menu_serv') }}</span></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-piscina/riviera-romagnola-hotel-piscina.php')}}" >{{ trans('labels.rr_pisc') }}</a></li>
		<li><a href="{{url(Utility::getLocaleUrl($locale).'benessere/riviera-romagnola.php')}}" >{{ trans('labels.rr_bene') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'internet-wireless-wifi/riviera-romagnola.php')}}" >{{ trans('labels.rr_wifi') }}</a></li>
        @if ($locale == 'it')
            <li><a href="http://hotelperdisabili.info-alberghi.com" >Hotel per Disabili</a></li>
        @endif
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-celiaci/riviera-romagnola.php')}}" >{{ trans('labels.rr_celi') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'animali-ammessi/riviera-romagnola.php')}}" >{{ trans('labels.rr_pet') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'bike-hotel/riviera-romagnola.php')}}">{{ trans('labels.rr_bike') }}</a></li>
        
        @if ($locale == 'it')
        <li><span>{{ trans('labels.menu_off') }}</span></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'early-booking/riviera-romagnola.php')}}">Prenota Adesso e Risparmia</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'week-end/riviera-romagnola-week-end.php')}}">Week End</a></li>       
        <li><a href="{{url(Utility::getLocaleUrl($locale).'offerte-last-minute-settembre/riviera-romagnola.php')}}" >Offerte Settembre</a></li>
        <li><a href="{{Utility::getUrlWithLang($locale,"/offerte-halloween/riviera-romagnola.php")}}" >Offerte Halloween</a></li>
		<li><a href="{{Utility::getUrlWithLang($locale,"/capodanno/riviera-romagnola.php")}}" >Offerte Capodanno</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'offerte-terme/riviera-romagnola.php')}}" >Terme di Rimini</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'offerte-coupon-riviera-romagnola.php')}}" >Coupon Sconto</a></li>
        <li><a href="http://fierarimini.info-alberghi.com/">Offerte Fiera di Rimini</a></li>
        @endif 
        
        @if ($locale == 'it')
        <li><span>{{ trans('labels.menu_fam') }}</span></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-bambini/riviera-romagnola-hotel-bambini.php')}}">Bambini Gratis</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'offerte-piano-famiglia/riviera-romagnola.php')}}">{{ trans('labels.rr_off_fam') }}</a></li>
        @endif 
        
        <li><span>{{ trans('labels.menu_trat') }}</span></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'hotel-all-inclusive/riviera-romagnola.php')}}" >{{ trans('labels.rr_ai') }}</a></li>
        
        @if ($locale == 'it')
            <li><a href="{{url(Utility::getLocaleUrl($locale).'pensione-completa/riviera-romagnola.php')}}" >Pensione Completa</a></li>
            <li><a href="{{url(Utility::getLocaleUrl($locale).'mezza-pensione/riviera-romagnola.php')}}" >Mezza Pensione</a></li>
        @endif
        
        <li><a href="{{url(Utility::getLocaleUrl($locale).'bed-and-breakfast/riviera-romagnola.php')}}" >{{ trans('labels.rr_bb') }}</a></li>
        
        @if ($locale == 'it')
            <li><a href="{{url(Utility::getLocaleUrl($locale).'solo-dormire/riviera-romagnola.php')}}" >Solo Dormire</a></li>
        @endif
        
        <li><span>{{ trans('labels.menu_par') }}</span></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'aquafan.php')}}">{{ trans('labels.rr_aqua_ricc') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'acquario-cattolica.php')}}">{{ trans('labels.rr_aqua_cat') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'delfinario-rimini.php')}}" >{{ trans('labels.rr_delf') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'mirabilandia-ravenna.php')}}">{{ trans('labels.rr_mira') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'fiabilandia-rimini.php')}}">{{ trans('labels.rr_fiabi') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'italia-in-miniatura.php')}}">{{ trans('labels.rr_ita') }}</a></li>
        <li><a href="{{url(Utility::getLocaleUrl($locale).'oltremare-riccione.php')}}">{{ trans('labels.rr_oltre') }}</a></li>
        
        <li><a class="ico lastMinute" href="{{url(Utility::getLocaleUrl($locale).'italia/hotel_riviera_romagnola/last_minute.html')}}">{{ trans('labels.menu_last') }}</a></li>
        <li><a class="ico offerteSpeciali" href="{{url(Utility::getLocaleUrl($locale).'italia/hotel_riviera_romagnola/offerte_speciali.html')}}">{{ trans('labels.menu_offer') }}</a></li>
        
    </ul>

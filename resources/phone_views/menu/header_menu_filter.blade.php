@if (!isset($count) && isset($clienti))
    @php $count = count($clienti); @endphp
@endif

<div class="fixed">

    <a class="backbutton" href="/{{$backurl->uri}}"><img src="{{ Utility::asset('/mobile/img/back.svg') }}"></a>

    <div id="tab-bar">

        <a href="{{Utility::getUrlWithLang($locale, '/')}}" class="logomobile" >
            <img src="{{ Utility::asset('/mobile/img/logo-ia.svg') }}" alt="Info Alberghi srl" title="Info Alberghi srl">
        </a>

    </div>

    @include("widget.lang")

    <a class="menubutton" href="#" ><img src="{{ Utility::asset('images/menu.png') }}"></a>

</div>

@if (isset($clienti) && count($clienti))

    @include('cms_filter_listing.filtri')

@endif

<nav id="menu-mobile" role="navigation">
    <div class="menu-mobile">
        <div class="menu-mobile-inside">

            <h1 class="hidden" >{{trans("title.navigazione")}}</h1>

            <div class="breadcrumb">

                <span><img src="{{Utility::asset('/mobile/img/loc-listing.svg')}}"   />{{ trans("listing.sel_localita")}}</span>
                <a href="#" id="cambialocalita" data-txt="{!!trans("labels.cambia_localita")!!}" class="small button cyan">{!!trans("labels.cambia_localita")!!}</a>
                <div class="clear"></div>

            </div>
            
            <div class="menu-localita">

                {!! Utility::getMenuLocalitaMobile($cms_pagina) !!}
                <div class="clear"></div>

            </div>
            
            {!! $menu_tematico !!}
            <div class="clear"></div>
        </div>
    </div>
</nav>


<nav class="sticker filters-container"  data-offset="42">
    <div class="col-xs-3 filters-map">
        
        @php
            /**
             * $img = 'https://maps.googleapis.com/maps/api/staticmap';
             * $center 		= '?center=' . $google_maps["coords"]["lat"] . ',' . $google_maps["coords"]["long"];
             * $zoom 			= '&zoom=13&size=300x130';
             * $styler 	 	= '&style=element:labels|visibility:off';
             * $styler    	   .= '&style=element:geometry.stroke|visibility:off';
             * $key 			= '&key=' . Config::get("google.googlekey");
             * $link 			= $img . $center . $zoom . $styler . $key;
             */
            $link = Utility::asset("images/mappa-listing.png");
        @endphp

        <a href="#" class="mappa margin-bottom-3 margin-right-3" style="background-image: url('{{$link}}')" >
            <span class="btn btn-verde filters-map-button"><i class="icon-location"></i> {{trans('listing.vedi_mappa')}}</span>
        </a>

        @if (isset($cms_pagina->template))
            {!! Form::open(['url' => url(Utility::getUrlLocaleFromAppLocale($locale).'/mappa-ricerca'), 'method' => "get", 'id' => 'filter-map', 'target'=> '_self']) !!}

                {!! Form::hidden('lat',$google_maps["coords"]["lat"], ['id' => 'lat']) !!}
                {!! Form::hidden('long',$google_maps["coords"]["long"], ['id' => 'long']) !!}

                @if ($cms_pagina->template == 'localita')
                    {!! Form::hidden('macrolocalita_id',$google_maps['macrolocalita_id'], ['id' => 'macrolocalita_id']) !!}
                    {!! Form::hidden('localita_id',$google_maps['localita_id'], ['id' => 'localita_id']) !!}
                    {!! Form::hidden('ancora',$google_maps['ancora']) !!}
                @else
                    {!! Form::hidden('ancora',null, ['id' => 'ancora']) !!}
                @endif

                {!! Form::hidden('cms_pagina_id',null, ['id' => 'cms_pagina_id']) !!}
                {!! Form::hidden('cms_pagina_uri',null, ['id' => 'cms_pagina_uri']) !!}

                {!! Form::hidden('macro_localita_seo',$macro_localita_seo, ['id' => 'macro_localita_seo']) !!}
                {!! Form::hidden('localita_seo',$localita_seo, ['id' => 'localita_seo']) !!}
                {!! Form::hidden('locale',$locale, ['id' => 'locale']) !!}

            {!! Form::close() !!}
        @endif

        <script type="text/javascript">

            /*SETTO QUI QUESTA VARIABILE E POI nella funzione listing.js la RIassegno al campo hidden perchè altrimenti VA IN CACHE !!!*/

            @if (isset($cms_pagina))

                var __cms_pagina_id = "{{$cms_pagina->id}}";
                var __macro_localita_seo = "{{$macro_localita_seo}}";
                var __localita_seo = "{{$localita_seo}}";
                var __page_template = "{{$cms_pagina->template}}";
                var __cms_pagina_uri = "{{$cms_pagina->uri}}";

                var __ancora = "{{$google_maps['ancora']}}";
                var __lat = "{{$google_maps["coords"]["lat"]}}";
                var __long = "{{$google_maps["coords"]["long"]}}";

                @if (isset($google_maps['macrolocalita_id']))
                    if (__page_template == 'localita') {
                        var __macrolocalita_id = "{{$google_maps['macrolocalita_id']}}";
                    }
                @endif

                @if (isset($google_maps['localita_id']))
                    if (__page_template == 'localita') {
                        var __localita_id = "{{$google_maps['localita_id']}}";
                    }
                @endif

            @else

                // Riviera romagnola
                var __cms_pagina_id = "547";
                var __macro_localita_seo = "Riviera Romagnola";
                var __localita_seo = "Riviera Romagnola";
                var __ancora = "Riviera Romagnola";
                var __lat = "44.059959";
                var __long = "12.573509";
                var __macrolocalita_id = "11";
                var __localita_id = "0";
                var __page_template = "filter";
                var __cms_pagina_uri = "italia/hotel_riviera_romagnola.html";

            @endif

        </script>

    </div>

    <div class="col-xs-9">
        <div class="filters">
            <div class="filters-content">
                <div class="col-sm-6 col-xs-6 filter-container">

                    {{trans("listing.bottoni_ordina")}}:
                    <span class="sep"><a class="filter order @if($order == "nome") selected @endif name_order" data-href="nome" data-item="{{trans("listing.filtri_nome")}}" href="?order=nome">{{trans("listing.filtri_nome")}}</a></span>
                    <span class="sep"><a class="filter order tooltip-categories @if($order == "categoria_desc" || $order == "categoria_asc") selected @endif categoria_desc" data-href="categoria_desc" data-item="{!! trans("listing.filtri_stelle") !!}" href="?order=0" data-tooltip-id="tooltip-categories" >{!! trans("listing.filtri_stelle") !!}<i class="icon-down-open"></i></a></span>

                    {{-- le label non sono prezzo min/max MA sconto min/max --}}
                    @if (!empty($cms_pagina->listing_offerta_prenota_prima))
                        <a class="filter order tooltip-discount @if($order == "prezzo_min" || $order == "prezzo_max") selected @endif discount_min" data-href="prezzo_min" data-item="{{trans("listing.filtri_sconto")}}" href="?order=0" data-tooltip-id="tooltip-discount" >{{trans("listing.filtri_sconto")}}
                            <i class="icon-down-open"></i>
                        </a>
                    @else
                        <a class="filter order tooltip-price @if($order == "prezzo_min" || $order == "prezzo_max") selected @endif price_min" data-href="prezzo_min" data-item="{{trans("listing.filtri_prezzo")}}" href="?order=0" data-tooltip-id="tooltip-price" >{{trans("listing.filtri_prezzo")}}
                            <i class="icon-down-open"></i>
                        </a>
                    @endif

                    {{-- <a class="filter order tooltip-aperture @if($filter != "") selected @endif filtri_apertura" data-href="filtri_apertura" data-item="{!! trans("listing.filtri_apertura") !!}" href="?filter=0" data-tooltip-id="tooltip-aperture">{!! trans("listing.filtri_apertura") !!}<i class="icon-down-open"></i></a> --}}

                    <div id='tooltip-categories' class="tooltip-filters" style='display:none'>
                        <ul>
                            <li><a class="sublist" data-href="categoria_asc"  href="?order=categoria_asc" onclick="category_filter(this); return false;">{!! trans("listing.filtri_1_5") !!}</a></li>
                            <li><a class="sublist" data-href="categoria_desc" href="?order=categoria_desc" onclick="category_filter(this); return false;">{!! trans("listing.filtri_5_1") !!}</a></li>
                        </ul>
                    </div>

                    <div id='tooltip-price' class="tooltip-filters" style='display:none'>
                        <ul>
                            <li><a class="sublist" data-href="prezzo_min"  href="?order=prezzo_min" onclick="price_filter(this); return false;">{!! trans("listing.filtri_prezzo_min") !!}</a></li>
                            <li><a class="sublist" data-href="prezzo_max" href="?order=prezzo_max" onclick="price_filter(this); return false;">{!! trans("listing.filtri_prezzo_max") !!}</a></li>
                        </ul>
                    </div>

                    <div id='tooltip-discount' class="tooltip-filters" style='display:none'>
                        <ul>
                            <li><a class="sublist" data-href="prezzo_min"  href="?order=prezzo_min" onclick="discount_filter(this); return false;">{!! trans("listing.filtri_sconto_min") !!}</a></li>
                            <li><a class="sublist" data-href="prezzo_max" href="?order=prezzo_max" onclick="discount_filter(this); return false;">{!! trans("listing.filtri_sconto_max") !!}</a></li>
                        </ul>
                    </div>

                    <div id='tooltip-aperture' class="tooltip-filters" style='display:none'>
                        <ul>
                            <li><a class="sublist" data-href="annuale"  href="?filter=annuale" onclick="apertura_filter(this); return false;">{!! trans("listing.apertura_annuale") !!}</a></li>
                            <li><a class="sublist" data-href="aperto_capodanno" href="?filter=aperto_capodanno" onclick="apertura_filter(this); return false;">{!! trans("listing.apertura_capodanno") !!}</a></li>
                            <li><a class="sublist" data-href="aperto_pasqua" href="?filter=aperto_pasqua" onclick="apertura_filter(this); return false;">{!! trans("listing.apertura_pasqua") !!}</a></li>
                            <?php /*<li><a class="sublist" data-href="dopo_10_settembre" href="?filter=dopo_10_settembre" onclick="apertura_filter(this); return false;">{!! trans("listing.apertura_10_settembre") !!}</a></li>*/ ?>
                            <li><a class="sublist" data-href="aperto_eventi_e_fiere" href="?filter=aperto_eventi_e_fiere" onclick="apertura_filter(this); return false;">{!! trans("listing.apertura_eventi_e_fiere") !!}</a></li>

                        </ul>
                    </div>

                </div>

                <div class="col-sm-6 col-xs-6 wish">

                    <?php if (!isset($id_to_send)) $id_to_send = ""; ?>

                    {!! Form::open([ 'url' =>  Utility::getUrlWithLang($locale,'/wishlist'), 'style'=>'float:right; margin-left:15px;','onSubmit'=>'return window.whenChecked()']) !!}

                        {!! Form::hidden('ids_send_mail', $id_to_send ,['id'=>'ids_send_mail']) !!}
                        @if (isset($actual_link))
                            {!! Form::hidden('referer', $actual_link) !!}
                        @endif
                        {!! Form::hidden('wishlist',1) !!}

                        <button  type="submit" id="WishlistSubmit" class="btn-arancio btn tooltip" title="{{trans("listing.scrivi_tutti")}}"><i class='icon-mail-alt'></i> {{trans('listing.scrivi_tutti_short')}}</button>

                    {!! Form::close() !!}

                    {!! Form::open([ 'url' => Utility::getUrlWithLang($locale,'/compare'), 'style'=>'float:right', 'class'=>' hidden-sm hidden-xs' , 'onSubmit'=>'return window.whenChecked()', 'method' => 'get']) !!}

                        {!! Form::hidden('ids_send_mail',$id_to_send,['id'=>'ids_send_mail_compare']) !!}

                        @if (!isset($cms_pagina) || is_null($cms_pagina->ancora))
                            {!! Form::hidden('title', 'precedente' ) !!}
                        @else
                            {!! Form::hidden('title', $cms_pagina->ancora ) !!}
                        @endif

                        <button type="submit" id="CompareSubmit" class="btn-verde btn tooltip" title="{{trans("listing.confronta_desc")}}"><i class='icon-th-large'></i> {{trans('listing.confronta')}}</button>

                    {!! Form::close() !!}
                
                    <a class="btn btn-viola-chiaro venobox" data-vbtype="iframe" style="float:right; margin-right:15px;" href="{{Utility::filterUriToParams(url()->full())}}"><i class='icon-sliders'></i> {{trans("labels.filter")}}</a>
                
                </div>

                <div class="clearfix"></div>

            </div>
        </div><div class="clearfix"></div>

    </div>

<div class="clearfix"></div>
</nav>


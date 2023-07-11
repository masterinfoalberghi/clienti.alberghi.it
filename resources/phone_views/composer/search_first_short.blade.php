<div id="search_first_short" class="container">
    <div class="row">
        <a href="/" >
        
            <span class="col-sm-12" >
                <span class="inside" style="display:block;">        
                    @php

                        $localita    = \App\Macrolocalita::find($prefill["macrolocalita"]);

                        if ($cms_pagina->listing_macrolocalita_id == 0)
                            $cms_pagina->listing_macrolocalita_id = 11;

                        $localita_db = \App\Macrolocalita::find($cms_pagina->listing_macrolocalita_id);

                        if(!$localita || $localita->id != $localita_db->id)
                            $localita = $localita_db; 

                        $dates       = Utility::formatRangeDateWithYear($prefill["rooms"][0]["checkin"], $prefill["rooms"][0]["checkout"]);
                        $night       = Utility::night($prefill["rooms"][0]["checkin"],$prefill["rooms"][0]["checkout"]);
                        $label_night = Lang::get("labels.notte");

                        if ($night > 1)
                            $label_night = Lang::get("labels.notti");

                        $adult = $prefill["rooms"][0]["adult"];
                        $children = $prefill["rooms"][0]["children"];
                        $age_children = Utility::purgeMenoUno($prefill["rooms"][0]["age_children"], $children);

                        $rooms = count($prefill["rooms"]);

                    @endphp
                    {{$localita->nome}}, {{$night}} {{$label_night}} ({{$dates[0]}} &rarr; {{$dates[1]}})
                    
                        <i class="icon-pencil"></i>
                
                </span>
            </span>
            <span class="col-sm-12">
                <span class="inside bottom" style="display:block;">      
                    {{$adult}} Adulti, {{$children}} Bambini @if($age_children)({{$age_children}} anni)@endif, {{$rooms}} Camera
                </span>
            </span>
        </a>
    </div>
</div>
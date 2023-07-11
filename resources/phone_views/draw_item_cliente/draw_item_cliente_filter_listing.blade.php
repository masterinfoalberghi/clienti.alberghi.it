@php
    $tipoContenuto = "itemFilterListing";
    $n_foto = $cliente->immagini_gallery_count;
@endphp

<article data-id="{{$cliente->id}}" data-url="{{$url}}" data-categoria="{{$cliente->categoria_id}}" data-prezzo="{{$cliente->prezzo_min}}" data-lat="{{$cliente->mappa_latitudine}}" data-lon="{{$cliente->mappa_longitudine}}" class="draw_item_cliente_vetrina_listing link_item_slot @if(isset($class_item)) {{$class_item}} @endif ">

@include("widget.item-figure", ["cliente" => $cliente, 'image_listing' => $image_listing ])

<div class="col-text-draw">
    
    @include("widget.item-title", 	["cliente" => $cliente, "locale" => $locale, "url" => $url])
    @include("widget.item-favourite")
    
    @include("widget.item-address", ["cliente" => $cliente])
    @include("chiuso")
    @include("widget.bonus-vacanze")
    @include("widget.item-review")
    @include("widget.item-covid")
    @include('composer.puntiDiForza')
    @include('widget.item-distance')
    @include("widget.item-price", 	["cliente" => $cliente])
    
</div>
<div class="clear"></div>

</article>

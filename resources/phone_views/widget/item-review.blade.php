@if($cliente->enabled_rating_ia && $cliente->rating_ia >= 6)
<div class="rating_container">
    <span class="rating_value"><b>{{$cliente->rating_ia}}</b></span>
    <span class="rating_value_label">
        @if($cliente->rating_ia >= 6 && $cliente->rating_ia < 7)
            <b>{{__("listing.buono")}}!&nbsp;&nbsp;</b>
        @elseif($cliente->rating_ia >= 7 && $cliente->rating_ia < 8)
            <b>{{__("listing.ottimo")}}!&nbsp;&nbsp;</b>
        @elseif($cliente->rating_ia >= 8 && $cliente->rating_ia < 9)
            <b>{{__("listing.eccezionale")}}!&nbsp;&nbsp;</b>
        @elseif($cliente->rating_ia >= 9 && $cliente->rating_ia < 10)
            <b>{{__("listing.top")}}!&nbsp;&nbsp;</b>
        @endif
      
        <small>{{sprintf(__("listing.reviews"),$cliente->n_rating_ia)}}</small> <a href="#rating" class="icon-info-circled venobox-rating tooltip-rating " data-vbtype="inline"></a>
    </span>
</div>
@endif  
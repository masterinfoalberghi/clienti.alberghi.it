<span class="item-listing-favorites @if ($cliente->isFavourite()) preferito @endif">
											
	<a href="#" id="cuore_{{$cliente->id}}" title="{{trans("labels.add_preferiti")}}" data-id="{{$cliente->id}}" class="tooltip hearth attiva_preferito @if ($cliente->isFavourite()) disattiva_preferito @endif">
		<i class="@if (!$cliente->isFavourite()) icon-heart-empty @else icon-heart @endif"></i>
	</a>
	
</span>
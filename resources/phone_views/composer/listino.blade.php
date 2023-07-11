<?php

// visualizzo il listino standard
// @parameters : $listini (array listini), titolo (nel composer viene costruito aggiungendo anno_listino cioè anno ricavato dagli anni dei periodi)

$titolo_scritto = false;



?>
@if (count($listini))
<div class="row" id="listino">
	
	@foreach ($listini as $listino)
		
		

			<?php if (strtotime($listino->periodo_al) > strtotime("now")): 
			
			if (!$titolo_scritto): ?>
			<div class="col-xs-12">
				<h2>{{$titolo}}</h2>
			</div>
			<?php endif; $titolo_scritto = true; ?>
	
			<div class="col-xs-12 listino_items">
				<div class="listino_periodo">{{Utility::myFormatLocalized($listino->periodo_dal, '%d %B', $locale)}} / {{Utility::myFormatLocalized($listino->periodo_al, '%d %B', $locale)}}</div>
			
				@if ($listino->prezzo_ai["prezzo"] != "/")<div class="listino_item"><span>{{trans('hotel.trattamento_ai')}}</span><span class="price">{!!$listino->getPrezzoeNumeroOfferte($listino->prezzo_ai)!!}</span><div class="clear"></div></div>@endif
			   	@if ($listino->prezzo_pc["prezzo"] != "/")<div class="listino_item"><span>{{trans('hotel.trattamento_pc')}}</span><span class="price">{!!$listino->getPrezzoeNumeroOfferte($listino->prezzo_pc)!!}</span><div class="clear"></div></div>@endif
			   	@if ($listino->prezzo_mp["prezzo"] != "/")<div class="listino_item"><span>{{trans('hotel.trattamento_mp')}}</span><span class="price">{!!$listino->getPrezzoeNumeroOfferte($listino->prezzo_mp)!!}</span><div class="clear"></div></div>@endif
			   	@if ($listino->prezzo_bb["prezzo"] != "/")<div class="listino_item"><span>{{trans('hotel.trattamento_bb')}}</span><span class="price">{!!$listino->getPrezzoeNumeroOfferte($listino->prezzo_bb)!!}</span><div class="clear"></div></div>@endif
			   	@if ($listino->prezzo_sd["prezzo"] != "/")<div class="listino_item"><span>{{trans('hotel.trattamento_sd')}}</span><span class="price">{!!$listino->getPrezzoeNumeroOfferte($listino->prezzo_sd)!!}</span><div class="clear"></div></div>@endif
			   

			</div>
		
		<?php endif; ?>
		
	@endforeach

</div>
@endif





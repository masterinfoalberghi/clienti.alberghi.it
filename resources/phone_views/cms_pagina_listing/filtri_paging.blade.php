<div class="container filters">
	
	<div class="row">
	
		<div class="col-xs-3 mappa filter">
			
			<img src="{{Utility::asset('images/mappa.png')}}" /><p>{{trans("listing.vedi_mappa")}}</p>
						
		</div>
        
        <div class="col-xs-3 select-filter filter" data-uri="{{Utility::filterUriToParams(url()->full())}}">
			<img src="{{ Utility::asset('images/filter.png') }}" /><p>{{trans("labels.filter")}}</p>
		</div>
        
		<div class="col-xs-3 filter ordine">
		
			<img src="{{Utility::asset('images/sort.png')}}" /><p>{{trans("listing.bottoni_ordina")}}</p>
			
			<input id="order_text" name="order_text" type="hidden" value="{{trans("listing.bottoni_ordina")}}" />
					
        </div>
		        
        <div class="order_select">
			<ul>
				<li><a href="?order=nome" 			data-val="nome" 		 	>{{trans("listing.filtri_nome")}}</a></li>
				<li><a href="?order=categoria_asc" 	data-val="categoria_asc" 	>{!!trans("listing.filtri_1_5")!!}</a></li>
				<li><a href="?order=categoria_desc" data-val="categoria_desc"	>{!!trans("listing.filtri_5_1")!!}</a></li>
				<li><a href="?order=prezzo_min" 	data-val="prezzo_min" 	 	>{{trans("listing.filtri_prezzo_min")}}</a></li>
			</ul>
		</div>

		
		<div class="col-xs-3 filter email">
			<img src="{{Utility::asset('images/mail.png')}}" /><p>{{trans("hotel.scrivi")}}</p>
			<div class="badge red" style="display:none;">0</div>
			{!! Form::open(['id'=>'emailmultiplamobileforms', 'url' => url(Utility::getLocaleUrl($locale).'mail-multipla')]) !!}
			{!! Form::hidden('locale',$locale) !!}
			{!! Form::hidden('ids_send_mail',  $ids_send_mail)!!}
			{!! Form::hidden('no_execute_prefill_cookie', true)!!}
			{!! Form::hidden('cms_pagina_id', $cms_pagina->id)!!}	
			{!! Form::hidden('referer', $actual_link)!!}
			{!! Form::hidden('selezione_localita', $selezione_localita)!!}	
			{!!Form::close()!!}
		</div>
		
	</div>
	
</div>


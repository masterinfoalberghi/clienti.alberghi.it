
	@if (($order != "0" && $order != "") || ($filter != "0" && $filter != ""))
		<div class="alert alert-listing">

			{{trans("listing.order")}}

			@if ($order != "0" && $order != "")

				@if($order == "nome")
					<b>{{trans("listing.filtri_alphabetico")}}</b>
				@else
					
					@if (!empty($cms_pagina->listing_offerta_prenota_prima))
						@if ($order == 'prezzo_min')
							<b>{{trans("listing.filtri_sconto_min")}}</b>
						@elseif($order == 'prezzo_max')
							<b>{{trans("listing.filtri_sconto_max")}}</b>
						@else
						<b>{{trans("listing.filtri_" .$order)}}</b>

						@endif
					@else
						<b>{{trans("listing.filtri_" .$order)}}</b>
					@endif
				
				@endif

			@endif

			@if ($filter != "0" && $filter != "")
				
				@php $filter = str_replace("aperto", "apertura", $filter) @endphp
				<b>{{trans("listing." .$filter)}}</b>

			@endif

			

		</div>
	@endif
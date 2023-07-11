<nav id="followed-1" class="sticker filters-container"  data-offset="59">
			   
    <div class="col-md-3 hidden-sm filters-map">
	    
	    @php  

			$img = 'https://maps.googleapis.com/maps/api/staticmap';
			
			$center 		= '?center=' . $google_maps["coords"]["lat"] . ',' . $google_maps["coords"]["long"];
			$zoom 			= '&zoom=13&size=300x130';
			$styler 	 	= '&style=element:labels|visibility:off';
			$styler    	   .= '&style=element:geometry.stroke|visibility:off';		
			$key 			= '&key=' . Config::get("google.googlekey");
			
			$link 			= $img . $center . $zoom . $styler . $key;
		
		@endphp
		
	    <a href="/mappa/{{$google_maps["coords"]["lat"]}},{{$google_maps["coords"]["long"]}}" data-vbtype="iframe" class="venobox mappa margin-bottom-3 margin-right-3" style="background-image: url('{{$link}}')">
			<span class="btn  btn-verde filters-map-button"><i class="icon-location"></i> {{trans('listing.vedi_mappa')}}</span>
		</a>
		
    </div>
   
    <div class="col-md-9 col-sm-12">

		<div class="filters">
			
			<div class="filters-content">
				
				<div class="col-sm-8 filter-container">
					
					
					<span class="sep"><a class="filter order @if($order == "nome") selected @endif name_order" data-href="nome" href="?order=nome">{{trans("listing.filtri_nome")}}</a></span>
					<span class="sep"><a class="filter order tooltip-categories @if($order == "categoria_desc" || $order == "categoria_asc") selected @endif categoria_desc" data-href="categoria_desc" data-tooltip-id="tooltip-categories" href="?order=categoria_desc">{!! trans("listing.filtri_1_5") !!}<i class="icon-down-open"></i></a></span>
                    <span class="sep"><a class="filter order tooltip-aperture @if($filter != "") selected @endif filtri_apertura" data-href="filtri_apertura" data-tooltip-id="tooltip-aperture" href="?filter=0">{!! trans("listing.filtri_apertura") !!}<i class="icon-down-open"></i></a></span>
                    
					<a class="filter order tooltip-price @if($order == "prezzo_min" || $order == "prezzo_max") selected @endif price_min" data-href="prezzo_min" data-tooltip-id="tooltip-price" href="?order=prezzo_min">{{trans("listing.filtri_prezzo_min")}}<i class="icon-down-open"></i></a>
					
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

					<div id='tooltip-aperture' class="tooltip-filters" style='display:none'>
						<ul>
							<li><a class="sublist" data-href="annuale"  href="?filter=annuale" onclick="apertura_filter(this); return false;">{!! trans("listing.apertura_annuale") !!}</a></li>
							<li><a class="sublist" data-href="aperto_capodanno" href="?filter=aperto_capodanno" onclick="apertura_filter(this); return false;">{!! trans("listing.apertura_capodanno") !!}</a></li>
							<li><a class="sublist" data-href="aperto_pasqua" href="?filter=aperto_pasqua" onclick="apertura_filter(this); return false;">{!! trans("listing.apertura_pasqua") !!}</a></li>
							<?php /*<li><a class="sublist" data-href="dopo_10_settembre" href="?apertura=dopo_10_settembre" onclick="apertura_filter(this); return false;">{!! trans("listing.apertura_10_settembre") !!}</a></li>*/ ?>
							<li><a class="sublist" data-href="aperto_eventi_e_fiere" href="?filter=aperto_eventi_e_fiere" onclick="apertura_filter(this); return false;">{!! trans("listing.aperto_eventi_e_fiere") !!}</a></li>
						</ul>
					</div>
					
				</div>
				
				<div class="col-sm-4 wish">
																
					<?php if (!isset($id_to_send)) $id_to_send = ""; ?>
					
					{!! Form::open([ 'url' => '/wishlist', 'style'=>'float:right;','onSubmit'=>'return window.whenChecked()']) !!}
					
						{!! Form::hidden('ids_send_mail',$id_to_send,['id'=>'ids_send_mail']) !!}
						{!! Form::hidden('wishlist',1) !!}
						{!! Form::hidden('no_execute_prefill_cookie',1) !!}
						
						<button  type="submit" id="WishlistSubmit" class="btn-arancio btn "><i class='icon-mail-alt'></i> {{trans('listing.scrivi_email')}}</button>
						
					{!! Form::close() !!}
                    {{--
                    <a class="btn btn-viola-chiaro venobox" data-vbtype="iframe" style="float:right; margin-right:5px;" href="{{Utility::filterUriToParams(url()->full())}}"><i class='icon-sliders'></i> {{trans("labels.filter")}}</a>
					--}}

				</div>
		
				<div class="clearfix"></div>
				
			</div>
		</div><div class="clearfix"></div>

  	</div>	
  	
  	<div class="clearfix"></div>
			   
 </nav>


@if (count($hotel_simili))
	
	<div class="container padding-top">
    
	    	
	    <section id="related">
			
			<div class="row">    
			    <header>
			        <h2 class="content-section">{{ trans('hotel.altri_hotel_simili_1') }} <b>{{ $causale_hotel_simili }}</b> {{ trans('hotel.altri_hotel_simili_2') }}</h2>
			    </header>
			</div>
		    
		    <div class="row"> 
			    	
			    <?php $c=0; ?>	    
		        @foreach ($hotel_simili as $h)
			    
				    <article class="box">
					
						<div class="col-sm-4 click_all">
							<div class="@if ($c<2) margin-right-6 @endif @if ($c>0) margin-left-6 @endif margin-bottom-6 margin-top-6 ">
								<div class="box_background padding-2" style="background-image: url('{{Utility::asset($h->getListingImg("360x200"))}}');">
									<div class="pellicola">
										
										<header class="box_header animation">
											<hgroup>
												
												<a href="{{Utility::getUrlWithLang($locale,'/hotel.php?id='.$h->id)}}">
													<h3 class="box_title">{{{$h->nome}}}&nbsp;<span class="rating">{{{$h->stelle->nome}}}</span></h3>
												</a>
												<address class="item-listing-address " >
													<span class="localita"><i class="icon-location"></i> {{{ $h->localita->nome }}}</span> - <span  class="indirizzo">{{ $h->indirizzo }}</span>
												</address>
											</hgroup>
										</header>
												
										
										@if ($h->prezzo_min > 0 )
										<div class="box_content">
											<small class="padding-bottomx2-6 label">{{ trans('labels.hp_a_partire_da') }}</small>
											<div class="price">{{$h->prezzo_min}} &euro;</div>
										</div>
										@endif
										
									</div>
								</div>
							</div>
						</div>
						
						<?php $c == 2 ? $c=0 : $c++; ?>
						
				</article>
				
				@endforeach
		    </div>
		    	        
	    </selected>
	      
    
    </div>

@endif
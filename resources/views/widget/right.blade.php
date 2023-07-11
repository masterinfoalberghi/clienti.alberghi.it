	
		<div class="main-content-container right">
			
			<div class="container">
				<div class="row">
				
					<article class="main-content">			
						
						<div class="main-content-image-container">
							@if ($immagine)
								<figure>
									<img class="main-content-image" src="{{Utility::asset('/images/pagine/original/' . $immagine)}}" title="{{$h2}}" >
								</figure>
							@endif
						</div>
						
						<div class="col-sm-6"></div>
						
						<div class="col-sm-6 ">
							<div class=" main-content-text">
								
								<header>
									<hgroup>
										<h2>{!! $h2 !!}</h2>
										@if ($h3)<h3>{!! $h3 !!}</h3>@endif
									</hgroup>
								</header>
								
						        {!! $descrizione !!}
						        
							</div>
						
						</div>
						
						<div class="clearfix"></div>
									
					</article>
			
				
				</div>
			</div>
			<div class="clearfix"></div>
			
		</div>
		
		
					
						
						
					
			


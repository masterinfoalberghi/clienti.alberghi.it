<div class="main-content-container over"  style="background-image: url('{{Utility::asset("images/pagine/1920x400/".$immagine)}}')">	
	
	<div class="container">
		<div class="row">
			
			<article class="main-content">			
				
				<div class="main-content-text col-sm-6">
					
					<header>
						<hgroup>
							
							@if (isset($h1))
								<h1>{{$h1}}</h1>
							@else
								<h2>{!! $h2 !!}</h2>
								@if ($h3)<h3>{!! $h3 !!}</h3>@endif
							@endif
							
						</hgroup>
					</header>
					
				@if ($descrizione)	
					<div class="main-content-text-p"> 
					    {!! $descrizione !!}
					</div>
			    @endif
			    
				</div>
		        
			</article>
			
		</div>
	</div>
</div>


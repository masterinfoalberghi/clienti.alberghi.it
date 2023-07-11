 @if (count($briciole))
	<nav id="briciole">
		<div class="container">
			<div class="row">
				<ul class="breadcrumb">

					@foreach ($briciole as $nome => $url)
						@if($nome == "Home")
							<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">

								<a href="{{$url}}" class="home">
									<i class="icon-home" itemprop="name"><span style="display:none;">Home</span></i>
								</a>
								<meta itemprop="position" content="1" />

							</li>
						@else
							<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
							
								<a href="{{$url}}"><span itemprop="name">{{$nome}}</span></a>
								<meta itemprop="position" content="{{ $loop->iteration }}" />
								
							</li>
						@endif
					@endforeach
			
				</ul>
			</div>
		</div>
		
		 <?php /*@if ($cliente->isFavourite())
            	<span class="hearth"><img src="{{ Utility::asset('images/cuore.png') }}" id="cuore_{{$cliente->id}}" data-id="{{$cliente->id}}" class="disattiva_preferito" /></span>
            @else
            	<span class="hearth"><img src="{{ Utility::asset('images/cuore_vuoto.png') }}" id="cuore_vuoto_{{$cliente->id}}" data-id="{{$cliente->id}}" class="attiva_preferito" /></span>
            @endif*/ ?>
	</nav>
@endif

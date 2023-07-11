<div id="followed-2" class="intestazione sticker ">
			
	<div class="container">
		<div class="row">
			
			<header>

                <div class=" col-sm-12">
                    <hgroup data-hotel-edit="{{$cliente->id}}">
                        <h1>{{$cliente->nome}}</h1>
                        <span class="rating">{{$cliente->stelle->nome}}</span>
                        <h2>{{ $cliente->localita->nome }}</h2>
                        @if ($cliente->chiuso_temp)
                                <div class="chiusoTemp">
                                    {{__("labels.chiusura_temporanea")}}
                                </div>
                            @endif
                        <span class="hotel-edit"></span>
                    </hgroup>
                </div>

               

			</header>
			
			<div class="clearfix"></div>
		</div>
	</div>

	
	<div class="menu-secondario-container animation">
	
		<div class="container">
			<div class="row">
				<nav>  	
					
					<header class="hidden col-sm-12">
						<h3>{{trans("title.menu")}}</h3>
					</header>
					
				</nav>	
			</div>
		</div>
	
	</div>

</div>
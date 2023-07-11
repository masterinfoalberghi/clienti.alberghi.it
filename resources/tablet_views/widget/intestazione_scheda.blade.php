<div id="followed-2" class="intestazione sticker ">
	<div class="container">
		<div class="row">
			<header>
				<hgroup data-hotel-edit="{{$cliente->id}}">
				    <h1>{{$cliente->nome}}</h1>
				    <span class="rating">{{$cliente->stelle->nome}}</span>
				    <h2>{{ $cliente->localita->nome }}</h2>
				    @if ($cliente->chiuso_temp)
							<div class="chiusoTemp" style="left:0; top:3px;">
								{{__("labels.chiusura_temporanea")}}
							</div>
						@endif
				    <span class="hotel-edit"></span>
				</hgroup>
			</header>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="menu-secondario-container animation">
		<div class="container">
			<div class="row">
				<nav>  	
					<header class="hidden col-sm-10">
						<h3>{{trans("title.menu")}}</h3>
					</header>
					<div class="hidden-md hidden-lg button-ipad col-sm-2">
						<a id="contact-button" href="#" class="btn btn-arancio"><i class="icon-mail-alt"></i>{{trans("listing.scrivi_email")}}</a>
					</div>
				</nav>
			</div>
		</div>
	</div>
	
</div>


<div id="logo">
	<div class="container">
		<div class="row">
			<div class="col-xs-4">
				<div class="logo">
					<a href="{{Utility::getUrlWithLang($locale, '/')}}" class="logo_link">
						<img src="{{ Utility::asset('images/logo.png') }}" alt="Info Alberghi srl" />
					</a>
				</div>
			</div>
			
			<div class="col-xs-8 right" style="position: relative">
				
				<small id="linkheader">
				
					@php $conteggio = Utility::getPreferitiCount(); @endphp
					@include("widget.lang")

					@php
						$locale == 'it' ? $class = "visible" : $class = "no_visible"
					@endphp
					
					<div class="up-link reverse {{$class}}">
						{!!Form::open(["id"=>"form_cerca", 'url' => 'cerca'])!!}
							<input type="text" placeholder="Cerca nel sito" name="cerca" value="{{$da_cercare ?? ''}}" id="txtCerca" required style=" display:inline-block">
							<button type="submit"><i class="icon-search"></i>
						{!!Form::close()!!}
					</div>
					
					<a class="up-link reverse heart {{$class}}" href="{{Utility::getUrlWithLang($locale, '/preferiti')}}">
					<i class="icon-heart {{$class}}"></i></a><span class="badge @if($conteggio>0) rosso @endif {{$class}}" style="margin-left: -6px; top: -10px;">{{$conteggio}}</span>
					
				</small>
				
					@if(!isset($home))
						<a class="menubutton" href="#"><i class="icon-menu"></i></a>						
					@endif
					
			</div>
			<div class="clearfix"></div>

		</div>
	</div>
</div>

<div class="clearfix"></div>

@if(!isset($home))

	<nav id="menu-principale" class="sticker-menu" >
		
		<div class="container">
			<div class="row">

				<div id="content-link" class="col-sm-12">
					
				</div>

				<div id="content-menu" class="col-sm-12">
				
					@if (isset($menu_localita))
						{!! $menu_localita !!}
					@else
						{!!Utility::getMenuLocalita($locale)!!}
					@endif
					
				</div>


			</div>
		</div>
	</nav>

@endif

<div class="clearfix"></div>
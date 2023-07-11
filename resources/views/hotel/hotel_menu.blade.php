<header>
	<div class="container">
		<div id="logo">
			<a href="{{url('/')}}/"><img src="{{ Utility::asset('images/logo.png') }}" class="logo" /></a>
		</div>
		@include('search.header_search_box')
		<nav>
			{!! $menu_localita !!}
		</nav>
	</div>
</header>
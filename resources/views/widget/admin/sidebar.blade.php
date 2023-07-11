<div class="sidebar-menu">
	<div class="sidebar-menu-inner">

	<header class="logo-env"><!-- set fixed position by adding class "navbar-fixed-top" -->
		<!-- logo -->
		<div class="logo">
			<a href="{{ url("admin") }}">
				<span style="color: #FFF">INFO</span><span style="color: #3FA7E6">ALBERGHI</span>
			</a>
		</div>

		<div class="sidebar-collapse">
			<a class="sidebar-collapse-icon with-animation" href="#"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition --> <i class="entypo-menu"></i></a>
		</div>
		<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->

		<div class="sidebar-mobile-menu visible-xs">
			<a class="with-animation" href="#"><!-- add class "with-animation" to support animation --> <i class="entypo-menu"></i></a>
		</div>
	</header>

	@include('templates.admin_inc_menu')

	</div> <!-- / sidebar-menu-inner -->
</div> <!--  / sidebar-menu -->
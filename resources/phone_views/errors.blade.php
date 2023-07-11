<div class="alert ">
	<img src="{{Utility::asset("images/icons/white/HighPriority.png", false, true)}}" />
	<p>{{trans("hotel.attenzione")}}</p>
	<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		
		@if (Session::has('flash_message'))
			<li>{{Session::get('flash_message')}}</li>
		@endif
	</ul>
	<a href="#chiudi">x</a>
</div> 	
	<br />
<div class="clear"></div>


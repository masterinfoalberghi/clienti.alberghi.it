@if(Session::has('flash_message'))
	<div class="alert alert-danger">
		{{ Session::get('flash_message') }}	
	</div>
	<div class="clear"></div>
@endif
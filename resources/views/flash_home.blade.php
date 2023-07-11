@if(Session::has('flash_message'))
	<div class="alert alert-listing alert-home">
		{{ Session::get('flash_message') }}	
		<a href="">
			<i class="icon-cancel"></i>
		</a>
	</div>
@endif
@extends('templates.admin')

@section('title')
	Featured
@endsection

@section('content')

	@if((Auth::user()->hasRole(["root", "admin", "operatore"])) )
	
		<br />
		<div class="row">
		<div class="col-md-6">
		{!! $featured !!}
		</div>
		</div>
		<div style="clear:both;"></div>

	@endif

@endsection


@section('onheadclose')



@endsection

@extends('templates.admin')

@section('title')
	Hotfix
@endsection

@section('content')

	@if((Auth::user()->hasRole(["root", "admin", "operatore"])) )
		
		<br />
		<div class="row">
			<div class="col-md-6">
				{!! $hotfix !!}
			</div>
		</div>
		<div style="clear:both;"></div>
		
	@endif

@endsection


@section('onheadclose')



@endsection

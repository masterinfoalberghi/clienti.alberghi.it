@extends('templates.admin')
@section('title') Statistiche @endsection
@section('content')

<div class="row">
	@if ($groups)
		@foreach ($groups as $group)
		<div class="col-md-6 col-sm-6">
			
			<h3>{!!$group['nome']!!}</h3>
			
			<div class="section-menu">
				@foreach ($group['links'] as $link)
						<i class="glyphicon glyphicon-{{ $link['icon'] ? $link['icon'] : 'random' }}"></i>&nbsp;&nbsp;&nbsp;<b><a href="{!! url($link['route']) !!}">{!! $link['nome'] !!}</a></b> - {!! $link['desc'] !!}<br />
				@endforeach
			</div>
			
		</div>
		@endforeach
	@endif
</div>
@endsection

@section('onheadclose')
	
	<style>
		.main-content h3 { border-bottom:1px solid #ddd; padding:15px 0; color:#aaa;  }
		.section-menu { line-height: 24px; font-size: 14px; }
	</style>
	
@endsection

@section('onbodyclose') @endsection

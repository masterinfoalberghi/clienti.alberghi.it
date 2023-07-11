@extends('templates.admin')


@section('title')
	SENDGRID - Dashboard
@endsection

@section('content')

  <ul>
    <li>Bounces TOTALI: <a href="{{ route('sendgrid-bounces') }}" target="_blank">{{$bounces_count->count}}</a></li>
    <li>Blocks TOTALI: <a href="{{ route('sendgrid-blocks') }}" target="_blank">{{$blocks_count->count}}</a></li>
  </ul>

@endsection
@extends('templates.admin')


@section('title')
	SENDGRID - {{$title}} - ({{count($elements)}})
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
    <h4>{{$desc}}</h4>
      <table class="table table-hover table-bordered table-responsive">
      <thead>
        <tr>
           <th>Email</th>
           <th>Time</th>
           <th>Reason</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($elements as $item)
        <tr>
          <td>{{$item->email}}</td>
          <td>{{$item->created}}</td>
          <td>{{$item->reason}}</td>
        </tr>
        @endforeach
      </tbody>
      </table>
    </div>
</div>

@endsection



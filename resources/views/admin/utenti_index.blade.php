@extends('templates.admin')

@section('title')
Utenti
@endsection

@section('content')
<div style="margin-bottom: 20px;">
<span class="badge" style="background: transparent; font-size: 14px;">{{$data["account_hotel"]}}</span> account hotel
<span class="badge" style="background: #FF9800; font-size: 14px;">{{$data["account_operatore"]}}</span>  account operatore
<span class="badge" style="background: #f44336; font-size: 14px;">{{$data["account_admin"]}}</span> account admin
</div>

<h3>Amministratori</h3>
@php 
$role = "admin"; 
  $title = ["admin" => "Amministratori", "operatore" => "Operatori", "hotel" => "Clienti", "commerciale" => "Commerciali"];
@endphp
<table class="table table-hover table-bordered table-responsive datatable">
  <thead>
    <tr>
      <th width="50"></th>
      <th width="50">ID</th>
      <th width="300">Email</th>
      <th width="300">Username</th>
      <th width="300">Hotel</th>
      <th width="200">Inserito il</th>
      <th width="200">Ultima modifica</th>
      <th ></th>
    </tr>
  </thead>
  <tbody>
  @foreach($data["records"] as $user)
    @if($user->role != $role)
        @php $role = $user->role; @endphp
      </tbody>
    </table>
    <h3>{{$title[$user->role]}}</h3>
    <table class="table table-hover table-bordered table-responsive datatable">
      <thead>
        <tr>
          <th width="50"></th>
          <th width="50">ID</th>
          <th width="300">Email</th>
          <th width="300">Username</th>
          <th width="300">Hotel</th>
          <th width="200">Inserito il</th>
          <th width="200">Ultima modifica</th>
          <th ></th>
        </tr>
      </thead>
       <tbody>
        @endif
    <tr>
      <td><img src="https://www.gravatar.com/avatar/{{ md5( strtolower( trim( $user->email ))) }}?size=45&d=mp" /></td>
      <td>{{ $user->id }}</td>
      <td>{{ $user->email }}</td>
      <td>{{ $user->username }}</td>
      <td>{{ $user->getHotelName() }}</td>
      <td>{{ $user->created_at->formatLocalized("%x %X") }}</td>
      <td>{{ $user->updated_at->formatLocalized("%x %X") }}</td>
      <td class="text-center">
        <a href="{{ url("admin/utenti/".$user->id."/edit") }}" class="btn btn-primary">Modifica</a>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
@endsection
@extends('templates.admin')

@section('title')
Vetrine
@endsection

@section('content')

<div>
  <a href="{{ url("admin/vetrine/create") }}" class="btn btn-primary">Nuova vetrina</a>
</div>

@if(count($data["records"]) === 0)
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessuna <em>Vetrina</em>, <a href="{{ url("admin/vetrine/create") }}">creane una ora</a>.
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Elenco vetrine create</h4>

      <table class="table table-hover table-bordered table-responsive">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Descrizione</th>
            <th>attiva</th>
          </tr>
        </thead>
        <tbody>
        @foreach($data["records"] as $vetrina)
          <tr>
            <td>
              {{ $vetrina->nome }}
            </td>
            <td>
              {{ $vetrina->descrizione }}
            </td>
            <td>{!! Utility::viewBool($vetrina->attiva, false) !!}</td>
            <td class="text-center">
              <a href="{{ url("admin/vetrine/".$vetrina->id. "/edit") }}" class="btn btn-primary">Modifica</a>
            </td>
            <td class="text-center">
              <a href="{{ url("admin/vetrine/".$vetrina->id. "/slots") }}" class="btn btn-blue">Slots</a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>

    </div>
  </div>
@endif

@endsection

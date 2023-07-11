@extends('templates.admin')

@section('title')
Listini Personalizzati
@endsection

@section('content')

@if(count($data["records"]) === 0)
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessun <em>Listino Personalizzato</em>, <a href="{{ url("admin/listini-custom/create") }}">creane una ora</a>.
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Elenco listini creati. <a href="{{ url("admin/listini-custom/order") }}">Modifica l'ordine di visualizzazione</a></h4>

      <table class="table table-hover table-bordered table-responsive">
        <thead>
          <tr>
            <th>Titolo</th>
            <th>Attivo</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        @foreach($data["records"] as $listino)
          <tr>
            <td>
              {{ $listino->listiniCustomLingua->findByLang("it")->titolo ? $listino->listiniCustomLingua->findByLang("it")->titolo : '-' }}
            </td>
            <td>
              {{ Utility::viewBool($listino->attivo) }}
            </td>
            <td class="text-center">
              <a href="{{ url("admin/listini-custom/".$listino->id."/edit") }}" class="btn btn-primary">Modifica</a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>

    </div>
  </div>
@endif

@endsection

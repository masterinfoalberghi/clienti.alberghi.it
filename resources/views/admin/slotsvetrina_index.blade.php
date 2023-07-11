@extends('templates.admin')

@section('title')
Slots della vetrina {{ $vetrina->nome }} - {{ $vetrina->descrizione }}
@endsection

@section('content')

<div>
  <a href="{{ url("admin/vetrine/" .$vetrina->id. "/slots/create") }}" class="btn btn-primary">Nuovo slot</a>
</div>

@if(count($data["records"]) === 0)
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessuno <em>Slot</em>, <a href="{{ url("admin/vetrine/slots/create") }}">creane uno ora</a>.
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">
      
      <h4>Elenco slots creati</h4>

      <table class="table table-hover table-bordered table-responsive">
        <thead>
          <tr>
            <th>ID Cliente</th>
            <th>Titolo Vetrina</th>
            <th>Scadenza</th>
            <th>Attiva</th>
			<th></th>
          </tr>
        </thead>
        <tbody>
        @foreach($data["records"] as $slot)
          @if (is_null($slot->cliente))
            <tr><td>Attenzione! Vetrina assegnata a cliente che non esiste!! {{$slot->hotel_id}}</td></tr>
          @else
            <tr>              
              <td>
                {{ $slot->cliente->id }}
              </td>
              <td>
                {{ $slot->cliente->nome }}
              </td>
              
            
              <td>
                {{ Utility::getLocalDate($slot->data_scadenza, '%d/%m/%y') }}
              </td>
              <td>{{ Utility::viewBool($slot->attiva) }}</td>
              <td class="text-center">
                <a href="{{ url("admin/vetrine/".$vetrina->id. "/slots/". $slot->id . "/edit") }}" class="btn btn-primary">Modifica</a>
              </td>
            </tr>
          @endif
        @endforeach
        </tbody>
      </table>

    </div>
  </div>
@endif

@endsection

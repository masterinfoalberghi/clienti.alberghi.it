@extends('templates.admin')

@section('title')
Offerte Bambini Gratis
@endsection

@section('content')

@if(count($data["records"]) === 0)
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessuna <em>Offerta Bambini Gratis</em>, <a href="{{ url("admin/bambini-gratis/create") }}">creane una ora</a>.
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Elenco periodi creati</h4>

      <table class="table table-hover table-bordered table-responsive">
        <thead>
          <tr>
            <th>Periodo di validità</th>
            <th>Caratteristiche</th>
            <th>Stato</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        @foreach($data["records"] as $bambino_gratis)
          @if (!$bambino_gratis->isAttivo())
            <tr style="color: #d67e00;">
          @elseif(!$bambino_gratis->attivo)
            <tr style="color: #b11b1b;">
          @else
            <tr style="color:initial;">
          @endif
            <td>
              dal {{ $bambino_gratis->valido_dal->formatLocalized("%d/%m/%y") }}
              al  {{ $bambino_gratis->valido_al->formatLocalized("%d/%m/%y") }}
            </td>
            <td>
              Bambini gratis da 0 a {{ $bambino_gratis->_fino_a_anni() }} {{ $bambino_gratis->anni_compiuti }}
            </td>
            <td class="no-hover">
              @if (!$bambino_gratis->isAttivo())
              
               <button type="button" class="btn btn-orange btn-icon icon-left">
                  Scaduta
                  <i class="entypo-hourglass"></i>
                </button>
              @elseif(!$bambino_gratis->attivo)
                <button type="button" class="btn btn-red btn-icon icon-left">
                  Archiviata
                  <i class="entypo-download"></i> 
                </button>
              @else
                <button type="button" class="btn btn-green btn-icon icon-left">
                  On line
                  <i class="entypo-thumbs-up"></i> 
                </button>
              @endif
            </td>
            <td class="text-center">
              <a href="{{ url("admin/bambini-gratis/".$bambino_gratis->id."/edit") }}" class="btn btn-primary">Modifica</a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>

    </div>
  </div>
@endif

@endsection

@extends('templates.admin')

@section('title')
  Poi
@endsection

@section('content')
  <h5>Google POI</h5>
  <table class="table table-bordered responsive">
    <thead>
      <tr>
        <th>type</th>
        <th width="23%">Name</th>
        <th>Address</th>
        <th>lat/lng</th>
        <th>Google types</th>
        <th width="13%">Distances</th>
        <th>Updated</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @if ($g_poi->count())
        @foreach ($g_poi as $item)
          <form method="POST" action="{{ route('delete-google-poi', [$item->id]) }}"
            id="form_google_poi_{{ $item->id }}">
            @csrf
          </form>
          <tr>
            <td>{{ $item->type }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->end_address }}</td>
            <td>{{ $item->lat . ' / ' . $item->lng }}</td>
            <td>{{ $item->google_types }}</td>
            <td>{{ $item->distances }}</td>
            <td>{{ $item->updated_at->format('d/m/Y h:i') }}</td>
            <td>
              <a href="#" onclick="jQuery('#modal-confirm-delete-{{ $item->id }}').modal('show');"
                class="btn btn-danger btn-sm btn-icon">
                <i class="entypo-cancel"></i>Delete
              </a>
            </td>
          </tr>
          <div class="modal fade" id="modal-confirm-delete-{{ $item->id }}" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">Eliminazione record</h4>
                </div>
                <div class="modal-body">
                  Confermi di voler eliminare in maniera definitiva ed irreversibile il record?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                  <button type="button" class="btn btn-primary"
                    onclick="jQuery('#form_google_poi_'+{{ $item->id }}).submit();">Conferma</button>
                </div>
              </div>
            </div>
          </div>
          @endforeach
      @else
        <p>Nessun Google POI</p>
      @endif
    </tbody>
  </table>
  <h5>Normal POI</h5>
  <div class="row">
    @foreach ($array_poi as $cat => $poi_arr)
      <div class="col-sm-6">
        <div class="tile-block tile-gray" id="todo_tasks">
          <div class="tile-header" style="color: #767676"> 
            {{$cat}}
          </div>
          <div class="tile-content">

            <table class="table responsive">
              @foreach ($poi_arr as $item)
              <tr>
                  <td>{{$item['nome']}}</td> 
                  <td>{{$item['distanza']}}</td> 
                  <td>{{$item['tempo']}}</td> 
                  <td>@if (!empty($item['rotta'])) direzione @endif {{$item['rotta']}}</td> 
              </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    @endforeach

  </div>
@endsection

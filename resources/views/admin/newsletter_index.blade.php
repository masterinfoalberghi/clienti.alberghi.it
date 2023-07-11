@extends('templates.admin')

@section('title')
Link newsletter
@endsection

@section('content')

@if(count($newsletterLinks) === 0)
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessun <em>link alla newsletter</em>, <a href="{{ url("admin/newsletterLink/create") }}">creane uno ora</a>.
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Elenco Link creati</h4>

      <table class="table table-hover table-bordered table-responsive">
        <thead>
          <tr>
            <td>Visibile</td>
            <th>Titolo</th>
            <th></th>
            <th>Data invio</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        @foreach($newsletterLinks as $link)
            <tr>
              <td>
                @if (in_array($link->id, $newsletterPublished_ids))
                  <i class="entypo-check text-success"></i>
                @else
                  <i class="entypo-cancel text-danger"></i>
                @endif
              </td>
              <td>
                  {{ $link->titolo }}
              </td>
              <td>
                  <a href="{{ url("$link->url") }}" target="_blank" class="">
                  Leggi la newsletter
                  </a>
              </td>
              <td>
                @if (!is_null($link->data_invio))
                 {{ $link->data_invio->formatLocalized("%d/%m/%y") }}
                @endif
              </td>

            <td class="text-center">
              <a href="{{ url('admin/newsletterLink/'.$link->id.'/edit')}}" class="btn btn-primary">
                Modifica
              </a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>

    </div>
  </div>
@endif

@endsection

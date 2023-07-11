@extends('templates.admin')

@section('title')
COVID-19: igiene e regole in struttura
@endsection


@section('content')
<form role="form" action="{{ route('servizi-covid.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  @foreach ($gruppi_servizi_covid as $gruppo)
    <div class="row">
      <div class="col-md-2">    
        <p class="nome_gruppo">{{$gruppo->nome_it}}</p>
      </div>
    </div>
    @foreach ($gruppo->servizi as $servizio)
        <div class="row" style="margin-top: 15px;">
          <div class="col-md-3">
            <input type="checkbox" name="servizio_covid[]" id="{{$servizio->id}}" value="{{$servizio->id}}" {{ array_key_exists($servizio->id, $servizi_ids) ? 'checked' : ''}}>
            <label for="{{$servizio->id}}">
              {{$servizio->nome_it}}
            </label> 
          </div>
          @if ($servizio->to_fill)
          <div class="col-md-1">
            <input type="text" name="distanza_{{$servizio->id}}" id="distanza_{{$servizio->id}}" class="form-control" value="{{ array_key_exists($servizio->id, $servizi_ids) ? $servizi_ids[$servizio->id]  : ''}}">
          </div>
          @endif
        </div>
    @endforeach
  @endforeach
  <input type="submit" value="Salva" class="btn btn-info"  style="margin-top: 25px;">
</form>
@endsection


@section('onbodyclose')
@endsection

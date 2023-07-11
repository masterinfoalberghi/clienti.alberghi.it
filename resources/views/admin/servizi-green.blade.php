@extends('templates.admin')

@section('title')
GREEN: servizi eco in struttura
@endsection


@section('content')
<form role="form" action="{{ route('servizi-green.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  @foreach ($gruppi_servizi_green as $gruppo)
    <div class="row">
      <div class="col-md-5">    
        <p class="nome_gruppo">{{$gruppo->nome_it}}</p>
      </div>
    </div>
    @foreach ($gruppo->servizi as $servizio)
        <div class="row" style="margin-top: 15px;">
          <div class="col-md-5">
            <label for="{{$servizio->id}}" style="display: flex; align-items: baseline;">
              <input type="checkbox" name="servizio_green[]" style="margin-right:10px;" id="{{$servizio->id}}" value="{{$servizio->id}}" {{ array_key_exists($servizio->id, $servizi_ids) ? 'checked' : ''}}>
              {{$servizio->nome_it}}
            </label> 
          </div>          
          <div class="col-md-5">
            <input type="text" name="altro_{{$servizio->id}}" id="altro_{{$servizio->id}}" class="form-control" value="{{ array_key_exists($servizio->id, $servizi_ids) ? $servizi_ids[$servizio->id]  : ''}}">
          </div>
        </div>
    @endforeach
  @endforeach
  <input type="submit" value="Salva" class="btn btn-info"  style="margin-top: 25px;">
</form>
@endsection


@section('onbodyclose')
@endsection

@extends('templates.admin')

@section('title')
Evidenze Top - {{$titolo}}
@endsection

@section('content')

@if (count($data))
  <table class="table table-hover table-bordered table-responsive datatable">
    <thead>
      <tr>
        <th>Titolo</th>
        <th>Pagina</th>
      </tr>
    </thead>
    <tbody>
      @foreach($data as $titolo => $url)
        <tr>
          <td>{{ $titolo }}</td>
          @php $url = str_replace("https://clienti", "https://www", $url); @endphp
          <td><a href="{{$url}}" title="{{$url}}" target="_blank">{{$url}}</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="alert alert-info" style="text-align: center; font-size: 13px; border: 1px solid #2c7ea1;">
          Le <strong>evidenze</strong> sono uno spazio pubblicitario <strong>aggiuntivo</strong>. Per ulteriori informazioni puoi contattare direttamente il tuo <strong>commerciale di riferimento</strong> o chiamare il numero <strong>0541 29187</strong> (dalle 9 alle 18).
        </div>
      </div>
    </div>
    
@endif
@endsection


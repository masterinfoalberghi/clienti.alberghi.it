@extends('templates.admin')

@section('title')
Coupon
@endsection

@section('content')

@if($coupon->isEmpty())
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessun <em>Coupon</em>, <a href="{{ url("admin/coupon/create") }}">creane uno ora</a>.
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Elenco coupon</h4>

      <table class="table table-hover table-bordered table-responsive" id="elenco_coupon">
      
        <thead>
          <tr>
            <th>Coupon disponibili</th> 
            <th>Coupon scaricati</th>
            <th>Sconto</th>
            <th>Dal</th> 
            <th>Al</th>
            <th>Notti minime</th>  
            <th>Referente</th> 
            <th>Coupon creato il</th> 
            <th>Coupon archiviato il</th>
            <th>Coupon attivo</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
          <tbody>
          @foreach($coupon as $c)
            <tr>
              <td>
                {!!$c->numero !!} 
              </td>
              <td id="utilizzati">
                @if ($c->utilizzati)
                  <a href="{{ url('admin/coupon/'.$c->id.'/generati') }}">{{$c->utilizzati}}</a>
                @endif 
              </td>
              <td>
                {!!$c->valore !!} 
              </td>
              <td>
                {{Utility::getLocalDate($c->periodo_dal, '%d/%m/%y')}}
              </td>
              <td>
                {{Utility::getLocalDate($c->periodo_al, '%d/%m/%y')}}
              </td>
              <td>
                {{ $c->durata_min }}
              </td>
              <td>
                {{ $c->referente }}
              </td>
              <td>{{ $c->created_at }}</td>
              <td>{{ $c->data_chiusura }}</td>
              <td>{{ $c->attivo ? "S&igrave;" : "No"}}</td>
              @if (!$c->attivo)
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              @else
                <td>
                  <a href="{{ url('admin/coupon/'.$c->id.'/edit')}}" class="btn btn-primary">Modifica</a>
                </td>
                <td>
                  {!! Form::open(['id' => 'archvia', 'url' => 'admin/coupon/'.$c->id.'/archivia', 'method' => 'POST']) !!}
                    <button type="submit" class="btn btn-red">Archivia</button>
                  {!! Form::close() !!}
                </td>
              @endif
            </tr>
          @endforeach
          </tbody> 
      </table>
    </div>
  </div>
@endif

@endsection

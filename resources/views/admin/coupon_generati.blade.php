@extends('templates.admin')

@section('title')
Cupon sconto
@endsection

@section('content')


<div class="row">
  <div class="col-lg-12">
    <ul>
      <li>Valore  EURO {{$coupon->valore}}</li>
      <li>Validità  dal {{Utility::getLocalDate($coupon->periodo_dal, '%d/%m/%y')}} al {{Utility::getLocalDate($coupon->periodo_dal, '%d/%m/%y')}}</li>
      <li>Numero   {!!$coupon->numero !!}</li>
      <li>Attivo {{ $coupon->attivo ? "S&igrave;" : "No"}}</li>
    </ul>
  </div>
</div>


@if($coupon_generati->isEmpty())
  <div class="row">
    <div class="col-lg-12">
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Nessun coupon sconto generato.
      </div>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">
      <h4>Elenco completo degli utenti possessori del coupon</h4>

      <table class="table table-hover table-bordered table-responsive" id="coupon_generati">
      
        <thead>
          <tr>
            <th>Codice</th> 
            <th>Email cliente</th>
            <th>Data creazione</th>
          </tr>
        </thead>
          <tbody>
          @foreach($coupon_generati as $c)
            <tr>
              <td>
                {!! $c->codice !!} 
              </td>
              <td>
                  {!! $c->utente->email !!}
              </td>
              <td>
                {!!$c->created_at !!} 
              </td>
            </tr>
          @endforeach
          </tbody> 
      </table>
    </div>
  </div>
@endif

<div class="row">
  <div class="col-lg-12">
    <a href="{{ url('admin/coupon') }}"><button type="button" class="btn btn-black">Indietro</button></a>
  </div>
</div>


@endsection

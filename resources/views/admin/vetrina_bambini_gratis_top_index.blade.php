@extends('templates.admin')

@section('title')Offerte Bambini Gratis "Top"@endsection

@section('content')

@if(isset($message) && $message != "")
<div class="row">
  <div class="col-lg-12">
    <p class="message-box danger">
      Attualmente non puoi inserire altre offerte.<br>
      Hai raggiunto il <b>numero massimo di <i>{{$message}}</i> offerte attive contemporaneamente</b>.<br><br>
      Archivia alcune offerte per poterne inserire di nuove!
    </p>
  </div>
</div>
@endif

@if($offerte->isEmpty())
  <div class="row">
    <div class="col-lg-12">
    @if(isset($archiviate))
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Nessuna offerta "Top" <em>archiviata</em>.
      </div>
    @else
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessuna <em>Offerta "Top"</em>, <a href="{{ url("admin/vetrine-bg-top/create") }}">creane una ora</a>.
      </div>
    @endif
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Elenco offerte @if(isset($archiviate))<b>ARCHIVIATE</b>@endif</h4>

      <table class="table table-hover table-bordered table-responsive">
      @foreach($offerte as $offerta)
        <thead>
          <tr>
            <th>Caratteristiche</th>
            <th>Note</th>
            <th>Validità</th>
            @if(!isset($archiviate))<th>Online</th>@endif
            <th class="text-center" rowspan="4" valign="center">
              <a href="{{ url('admin/vetrine-bg-top/'.$offerta->id.'/edit')}}" class="btn btn-primary">Modifica</a>
            </th>
            <th class="text-center" rowspan="4" valign="center">
              
              @if (isset($archiviate))
                  {!! Form::open(['id' => 'archvia', 'url' => 'admin/vetrine-bg-top/'.$offerta->id.'/ripristina', 'method' => 'POST']) !!}
                    <button type="submit" class="btn btn-green">Pubblica</button>
                  {!! Form::close() !!}
                @else
                  {!! Form::open(['id' => 'archvia', 'url' => 'admin/vetrine-bg-top/'.$offerta->id.'/archivia', 'method' => 'POST']) !!}
                    <button type="submit" class="btn btn-red">Archivia</button>
                  {!! Form::close() !!}
                @endif  
            </th>
          </tr>
        </thead>
          <tbody>
          @foreach ($offerta->offerte_lingua as $offerta_lingua)
            <tr>
              <td>
                Bambini gratis da 0 a {{ $offerta->_fino_a_anni() }} {{ $offerta->_anni_compiuti() }}&nbsp;&nbsp;dal {{ $offerta->valido_dal->formatLocalized("%x") }}
              al  {{ $offerta->valido_al->formatLocalized("%x") }}
              </td>
              <td>
                {!!$offerta_lingua->note !!} 
              </td>
              <td>
               {{$offerta->getMesiValiditaAsStr()}}
              </td>
              
              @if(!isset($archiviate))
                @if ( in_array($mese, explode(',', $offerta->mese)) )
                  <td class="online">Si</td>
                @else
                  <td class="offline">No</td>
                @endif
              @endif
            </tr>
          @endforeach
         
          {{-- elenco delle pagine associate --}}
          <thead><tr><th colspan="5">Associata alle pagine</th></tr></thead>
          @foreach ($offerta->offerte_lingua as $offerta_lingua)
          <tr>
            <td><img src="{{ Langs::getImage($offerta_lingua->lang_id) }}" alt="{{ $offerta_lingua->lang_id }}"> {{ Langs::getName($offerta_lingua->lang_id) }}</td>
            <td colspan="4">
                @if (isset($offerta_lingua->pagina))
                  {{$offerta_lingua->pagina->uri}}
                @else
                  Nessuna pagina !!
                @endif
            </td>
          </tr>
          @endforeach

          <tr><th colspan="5" style="background-color: #fff; height: 100px;"></th></tr>
          </tbody> 
      @endforeach
      </table>
    </div>
  </div>
@endif

@endsection

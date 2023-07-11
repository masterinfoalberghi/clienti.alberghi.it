@extends('templates.admin')

@section('title')
Offerte "Top"
@endsection

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
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessuna <em>Offerta "Top"</em>, <a href="{{ url("admin/vetrine-offerte-top/create") }}">creane una ora</a>.
      </div>
    @endif
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Elenco offerte TOP @if(isset($archiviate))<b>ARCHIVIATE</b>@endif</h4>

      <table class="table table-hover table-bordered table-responsive">
      @foreach($offerte as $offerta)
        <thead>
          <tr @if ($offerta->nascondi_in_scheda) class="nascondi_in_scheda" @endif>
            <th>Testo</th>
            <th>Validità</th>
            <th>Prezzo/pers</th>
            <th>Per persone</th>
            @if(!isset($archiviate))<th>Online</th>@endif
            <th class="text-center" rowspan="4" valign="center">
              <a href="{{ url('admin/vetrine-offerte-top/'.$offerta->id.'/edit')}}" class="btn btn-white">Modifica</a>
            </th>
            <th class="text-center" rowspan="4" valign="center">
              
              @if (isset($archiviate))
                  {!! Form::open(['id' => 'archvia', 'url' => 'admin/vetrine-offerte-top/'.$offerta->id.'/ripristina', 'method' => 'POST']) !!}
                    <button type="submit" class="btn btn-green">Pubblica</button>
                  {!! Form::close() !!}
              @else
                {!! Form::open(['id' => 'archvia', 'url' => 'admin/vetrine-offerte-top/'.$offerta->id.'/archivia', 'method' => 'POST']) !!}
                  <button type="submit" class="btn btn-red">Archivia</button>
                {!! Form::close() !!}
              @endif
            </th>
            <th>
              {!! Form::open(['id' => 'clona', 'url' => 'admin/vetrine-offerte-top/'.$offerta->id.'/clona', 'method' => 'POST']) !!}
                    <button type="submit" class="btn btn-info">Clona</button>
                {!! Form::close() !!}
            </th>
          </tr>
        </thead>
          <tbody>
          @foreach ($offerta->offerte_lingua as $offerta_lingua)
            <tr>
              <td>
                {!!$offerta_lingua->titolo !!} <br> {!!$offerta_lingua->testo!!} 
              </td>
              <td>
               {{$offerta->getMesiValiditaAsStr()}}
              </td>
              <td>
                {{ $offerta->prezzo_a_persona }}
              </td>
              <td>
                {{ $offerta->per_persone }}
              </td>
              @if(!isset($archiviate))
                @if ( in_array($mese, explode(',', $offerta->mese)) )
                  <td class="online">Si</td>
                @else
                  <td class="offline">No</td>
                @endif
              @endif
              <td colspan="3">&nbsp;</td>
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

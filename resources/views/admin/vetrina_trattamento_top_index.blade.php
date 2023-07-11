@extends('templates.admin')

@section('title')
Vetrine trattamento "Top"
@endsection

@section('content')

@if($vetrine->isEmpty())
  <div class="row">
    <div class="col-lg-12">
    @if(isset($archiviate))
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Vetrine trattamento "Top" <em>archiviata</em>.
      </div>
    @else
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessuna <em>Vetrine trattamento "Top"</em>, <a href="{{ url("admin/vetrine-trattamento-top/create") }}">creane una ora</a>.
      </div>
    @endif
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Elenco Vetrine trattamento TOP @if(isset($archiviate))<b>ARCHIVIATE</b>@endif</h4>

      <table class="table table-hover table-bordered table-responsive">
      @foreach($vetrine as $vetrina)
        <thead>
          <tr>
            <th>Validità</th>
            @if(!isset($archiviate))<th>Online</th>@endif
            <th class="text-center" rowspan="4" valign="center">
              <a href="{{ url('admin/vetrine-trattamento-top/'.$vetrina->id.'/edit')}}" class="btn btn-primary">Modifica</a>
            </th>
            <th class="text-center" rowspan="4" valign="center">
              
              @if (isset($archiviate))
                  {!! Form::open(['id' => 'archvia', 'url' => 'admin/vetrine-trattamento-top/'.$vetrina->id.'/ripristina', 'method' => 'POST']) !!}
                    <button type="submit" class="btn btn-green">Pubblica</button>
                  {!! Form::close() !!}
                @else
                  {!! Form::open(['id' => 'archvia', 'url' => 'admin/vetrine-trattamento-top/'.$vetrina->id.'/archivia', 'method' => 'POST']) !!}
                    <button type="submit" class="btn btn-red">Archivia</button>
                  {!! Form::close() !!}
                @endif  
            </th>
          </tr>
        </thead>
          <tbody>
          
          @foreach ($vetrina->vetrine_lingua as $vetrina_lingua)
            @if ($vetrina_lingua->lang_id == 'it')
              <tr>
                  <th>{{$vetrina->getMesiValiditaAsStr()}}</th>
                  @if(!isset($archiviate))
                    @if ( in_array($mese, explode(',', $vetrina->mese)) )
                      <td colspan="3" class="online">Si</td colspan="3">
                    @else
                      <td colspan="3" class="offline">No</td>
                    @endif
                  @endif
              </tr>              
            @endif
          <tr>
            <td><img src="{{ Langs::getImage($vetrina_lingua->lang_id) }}" alt="{{ $vetrina_lingua->lang_id }}"> {{ Langs::getName($vetrina_lingua->lang_id) }}</td>
            <td colspan="4">
                @if (isset($vetrina_lingua->pagina))
                  {{$vetrina_lingua->pagina->uri}}
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

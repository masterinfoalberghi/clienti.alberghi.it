@extends('templates.admin')

@section('title')
Offerte "prenota prima"
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
        <i class="fa fa-info-circle"></i> Nessuna offerta "prenota prima" <em>archiviata</em>.
      </div>
    @else
      <div class="alert alert-info">
        <i class="fa fa-info-circle"></i> Non hai ancora creato nessuna <em>Offerta "prenota prima"</em>, <a href="{{ url("admin/prenota-prima/create") }}">creane una ora</a>.
      </div>
    @endif
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-12">

      <h4>Elenco {{$offerte->count()}} offerte @if(isset($archiviate))<b>ARCHIVIATE</b>@endif</h4>

      <table class="table table-hover table-bordered table-responsive">
      @foreach($offerte as $offerta)
        <thead>
          <tr>
            <th>Testo @if ( isset($archiviate) ) <a href="#" data-id="{{$offerta->id}}" class="del_offer"><i class="entypo-trash"></i>Elimina</a> @endif</th>
            <th>Validità</th>
            <th>% sconto</th>
            <th>Prenota entro</th>
            @if(!isset($archiviate))<th>Online</th>@endif
            <th class="text-center" rowspan="4" valign="center">
              <a href="{{ url('admin/prenota-prima/'.$offerta->id.'/edit')}}" class="btn btn-primary">Modifica</a>
            </th>
            <th class="text-center" rowspan="4" valign="center">
              
              @if (isset($archiviate))
                  
                  {!! Form::open(['id' => 'record_delete_'.$offerta->id, 'url' => 'admin/prenota-prima/'.$offerta->id, 'method' => 'DELETE']) !!}
                    {{-- questo hidden lo uso per sapere se DOPO IL DELETE devo fare un redirect negli attivi o negli archiviati --}}
                    <input type="hidden" name="archiviato" value="{{!$offerta->attivo}}">
                  {!! Form::close() !!}

                  {!! Form::open(['id' => 'archvia', 'url' => 'admin/prenota-prima/'.$offerta->id.'/ripristina', 'method' => 'POST']) !!}
                    <button type="submit" class="btn btn-green">Pubblica</button>
                  {!! Form::close() !!}
                @else
                  {!! Form::open(['id' => 'archvia', 'url' => 'admin/prenota-prima/'.$offerta->id.'/archivia', 'method' => 'POST']) !!}
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
                {!!$offerta_lingua->titolo !!} <br> {!!$offerta_lingua->testo!!} 
              </td>
              <td>
                {{Utility::getLocalDate($offerta->valido_al, '%d/%m/%y')}}
              </td>
              <td>
                {{ $offerta->perc_sconto }}
              </td>
              <td>
                {{Utility::getLocalDate($offerta->prenota_entro, '%d/%m/%y')}}
              </td>
              @if(!isset($archiviate))
                @if ($offerta->prenota_entro >= $oggi)
                  <td class="online">Si</td>
                @else
                  <td class="offline">No</td>
                @endif
              @endif
            </tr>
          @endforeach
          <tr><th colspan="5" style="background-color: #fff; height: 100px;"></th></tr>
          </tbody> 
      @endforeach
      </table>
    </div>
  </div>
  @if(isset($archiviate))
    <script>
      
      jQuery(document).ready(function(){

        jQuery('.del_offer').click(function(event){
          event.preventDefault();

          if(window.confirm('ATTENZIONE! L\'offerta verrà eliminata definitivamente. Continuare?'))
          {
            var id = jQuery(this).data('id');
            jQuery('#record_delete_'+id).submit();
          } 

        });
        
                
      });
      
      
    </script>
  @endif
@endif

@endsection

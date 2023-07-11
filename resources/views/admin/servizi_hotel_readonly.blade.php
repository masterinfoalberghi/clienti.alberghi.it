@extends('templates.admin')

@section('title')
    Servizi
@endsection


@section('content')

        <div class="alert alert-warning">
            <p>
                <span style="font-size: 13px">
                    <b>Per aggiungere, modificare o eliminare informazioni</b> presenti servizi della tua scheda inviare una mail a <a href="mailto:supporto@info-alberghi.com">supporto@info-alberghi.com</a> oppure contattare il numero 0541 29187 (dal lunedì al venerdì - orario: 9-13 / 14-18).
                </span>
            </p>
        </div><br /><br />
            
		<div class="row">
        
            <div class="col-md-12">
                Questa è la <strong>situazione attuale dei tuoi servizi</strong>.<br />
            </div>


        <div class="col-md-4">
        {{--
        LA FORMATTAZIONE DEVE ESSERE UGUALE A QUELLA DEL FOGLIO CARTACEO
        CHE RIEMPIONO I COMMERCIALI, ALTRIMENTI NON RIESCONO AD INSERIRE !!!???!!!
         --}}
        <?php $count = 1; ?>

        @foreach ($categorie as $categoria)

          @if ($categoria->listing && $locale != 'it')
            {{-- do nosting --}}
          @elseif($categoria->nome == 'ListingPiscina' || $categoria->nome == 'ListingCentroBenessere')
            {{-- do nosting --}}
          @else
            {{-- Le categorie listing per cui deveo insererire solo il numero di metri le faccio vedere solo in it --}}
              @if ($count == 3 || $count == 6)
                </div>
                <div class="col-md-4">
              @endif
              <div class="panel-heading">
                <div class="panel-title" style="margin-top: 20px; color: #000; border: 1px solid #666; padding: 5px;">
                  {!!$categoria->nome!!}
                </div>
              </div>
              <div class="panel-body">
                @foreach ($categoria->servizi as $servizio)
                  <div class="form-group readonly">
                      <div class="col-sm-12">

                        @if ($categoria->listing)
                          <?php $class="clickListing"; ?>
                        @else
                          <?php $class="click"; ?>
                        @endif

                        @if ($categoria->nome == 'Servizi in hotel' && $servizio->servizi_lingua->first()->nome == 'piscina')
                          <?php $checkbox_id = ['id' => 'checkbox_piscina']; ?>
                        @elseif ($categoria->nome == 'Servizi in hotel' && strpos($servizio->servizi_lingua->first()->nome, 'centro benessere')  !== false )
                           <?php $checkbox_id = ['id' => 'checkbox_benessere']; ?>
                        @elseif ($categoria->nome == 'Servizi per disabili' && strpos($servizio->servizi_lingua->first()->nome, 'servizi per disabili')  !== false )
                           <?php $checkbox_id = ['id' => 'checkbox_disabili']; ?>
                        @else
                           <?php $checkbox_id = []; ?>
                        @endif

                        @if ($categoria->tipo == 'disabili')
                          <?php $checkbox_id['class'] = 'conta'; ?>
                          <?php $checkbox_id['data-categoria-id'] = $categoria->id; ?>
                        @endif

                        <?php $checkbox_id['disabled'] = 'disabled'; ?>

                        <div class="checkbox" @if ($categoria->listing) style="float: left; width:40%;" @endif>
                          <label>{!! Form::checkbox('servizi[]', $servizio->id, in_array($servizio->id, $servizi_ids) ?? null , $checkbox_id)  !!} {{ $servizio->servizi_lingua->first()->nome}}</label>
                        </div>
                        @if ($array_note[$servizio->id] != '')
                          <div tabindex="0" id="{{$servizio->id}}|{{$hotel_id}}" class="click_readonly" @if (!in_array($servizio->id, $servizi_ids)) style="display:none;" @endif>{{ ($array_note[$servizio->id] == '' && ! $categoria->listing ) ? ' ' : $array_note[$servizio->id] }}</div>
                        @endif
                      @if ($categoria->listing)
                       <span class="metri"@if (!in_array($servizio->id, $servizi_ids)) style="display:none;" @endif>&nbsp;{{ trans('labels.metri') }}</span>
                      @endif

                      </div>
                  </div>
                @endforeach

                @if (!$categoria->listing)

                  <strong>Altri servizi</strong>


                  @foreach ($array_servizi_privati[$categoria->id] as $servizi_privati)
                    @if (isset($servizi_privati['id']) && $servizi_privati['nome'] != 'to_translate')
                      
                      <div class="checkbox">
                      {!! Form::checkbox('serviziPrivato[]', $servizi_privati['id'], true, ['disabled']) !!}
                      </div>
                      <div class="falseclick">
                      {{$servizi_privati['nome']}}
                      </div>
                      
                    @endif
                  @endforeach

                @endif

              </div> {{-- / .panel-body --}}
            <?php $count ++; ?>
          @endif {{-- $categoria->listing && $locale != 'it' --}}

        @endforeach

        </div>
		</div>


@endsection

@section('onheadclose')


  <script type="text/javascript">

    jQuery(function() {



    });
  </script>

@stop

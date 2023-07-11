@extends('templates.admin')

@section('title')
  @if (isset($vetrina))
    Modifica vetrina trattamento "Top"
  @else
    Nuova vetrina trattamento "Top"
  @endif
@endsection



@section('content')
<div class="row">
  <div class="col-lg-12">

    @if (isset($vetrina))
      {!! Form::open(['id' => 'record_delete', 'url' => 'admin/vetrine-trattamento-top/'.$vetrina->id, 'method' => 'DELETE']) !!}
        {{-- questo hidden lo uso per sapere se DOPO IL DELETE devo fare un redirect negli attivi o negli archiviati --}}
        <input type="hidden" name="attivo" value="{{!$vetrina->attivo}}">
      {!! Form::close() !!}
    @endif

   @if (isset($vetrina))
      {!! Form::model($vetrina, ['role' => 'form', 'route'=>['vetrine-trattamento-top.update',$vetrina->id], 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'form_modifica_offerta','files' => true]) !!}
      {{-- passo $vetrina->id per la validazione--}}
      <input type="hidden" name="offerta_id" value="{{$vetrina->id}}">
     
      {{-- questo hidden lo uso solo nelle offerte archiviate e mi dice se devo fare "salva e pubblica" --}}
      <input type="hidden" name="salva_e_pubblica" id="salva_e_pubblica" value="0">

    @else
      {!! Form::open(['role' => 'form', 'route'=>['vetrine-trattamento-top.store'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
    @endif

      <div class="form-horizontal">
      
        <div class="form-group">
          {!! Form::label('mese', 'Mesi validità', ['class' => 'col-sm-2 control-label']) !!}
          @foreach ($mesi as $anno => $mese)
            <div class="col-sm-2">
                {{$anno}}
               {!! Form::select('mese'.$anno.'[]',$mese,isset($vetrina) ? explode(',', $vetrina->mese)  : null,["multiple", "id" => "mesi_select", "class"=>"form-control"]) !!}
            </div>
          @endforeach
        </div>
        
        <div class="row">
            <div class="col-lg-12">
             <!-- Nav tabs -->
		      <ul class="nav nav-tabs col-lg-offset-2" role="tablist" >
		        @foreach (Langs::getAll() as $lang)
		          <li role="presentation" <?php echo $lang === "it" ? 'class="active"' : null?>>
		            <a class="tab_lang" data-id="{{ $lang }}" href="#{{ $lang }}" aria-controls="profile" role="tab" data-toggle="tab">
		              <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
		            </a>
		          </li>
		        @endforeach
		      </ul>
			
            <div class="tab-content">
              {{-- visualizzo in tutte lingue TITOLO e TESTO --}}
              @foreach (Langs::getAll() as $lang)
                <div role="tabpanel" class="tab-pane <?=( $lang === "it" ? 'active' : null)?>" id="{{ $lang }}">                            
                  @if (isset($vetrina))
                    
                    <?php $vetrina_lingua = $vetrinaLingua[$lang]; ?>

                      <div class="form-group">
                          {!! Form::label('pagina_id'.$lang, 'pagina associata', ['class' => 'col-sm-2 control-label']) !!}
                          <div class="col-sm-4">
                              
                              @if (array_key_exists($vetrina_lingua['pagina_id'], $pagine[$lang]->toArray()))
                                {!! Form::select('pagina_id'.$lang, $pagine[$lang]->toArray(), $vetrina_lingua['pagina_id'], ["class"=>"form-control ipo"]) !!}
                              @else
                                {!! Form::select('pagina_id'.$lang,['0' => '']+$pagine[$lang]->toArray(), null, ["class"=>"form-control ipo"]) !!}
                              @endif  
                          
                          </div>
                      </div>
                
                  @else

                      <div class="form-group">
                          {!! Form::label('pagina_id'.$lang, 'pagina associata', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-4">
                           {!! Form::select('pagina_id'.$lang,['0' => '']+$pagine[$lang]->toArray(), null, ["class"=>"form-control ipo"]) !!}
                        </div>
                      </div>
                    
                  @endif
                  
                </div>
              @endforeach
            </div> {{-- .tab-content --}}
        </div> {{-- .col-lg-12 --}}
      </div> {{-- .row --}}
        
        
        <div class="form-group">
          <div class="col-sm-12">
            {{-- SE sono in un'offerta ARCHIVIATA --}}
            @if (isset($vetrina) && !$vetrina->attivo)

                <button type="submit" class="btn btn-primary">Salva e archivia</button>
                <button type="button" class="btn btn-primary" onclick="jQuery('#salva_e_pubblica').val(1); jQuery('#form_modifica_offerta').submit();">Salva e pubblica</button>
                <button type="button" onclick="jQuery('#modal-confirm-delete').modal('show');" class="btn btn-danger pull-right"><i class="glyphicon glyphicon-remove"></i> Elimina</button>
                <div class="modal fade" id="modal-confirm-delete" aria-hidden="true" style="display: none;">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Eliminazione record</h4>
                      </div>
                      <div class="modal-body">
                        Confermi di voler eliminare in maniera definitiva ed irreversibile il record?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" onclick="jQuery('#record_delete').submit();">Conferma</button>
                      </div>
                    </div>
                  </div>
                </div>
            @else
            	<br /><br />
                @include('templates.admin_inc_record_buttons')
                        
            @endif
          </div>
        </div>
      </div>

    {!! Form::close() !!}

  </div>
</div>


@endsection
